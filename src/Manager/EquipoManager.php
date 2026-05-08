<?php

declare(strict_types=1);

namespace App\Manager;

use App\Entity\Categoria;
use App\Entity\Equipo;
use App\Enum\EstadoEquipo;
use App\Enum\EstadoPartido;
use App\Exception\AppException;
use App\Repository\EquipoRepository;
use App\Repository\PartidoRepository;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

class EquipoManager
{
    public function __construct(
        private EquipoRepository $equipoRepository,
        private PartidoRepository $partidoRepository,
        private ValidadorManager $validadorManager,
        #[Autowire(service: 'monolog.logger.sgt')]
        private LoggerInterface $logger
    ) {
    }

    public function obtenerEquipos(): array
    {
        return $this->equipoRepository->findAll();
    }

    public function obtenerEquipo(int $id): ?Equipo
    {
        if (!$equipo = $this->equipoRepository->find($id)) {
            throw new AppException('No se encontró el equipo');
        }
        return $equipo;
    }

    public function obtenerEquiposPorCategoria(Categoria $categoria): array
    {
        return $this->equipoRepository->findBy(['categoria' => $categoria]);
    }

    public function crearEquipo(
        Categoria $categoria,
        string $nombre,
        string $nombreCorto,
        string $pais,
        string $provincia,
        string $localidad,
        ?string $logoPath = null
    ): Equipo {

        if ($this->equipoRepository->findOneBy(['categoria' => $categoria, 'nombre' => $nombre])) {
            throw new AppException('Ya existe un equipo con ese nombre en esta categoría');
        }

        if ($this->equipoRepository->findOneBy(['categoria' => $categoria, 'nombreCorto' => $nombreCorto])) {
            throw new AppException('Ya existe un equipo con ese nombre corto en esta categoría');
        }

        $this->validadorManager->validarEquipo(
            $nombre,
            $nombreCorto,
            $pais,
            $provincia,
            $localidad
        );
        $ruta = $categoria->getTorneo()->getRuta();
        $numeroEquipo = count($this->equipoRepository->buscarEquiposXTorneo($ruta)) + 1;

        $equipo = new Equipo();
        $equipo->setCategoria($categoria);
        $equipo->setNombre($nombre);
        $equipo->setNombreCorto($nombreCorto);
        $equipo->setPais($pais);
        $equipo->setProvincia($provincia);
        $equipo->setLocalidad($localidad);
        $equipo->setLogoPath($logoPath);
        $equipo->setEstado(EstadoEquipo::BORRADOR->value);
        $equipo->setNumero($numeroEquipo);

        $this->equipoRepository->guardar($equipo, false);

        $this->logger->info('Equipo creado', [
            'equipo_id' => $equipo->getId(),
            'nombre' => $equipo->getNombre(),
            'categoria_id' => $categoria->getId(),
            'torneo' => $categoria->getTorneo()->getRuta(),
        ]);

        return $equipo;
    }

    public function editarEquipo(
        Equipo $equipo,
        string $nombre,
        string $nombreCorto,
        string $pais,
        string $provincia,
        string $localidad,
        ?string $logoPath = null
    ): void {

        if ($equipo->getNombre() !== $nombre 
            && $this->equipoRepository->findOneBy(['categoria' => $equipo->getCategoria(), 'nombre' => $nombre])
        ) {
            throw new AppException('Ya existe un equipo con ese nombre en esta categoría');
        }

        if ($equipo->getNombreCorto() !== $nombreCorto 
            && $this->equipoRepository->findOneBy(['categoria' => $equipo->getCategoria(), 'nombreCorto' => $nombreCorto])
        ) {
            throw new AppException('Ya existe un equipo con ese nombre corto en esta categoría');
        }

        $this->validadorManager->validarEquipo(
            $nombre,
            $nombreCorto,
            $pais,
            $provincia,
            $localidad
        );

        $equipo->setNombre($nombre);
        $equipo->setNombreCorto($nombreCorto);
        $equipo->setPais($pais);
        $equipo->setProvincia($provincia);
        $equipo->setLocalidad($localidad);
        if ($logoPath !== null) {
            $equipo->setLogoPath($logoPath);
        }

        $this->equipoRepository->guardar($equipo, true);

        $this->logger->info('Equipo editado', [
            'equipo_id' => $equipo->getId(),
            'nombre' => $equipo->getNombre(),
        ]);
    }

    public function eliminarEquipo(Equipo $equipo): void
    {
        $this->logger->info('Equipo eliminado', [
            'equipo_id' => $equipo->getId(),
            'nombre' => $equipo->getNombre(),
        ]);

        $this->equipoRepository->eliminar($equipo, true);
    }

    public function bajarEquipo(Equipo $equipo): void
    {
        $equipo->setEstado(EstadoEquipo::NO_PARTICIPA->value);
        $this->equipoRepository->guardar($equipo, true);

        $cancelados = 0;
        $partidos = $equipo->getPartidosLocal();
        foreach ($partidos as $partido) {
            $partido->setEstado(EstadoPartido::CANCELADO->value);
            $this->partidoRepository->guardar($partido);
            $cancelados++;
        }

        $partidos = $equipo->getPartidosVisitante();
        foreach ($partidos as $partido) {
            $partido->setEstado(EstadoPartido::CANCELADO->value);
            $this->partidoRepository->guardar($partido);
            $cancelados++;
        }

        $this->logger->warning('Equipo dado de baja', [
            'equipo_id' => $equipo->getId(),
            'nombre' => $equipo->getNombre(),
            'partidos_cancelados' => $cancelados,
        ]);
    }
}

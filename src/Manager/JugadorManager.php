<?php

declare(strict_types=1);

namespace App\Manager;

use App\Entity\Equipo;
use App\Entity\Jugador;
use App\Exception\AppException;
use App\Repository\JugadorRepository;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

class JugadorManager
{
    public function __construct(
        private JugadorRepository $jugadorRepository,
        private ValidadorManager $validadorManager,
        #[Autowire(service: 'monolog.logger.sgt')]
        private LoggerInterface $logger
    ) {
    }

    public function obtenerJugadores(): array
    {
        return $this->jugadorRepository->findAll();
    }

    public function obtenerJugador(int $id): ?Jugador
    {
        if (!$jugador = $this->jugadorRepository->find($id)) {
            throw new AppException('No se encontró el jugador');
        }
        return $jugador;
    }

    public function obtenerJugadoresPorEquipo(Equipo $equipo): array
    {
        return $this->jugadorRepository->findBy(['equipo' => $equipo]);
    }

    public function crearJugador(
        Equipo $equipo,
        string $nombre,
        string $apellido,
        string $tipoDocumento,
        string $numeroDocumento,
        ?string $fechaNacimiento,
        string $tipo,
        bool $responsable,
        string $email,
        string $celular
    ): void {

        if ($this->jugadorRepository->findOneBy(
            ['equipo' => $equipo,
                'tipoDocumento' => $tipoDocumento,
                'numeroDocumento' => $numeroDocumento]
        )
        ) {
            throw new AppException('Ya existe un jugador con ese DNI');
        }

        $this->validadorManager->validarJugador(
            $nombre,
            $apellido,
            $tipoDocumento,
            $numeroDocumento,
            $fechaNacimiento,
        );

        $jugador = new Jugador();
        $jugador->setEquipo($equipo);
        $jugador->setNombre($nombre);
        $jugador->setApellido($apellido);
        $jugador->setTipoDocumento($tipoDocumento);
        $jugador->setNumeroDocumento($numeroDocumento);
        if ($fechaNacimiento !== null) {
            $jugador->setNacimiento(new \DateTimeImmutable($fechaNacimiento));
        }
        $jugador->setTipo($tipo);
        $jugador->setResponsable($responsable);
        $jugador->setEmail($email);
        $jugador->setCelular($celular);

        $this->jugadorRepository->guardar($jugador, true);

        $this->logger->info('Jugador creado', [
            'jugador_id' => $jugador->getId(),
            'nombre' => $jugador->getNombre() . ' ' . $jugador->getApellido(),
            'equipo_id' => $equipo->getId(),
            'equipo' => $equipo->getNombre(),
        ]);
    }

    public function editarJugador(
        Jugador $jugador,
        string $nombre,
        string $apellido,
        string $tipoDocumento,
        string $numeroDocumento,
        ?string $fechaNacimiento,
        string $tipo,
        bool $responsable,
        string $email,
        string $celular
    ): void {

        if ($jugador->getNumeroDocumento() !== $numeroDocumento 
            && $this->jugadorRepository->findOneBy(['numeroDocumento' => $numeroDocumento])
        ) {
            throw new AppException('Ya existe un jugador con ese DNI');
        }

        $this->validadorManager->validarJugador(
            $nombre,
            $apellido,
            $tipoDocumento,
            $numeroDocumento,
            $fechaNacimiento,
        );
            $jugador->setNombre($nombre);
            $jugador->setTipoDocumento($tipoDocumento);
            $jugador->setNumeroDocumento($numeroDocumento);
            $jugador->setNacimiento(new \DateTimeImmutable($fechaNacimiento) ?? null);
            $jugador->setTipo($tipo);
            $jugador->setResponsable($responsable);
            $jugador->setEmail($email);
            $jugador->setCelular($celular);

            $this->jugadorRepository->guardar($jugador, true);

        $this->logger->info('Jugador editado', [
            'jugador_id' => $jugador->getId(),
            'nombre' => $jugador->getNombre() . ' ' . $jugador->getApellido(),
        ]);
    }

    public function eliminarJugador(Jugador $jugador): void
    {
        $this->logger->info('Jugador eliminado', [
            'jugador_id' => $jugador->getId(),
            'nombre' => $jugador->getNombre() . ' ' . $jugador->getApellido(),
            'equipo_id' => $jugador->getEquipo()?->getId(),
        ]);

        $this->jugadorRepository->eliminar($jugador, true);
    }
}

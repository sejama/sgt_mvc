<?php

namespace App\Manager;

use App\Entity\Categoria;
use App\Entity\Grupo;
use App\Entity\Partido;
use App\Exception\AppException;
use App\Manager\CanchaManager;
use App\Repository\PartidoRepository;
use App\Utils\GenerarPdf;

class PartidoManager
{
    public function __construct(
        private CanchaManager $canchaManager,
        private PartidoRepository $partidoRepository,
        private GrupoManager $grupoManager,
    ) {
    }

    public function obtenerPartidosXGrupo(int $grupoId): array
    {
        return $this->partidoRepository->findBy(['grupo' => $grupoId]);
    }

    public function obtenerPartido(int $partidoId): Partido
    {
        return $this->partidoRepository->findOneBy(['id' => $partidoId]);
    }

    public function obtenerPartidosXTorneo(string $ruta): array
    {
        return $this->partidoRepository->buscarPartidosXTorneo($ruta);
    }

    public function obtenerPartidosSinAsignarXTorneo($ruta): array
    {
        return $this->partidoRepository->buscarPartidosSinAsignarXTorneo($ruta);
    }

    public function obtenerPartidosProgramadosXTorneo($ruta): array
    {
        $paritdosOrdenados = [];
        foreach ($this->partidoRepository->buscarPartidosProgramadosXTorneo($ruta) as $partido) {
            $partido['fecha'] = $partido['horario']->format('Y-m-d');
            $partido['hora'] = $partido['horario']->format('H:i');
            $paritdosOrdenados[$partido['sede']][$partido['cancha']][] = $partido;
        }
        return $paritdosOrdenados;
    }

    public function obtenerPartidoXCancha(int $canchaId): array
    {
        return $this->partidoRepository->findBy(['cancha' => $canchaId]);
    }

    public function crearPartidoXCategoria(Categoria $categoria): void
    {
        foreach ($this->grupoManager->obtenerGrupos($categoria) as $grupo) {
            $this->crearPartidosXGrupo($grupo);
        }
    }

    public function crearPartidosXGrupo(Grupo $grupo): void
    {
        $equipos = $grupo->getEquipo();
        $numPartido = $this->obtenerPartidosXTorneo($grupo->getCategoria()->getTorneo()->getRuta());
        $numero = count($numPartido) + 1;
        for ($i = 0; $i < count($equipos); $i++) {
            for ($j = $i + 1; $j < count($equipos); $j++) {
                $partido = new Partido();
                $partido->setCancha(null);
                $partido->setGrupo($grupo);
                $partido->setCategoria($grupo->getCategoria());
                $partido->setEstado(\App\Enum\EstadoPartido::BORRADOR->value);
                $partido->setTipo(\App\Enum\TipoPartido::CLASIFICATORIO->value);
                $partido->setEquipoLocal($equipos[$i]);
                $partido->setEquipoVisitante($equipos[$j]);
                $partido->setNumero($numero++);

                $this->partidoRepository->guardar($partido);
            }
        }
    }

    public function obtenerSedesyCanchasXTorneo(string $ruta): array
    {
        return $this->canchaManager->obtenerSedesYCanchasByTorneo($ruta);
    }

    public function editarPartido(string $ruta, int $partidoId, int $canchaId, string $horario): void
    {
        $horario = new \DateTimeImmutable(substr_replace($horario, '00', -2));

        if ($this->partidoRepository->buscarPartidoXCanchaHorario($canchaId, $horario)) {
            throw new AppException('Ya existe un partido programado en esa cancha y horario');
        }
        $partido = $this->obtenerPartido($partidoId);
        $partido->setCancha($this->canchaManager->obtenerCancha($canchaId));
        $partido->setHorario($horario);

        $pdf = new GenerarPdf();
        $pdf->generarPdf($partido, $ruta);
        $this->partidoRepository->guardar($partido);
    }
}

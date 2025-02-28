<?php

namespace App\Manager;

use App\Entity\Categoria;
use App\Entity\Grupo;
use App\Entity\Partido;
use App\Entity\PartidoConfig;
use App\Exception\AppException;
use App\Manager\CanchaManager;
use App\Repository\PartidoConfigRepository;
use App\Repository\PartidoRepository;
use App\Utils\GenerarPdf;

class PartidoManager
{
    public function __construct(
        private CanchaManager $canchaManager,
        private GrupoManager $grupoManager,
        private PartidoRepository $partidoRepository,
        private PartidoConfigRepository $partidoConfigRepository,
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
        $partidos = [];
        $partidos['clasificatorios'] = $this->partidoRepository->buscarPartidosSinAsignarXTorneo($ruta);
        $partidos['eliminatorias'] = $this->partidoRepository->buscarPartidosPlayOffGrupoXTorneo($ruta);
        $partidos['finales'] = $this->partidoRepository->buscarPartidosPlayOffFinalesXTorneo($ruta);
        return $partidos;
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

    public function crearPartidoXCategoria(Categoria $categoria, array $partidosPlayOff): void
    {
        foreach ($this->grupoManager->obtenerGrupos($categoria) as $grupo) {
            $this->crearPartidosXGrupo($grupo);
        }
        $numPartido = $this->obtenerPartidosXTorneo($categoria->getTorneo()->getRuta());
        $numero = count($numPartido) + 1;
        
        foreach ($partidosPlayOff as $tiposPlayOff) {
            $partidoId = [];
            foreach ($tiposPlayOff as $partidoPlayOff) {
                foreach ($partidoPlayOff as $playOff) {
                    
                    $partido = new Partido();
                    $partido->setCancha(null);
                    $partido->setGrupo(null);
                    $partido->setCategoria($categoria);
                    $partido->setEstado(\App\Enum\EstadoPartido::BORRADOR->value);
                    $partido->setTipo(\App\Enum\TipoPartido::ELIMINATORIO->value);
                    $partido->setEquipoLocal(null);
                    $partido->setEquipoVisitante(null);
                    $partido->setNumero($numero++);
                    
                    $this->partidoRepository->guardar($partido);
                    
                    $partidoConfig = new PartidoConfig();
                    $partidoConfig->setPartido($partido);
                    $partidoConfig->setNombre($playOff['nombre']);

                    if (isset($playOff['grupoEquipo1']) && isset($playOff['posicionEquipo1']) && isset($playOff['grupoEquipo2']) && isset($playOff['posicionEquipo2'])) {    
                        $partidoConfig->setGrupoEquipo1($this->grupoManager->obtenerGrupo((int)$playOff['grupoEquipo1']));
                        $partidoConfig->setPosicionEquipo1($playOff['posicionEquipo1']);
                        $partidoConfig->setGrupoEquipo2($this->grupoManager->obtenerGrupo((int)$playOff['grupoEquipo2']));
                        $partidoConfig->setPosicionEquipo2($playOff['posicionEquipo2']);
                    }

                    if (isset($playOff['equipoGanador1']) && isset($playOff['equipoGanador2'])) {
                        $partidoConfig->setGanadorPartido1($this->partidoRepository->obtenerPartidoXNumero($partidoId[(int)$playOff['equipoGanador1']]['partidoNumero']));
                        $partidoConfig->setGanadorPartido2($this->partidoRepository->obtenerPartidoXNumero($partidoId[(int)$playOff['equipoGanador2']]['partidoNumero']));
                    }

                    $this->partidoConfigRepository->guardar($partidoConfig);
                    $partidoId[] = [
                        'partidoNumero' => $partido->getNumero(),
                    ];
                }
            }
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

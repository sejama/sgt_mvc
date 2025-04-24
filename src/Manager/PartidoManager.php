<?php

namespace App\Manager;

use App\Entity\Categoria;
use App\Entity\Grupo;
use App\Entity\Partido;
use App\Entity\PartidoConfig;
use App\Exception\AppException;
use App\Manager\CanchaManager;
use App\Manager\ValidadorPartidoManager;
use App\Repository\EquipoRepository;
use App\Repository\PartidoConfigRepository;
use App\Repository\PartidoRepository;
use App\Utils\GenerarPdf;

class PartidoManager
{
    public function __construct(
        private CanchaManager $canchaManager,
        private GrupoManager $grupoManager,
        private EquipoRepository $equipoRepository,
        private PartidoRepository $partidoRepository,
        private PartidoConfigRepository $partidoConfigRepository,
        private ValidadorPartidoManager $validadorPartidoManager
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
        foreach ($this->partidoRepository->buscarPartidosProgramadosClasificatorioXTorneo($ruta) as $partido) {
            $partido['fecha'] = $partido['horario']->format('Y-m-d');
            $partido['hora'] = $partido['horario']->format('H:i');
            
            // Validar que 'cancha' y 'fecha' existan y sean válidos
            if (isset($partido['sede'], $partido['cancha'], $partido['fecha'])) {
                $paritdosOrdenados[$partido['sede']][$partido['cancha']][$partido['fecha']][] = $partido;
            }
        }
        foreach ($this->partidoRepository->buscarPartidosProgramadosPlayOffXTorneo($ruta) as $partido) {
            $partido['fecha'] = $partido['horario']->format('Y-m-d');
            $partido['hora'] = $partido['horario']->format('H:i');
            
            if (isset($partido['sede'], $partido['cancha'], $partido['fecha'])) {
                $paritdosOrdenados[$partido['sede']][$partido['cancha']][$partido['fecha']][] = $partido;
            }
        }

        foreach ($this->partidoRepository->buscarPartidosProgramadosPlayOffFinalesXTorneo($ruta) as $partido) {
            $partido['fecha'] = $partido['horario']->format('Y-m-d');
            $partido['hora'] = $partido['horario']->format('H:i');
            
            if (isset($partido['sede'], $partido['cancha'], $partido['fecha'])) {
                $paritdosOrdenados[$partido['sede']][$partido['cancha']][$partido['fecha']][] = $partido;
            }
        }

        // Ordenar los partidos por fecha y hora
    foreach ($paritdosOrdenados as $sede => &$canchas) {
        foreach ($canchas as $cancha => &$fechas) {
            foreach ($fechas as $fecha => &$partidos) {
                usort($partidos, function ($a, $b) {
                    $horaA = strtotime($a['hora']);
                    $horaB = strtotime($b['hora']);
                    return $horaA <=> $horaB;
                });
            }
        }
    }
        
        return $paritdosOrdenados;
    }

    public function obtenerPartidoXCancha(int $canchaId): array
    {
        return $this->partidoRepository->findBy(['cancha' => $canchaId]);
    }

    public function crearPartidoXCategoria(Categoria $categoria, array $partidosPlayOff): void
    {
        $this->validadorPartidoManager->validarPlayOff($partidosPlayOff);
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

                if ($equipos[$i]->getEstado() === \App\Enum\EstadoEquipo::BORRADOR->value) {
                    $equipos[$i]->setEstado(\App\Enum\EstadoEquipo::ACTIVO->value);
                    $this->equipoRepository->guardar($equipos[$i]);
                }

                if ($equipos[$j]->getEstado() === \App\Enum\EstadoEquipo::BORRADOR->value) {
                    $equipos[$j]->setEstado(\App\Enum\EstadoEquipo::ACTIVO->value);
                    $this->equipoRepository->guardar($equipos[$j]);
                }

                $this->equipoRepository->guardar($equipos[$i]);
                $this->equipoRepository->guardar($equipos[$j]);
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
        $partido->setEstado(\App\Enum\EstadoPartido::PROGRAMADO->value);

        $pdf = new GenerarPdf();
        $pdf->generarPdf($partido, $ruta);

        $this->partidoRepository->guardar($partido);
        
        if ($partido->getEquipoLocal() !== null) {
            $equipoLocal = $partido->getEquipoLocal();
            if ($equipoLocal->getEstado() === \App\Enum\EstadoEquipo::BORRADOR->value) {
                $equipoLocal->setEstado(\App\Enum\EstadoEquipo::ACTIVO->value);
                $this->equipoRepository->guardar($equipoLocal);
            }
        }
       
        if ($partido->getEquipoVisitante() !== null) {
            $equipoVisitante = $partido->getEquipoVisitante();
            if ($equipoVisitante->getEstado() === \App\Enum\EstadoEquipo::BORRADOR->value) {
                $equipoVisitante->setEstado(\App\Enum\EstadoEquipo::ACTIVO->value);
                $this->equipoRepository->guardar($equipoVisitante);
            }
        }

    }

    public function cargarResultado(int $partidoId, array $resultadoLocal, array $resultadoVisitante): void
    {
        $partido = $this->obtenerPartido($partidoId);
        
        $partido->setLocalSet1($resultadoLocal[0] ? (int)$resultadoLocal[0] : null);
        $partido->setLocalSet2($resultadoLocal[1] ? (int)$resultadoLocal[1] : null);
        $partido->setLocalSet3($resultadoLocal[2] ? (int)$resultadoLocal[2] : null);

        $partido->setVisitanteSet1($resultadoVisitante[0] ? (int)$resultadoVisitante[0] : null);
        $partido->setVisitanteSet2($resultadoVisitante[1] ? (int)$resultadoVisitante[1] : null);
        $partido->setVisitanteSet3($resultadoVisitante[2] ? (int)$resultadoVisitante[2] : null);
        
        $partido->setEstado(\App\Enum\EstadoPartido::FINALIZADO->value);
        $this->partidoRepository->guardar($partido);

        $partidoConfig = $this->partidoConfigRepository->obtenerPartidoConfigXGanadorPartido($partido);
        $ganador = null; 
        $local = $visitante = 0;

        if ($partido->getLocalSet1() > $partido->getVisitanteSet1()) {
            $local++;
        } else {
            $visitante++;
        } 

        if ($partido->getLocalSet2() > $partido->getVisitanteSet2()) {
            $local++;
        } else {
            $visitante++;
        }

        if ($partido->getLocalSet3() !== null and $partido->getVisitanteSet3() !== null) {
            if ($partido->getLocalSet3() > $partido->getVisitanteSet3()) {
                $local++;
            } else {
                $visitante++;
            }
        }

        if ($local > $visitante) {
            $ganador = $partido->getEquipoLocal();
        } else {
            $ganador = $partido->getEquipoVisitante();
        }
        

        if ($partidoConfig) {
            $partidoSiguiente = $partidoConfig->getPartido();
            if ($partidoConfig->getGanadorPartido1() === $partido) {
                $partidoSiguiente->setEquipoLocal($ganador);  
            } elseif ($partidoConfig->getGanadorPartido2() === $partido) {
                $partidoSiguiente->setEquipoVisitante($ganador); 
            }
        }

        $this->partidoRepository->guardar($partidoSiguiente);
    }

    public function obtenerPartidosXCategoriaClasificatorio(Categoria $categoria): array
    {
        $partidos = $this->partidoRepository->obtenerPartidosXCategoriaClasificatorio($categoria->getId());
        
        return $partidos;
    }

    public function obtenerPartidosXCategoriaEliminatoriaPostClasificatorio(Categoria $categoria): array
    {
        $todos = $this->partidoRepository->obtenerPartidosXCategoriaEliminatoriaPostClasificatorio($categoria->getId());
        foreach ($todos as $partido) {
            if (str_contains($partido['nombre'], 'Oro')) {
                $partidos['oro'][] = $partido;
            } elseif (str_contains($partido['nombre'], 'Plata')) {
                $partidos['plata'][] = $partido;
            } elseif (str_contains($partido['nombre'], 'Bronce')) {
                $partidos['bronce'][] = $partido;
            } else {
                $partidos['general'][] = $partido;
            }
        }
        return $partidos;
    }
}
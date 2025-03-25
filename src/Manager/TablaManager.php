<?php

declare(strict_types=1);

namespace App\Manager;

use App\Entity\Grupo;
use App\Enum\EstadoGrupo;
use App\Enum\EstadoPartido;
use App\Repository\CategoriaRepository;
use App\Repository\GrupoRepository;

class TablaManager {

    public function __construct(
        private CategoriaRepository $categoriaRepository,
        private GrupoRepository $grupoRepository,
    ) {
    }

    public function calcularPosiciones(Grupo $grupo)
    {
        $posiciones = [];
        $partidos = $grupo->getPartidos();
        $equipos = $grupo->getEquipo();

        foreach ($equipos as $equipo) {
            $posiciones[$equipo->getId()] = [
                'nombre' => $equipo->getNombre(),
                'equipo' => $equipo,
                'partidosJugados' => 0,
                'partidosGanados' => 0,
                'partidosPerdidos' => 0,
                'setsFavor' => 0,
                'setsContra' => 0,
                'setsDiferencia' => 0,
                'puntosFavor' => 0,
                'puntosContra' => 0,
                'puntosDiferencia' => 0,
                'puntos' => 0,
            ];
        }
        $partidosFinalizados = 0;
        foreach ($partidos as $partido) {
            if ($partido->getEstado() === EstadoPartido::FINALIZADO->value) {
                $partidosFinalizados++;
                $equipoLocal = $partido->getEquipoLocal();
                $equipoVisitante = $partido->getEquipoVisitante();

                $posiciones[$equipoLocal->getId()]['partidosJugados']++;
                $posiciones[$equipoVisitante->getId()]['partidosJugados']++;

                $puntosLocal = $partido->getLocalSet1() + $partido->getLocalSet2() + $partido->getLocalSet3() + $partido->getLocalSet4() + $partido->getLocalSet5();
                $puntosVisitante = $partido->getVisitanteSet1() + $partido->getVisitanteSet2() + $partido->getVisitanteSet3() + $partido->getVisitanteSet4() + $partido->getVisitanteSet5();

                $posiciones[$equipoLocal->getId()]['puntosFavor'] += $puntosLocal;
                $posiciones[$equipoLocal->getId()]['puntosContra'] += $puntosVisitante;
                $posiciones[$equipoLocal->getId()]['puntosDiferencia'] += $puntosLocal - $puntosVisitante;

                $posiciones[$equipoVisitante->getId()]['puntosFavor'] += $puntosVisitante;
                $posiciones[$equipoVisitante->getId()]['puntosContra'] += $puntosLocal;
                $posiciones[$equipoVisitante->getId()]['puntosDiferencia'] += $puntosVisitante - $puntosLocal;

                $setsLocal = 0;
                $setsVisitante = 0;

                if ($partido->getLocalSet1() > $partido->getVisitanteSet1()) {
                    $setsLocal++;
                } else {
                    $setsVisitante++;
                }

                if ($partido->getLocalSet2() > $partido->getVisitanteSet2()) {
                    $setsLocal++;
                } else {
                    $setsVisitante++;
                }

                if ($partido->getLocalSet3() !== null && $partido->getVisitanteSet3() !== null) {
                    if ($partido->getLocalSet3() > $partido->getVisitanteSet3()) {
                        $setsLocal++;
                    } else {
                        $setsVisitante++;
                    }
                }


                if ($partido->getLocalSet4() !== null && $partido->getVisitanteSet4() !== null) {
                    if ($partido->getLocalSet4() > $partido->getVisitanteSet4()) {
                        $setsLocal++;
                    } else {
                        $setsVisitante++;
                    }
                }

                if ($partido->getLocalSet5() !== null && $partido->getVisitanteSet5() !== null) {
                    if ($partido->getLocalSet5() > $partido->getVisitanteSet5()) {
                        $setsLocal++;
                    } else {
                        $setsVisitante++;
                    }
                }

                $posiciones[$equipoLocal->getId()]['setsFavor'] += $setsLocal;
                $posiciones[$equipoLocal->getId()]['setsContra'] += $setsVisitante;
                $posiciones[$equipoLocal->getId()]['setsDiferencia'] += $setsLocal - $setsVisitante;

                $posiciones[$equipoVisitante->getId()]['setsFavor'] += $setsVisitante;
                $posiciones[$equipoVisitante->getId()]['setsContra'] += $setsLocal;
                $posiciones[$equipoVisitante->getId()]['setsDiferencia'] += $setsVisitante - $setsLocal;

                if ($setsLocal > $setsVisitante) {
                    $posiciones[$equipoLocal->getId()]['partidosGanados']++;
                    $posiciones[$equipoVisitante->getId()]['partidosPerdidos']++;

                } else {
                    $posiciones[$equipoVisitante->getId()]['partidosGanados']++;
                    $posiciones[$equipoLocal->getId()]['partidosPerdidos']++;

                }

                $posiciones[$equipoLocal->getId()]['puntos'] = $posiciones[$equipoLocal->getId()]['partidosGanados'] * 2 + $posiciones[$equipoLocal->getId()]['partidosPerdidos'];
                $posiciones[$equipoVisitante->getId()]['puntos'] = $posiciones[$equipoVisitante->getId()]['partidosGanados'] * 2 + $posiciones[$equipoVisitante->getId()]['partidosPerdidos'];
                
            
            }
            
            if ($partido->getEstado() === EstadoPartido::CANCELADO->value) {
                $partidosFinalizados++;
            }
            
        }

        if ($partidosFinalizados === count($partidos) && $grupo->getEstado() !== EstadoGrupo::FINALIZADO->value) {
            $grupo->setEstado(EstadoGrupo::FINALIZADO->value);
            $this->grupoRepository->guardar($grupo);
        }

        // Ordenar el array por puntos, setsDiferencia y puntosDiferencia
        usort($posiciones, function ($a, $b) {
            if ($a['puntos'] === $b['puntos']) {
                if ($a['setsDiferencia'] === $b['setsDiferencia']) {
                    return $b['puntosDiferencia'] <=> $a['puntosDiferencia'];
                }
                return $b['setsDiferencia'] <=> $a['setsDiferencia'];
            }
            return $b['puntos'] <=> $a['puntos'];
        });

        return $posiciones;
    }
}
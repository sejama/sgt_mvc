<?php

namespace App\Manager;

use App\Entity\Categoria;
use App\Entity\Grupo;
use App\Enum\EstadoGrupo;
use App\Enum\EstadoPartido;
use App\Exception\AppException;
use App\Repository\GrupoRepository;

class GrupoManager
{
    public function __construct(
        private GrupoRepository $grupoRepository,
        private CategoriaManager $categoriaManager,
        private ValidadorManager $validadorManager
    ) {
    }

    public function obtenerGrupo(int $id): Grupo
    {
        if (!$grupo = $this->grupoRepository->find($id)) {
            throw new AppException('No se encontró el grupo');
        }
        return $grupo;
    }

    public function obtenerGrupos(Categoria $categoria): array
    {
        return $this->grupoRepository->findBy(['categoria' => $categoria], ['nombre' => 'ASC']);
    }

    public function crearGrupos(
        array $grupos,
    ) {
        $totalClasificados = 0;
        $totalEquiposZonas = 0;
        $inicio = 0;

        $categoria = $this->categoriaManager->obtenerCategoria($grupos[0]['categoria']);
        $equipos = $categoria->getEquipos();

        $totalEquipos = count($equipos);

        foreach ($grupos as $grupo) {
            $totalEquiposZonas += $grupo['cantidad'];
        }
        if ($totalEquiposZonas !== $totalEquipos) {
            throw new AppException(
                'La cantidad de equipos en las zonas no coincide con la cantidad de equipos en la categoría'
            );
        }

        $equipos = [];
        foreach ($categoria->getEquipos() as $equipo) {
            $equipos[] = $equipo;
        }

        foreach ($grupos as $grupo) {
            try {
                $this->validadorManager->validarGrupo($grupo['nombre']);

                if (!$grupo['clasificaOro']) {
                    throw new AppException('No se puede crear un grupo sin equipos que clasifiquen a oro');
                }

                if ($totalEquipos < $totalClasificados += $grupo['clasificaOro']) {
                    throw new AppException('No se puede clasificar más equipos de los que hay en la categoría');
                }

                if ($grupo['clasificaPlata'] && $totalEquipos < $totalClasificados += $grupo['clasificaPlata']) {
                    throw new AppException('No se puede clasificar más equipos de los que hay en la categoría');
                }

                if ($grupo['clasificaBronce'] && !$grupo['clasificaPlata']) {
                    throw new AppException('No se puede clasificar equipos de bronce sin clasificar equipos de plata');
                }

                if ($grupo['clasificaBronce'] &&  $totalEquipos < $totalClasificados += $grupo['clasificaBronce']) {
                    throw new AppException('No se puede clasificar más equipos de los que hay en la categoría');
                }

                $entidad = new Grupo();
                $entidad->setNombre($grupo['nombre']);
                $entidad->setCategoria($categoria);
                $entidad->setClasificaOro($grupo['clasificaOro']);
                $entidad->setClasificaPlata($grupo['clasificaPlata']);
                $entidad->setClasificaBronce($grupo['clasificaBronce']);
                $entidad->setEstado(EstadoGrupo::BORRADOR->value);

                $equiposGrupo = array_slice($equipos, $inicio, $inicio += $grupo['cantidad']);
                foreach ($equiposGrupo as $equipo) {
                    $entidad->addEquipo($equipo);
                }
                $this->grupoRepository->guardar($entidad);
            } catch (AppException $e) {
                throw new AppException($e->getMessage());
            } catch (\Exception $e) {
                throw new AppException('Error al crear los grupos ' . $e->getMessage());
            }
        }
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

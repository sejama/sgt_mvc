<?php

namespace App\Controller;

use App\Manager\CategoriaManager;
use App\Manager\GrupoManager;
use App\Manager\EquipoManager;
use App\Manager\PartidoManager;
use App\Manager\TablaManager;
use App\Manager\TorneoManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class MainController extends AbstractController
{
    /**
     * @param array<string, mixed> $partidosProgramados
     * @return array<string, mixed>
     */
    private function completarLogosPartidosProgramados(array $partidosProgramados, iterable $categorias): array
    {
        $logosPorEquipo = [];

        foreach ($categorias as $categoria) {
            foreach ($categoria->getEquipos() as $equipo) {
                $nombre = $equipo->getNombre();
                if (!is_string($nombre) || $nombre === '') {
                    continue;
                }

                $logosPorEquipo[strtolower(trim($nombre))] = $equipo->getLogoPath();
            }
        }

        foreach ($partidosProgramados as $sede => $canchas) {
            foreach ($canchas as $cancha => $fechas) {
                foreach ($fechas as $fecha => $partidos) {
                    foreach ($partidos as $idx => $partido) {
                        $logoLocal = $partido['equipoLocalLogoPath'] ?? null;
                        $logoVisitante = $partido['equipoVisitanteLogoPath'] ?? null;

                        if (!$logoLocal && isset($partido['equipoLocal']) && is_string($partido['equipoLocal'])) {
                            $claveLocal = strtolower(trim($partido['equipoLocal']));
                            if (isset($logosPorEquipo[$claveLocal])) {
                                $partido['equipoLocalLogoPath'] = $logosPorEquipo[$claveLocal];
                            }
                        }

                        if (!$logoVisitante && isset($partido['equipoVisitante']) && is_string($partido['equipoVisitante'])) {
                            $claveVisitante = strtolower(trim($partido['equipoVisitante']));
                            if (isset($logosPorEquipo[$claveVisitante])) {
                                $partido['equipoVisitanteLogoPath'] = $logosPorEquipo[$claveVisitante];
                            }
                        }

                        $partidosProgramados[$sede][$cancha][$fecha][$idx] = $partido;
                    }
                }
            }
        }

        return $partidosProgramados;
    }

    /**
     * @param array<string, mixed> $partidosProgramados
     * @return array<string, mixed>
     */
    private function ordenarPartidosProgramados(array $partidosProgramados): array
    {
        ksort($partidosProgramados, SORT_NATURAL | SORT_FLAG_CASE);

        foreach ($partidosProgramados as $sede => $canchas) {
            if (!is_array($canchas)) {
                continue;
            }

            ksort($canchas, SORT_NATURAL | SORT_FLAG_CASE);

            foreach ($canchas as $cancha => $fechas) {
                if (!is_array($fechas)) {
                    continue;
                }

                ksort($fechas);

                foreach ($fechas as $fecha => $partidos) {
                    if (!is_array($partidos)) {
                        $fechas[$fecha] = [];
                        continue;
                    }

                    usort($partidos, static function (array $a, array $b): int {
                        $horaA = (string) ($a['hora'] ?? ($a['horario'] ?? ''));
                        $horaB = (string) ($b['hora'] ?? ($b['horario'] ?? ''));

                        return strcmp($horaA, $horaB);
                    });

                    $fechas[$fecha] = $partidos;
                }

                $canchas[$cancha] = $fechas;
            }

            $partidosProgramados[$sede] = $canchas;
        }

        return $partidosProgramados;
    }

    #[Route('/', name: 'app_main', methods: ['GET'])]
    public function index(
        TorneoManager $torneoManager
    ): Response {
        if ($this->getUser() != null) {
            if ($this->getUser()->getCreatedAt() == $this->getUser()->getUpdatedAt()) {
                return $this->render('usuario/cambiar_password.html.twig', ['idUser' => $this->getUser()->getId()]);
            }
        }
        $torneos = $torneoManager->obtenerTorneos();
        return $this->render(
            'main/index.html.twig',
            [
            'torneos' => $torneos,
            ]
        );
    }

    #[Route('/torneo/{ruta}', name: 'app_main_torneo', methods: ['GET'])]
    public function torneo(
        TorneoManager $torneoManager,
        PartidoManager $partidoManager,
        CategoriaManager $categoriaManager,
        GrupoManager $grupoManager,
        EquipoManager $equipoManager,
        Request $request,
        string $ruta
    ): Response {

        $torneo = $torneoManager->obtenerTorneo($ruta);
        $categorias = $torneo->getCategorias();
        $partidosProgramados = $partidoManager->obtenerPartidosProgramadosXTorneo($ruta);
        $partidosProgramados = $this->completarLogosPartidosProgramados($partidosProgramados, $categorias);

        // Leer filtros de query string
        $selectedCategoriaId = $request->query->getInt('categoria') ?: null;
        $selectedGrupoId = $request->query->getInt('grupo') ?: null;
        $selectedEquipoId = $request->query->getInt('equipo') ?: null;

        $categoriaSelected = null;
        $grupoSelected = null;
        $equipoSelected = null;
        $equipos = [];
        $grupos = [];

        if ($selectedCategoriaId) {
            $categoriaSelected = $categoriaManager->obtenerCategoria($selectedCategoriaId);
            if ($categoriaSelected) {
                // Lista de grupos de la categoría
                $grupos = $grupoManager->obtenerGrupos($categoriaSelected);
                // Colección de equipos de la categoría (se puede sobrescribir si hay grupo seleccionado)
                $equipos = $categoriaSelected->getEquipos();
            }
        }

        if ($selectedGrupoId) {
            try {
                $grupoSelected = $grupoManager->obtenerGrupo($selectedGrupoId);
                if ($grupoSelected) {
                    // Equipos limitados al grupo seleccionado
                    $equipos = $grupoSelected->getEquipo();
                }
            } catch (\Exception $e) {
                $grupoSelected = null;
            }
        }

        if ($selectedEquipoId) {
            try {
                $equipoSelected = $equipoManager->obtenerEquipo($selectedEquipoId);
            } catch (\Exception $e) {
                $equipoSelected = null;
            }
        }

        // Aplicar filtros sobre la estructura de partidos programados
        if ($selectedCategoriaId || $selectedGrupoId || $selectedEquipoId) {
            $filtrados = [];
            foreach ($partidosProgramados as $sede => $canchas) {
                foreach ($canchas as $cancha => $fechas) {
                    foreach ($fechas as $fecha => $partidos) {
                        $nuevos = [];
                                    foreach ($partidos as $partido) {
                                        $matchCategoria = true;
                                        $matchGrupo = true;
                                        $matchEquipo = true;

                                        if ($categoriaSelected) {
                                            $matchCategoria = ($partido['categoria'] ?? '') === $categoriaSelected->getNombre();
                                        }

                                        if ($grupoSelected) {
                                            $matchGrupo = ($partido['grupo'] ?? '') === $grupoSelected->getNombre();
                                        }

                                        if ($equipoSelected) {
                                            $nombreEquipo = $equipoSelected->getNombre();
                                            $local = $partido['equipoLocal'] ?? '';
                                            $visitante = $partido['equipoVisitante'] ?? '';
                                            $matchEquipo = (stripos($local, $nombreEquipo) !== false) || (stripos($visitante, $nombreEquipo) !== false);
                                        }

                                        if ($matchCategoria && $matchGrupo && $matchEquipo) {
                                            $nuevos[] = $partido;
                                        }
                        }
                        if (count($nuevos) > 0) {
                            $filtrados[$sede][$cancha][$fecha] = $nuevos;
                        }
                    }
                }
            }
            $partidosProgramados = $filtrados;
    }

    $partidosProgramados = $this->ordenarPartidosProgramados($partidosProgramados);

    return $this->render(
        'main/torneo.html.twig',
        [
            'torneo' => $torneo,
            'categorias' => $categorias,
            'partidosProgramados' => $partidosProgramados,
            'selectedCategoriaId' => $selectedCategoriaId,
            'selectedGrupoId' => $selectedGrupoId,
            'selectedEquipoId' => $selectedEquipoId,
            'grupos' => $grupos,
            'equipos' => $equipos,
        ]
    );
}

    #[Route('/torneo/{ruta}/categoria/{categoriaId}', name: 'app_main_categoria', methods: ['GET'])]
    public function categoria(
        TorneoManager $torneoManager,
        CategoriaManager $categoriaManager,
        TablaManager $tablaManager,
        string $ruta,
        int $categoriaId
    ): Response {
        $categoria = $categoriaManager->obtenerCategoria($categoriaId);
        $grupos = $categoria->getGrupos();
        $gruposPosiciones = [];
        foreach ($grupos as $grupo) {
            $gruposPosiciones[$grupo->getId()][] = $grupo;
            $gruposPosiciones[$grupo->getId()][] = $tablaManager->calcularPosiciones($grupo);
        }
        return $this->render(
            'main/categoria.html.twig',
            [
                'ruta' => $ruta,
                'grupos' => $gruposPosiciones
            ]
        );
    }
}

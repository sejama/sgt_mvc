<?php

namespace App\Controller;

use App\Manager\CategoriaManager;
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
        EquipoManager $equipoManager,
        Request $request,
        string $ruta
    ): Response {

        $torneo = $torneoManager->obtenerTorneo($ruta);
        $categorias = $torneo->getCategorias();
        $partidosProgramados = $partidoManager->obtenerPartidosProgramadosXTorneo($ruta);

        // Leer filtros de query string
        $selectedCategoriaId = $request->query->getInt('categoria') ?: null;
        $selectedEquipoId = $request->query->getInt('equipo') ?: null;

        $categoriaSelected = null;
        $equipoSelected = null;
        $equipos = [];

        if ($selectedCategoriaId) {
            $categoriaSelected = $categoriaManager->obtenerCategoria($selectedCategoriaId);
            if ($categoriaSelected) {
                // Colección de equipos de la categoría (puede iterarse desde Twig)
                $equipos = $categoriaSelected->getEquipos();
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
        if ($selectedCategoriaId || $selectedEquipoId) {
            $filtrados = [];
            foreach ($partidosProgramados as $sede => $canchas) {
                foreach ($canchas as $cancha => $fechas) {
                    foreach ($fechas as $fecha => $partidos) {
                        $nuevos = [];
                        foreach ($partidos as $partido) {
                            $matchCategoria = true;
                            $matchEquipo = true;

                            if ($categoriaSelected) {
                                $matchCategoria = ($partido['categoria'] ?? '') === $categoriaSelected->getNombre();
                            }

                            if ($equipoSelected) {
                                $nombreEquipo = $equipoSelected->getNombre();
                                $local = $partido['equipoLocal'] ?? '';
                                $visitante = $partido['equipoVisitante'] ?? '';
                                $matchEquipo = (stripos($local, $nombreEquipo) !== false) || (stripos($visitante, $nombreEquipo) !== false);
                            }

                            if ($matchCategoria && $matchEquipo) {
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

    return $this->render(
        'main/torneo.html.twig',
        [
            'torneo' => $torneo,
            'categorias' => $categorias,
            'partidosProgramados' => $partidosProgramados,
            'selectedCategoriaId' => $selectedCategoriaId,
            'selectedEquipoId' => $selectedEquipoId,
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

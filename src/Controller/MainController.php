<?php

namespace App\Controller;

use App\Manager\CategoriaManager;
use App\Manager\PartidoManager;
use App\Manager\TablaManager;
use App\Manager\TorneoManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
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
        string $ruta
    ): Response {

        $torneo = $torneoManager->obtenerTorneo($ruta);
        $categorias = $torneo->getCategorias();
        $partidosProgramados = $partidoManager->obtenerPartidosProgramadosXTorneo($ruta);
        return $this->render(
            'main/torneo.html.twig',
            [
            'torneo' => $torneo,
            'categorias' => $categorias,
            'partidosProgramados' => $partidosProgramados,
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

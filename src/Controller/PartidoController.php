<?php

namespace App\Controller;

use App\Manager\CategoriaManager;
use App\Manager\GrupoManager;
use App\Manager\PartidoManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/torneo/{ruta}/categoria/{categoriaId}/partido')]
class PartidoController extends AbstractController
{
    #[Route('/partido', name: 'app_partido')]
    public function index(
        int $categoriaId,
        CategoriaManager $categoriaManager,
        GrupoManager $grupoManager,
        PartidoManager $partidoManager
    ): Response
    {
        $categoria = $categoriaManager->obtenerCategoria($categoriaId);
        $partidoManager->crearPartidoXCategoria($categoria);
        return $this->render('partido/index.html.twig', [
            'controller_name' => 'PartidoController',
        ]);
    }
}

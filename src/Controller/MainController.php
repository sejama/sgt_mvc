<?php

namespace App\Controller;

use App\Manager\TorneoManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MainController extends AbstractController
{
    #[Route('/', name: 'app_main')]
    public function index(
        TorneoManager $torneoManager
    ): Response
    {
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

    #[Route('/torneo/{ruta}', name: '_torneo')]
    public function torneo(
        TorneoManager $torneoManager,
        string $ruta
    ): Response {

        $torneo = $torneoManager->obtenerTorneo($ruta);
        $categorias = $torneo->getCategorias();
        $grupos = [];
        foreach ($categorias as $categoria) {
            $grupos[] = $categoria->getGrupos();
        }
        return $this->render(
            'main/torneo.html.twig',
            [
            'torneo' => $torneo,
            'categorias' => $categorias,
            'grupos' => $grupos,
            ]
        );
    }
}

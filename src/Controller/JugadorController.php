<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class JugadorController extends AbstractController
{
    #[Route('/jugador', name: 'app_jugador')]
    public function index(): Response
    {
        return $this->render('jugador/index.html.twig', [
            'controller_name' => 'JugadorController',
        ]);
    }
}

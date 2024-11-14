<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MainController extends AbstractController
{
    #[Route('/', name: 'app_main')]
    public function index(): Response
    {
        if ($this->getUser() != null) {
            if ($this->getUser()->getCreatedAt() == $this->getUser()->getUpdatedAt()) {
                return $this->render('usuario/cambiar_password.html.twig', ['idUser' => $this->getUser()->getId()]);
            }
        }
        return $this->render(
            'main/index.html.twig',
            [
            'controller_name' => 'MainController',
            ]
        );
    }
}

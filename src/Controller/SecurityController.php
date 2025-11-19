<?php

namespace App\Controller;

use App\Manager\UsuarioManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'security_login')]
    public function login(
        AuthenticationUtils $authenticationUtils,
        UsuarioManager $rm
    ): Response {

        if ($rm->obtenerUsuarios() === []) {
            return $this->redirectToRoute('admin_usuario_crear');
        }
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

            if ($error) {
                $this->addFlash('error', $error->getMessageKey());
            }
            return $this->render(
                'security/login.html.twig',
                [
                    'last_username' => $lastUsername,
                    'error' => $error,
                ]
            );
    }

    #[Route(path: '/logout', name: 'security_logout')]
    public function logout(): void
    {
        throw new \LogicException(
            'This method can be blank - it will be intercepted by the logout key on your firewall.'
        );
    }
}

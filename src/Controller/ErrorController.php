<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ErrorController extends AbstractController
{
    #[Route('/error', name: 'app_error_page', methods: ['GET'])]
    public function index(Request $request): Response
    {
        $status = (int) $request->query->get('status', 400);
        if ($status < 400 || $status > 599) {
            $status = 400;
        }

        $title = trim((string) $request->query->get('title', 'Error'));
        $message = trim((string) $request->query->get('message', 'Ha ocurrido un error inesperado.'));

        $response = $this->render('error/index.html.twig', [
            'status' => $status,
            'title' => $title !== '' ? $title : 'Error',
            'message' => $message !== '' ? $message : 'Ha ocurrido un error inesperado.',
        ]);
        $response->setStatusCode($status);

        return $response;
    }

    #[Route('/no-autorizado', name: 'app_error_forbidden', methods: ['GET'])]
    public function forbidden(Request $request): Response
    {
        $title = trim((string) $request->query->get('title', 'No autorizado'));
        $message = trim((string) $request->query->get('message', 'No tienes permisos para acceder a este recurso.'));

        $response = $this->render('error/forbidden.html.twig', [
            'title' => $title !== '' ? $title : 'No autorizado',
            'message' => $message !== '' ? $message : 'No tienes permisos para acceder a este recurso.',
        ]);
        $response->setStatusCode(403);

        return $response;
    }
}

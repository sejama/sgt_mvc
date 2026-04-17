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

        if ($status === 404) {
            $title = 'Pagina no encontrada';
            $message = 'Pagina no encontrada';
            $badgeClass = 'secondary';
        } elseif ($status === 401 || $status === 403) {
            $title = 'No autorizado';
            $message = 'No autorizado';
            $badgeClass = 'warning';
        } else {
            $title = trim((string) $request->query->get('title', 'Error'));
            $message = trim((string) $request->query->get('message', 'Ha ocurrido un error inesperado.'));
            $title = $title !== '' ? $title : 'Error';
            $message = $message !== '' ? $message : 'Ha ocurrido un error inesperado.';
            $badgeClass = 'secondary';
        }

        $response = $this->render('error/index.html.twig', [
            'status' => $status,
            'title' => $title,
            'message' => $message,
            'badgeClass' => $badgeClass,
        ]);
        $response->setStatusCode($status);

        return $response;
    }

    #[Route('/no-autorizado', name: 'app_error_forbidden', methods: ['GET'])]
    public function forbidden(): Response
    {
        $response = $this->render('error/index.html.twig', [
            'status' => 403,
            'title' => 'No autorizado',
            'message' => 'No autorizado',
            'badgeClass' => 'warning',
        ]);
        $response->setStatusCode(403);

        return $response;
    }
}

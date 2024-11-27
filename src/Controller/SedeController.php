<?php

namespace App\Controller;

use App\Exception\AppException;
use App\Manager\SedeManager;
use App\Manager\TorneoManager;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Throwable;

class SedeController extends AbstractController
{
    #[Route('/{ruta}/sede/nuevo', name: 'app_torneo_sede_nuevo', methods: ['GET', 'POST'])]
    public function agregarSede(
        string $ruta,
        TorneoManager $torneoManager,
        SedeManager $sedeManager,
        EntityManagerInterface $entityManager,
        Request $request,
        LoggerInterface $logger
    ): Response {
        $torneo = $torneoManager->obtenerTorneo($ruta);
        if ($this->getUser() !== null) {
            if ($request->isMethod('POST')) {
                try {
                    $nombre = $request->request->get('sedeNombre');
                    $direccion = $request->request->get('sedeDireccion');
                    $sedeManager->crearSede(
                        $torneo,
                        $nombre,
                        $direccion
                    );
                    $entityManager->flush();
                    $this->addFlash('success', "Sede creada con éxito.");
                    return $this->redirectToRoute('app_torneo');
                } catch (AppException $ae) {
                    $logger->error($ae->getMessage());
                    $this->addFlash('error', $ae->getMessage());
                } catch (Throwable $e) {
                    $logger->error($e->getMessage());
                    $this->addFlash('error', "Ha ocurrido un error inesperado. Por favor, intente nuevamente.");
                }
            }
            return $this->render(
                'sede/nuevo.html.twig',
                [
                    'torneo' => $torneo,
                ]
            );
        }
        return $this->redirectToRoute('app_login');
    }

    #[Route('/{ruta}/sede/{id}/editar', name: 'app_torneo_sede_editar', methods: ['GET', 'POST'])]
    public function editarSede(
        string $ruta,
        int $id,
        TorneoManager $torneoManager,
        SedeManager $sedeManager,
        EntityManagerInterface $entityManager,
        Request $request,
        LoggerInterface $logger
    ): Response {
        $torneo = $torneoManager->obtenerTorneo($ruta);
        if ($this->getUser() !== null) {
            $sede = $sedeManager->obtenerSede($id);
            if ($request->isMethod('POST')) {
                try {
                    $nombre = $request->request->get('sedeNombre');
                    $direccion = $request->request->get('sedeDireccion');
                    $sedeManager->editarSede(
                        $torneo,
                        $sede,
                        $nombre,
                        $direccion
                    );
                    $entityManager->flush();
                    $this->addFlash('success', "Sede editada con éxito.");
                    return $this->redirectToRoute('app_torneo');
                } catch (AppException $ae) {
                    $logger->error($ae->getMessage());
                    $this->addFlash('error', $ae->getMessage());
                } catch (Throwable $e) {
                    $logger->error($e->getMessage());
                    $this->addFlash('error', "Ha ocurrido un error inesperado. Por favor, intente nuevamente.");
                }
            }
            return $this->render(
                'sede/editar.html.twig',
                [
                    'torneo' => $torneo,
                    'sede' => $sede,
                ]
            );
        }
        return $this->redirectToRoute('app_login');
    }

    #[Route('/{ruta}/sede/eliminar/{id}', name: 'app_torneo_sede_eliminar', methods: ['GET'])]
    public function eliminarSede(
        string $ruta,
        int $id,
        TorneoManager $torneoManager,
        SedeManager $sedeManager,
        EntityManagerInterface $entityManager,
        LoggerInterface $logger
    ): Response {
        $torneo = $torneoManager->obtenerTorneo($ruta);
        if ($this->getUser() !== null) {
            try {
                $sede = $sedeManager->obtenerSede($id);
                $sedeManager->eliminarSede($sede);
                $entityManager->flush();
                $this->addFlash('success', "Sede eliminada con éxito.");
                return $this->redirectToRoute('app_torneo');
            } catch (AppException $ae) {
                $logger->error($ae->getMessage());
                $this->addFlash('error', $ae->getMessage());
            } catch (Throwable $e) {
                $logger->error($e->getMessage());
                $this->addFlash('error', "Ha ocurrido un error inesperado. Por favor, intente nuevamente.");
            }
        }
        return $this->redirectToRoute('app_login');
    }
}

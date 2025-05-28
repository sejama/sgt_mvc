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
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Throwable;

#[Route('/admin/torneo/{ruta}/sede')]
#[IsGranted('ROLE_ADMIN')]
class SedeController extends AbstractController
{
    #[Route('/nuevo', name: 'admin_sede_crear', methods: ['GET', 'POST'])]
    public function crearSede(
        string $ruta,
        TorneoManager $torneoManager,
        SedeManager $sedeManager,
        Request $request,
        EntityManagerInterface $entityManager,
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
                    return $this->redirectToRoute('admin_torneo_index');
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
        return $this->redirectToRoute('security_login');
    }

    #[Route('/{sedeId}/editar', name: 'admin_sede_editar', methods: ['GET', 'POST'])]
    public function editarSede(
        string $ruta,
        int $sedeId,
        TorneoManager $torneoManager,
        SedeManager $sedeManager,
        Request $request,
        LoggerInterface $logger
    ): Response {
        $torneo = $torneoManager->obtenerTorneo($ruta);
        if ($this->getUser() !== null) {
            $sede = $sedeManager->obtenerSede($sedeId);
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
                    $this->addFlash('success', "Sede editada con éxito.");
                    return $this->redirectToRoute('admin_torneo_index');
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
        return $this->redirectToRoute('security_login');
    }

    #[Route('/{sedeId}/eliminar', name: 'admin_sede_eliminar', methods: ['GET'])]
    public function eliminarSede(
        string $ruta,
        int $sedeId,
        TorneoManager $torneoManager,
        SedeManager $sedeManager,
        LoggerInterface $logger
    ): Response {
        $torneo = $torneoManager->obtenerTorneo($ruta);
        if ($this->getUser() !== null) {
            try {
                $sede = $sedeManager->obtenerSede($sedeId);
                $sedeManager->eliminarSede($sede);
                $this->addFlash('success', "Sede eliminada con éxito.");
                return $this->redirectToRoute('admin_torneo_index');
            } catch (AppException $ae) {
                $logger->error($ae->getMessage());
                $this->addFlash('error', $ae->getMessage());
            } catch (Throwable $e) {
                $logger->error($e->getMessage());
                $this->addFlash('error', "Ha ocurrido un error inesperado. Por favor, intente nuevamente.");
            }
        }
        return $this->redirectToRoute('security_login');
    }
}

<?php

namespace App\Controller;

use App\Exception\AppException;
use App\Manager\CanchaManager;
use App\Manager\SedeManager;
use App\Manager\TorneoManager;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/torneo/{ruta}/sede/{sedeId}/cancha')]
class CanchaController extends AbstractController
{
    #[Route('/', name: 'app_torneo_sede_cancha', methods: ['GET'])]
    public function index(
        string $ruta,
        int $sedeId,
        TorneoManager $torneoManager,
        SedeManager $sedeManager,
    ): Response {
        $torneo = $torneoManager->obtenerTorneo($ruta);
        $sede = $sedeManager->obtenerSede($sedeId);
        return $this->render('cancha/index.html.twig', [
            'torneo' => $torneo,
            'sede' => $sede,
        ]);
    }

    #[Route('/nuevo', name: 'app_torneo_sede_cancha_nueva', methods: ['GET', 'POST'])]
    public function agregarCancha(
        string $ruta,
        int $sedeId,
        Request $request,
        TorneoManager $torneoManager,
        SedeManager $sedeManager,
        CanchaManager $canchaManager,
        LoggerInterface $logger
    ): Response {
        $torneo = $torneoManager->obtenerTorneo($ruta);
        $sede = $sedeManager->obtenerSede($sedeId);

        if ($request->isMethod('POST')) {
            // Procesar el formulario
            try {
                $nombre = $request->request->get('nombreCancha');
                $descripcion = $request->request->get('descripcionCancha') ?? '';
                $canchaManager->crearCancha($sede, $nombre, $descripcion);
                $this->addFlash('success', 'Cancha creada con éxito.');
                return $this->redirectToRoute('app_torneo_sede_cancha', ['ruta' => $ruta, 'sedeId' => $sede->getId()]);
            } catch (AppException $ae) {
                $logger->error($ae->getMessage());
                $this->addFlash('error', $ae->getMessage());
            } catch (\Throwable $e) {
                $logger->error($e->getMessage());
                $this->addFlash('error', 'Ha ocurrido un error inesperado. Por favor, intente nuevamente.');
            }
        }
        return $this->render('cancha/nuevo.html.twig', [
            'torneo' => $torneo,
            'sede' => $sede,
        ]);
    }

    #[Route(
        '/{canchaId}/editar',
        name: 'app_torneo_sede_cancha_editar',
        methods: ['GET', 'POST']
    )]
    public function editarCancha(
        string $ruta,
        int $sedeId,
        int $canchaId,
        Request $request,
        TorneoManager $torneoManager,
        SedeManager $sedeManager,
        CanchaManager $canchaManager,
        LoggerInterface $logger
    ): Response {
        $torneo = $torneoManager->obtenerTorneo($ruta);
        $sede = $sedeManager->obtenerSede($sedeId);
        $cancha = $canchaManager->obtenerCancha($canchaId);

        if ($request->isMethod('POST')) {
            // Procesar el formulario
            try {
                $nombre = $request->request->get('nombreCancha');
                $descripcion = $request->request->get('descripcionCancha') ?? '';
                $canchaManager->editarCancha($cancha, $nombre, $descripcion);
                $this->addFlash('success', 'Cancha editada con éxito.');
                return $this->redirectToRoute('app_torneo_sede_cancha', ['ruta' => $ruta, 'sedeId' => $sede->getId()]);
            } catch (AppException $ae) {
                $logger->error($ae->getMessage());
                $this->addFlash('error', $ae->getMessage());
            } catch (\Throwable $e) {
                $logger->error($e->getMessage());
                $this->addFlash('error', 'Ha ocurrido un error inesperado. Por favor, intente nuevamente.');
            }
        }
        return $this->render('cancha/editar.html.twig', [
            'torneo' => $torneo,
            'sede' => $sede,
            'cancha' => $cancha,
        ]);
    }

    #[Route('/{canchaId}/eliminar', name: 'app_torneo_sede_cancha_eliminar', methods: ['GET'])]
    public function eliminarCancha(
        string $ruta,
        int $sedeId,
        int $canchaId,
        Request $request,
        TorneoManager $torneoManager,
        SedeManager $sedeManager,
        CanchaManager $canchaManager,
        LoggerInterface $logger
    ): Response {
        $torneo = $torneoManager->obtenerTorneo($ruta);
        $sede = $sedeManager->obtenerSede($sedeId);
        $cancha = $canchaManager->obtenerCancha($canchaId);

        if ($request->isMethod('GET')) {
            // Procesar el formulario
            try {
                $canchaManager->eliminarCancha($cancha);
                $this->addFlash('success', 'Cancha eliminada con éxito.');
                return $this->redirectToRoute('app_torneo_sede_cancha', ['ruta' => $ruta, 'sedeId' => $sede->getId()]);
            } catch (AppException $ae) {
                $logger->error($ae->getMessage());
                $this->addFlash('error', $ae->getMessage());
            } catch (\Throwable $e) {
                $logger->error($e->getMessage());
                $this->addFlash('error', 'Ha ocurrido un error inesperado. Por favor, intente nuevamente.');
            }
        }
        return $this->redirectToRoute('app_torneo_sede_cancha', ['ruta' => $ruta, 'sedeId' => $sede->getId()]);
    }
}

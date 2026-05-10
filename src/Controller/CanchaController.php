<?php

namespace App\Controller;

use App\Entity\Usuario;
use App\Exception\AppException;
use App\Manager\CanchaManager;
use App\Manager\SedeManager;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin/torneo/{ruta}/sede/{sedeId}/cancha')]
#[IsGranted('ROLE_ADMIN')]
class CanchaController extends AbstractController
{
    #[Route('/', name: 'admin_cancha_index', methods: ['GET'])]
    public function indexCancha(
        string $ruta,
        int $sedeId,
        SedeManager $sedeManager,
    ): Response {
        $sede = $sedeManager->obtenerSede($sedeId);
        if ($sede === null) {
            throw $this->createNotFoundException('Sede no encontrada');
        }
        return $this->render(
            'cancha/index.html.twig', [
            'ruta' => $ruta,
            'sede' => $sede,
            ]
        );
    }

    #[Route('/nuevo', name: 'admin_cancha_crear', methods: ['GET', 'POST'])]
    public function crearCancha(
        string $ruta,
        int $sedeId,
        Request $request,
        SedeManager $sedeManager,
        CanchaManager $canchaManager,
        LoggerInterface $logger
    ): Response {
        $sede = $sedeManager->obtenerSede($sedeId);
        if ($sede === null) {
            throw $this->createNotFoundException('Sede no encontrada');
        }
        /** @var \App\Entity\Sede $sede */

        if ($request->isMethod('POST')) {
            // Procesar el formulario
            try {
                $nombre = $request->request->get('nombreCancha');
                $descripcion = $request->request->get('descripcionCancha') ?? '';
                $canchaManager->crearCancha($sede, $nombre, $descripcion);
                $this->addFlash('success', 'Cancha creada con éxito.');
                $user = $this->getUser();
                if ($user === null) {
                    throw $this->createAccessDeniedException('Usuario no autenticado');
                }
                /** @var Usuario $user */
                $logger->info('Cancha creada: ' . 'nueva' . ', por el usuario: ' .  $user->getId());
                return $this->redirectToRoute('admin_cancha_index', ['ruta' => $ruta, 'sedeId' => $sede->getId()]);
            } catch (AppException $ae) {
                $logger->error($ae->getMessage());
                $this->addFlash('error', $ae->getMessage());
            } catch (\Throwable $e) {
                $logger->error($e->getMessage());
                $this->addFlash('error', 'Ha ocurrido un error inesperado. Por favor, intente nuevamente.');
            }
        }
        return $this->render(
            'cancha/nuevo.html.twig', [
            'ruta' => $ruta,
            'sede' => $sede,
            ]
        );
    }

    #[Route(
        '/{canchaId}/editar',
        name: 'admin_cancha_editar',
        methods: ['GET', 'POST']
    )]
    public function editarCancha(
        string $ruta,
        int $sedeId,
        int $canchaId,
        Request $request,
        SedeManager $sedeManager,
        CanchaManager $canchaManager,
        LoggerInterface $logger
    ): Response {
        $sede = $sedeManager->obtenerSede($sedeId);
        if ($sede === null) {
            throw $this->createNotFoundException('Sede no encontrada');
        }
        /** @var \App\Entity\Sede $sede */
        $cancha = $canchaManager->obtenerCancha($canchaId);
        if ($cancha === null) {
            throw $this->createNotFoundException('Cancha no encontrada');
        }
        /** @var \App\Entity\Cancha $cancha */

        if ($request->isMethod('POST')) {
            // Procesar el formulario
            try {
                $nombre = $request->request->get('nombreCancha');
                $descripcion = $request->request->get('descripcionCancha') ?? '';
                $canchaManager->editarCancha($cancha, $nombre, $descripcion);
                $this->addFlash('success', 'Cancha editada con éxito.');
                $user = $this->getUser();
                if ($user === null) {
                    throw $this->createAccessDeniedException('Usuario no autenticado');
                }
                /** @var Usuario $user */
                $logger->info('Cancha editada: ' . $cancha->getId() . ', por el usuario: ' .  $user->getId());
                return $this->redirectToRoute('admin_cancha_index', ['ruta' => $ruta, 'sedeId' => $sede->getId()]);
            } catch (AppException $ae) {
                $logger->error($ae->getMessage());
                $this->addFlash('error', $ae->getMessage());
            } catch (\Throwable $e) {
                $logger->error($e->getMessage());
                $this->addFlash('error', 'Ha ocurrido un error inesperado. Por favor, intente nuevamente.');
            }
        }
        return $this->render(
            'cancha/editar.html.twig', [
            'torneo' => $sede->getTorneo(),
            'sede' => $sede,
            'cancha' => $cancha,
            ]
        );
    }

    #[Route('/{canchaId}/eliminar', name: 'admin_cancha_eliminar', methods: ['POST'])]
    public function eliminarCancha(
        string $ruta,
        int $sedeId,
        int $canchaId,
        Request $request,
        SedeManager $sedeManager,
        CanchaManager $canchaManager,
        LoggerInterface $logger
    ): Response {
        $sede = $sedeManager->obtenerSede($sedeId);
        if ($sede === null) {
            throw $this->createNotFoundException('Sede no encontrada');
        }
        /** @var \App\Entity\Sede $sede */
        $cancha = $canchaManager->obtenerCancha($canchaId);
        if ($cancha === null) {
            throw $this->createNotFoundException('Cancha no encontrada');
        }
        /** @var \App\Entity\Cancha $cancha */
        if (!$this->isCsrfTokenValid('delete_cancha_' . $canchaId, (string) $request->request->get('_token'))) {
            throw $this->createAccessDeniedException('Token CSRF inválido.');
        }

        try {
            $canchaManager->eliminarCancha($cancha);
            $this->addFlash('success', 'Cancha eliminada con éxito.');
            $user = $this->getUser();
            if ($user === null) {
                throw $this->createAccessDeniedException('Usuario no autenticado');
            }
            /** @var Usuario $user */
            $logger->info('Cancha eliminada: ' . $cancha->getId() . ', por el usuario: ' .  $user->getId());
            return $this->redirectToRoute('admin_cancha_index', ['ruta' => $ruta, 'sedeId' => $sede->getId()]);
        } catch (AppException $ae) {
            $logger->error($ae->getMessage());
            $this->addFlash('error', $ae->getMessage());
        } catch (\Throwable $e) {
            $logger->error($e->getMessage());
            $this->addFlash('error', 'Ha ocurrido un error inesperado. Por favor, intente nuevamente.');
        }
        return $this->redirectToRoute('admin_cancha_index', ['ruta' => $ruta, 'sedeId' => $sede->getId()]);
    }
}

<?php

namespace App\Controller;

use App\Enum\Genero;
use App\Exception\AppException;
use App\Manager\CategoriaManager;
use App\Manager\SedeManager;
use App\Manager\TorneoManager;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use Throwable;

#[Route('/torneo')]
class TorneoController extends AbstractController
{
    #[Route('/', name: 'app_torneo', methods: ['GET'])]
    public function index(
        TorneoManager $torneoManager
    ): Response {
        if ($this->getUser() !== null) {
            $torneos = $torneoManager->obtenerTorneos((int)$this->getUser()->getId());
            return $this->render(
                'torneo/index.html.twig',
                [
                'torneos' => $torneos,
                ]
            );
        }
        return $this->redirectToRoute('app_login');
    }

    #[Route('/nuevo', name: 'app_torneo_nuevo', methods: ['GET', 'POST'])]
    public function nuevoTorneo(
        Request $request,
        EntityManagerInterface $entityManager,
        TorneoManager $torneoManager,
        CategoriaManager $categoriaManager,
        SedeManager $sedeManager,
        LoggerInterface $logger
    ): Response {
        if ($this->getUser() !== null) {
            if ($request->isMethod('POST')) {
                try {
                    //var_dump($request->request); die();
                    // Handle the submission of the form
                    $nombre = $request->request->get('nombre');
                    $ruta = $request->request->get('ruta');
                    $descripcion = $request->request->get('descripcion');
                    $fecha_inicio_torneo = str_replace("T", " ", $request->request->get('fechaInicioTorneo'));
                    $fecha_fin_torneo = str_replace("T", " ", $request->request->get('fechaFinTorneo'));
                    $fecha_inicio_inscripcion = str_replace("T", " ", $request->request->get('fechaInicioInscripcion'));
                    $fecha_fin_inscripcion = str_replace("T", " ", $request->request->get('fechaFinInscripcion'));
                    $categorias = $request->request->all('categorias');
                    $sedes = $request->request->all('sedes');
                    $torneo = $torneoManager->crearTorneo(
                        $nombre,
                        $ruta,
                        $descripcion,
                        $fecha_inicio_torneo,
                        $fecha_fin_torneo,
                        $fecha_inicio_inscripcion,
                        $fecha_fin_inscripcion,
                        $this->getUser()
                    );
                    foreach ($categorias as $categoria) {
                        $categoriaManager->crearCategoria(
                            $torneo,
                            $categoria['generoId'],
                            $categoria['categoriaNombre'],
                            $categoria['categoriaNombreCorto']
                        );
                    }
                    foreach ($sedes as $sede) {
                        $sedeManager->crearSede(
                            $torneo,
                            $sede['sedeNombre'],
                            $sede['sedeDireccion'],
                        );
                    }
                    $entityManager->flush();
                    $this->addFlash('success', "Torneo creado con éxito.");
                    return $this->redirectToRoute('app_torneo');
                } catch (AppException $ae) {
                    $logger->error($ae->getMessage());
                    $this->addFlash('error', $ae->getMessage());
                } catch (Throwable $e) {
                    $logger->error($e->getMessage());
                    $this->addFlash('error', "Ha ocurrido un error inesperado. Por favor, intente nuevamente.");
                }
            }
            foreach (Genero::cases() as $genero) {
                $generos[] = $genero->value;
            }
            return $this->render(
                'torneo/nuevo.html.twig',
                [
                'generos' => $generos,
                'hoy' => (
                    new \DateTimeImmutable('now', new \DateTimeZone('America/Argentina/Buenos_Aires'))
                    )->modify('-1 day'),
                ]
            );
        }
        return $this->redirectToRoute('app_login');
    }

    #[Route('/{ruta}/eliminar', name: 'app_torneo_eliminar', methods: ['GET'])]
    public function eliminarTorneo(
        string $ruta,
        TorneoManager $torneoManager,
        CategoriaManager $categoriaManager,
        SedeManager $sedeManager,
        EntityManagerInterface $entityManager,
        LoggerInterface $logger
    ): Response {
        if ($this->getUser() !== null) {
            try {
                $torneo = $torneoManager->obtenerTorneo($ruta);
                foreach ($torneo->getCategorias() as $categoria) {
                    $categoriaManager->eliminarCategoria($categoria);
                }
                foreach ($torneo->getSedes() as $sede) {
                    $sedeManager->eliminarSede($sede);
                }
                $torneoManager->eliminarTorneo($torneo);
                $entityManager->flush();
                $this->addFlash('success', "Torneo eliminado con éxito.");
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

    #[Route('/{ruta}/editar', name: 'app_torneo_editar', methods: ['GET', 'POST'])]
    public function editarTorneo(
        string $ruta,
        Request $request,
        EntityManagerInterface $entityManager,
        TorneoManager $torneoManager,
        CategoriaManager $categoriaManager,
        SedeManager $sedeManager,
        LoggerInterface $logger
    ): Response {
        if ($this->getUser() !== null) {
            $torneo = $torneoManager->obtenerTorneo($ruta);
            if ($torneo->getCreador()->getId() === $this->getUser()->getId()) {
                if ($request->isMethod('POST')) {
                    try {
                        // Handle the submission of the form
                        $nombre = $request->request->get('nombre');
                        $ruta = $request->request->get('ruta');
                        $descripcion = $request->request->get('descripcion');
                        $fecha_inicio_torneo = $request->request->get('fechaInicioTorneo') . ' ' .
                            $request->request->get('horaInicioTorneo');
                        $fecha_fin_torneo = $request->request->get('fechaFinTorneo') . ' ' .
                            $request->request->get('horaFinTorneo');
                        $fecha_inicio_inscripcion = $request->request->get('fechaInicioInscripcion') . ' ' .
                            $request->request->get('horaInicioInscripcion');
                        $fecha_fin_inscripcion = $request->request->get('fechaFinInscripcion') . ' ' .
                            $request->request->get('horaFinInscripcion');
                        $categorias = $request->request->all('categorias');
                        $sedes = $request->request->all('sedes');
                        $torneo = $torneoManager->editarTorneo(
                            $torneo,
                            $nombre,
                            $ruta,
                            $descripcion,
                            $fecha_inicio_torneo,
                            $fecha_fin_torneo,
                            $fecha_inicio_inscripcion,
                            $fecha_fin_inscripcion
                        );
                        $this->addFlash('success', "Torneo editado con éxito.");
                        return $this->redirectToRoute('app_torneo');
                    } catch (AppException $ae) {
                        $logger->error($ae->getMessage());
                        $this->addFlash('error', $ae->getMessage());
                    } catch (Throwable $e) {
                        $logger->error($e->getMessage());
                        $this->addFlash('error', "Ha ocurrido un error inesperado. Por favor, intente nuevamente.");
                    }
                }
                foreach (Genero::cases() as $genero) {
                    $generos[] = $genero->value;
                }
                return $this->render(
                    'torneo/editar.html.twig',
                    [
                        'torneo' => $torneo,
                        'generos' => $generos,
                    ]
                );
            }
            return $this->redirectToRoute('app_login');
        }
    }

    #[Route('/{ruta}/editar/reglamento', name: 'app_torneo_reglamento_editar', methods: ['GET', 'POST'])]
    public function editarReglamento(
        string $ruta,
        TorneoManager $torneoManager,
        Request $request,
        LoggerInterface $logger
    ): Response {
        if ($this->getUser() !== null) {
            $torneo = $torneoManager->obtenerTorneo($ruta);
            if ($torneo->getCreador()->getId() === $this->getUser()->getId()) {
                if ($request->isMethod('POST')) {
                    try {
                        $reglamento = $request->request->get('reglamento');
                        $torneoManager->editarReglamento($torneo, $reglamento);
                        $this->addFlash('success', "Reglamento editado con éxito.");
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
                    'torneo/reglamento/editar.html.twig',
                    [
                        'torneo' => $torneo,
                    ]
                );
            }
        }
        return $this->redirectToRoute('app_login');
    }
}

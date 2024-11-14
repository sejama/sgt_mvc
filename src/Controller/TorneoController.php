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
                    return $this->redirectToRoute('app_torneo');
                } catch (Throwable $e) {
                    $logger->error($e->getMessage());
                    $this->addFlash('error', "Ha ocurrido un error inesperado. Por favor, intente nuevamente.");
                    return $this->redirectToRoute('app_torneo');
                }
            }
            foreach (Genero::cases() as $genero) {
                $generos[] = $genero->value;
            }
            return $this->render(
                'torneo/nuevo.html.twig',
                [
                'generos' => $generos,
                ]
            );
        }
        return $this->redirectToRoute('app_login');
    }

    #[Route('/eliminar/{ruta}', name: 'app_torneo_eliminar', methods: ['GET'])]
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
                return $this->redirectToRoute('app_torneo');
            } catch (Throwable $e) {
                $logger->error($e->getMessage());
                $this->addFlash('error', "Ha ocurrido un error inesperado. Por favor, intente nuevamente.");
                return $this->redirectToRoute('app_torneo');
            }
        }
        return $this->redirectToRoute('app_login');
    }

    #[Route('/editar/{ruta}', name: 'app_torneo_editar', methods: ['GET', 'POST'])]
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
                        return $this->redirectToRoute('app_torneo');
                    } catch (Throwable $e) {
                        $logger->error($e->getMessage());
                        $this->addFlash('error', "Ha ocurrido un error inesperado. Por favor, intente nuevamente.");
                        return $this->redirectToRoute('app_torneo');
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

    #[Route('/{ruta}/categoria/nuevo', name: 'app_torneo_categoria_nuevo', methods: ['GET', 'POST'])]
    public function agregarCategoria(
        string $ruta,
        TorneoManager $torneoManager,
        CategoriaManager $categoriaManager,
        EntityManagerInterface $entityManager,
        Request $request,
        LoggerInterface $logger
    ): Response {
        $torneo = $torneoManager->obtenerTorneo($ruta);
        if ($this->getUser() !== null) {
            if ($request->isMethod('POST')) {
                try {
                    $genero = $request->request->get('genero');
                    $nombre = $request->request->get('nombre');
                    $nombreCorto = $request->request->get('nombreCorto');
                    $categoriaManager->crearCategoria(
                        $torneo,
                        $genero,
                        $nombre,
                        $nombreCorto
                    );
                    $entityManager->flush();
                    $this->addFlash('success', "Categoría creada con éxito.");
                    return $this->redirectToRoute('app_torneo');
                } catch (AppException $ae) {
                    $logger->error($ae->getMessage());
                    $this->addFlash('error', $ae->getMessage());
                    return $this->redirectToRoute('app_torneo');
                } catch (Throwable $e) {
                    $logger->error($e->getMessage());
                    $this->addFlash('error', "Ha ocurrido un error inesperado. Por favor, intente nuevamente.");
                    return $this->redirectToRoute('app_torneo');
                }
            }
            foreach (Genero::cases() as $genero) {
                $generos[] = $genero->value;
            }
            return $this->render(
                'torneo/categoria/nuevo.html.twig',
                [
                    'generos' => $generos,
                    'torneo' => $torneo,
                ]
            );
        }
        return $this->redirectToRoute('app_login');
    }

    #[Route('/{ruta}/categoria/eliminar/{id}', name: 'app_torneo_categoria_eliminar', methods: ['GET'])]
    public function eliminarCategoria(
        string $ruta,
        int $id,
        TorneoManager $torneoManager,
        CategoriaManager $categoriaManager,
        EntityManagerInterface $entityManager,
        LoggerInterface $logger
    ): Response {
        $torneo = $torneoManager->obtenerTorneo($ruta);
        if ($this->getUser() !== null) {
            try {
                $categoria = $categoriaManager->obtenerCategoria($id);
                $categoriaManager->eliminarCategoria($categoria);
                $entityManager->flush();
                $this->addFlash('success', "Categoría eliminada con éxito.");
                return $this->redirectToRoute('app_torneo');
            } catch (AppException $ae) {
                $logger->error($ae->getMessage());
                $this->addFlash('error', $ae->getMessage());
                return $this->redirectToRoute('app_torneo');
            } catch (Throwable $e) {
                $logger->error($e->getMessage());
                $this->addFlash('error', "Ha ocurrido un error inesperado. Por favor, intente nuevamente.");
                return $this->redirectToRoute('app_torneo');
            }
        }
        return $this->redirectToRoute('app_login');
    }

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
                    return $this->redirectToRoute('app_torneo');
                } catch (Throwable $e) {
                    $logger->error($e->getMessage());
                    $this->addFlash('error', "Ha ocurrido un error inesperado. Por favor, intente nuevamente.");
                    return $this->redirectToRoute('app_torneo');
                }
            }
            return $this->render(
                'torneo/sede/nuevo.html.twig',
                [
                    'torneo' => $torneo,
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
                return $this->redirectToRoute('app_torneo');
            } catch (Throwable $e) {
                $logger->error($e->getMessage());
                $this->addFlash('error', "Ha ocurrido un error inesperado. Por favor, intente nuevamente.");
                return $this->redirectToRoute('app_torneo');
            }
        }
        return $this->redirectToRoute('app_login');
    }

}
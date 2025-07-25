<?php

namespace App\Controller;

use App\Enum\Genero;
use App\Exception\AppException;
use App\Manager\CategoriaManager;
use App\Manager\TorneoManager;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Throwable;

#[Route('/admin/torneo/{ruta}/categoria')]
#[IsGranted('ROLE_ADMIN')]
class CategoriaController extends AbstractController
{
    #[Route('/nuevo', name: 'admin_categoria_crear', methods: ['GET', 'POST'])]
    public function crearCategoria(
        string $ruta,
        TorneoManager $torneoManager,
        CategoriaManager $categoriaManager,
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
                    $this->addFlash('success', "Categoría creada con éxito.");
                    $logger->info('Categoria creada: ' . 'nueva' . ', por el usuario: ' .  $this->getUser()->getId());
                    return $this->redirectToRoute('admin_torneo_index');
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
                'categoria/nuevo.html.twig',
                [
                    'generos' => $generos,
                    'torneo' => $torneo,
                ]
            );
        }
        return $this->redirectToRoute('security_login');
    }

    #[Route('/{categoriaId}/editar/', name: 'admin_categoria_editar', methods: ['GET', 'POST'])]
    public function editarCategoria(
        string $ruta,
        int $categoriaId,
        TorneoManager $torneoManager,
        CategoriaManager $categoriaManager,
        Request $request,
        LoggerInterface $logger
    ): Response {
        $torneo = $torneoManager->obtenerTorneo($ruta);
        if ($this->getUser() !== null) {
            $categoria = $categoriaManager->obtenerCategoria($categoriaId);
            if ($request->isMethod('POST')) {
                try {
                    $genero = $request->request->get('genero');
                    $nombre = $request->request->get('nombre');
                    $nombreCorto = $request->request->get('nombreCorto');
                    $categoriaManager->editarCategoria(
                        $categoria,
                        $genero,
                        $nombre,
                        $nombreCorto
                    );
                    $this->addFlash('success', "Categoría editada con éxito.");
                    $logger->info('Categoria editada: ' . $categoria->getId() . ', por el usuario: ' .  $this->getUser()->getId());
                    return $this->redirectToRoute('admin_torneo_index');
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
                'categoria/editar.html.twig',
                [
                    'generos' => $generos,
                    'torneo' => $torneo,
                    'categoria' => $categoria,
                ]
            );
        }
        return $this->redirectToRoute('security_login');
    }

    #[Route(
        '/{categoriaId}/editar/disputa/',
        name: 'admin_categoria_editar_disputa',
        methods: ['GET', 'POST']
    )]
    public function editarDisputa(
        string $ruta,
        int $categoriaId,
        TorneoManager $torneoManager,
        CategoriaManager $categoriaManager,
        Request $request,
        LoggerInterface $logger
    ): Response {
        $torneo = $torneoManager->obtenerTorneo($ruta);
        if ($this->getUser() !== null) {
            $categoria = $categoriaManager->obtenerCategoria($categoriaId);
            if ($request->isMethod('POST')) {
                try {
                    $disputa = $request->request->get('disputa');
                    $categoriaManager->editarDisputa($categoria, $disputa);
                    $this->addFlash('success', "Disputa editada con éxito.");
                    $logger->info('Disputa editada en la categoria: ' . $categoria->getId() . ', por el usuario: ' .  $this->getUser()->getId());
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
                'categoria/editar_disputa.html.twig',
                [
                    'torneo' => $torneo,
                    'categoria' => $categoria,
                ]
            );
        }
        return $this->redirectToRoute('security_login');
    }

    #[Route('/{categoriaId}/eliminar', name: 'admin_categoria_eliminar', methods: ['GET'])]
    public function eliminarCategoria(
        string $ruta,
        int $categoriaId,
        TorneoManager $torneoManager,
        CategoriaManager $categoriaManager,
        LoggerInterface $logger
    ): Response {
        $torneo = $torneoManager->obtenerTorneo($ruta);
        if ($this->getUser() !== null) {
            try {
                $categoriaManager->eliminarCategoria($categoriaId);
                $this->addFlash('success', "Categoría eliminada con éxito.");
                $logger->info('Categoria eliminada: ' . $categoriaId . ', por el usuario: ' .  $this->getUser()->getId());
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

    #[Route('/{categoriaId}/cerrar', name: 'admin_categoria_cerrar', methods: ['POST'])]
    public function cerrarCategoria(
        string $ruta,
        int $categoriaId,
        CategoriaManager $categoriaManager,
        LoggerInterface $logger
    ): Response {
        if ($this->getUser() !== null) {
            try {
                $categoria = $categoriaManager->obtenerCategoria($categoriaId);
                $categoriaManager->cerrarCategoria($categoria);
                $this->addFlash('success', "Categoría cerrada con éxito.");
                $logger->info('Categoria cerrada: ' . $categoria->getId() . ', por el usuario: ' .  $this->getUser()->getId());
                return $this->redirectToRoute('admin_equipo_index', [
                    'ruta' => $ruta,
                    'categoriaId' => $categoriaId,
                ]);
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

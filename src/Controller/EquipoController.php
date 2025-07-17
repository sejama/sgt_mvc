<?php

namespace App\Controller;

use App\Enum\TipoDocumento;
use App\Exception\AppException;
use App\Manager\CategoriaManager;
use App\Manager\EquipoManager;
use App\Manager\JugadorManager;
use App\Manager\TorneoManager;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin/torneo/{ruta}/categoria/{categoriaId}/equipo')]
#[IsGranted('ROLE_ADMIN')]
class EquipoController extends AbstractController
{
    #[Route('/', name: 'admin_equipo_index', methods: ['GET'])]
    public function indexEquipo(
        string $ruta,
        int $categoriaId,
        TorneoManager $torneoManager,
        EquipoManager $equipoManager,
        CategoriaManager $categoriaManager
    ): Response {
        $torneo = $torneoManager->obtenerTorneo($ruta);
        $categoria = $categoriaManager->obtenerCategoria($categoriaId);
        $equipos = $equipoManager->obtenerEquiposPorCategoria($categoria);
        return $this->render(
            'equipo/index.html.twig', [
            'torneo' => $torneo,
            'categoria' => $categoria,
            'equipos' => $equipos,
            ]
        );
    }

    #[Route('/nuevo', name: 'admin_equipo_crear', methods: ['GET', 'POST'])]
    public function crearEquipo(
        string $ruta,
        int $categoriaId,
        Request $request,
        EquipoManager $equipoManager,
        JugadorManager $jugadorManager,
        CategoriaManager $categoriaManager,
        LoggerInterface $logger
    ): Response {
        if ($request->isMethod('POST')) {
            try {
                $nombre = $request->request->get('nombre');
                $nombreCorto = $request->request->get('nombreCorto');
                $pais = $request->request->get('pais') ?? null;
                $provincia = $request->request->get('provincia') ?? null;
                $localidad = $request->request->get('localidad') ?? null;
                $delegado = $request->request->all('delegado');
                
                $categoria = $categoriaManager->obtenerCategoria($categoriaId);
                
                $equipo = $equipoManager->crearEquipo($categoria, $nombre, $nombreCorto, $pais, $provincia, $localidad);
                
                $jugadorManager->crearJugador(
                    $equipo,
                    $delegado[0]['nombre'],
                    $delegado[0]['apellido'],
                    $delegado[0]['tipoDocumento'],
                    $delegado[0]['numeroDocumento'],
                    null,
                    'Entrenador',
                    true,
                    $delegado[0]['email'],
                    $delegado[0]['celular'],
                );
                
                $this->addFlash('success', "Equipo creado con éxito.");
                $logger->info('Equipo creado: ' . $equipo->getId() . ', por el usuario: ' .  $this->getUser()->getId());
                return $this->redirectToRoute('admin_equipo_index', ['ruta' => $ruta, 'categoriaId' => $categoriaId]);
            } catch (AppException $ae) {
                $logger->error($ae->getMessage());
                $this->addFlash('error', $ae->getMessage());
            } catch (\Throwable $e) {
                $logger->error($e->getMessage());
                $this->addFlash('error', "Ha ocurrido un error inesperado. Por favor, intente nuevamente.");
            }
        }

        foreach (TipoDocumento::cases() as $tipoDocumento) {
            $tipoDocumentos[] = $tipoDocumento->value;
        }
        return $this->render(
            'equipo/nuevo.html.twig', [
            'ruta' => $ruta,
            'categoriaId' => $categoriaId,
            'tipoDocumentos' => $tipoDocumentos,
            ]
        );
    }

    #[Route('/{equipoId}/editar', name: 'admin_equipo_editar', methods: ['GET', 'POST'])]
    public function editarEquipo(
        string $ruta,
        int $categoriaId,
        int $equipoId,
        Request $request,
        EquipoManager $equipoManager,
        LoggerInterface $logger
    ): Response {
        $equipo = $equipoManager->obtenerEquipo($equipoId);
        if ($request->isMethod('POST')) {
            try {
                $nombre = $request->request->get('nombre');
                $nombreCorto = $request->request->get('nombreCorto');
                $pais = $request->request->get('pais') ?? null;
                $provincia = $request->request->get('provincia') ?? null;
                $localidad = $request->request->get('localidad') ?? null;
                $equipoManager->editarEquipo($equipo, $nombre, $nombreCorto, $pais, $provincia, $localidad);
                $this->addFlash('success', "Equipo editado con éxito.");
                $logger->info('Equipo editado: ' . $equipo->getId() . ', por el usuario: ' .  $this->getUser()->getId());
                return $this->redirectToRoute('admin_equipo_index', ['ruta' => $ruta, 'categoriaId' => $categoriaId]);
            } catch (AppException $ae) {
                $logger->error($ae->getMessage());
                $this->addFlash('error', $ae->getMessage());
            } catch (\Throwable $e) {
                $logger->error($e->getMessage());
                $this->addFlash('error', "Ha ocurrido un error inesperado. Por favor, intente nuevamente.");
            }
        }
        return $this->render(
            'equipo/editar.html.twig', [
            'ruta' => $ruta,
            'categoriaId' => $categoriaId,
            'equipo' => $equipo,
            ]
        );
    }

    #[Route('/{equipoId}/eliminar', name: 'admin_equipo_eliminar', methods: ['GET'])]
    public function eliminarEquipo(
        string $ruta,
        int $categoriaId,
        int $equipoId,
        EquipoManager $equipoManager,
        LoggerInterface $logger
    ): Response {
        try {
            $equipo = $equipoManager->obtenerEquipo($equipoId);
            $equipoManager->eliminarEquipo($equipo);
            $this->addFlash('success', "Equipo eliminado con éxito.");
            $logger->info('Equipo eliminado: ' . $equipo->getId() . ', por el usuario: ' .  $this->getUser()->getId());
        } catch (AppException $ae) {
            $logger->error($ae->getMessage());
            $this->addFlash('error', $ae->getMessage());
        } catch (\Throwable $e) {
            $logger->error($e->getMessage());
            $this->addFlash('error', "Ha ocurrido un error inesperado. Por favor, intente nuevamente.");
        }
        return $this->redirectToRoute('admin_equipo_index', ['ruta' => $ruta, 'categoriaId' => $categoriaId]);
    }

    #[Route('/{equipoId}/bajar', name: 'admin_equipo_bajar', methods: ['GET'])]
    public function cambiarEstado(
        string $ruta,
        int $categoriaId,
        int $equipoId,
        EquipoManager $equipoManager,
        LoggerInterface $logger
    ): Response {
        try {
            $equipo = $equipoManager->obtenerEquipo($equipoId);
            $equipoManager->bajarEquipo($equipo);
            $this->addFlash('success', "Equipo dado de baja con éxito.");
            $logger->info('Equipo dado de baja: ' . $equipo->getId() . ', por el usuario: ' .  $this->getUser()->getId());
        } catch (AppException $ae) {
            $logger->error($ae->getMessage());
            $this->addFlash('error', $ae->getMessage());
        } catch (\Throwable $e) {
            $logger->error($e->getMessage());
            $this->addFlash('error', "Ha ocurrido un error inesperado. Por favor, intente nuevamente.");
        }
        return $this->redirectToRoute('admin_equipo_index', ['ruta' => $ruta, 'categoriaId' => $categoriaId]);
    }
}

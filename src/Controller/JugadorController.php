<?php

namespace App\Controller;

use App\Enum\TipoDocumento;
use App\Enum\TipoPersona;
use App\Manager\EquipoManager;
use App\Manager\JugadorManager;
use Monolog\Logger;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin/torneo/{ruta}/categoria/{categoriaId}/equipo/{equipoId}/jugador')]
#[IsGranted('ROLE_ADMIN')]
class JugadorController extends AbstractController
{
    #[Route('/', name: 'admin_jugador_index', methods: ['GET'])]
    public function index(
        string $ruta,
        int $categoriaId,
        int $equipoId,
        EquipoManager $equipoManager,
        JugadorManager $jugadorManager
    ): Response {
        $equipo = $equipoManager->obtenerEquipo($equipoId);
        $jugadores = $jugadorManager->obtenerJugadoresPorEquipo($equipo);
        return $this->render(
            'jugador/index.html.twig', [
            'ruta' => $ruta,
            'categoriaId' => $categoriaId,
            'equipo' => $equipo,
            'jugadores' => $jugadores,
            ]
        );
    }

    #[Route('/nuevo', name: 'admin_jugador_crear', methods: ['GET', 'POST'])]
    public function crearJugador(
        string $ruta,
        int $categoriaId,
        int $equipoId,
        Request $request,
        EquipoManager $equipoManager,
        JugadorManager $jugadorManager,
        LoggerInterface $logger
    ): Response {
        $equipo = $equipoManager->obtenerEquipo($equipoId);

        if ($request->isMethod('POST')) {
            try {
                $nombre = $request->request->get('nombre');
                $apellido = $request->request->get('apellido');
                $nacimiento = $request->request->get('nacimiento');
                $tipo = $request->request->get('tipoPersona');
                $tipoDocumento = $request->request->get('tipoDocumento');
                $numeroDocumento = $request->request->get('numeroDocumento');
                $email = $request->request->get('email');
                $celular = $request->request->get('celular');

                $jugadorManager->crearJugador(
                    $equipo,
                    $nombre,
                    $apellido,
                    $tipoDocumento,
                    $numeroDocumento,
                    $nacimiento,
                    $tipo,
                    false,
                    $email,
                    $celular
                );
                $this->addFlash('success', 'Jugador editado correctamente');
                $logger->info('Jugador creado en el equipo: ' . $equipo->getId() . ', con el nombre: ' . $nombre . ' ' . $apellido . ', por el usuario: ' .  $this->getUser()->getId());
                return $this->redirectToRoute(
                    'admin_jugador_index', [
                    'ruta' => $ruta,
                    'categoriaId' => $categoriaId,
                    'equipoId' => $equipoId,
                    ]
                );
            } catch (\Exception $e) {
                $this->addFlash('error', $e->getMessage());
                $logger->error($e->getMessage());
            } catch (\Throwable $e) {
                $this->addFlash('error', 'Ha ocurrido un error inesperado. Por favor, intente nuevamente.');
                $logger->error($e->getMessage());
            }
        }

        foreach (TipoDocumento::cases() as $tipoDocumento) {
            $tipoDocumentos[] = $tipoDocumento->value;
        }

        foreach (TipoPersona::cases() as $tipoPersona) {
            $tipoPersonas[] = $tipoPersona->value;
        }
        return $this->render(
            'jugador/nuevo.html.twig', [
            'ruta' => $ruta,
            'categoriaId' => $categoriaId,
            'equipo' => $equipo,
            'tipoDocumentos' => $tipoDocumentos,
            'tipoPersonas' => $tipoPersonas,
            ]
        );
    }

    #[Route('/{jugadorId}/editar', name: 'admin_jugador_editar', methods: ['GET', 'POST'])]
    public function editarJugador(
        string $ruta,
        int $categoriaId,
        int $equipoId,
        int $jugadorId,
        Request $request,
        EquipoManager $equipoManager,
        JugadorManager $jugadorManager,
        LoggerInterface $logger
    ): Response {
        $equipo = $equipoManager->obtenerEquipo($equipoId);
        $jugador = $jugadorManager->obtenerJugador($jugadorId);

        if ($request->isMethod('POST')) {
            try {
                $nombre = $request->request->get('nombre');
                $apellido = $request->request->get('apellido');
                $nacimiento = $request->request->get('nacimiento');
                $tipo = $request->request->get('tipoPersona');
                $tipoDocumento = $request->request->get('tipoDocumento');
                $numeroDocumento = $request->request->get('numeroDocumento');
                $email = $request->request->get('email');
                $celular = $request->request->get('celular');


                $jugadorManager->editarJugador(
                    $jugador,
                    $nombre,
                    $apellido,
                    $tipoDocumento,
                    $numeroDocumento,
                    $nacimiento,
                    $tipo,
                    $jugador->isResponsable(),
                    $email,
                    $celular
                );
                $this->addFlash('success', 'Jugador editado correctamente');
                $logger->info('Jugador editado en el equipo: ' . $equipo->getId() . ', con el nombre: ' . $nombre . ' ' . $apellido . ', por el usuario: ' .  $this->getUser()->getId());
                return $this->redirectToRoute(
                    'admin_jugador_index', [
                    'ruta' => $ruta,
                    'categoriaId' => $categoriaId,
                    'equipoId' => $equipoId,
                    ]
                );
            } catch (\Exception $e) {
                $this->addFlash('error', $e->getMessage());
                $logger->error($e->getMessage());
            } catch (\Throwable $e) {
                $this->addFlash('error', 'Ha ocurrido un error inesperado. Por favor, intente nuevamente.');
                $logger->error($e->getMessage());
            }
        }

        foreach (TipoDocumento::cases() as $tipoDocumento) {
            $tipoDocumentos[] = $tipoDocumento->value;
        }

        foreach (TipoPersona::cases() as $tipoPersona) {
            $tipoPersonas[] = $tipoPersona->value;
        }

        return $this->render(
            'jugador/editar.html.twig', [
            'ruta' => $ruta,
            'categoriaId' => $categoriaId,
            'equipo' => $equipo,
            'jugador' => $jugador,
            'tipoDocumentos' => $tipoDocumentos,
            'tipoPersonas' => $tipoPersonas,
            ]
        );
    }

    #[Route('/{jugadorId}/eliminar', name: 'admin_jugador_eliminar', methods: ['GET'])]
    public function eliminarJugador(
        string $ruta,
        int $categoriaId,
        int $equipoId,
        int $jugadorId,
        EquipoManager $equipoManager,
        JugadorManager $jugadorManager,
        LoggerInterface $logger
    ): Response {
        $equipo = $equipoManager->obtenerEquipo($equipoId);
        $jugador = $jugadorManager->obtenerJugador($jugadorId);
        $jugadorManager->eliminarJugador($jugador);
        $this->addFlash('success', 'Jugador eliminado correctamente');
        $logger->info('Jugador eliminado del equipo: ' . $equipo->getId() . ', con el nombre: ' . $jugador->getNombre() . ' ' . $jugador->getApellido() . ', por el usuario: ' .  $this->getUser()->getId());
        return $this->redirectToRoute(
            'admin_jugador_index', [
            'ruta' => $ruta,
            'categoriaId' => $categoriaId,
            'equipoId' => $equipoId,
            ]
        );
    }
}

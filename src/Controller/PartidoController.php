<?php

namespace App\Controller;

use App\Exception\AppException;
use App\Manager\CategoriaManager;
use App\Manager\EquipoManager;
use App\Manager\PartidoManager;
use App\Manager\TorneoManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Throwable;

#[Route('/admin/torneo/{ruta}')]
#[IsGranted('ROLE_ADMIN')]
class PartidoController extends AbstractController
{
    #[Route('/categoria/{categoriaId}/partido/crear', name: 'app_partido_crear')]
    public function crearPartidos(
        string $ruta,
        int $categoriaId,
        CategoriaManager $categoriaManager,
        TorneoManager $torneoManager,
        EquipoManager $equipoManager,
        PartidoManager $partidoManager
    ): Response {
        $categoria = $categoriaManager->obtenerCategoria($categoriaId);
        $partidoManager->crearPartidoXCategoria($categoria);

        $torneo = $torneoManager->obtenerTorneo($ruta);
        $equipos = $equipoManager->obtenerEquiposPorCategoria($categoria);

        return $this->render('equipo/index.html.twig', [
            'torneo' => $torneo,
            'categoria' => $categoria,
            'equipos' => $equipos,
        ]);
    }

    #[Route('/partido', name: 'app_partido')]
    public function index(
        string $ruta,
        PartidoManager $partidoManager,
        TorneoManager $torneoManager,
    ): Response {
        $torneo = $torneoManager->obtenerTorneo($ruta);
        $partidosSinAsignar = $partidoManager->obtenerPartidosSinAsignarXTorneo($ruta);
        $partidosProgramados = $partidoManager->obtenerPartidosProgramadosXTorneo($ruta);
        $canchas = $partidoManager->obtenerSedesyCanchasXTorneo($ruta);
        return $this->render('partido/index.html.twig', [
            'torneo' => $torneo,
            'partidosSinAsignar' => $partidosSinAsignar,
            'partidosProgramados' => $partidosProgramados,
            'canchas' => $canchas,
        ]);
    }

    #[Route('/partido/editar', name: 'app_partido_editar', methods: ['POST'])]
    public function editarPartido(
        string $ruta,
        Request $request,
        PartidoManager $partidoManager
    ): Response {
        try {
            $partidoId = (int)$request->request->get('var_partidoId');
            $cancha = (int)$request->request->get('var_cancha');
            $horario = $request->request->get('var_horario');

            $partidoManager->editarPartido($ruta, $partidoId, $cancha, $horario);

            return $this->redirectToRoute('app_partido', ['ruta' => $ruta]);
        } catch (AppException $ae) {
            // Handle the exception
            $this->addFlash('error', $ae->getMessage());
            return $this->redirectToRoute('app_partido', ['ruta' => $ruta]);
        } catch (Throwable $e) {
            // Handle the exception
            $this->addFlash('error', 'Ocurrió un error al editar el partido ' . $e);
            return $this->redirectToRoute('app_partido', ['ruta' => $ruta]);
        }
    }

    #[Route('/partido/{partidoId}/cargar_resultado', name: 'app_partido_cargar_resultado', methods: ['GET', 'POST'])]
    public function cargarResultado(
        string $ruta,
        int $partidoId,
        Request $request,
        PartidoManager $partidoManager
    ): Response {
        try {
            $resultadoLocal = (int)$request->request->get('var_resultado_local');
            $resultadoVisitante = (int)$request->request->get('var_resultado_visitante');

           // $partidoManager->cargarResultado($partidoId, $resultadoLocal, $resultadoVisitante);

            return $this->redirectToRoute('app_partido', ['ruta' => $ruta]);
        } catch (AppException $ae) {
            // Handle the exception
            $this->addFlash('error', $ae->getMessage());
            return $this->redirectToRoute('app_partido', ['ruta' => $ruta]);
        } catch (Throwable $e) {
            // Handle the exception
            $this->addFlash('error', 'Ocurrió un error al cargar el resultado ' . $e);
            return $this->redirectToRoute('app_partido', ['ruta' => $ruta]);
        }
    }
}

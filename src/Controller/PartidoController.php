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
    #[Route('/categoria/{categoriaId}/partido/crear', name: 'app_partido_crear', methods: ['GET','POST'])]
    public function crearPartidoClasificatorio(
    string $ruta,
    int $categoriaId,
    CategoriaManager $categoriaManager,
    TorneoManager $torneoManager,
    EquipoManager $equipoManager,
    PartidoManager $partidoManager,
    Request $request
): Response {
    try {
        $categoria = $categoriaManager->obtenerCategoria($categoriaId);
        $grupos = $categoria->getGrupos();
        $tipoOro =  $tipoPlata = $tipoBronce = [];
        $equiposOro = $equiposPlata = $equiposBronce = 0;
        $torneo = $torneoManager->obtenerTorneo($ruta);
        
        if ($request->isMethod('POST')) {
            $partidosPlayOff = $request->request->all();
            $partidoManager->crearPartidoXCategoria($categoria, $partidosPlayOff);
            return $this->redirectToRoute('app_partido', ['ruta' => $ruta]);
        }

        foreach ($grupos as $grupo) {
            $equiposOro += $grupo->getClasificaOro();
            $equiposPlata += $grupo->getClasificaPlata();
            $equiposBronce += $grupo->getClasificaBronce();
        }

        switch ($equiposOro) {
            case 2:
                $tipoOro[] = 'Final Oro';
                break;
            case 4:
                $tipoOro[] = 'Semi Final Oro';
                $tipoOro[] = 'Final Oro';
                break;
            case 8:
                $tipoOro[] = 'Cuartos de Final Oro';
                $tipoOro[] = 'Semi Final Oro';
                $tipoOro[] = 'Final Oro';
                break;
            default:
                break;
        }

        switch ($equiposPlata) {
            case 2:
                $tipoPlata[] = 'Final Plata';
                break;
            case 4:
                $tipoPlata[] = 'Semi Final Plata';
                $tipoPlata[] = 'Final Plata';
                break;
            case 8:
                $tipoPlata[] = 'Cuartos de Final Plata';
                $tipoPlata[] = 'Semi Final Plata';
                $tipoPlata[] = 'Final Plata';
                break;
            default:
                break;
        }

        switch ($equiposBronce) {
            case 2:
                $tipoBronce[] = 'Final Bronce';
                break;
            case 4:
                $tipoBronce[] = 'Semi Final Bronce';
                $tipoBronce[] = 'Final Bronce';
                break;
            case 8:
                $tipoBronce[] = 'Cuartos de Final Bronce';
                $tipoBronce[] = 'Semi Final Bronce';
                $tipoBronce[] = 'Final Bronce';
                break;
            default:
                break;
        }

        $params = [
            'torneo' => $torneo,
            'categoria' => $categoria,
            'grupos' => $grupos,
            'equiposOro' => $equiposOro, 
            'equiposPlata' => $equiposPlata,
            'equiposBronce' => $equiposBronce,
            'tipoOro' => $tipoOro, 
            'tipoPlata' =>  $tipoPlata,
            'tipoBronce' => $tipoBronce,
            'partidosPlayOff' => $request->request->all() // Mantener los datos del formulario
        ];

        return $this->render('partido/crear.html.twig', $params);
    } catch (AppException $ae) {
        // Handle the exception
        $this->addFlash('error', $ae->getMessage());
        return $this->redirectToRoute('app_partido', ['ruta' => $ruta]);
    } catch (Throwable $e) {
        // Handle the exception
        $this->addFlash('error', 'Ocurrió un error al crear el partido ' . $e);
        return $this->redirectToRoute('app_partido', ['ruta' => $ruta]);
    }
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
        // Organizar las sedes y sus canchas en un array estructurado
        $sedesCanchas = [];
        foreach ($canchas as $cancha) {
            $sedeNombre = $cancha['sede'];
            $canchaNombre = [
                'id' => $cancha['id'],
                'cancha' => $cancha['cancha'],
            ];

            if (!isset($sedesCanchas[$sedeNombre])) {
                $sedesCanchas[$sedeNombre] = [];
            }

            $sedesCanchas[$sedeNombre][] = $canchaNombre;
        }
        return $this->render(
            'partido/index.html.twig', [
            'torneo' => $torneo,
            'partidosSinAsignar' => $partidosSinAsignar,
            'partidosProgramados' => $partidosProgramados,
            'canchas' => $sedesCanchas,
            ]
        );
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
            if($request->isMethod('POST')) {
                
                $resultadoLocal = $request->request->all('puntosLocal');
                $resultadoVisitante = $request->request->all('puntosVisitante');
                $partidoManager->cargarResultado($partidoId, $resultadoLocal, $resultadoVisitante);
                return $this->redirectToRoute('app_partido', ['ruta' => $ruta]);
            }
            $partido = $partidoManager->obtenerPartido($partidoId);

            return $this->render(
                'partido/cargarResultado.html.twig', [
                'partido' => $partido,
                'ruta' => $ruta,
                ]
            );
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

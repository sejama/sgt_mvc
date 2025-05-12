<?php

namespace App\Controller;

use App\Exception\AppException;
use App\Manager\CategoriaManager;
use App\Manager\EquipoManager;
use App\Manager\PartidoManager;
use App\Manager\TorneoManager;
use App\Security\Voter\PartidoVoter;
use App\Utils\GenerarPdf;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Throwable;

#[Route('/admin/torneo/{ruta}')]
class PartidoController extends AbstractController
{
    #[Route('/categoria/{categoriaId}/partido/crear', name: 'admin_partido_crear', methods: ['GET','POST'])]
    #[IsGranted('ROLE_ADMIN')]
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
            return $this->redirectToRoute('admin_partido_index', ['ruta' => $ruta]);
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
        return $this->redirectToRoute('admin_partido_index', ['ruta' => $ruta]);
    } catch (Throwable $e) {
        // Handle the exception
        $this->addFlash('error', 'Ocurrió un error al crear el partido ' . $e);
        return $this->redirectToRoute('admin_partido_index', ['ruta' => $ruta]);
    }
}

    #[Route('/partido', name: 'admin_partido_index', methods: ['GET'])]
    #[IsGranted('ROLE_ADMIN')]
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

    #[Route('/partido/editar', name: 'admin_partido_editar', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
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

            return $this->redirectToRoute('admin_partido_index', ['ruta' => $ruta]);
        } catch (AppException $ae) {
            // Handle the exception
            $this->addFlash('error', $ae->getMessage());
            return $this->redirectToRoute('admin_partido_index', ['ruta' => $ruta]);
        } catch (Throwable $e) {
            // Handle the exception
            $this->addFlash('error', 'Ocurrió un error al editar el partido ' . $e);
            return $this->redirectToRoute('admin_partido_index', ['ruta' => $ruta]);
        }
    }

    #[Route('/partido/{partidoNumero}/pdf', name: 'admin_partido_pdf', methods: ['GET'])]
    public function generarPDF(
        string $ruta,
        int $partidoNumero,
        PartidoManager $partidoManager
    ): Response {
        try {
            $partido =  $partidoManager->obtenerPartido($ruta, $partidoNumero);

            $pdf = new GenerarPdf();
            $pdf->generarPdf($partido, $ruta);
            $this->addFlash('success', 'PDF generado correctamente.');
            return $this->redirectToRoute('admin_partido_index', ['ruta' => $ruta]);
        } catch (AppException $ae) {
            // Handle the exception
            $this->addFlash('error', $ae->getMessage());
            return $this->redirectToRoute('admin_partido_index', ['ruta' => $ruta]);
        } catch (Throwable $e) {
            // Handle the exception
            $this->addFlash('error', 'Ocurrió un error al cargar el partido ' . $e);
            return $this->redirectToRoute('admin_partido_index', ['ruta' => $ruta]);
        }
    }

    #[Route('/partido/{partidoNumero}/cargar_resultado', name: 'admin_partido_resultado', methods: ['GET', 'POST'])]
    public function cargarResultado(
        string $ruta,
        int $partidoNumero,
        Request $request,
        PartidoManager $partidoManager
    ): Response {
        try {
            $partido =  $partidoManager->obtenerPartido($ruta, $partidoNumero);

            // Verificar permisos usando el voter
            $this->denyAccessUnlessGranted(PartidoVoter::CARGAR_RESULTADO, $partido);

            if($request->isMethod('POST')) {
                $resultadoLocal = $request->request->all('puntosLocal');
                $resultadoVisitante = $request->request->all('puntosVisitante');
                $partidoManager->cargarResultado($partido, $resultadoLocal, $resultadoVisitante);
                $this->addFlash('success', 'Resultado cargado correctamente.');
                if ($this->isGranted('ROLE_PLANILLERO')) {
                    return $this->redirectToRoute('app_main_torneo', ['ruta' => $ruta]);
                } else {
                    return $this->redirectToRoute('admin_partido_index', ['ruta' => $ruta]);
                }
            }

            return $this->render(
                'partido/cargarResultado.html.twig', [
                'partido' => $partido,
                'ruta' => $ruta,
                ]
            );
        } catch (AccessDeniedException $e) {
             // Verificar si el usuario está autenticado
            if (!$this->getUser()) {
                // Guardar la URL actual en la sesión para redirigir después del login
                $request->getSession()->set('_security.main.target_path', $request->getUri());

                // Redirigir al login si no está autenticado
                return $this->redirectToRoute('security_login');
            }

            // Redirigir al app_main_torneo con un mensaje de error
            $this->addFlash('error', 'No tienes permiso para cargar el resultado de este partido.');
            return $this->redirectToRoute('app_main_torneo', ['ruta' => $ruta]);
        } catch (AppException $ae) {
            // Handle the exception
            $this->addFlash('error', $ae->getMessage());
            return $this->redirectToRoute('app_main_torneo', ['ruta' => $ruta]);
        } catch (Throwable $e) {
            // Handle the exception
            $this->addFlash('error', 'Ocurrió un error al cargar el resultado ' . $e);
            return $this->redirectToRoute('app_main_torneo', ['ruta' => $ruta]);
        }
    }
}

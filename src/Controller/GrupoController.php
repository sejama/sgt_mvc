<?php

namespace App\Controller;

use App\Exception\AppException;
use App\Manager\CategoriaManager;
use App\Manager\GrupoManager;
use App\Manager\PartidoManager;
use App\Manager\TablaManager;
use App\Manager\TorneoManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin/torneo/{ruta}/categoria/{categoriaId}')]
#[IsGranted('ROLE_ADMIN')]
class GrupoController extends AbstractController
{
    #[Route('/grupos', name: 'admin_grupo_index', methods: ['GET'])]
    public function gruposIndex(
        string $ruta,
        int $categoriaId,
        TorneoManager $torneoManager,
        CategoriaManager $categoriaManager,
        TablaManager $tablaManager,
        PartidoManager $partidoManager,
    ): Response {
        $torneo = $torneoManager->obtenerTorneo($ruta);
        $categoria = $categoriaManager->obtenerCategoria($categoriaId);
        $grupos = $categoria->getGrupos();
        $gruposPosiciones = [];
        foreach ($grupos as $grupo) {
            $gruposPosiciones[$grupo->getId()][] = $grupo;
            $gruposPosiciones[$grupo->getId()][] = $tablaManager->calcularPosiciones($grupo);
        }
    
        $partidosClasificatorios = $partidoManager->obtenerPartidosXCategoriaClasificatorio($categoria);
        $partidosPlayOff = $partidoManager->obtenerPartidosXCategoriaEliminatoriaPostClasificatorio($categoria);

        return $this->render(
            'grupo/index.html.twig', [
            'torneo' => $torneo,
            'categoria' => $categoria,
            'grupos' => $gruposPosiciones,
            'partidosClasificatorios' => $partidosClasificatorios,
            'partidosOro' => $partidosPlayOff['oro'],
            'partidosPlata' => $partidosPlayOff['plata'] ?? [],
            'partidosBronce' => $partidosPlayOff['bronce'] ?? [],
            ]
        );
    }

    #[Route('/armarPlayoff', name: 'admin_playoff_armar', methods: ['POST'])]
    public function armarPlayOff(
        string $ruta,
        int $categoriaId,
        TorneoManager $torneoManager,
        CategoriaManager $categoriaManager,
        TablaManager $tablaManager
    ): Response {
        try {
            $torneo = $torneoManager->obtenerTorneo($ruta);
            $categoria = $categoriaManager->obtenerCategoria($categoriaId);
            $grupos = $categoria->getGrupos();
            $gruposPosiciones = [];
            foreach ($grupos as $grupo) {
                $gruposPosiciones[$grupo->getId()][] = $grupo;
                $gruposPosiciones[$grupo->getId()][] = $tablaManager->calcularPosiciones($grupo);
            }
            $categoriaManager->armarPlayOff($categoria);
            $this->addFlash('success', 'Playoff armado con éxito para la categoría ' . $categoria->getNombre() . '.');
            return $this->redirectToRoute('admin_grupo_index', ['ruta' => $ruta, 'categoriaId' => $categoriaId]);
        } catch (AppException $ae) {
            $this->addFlash('danger', "una app exception");
        } catch (\Exception $e) {
            $this->addFlash('danger', "Ocurrió un error al armar el playoff.");
        }
        return $this->redirectToRoute('admin_grupo_index', ['ruta' => $ruta, 'categoriaId' => $categoriaId]);
    }

    #[Route('/grupo/crear', name: 'admin_grupo_crear', methods: ['GET', 'POST'])]
    public function crearGrupo(
        Request $request,
        GrupoManager $grupoManager,
        TorneoManager $torneoManager,
        CategoriaManager $categoriaManager,
        string $ruta,
        int $categoriaId
    ): Response {
        $torneo = $torneoManager->obtenerTorneo($ruta);
        $categoria = $categoriaManager->obtenerCategoria($categoriaId);
        if ($request->isMethod('POST')) {
            try {
                $grupos = [];
                $cantidadGrupos = (int)$request->request->get('cantidadGrupos');
                $gruposReq =  $request->request->all('grupos');

                if (count($gruposReq) !== $cantidadGrupos) {
                    $this->addFlash('danger', "La cantidad de grupos no coincide con la cantidad ingresada.");
                    return $this->redirectToRoute('admin_grupo_crear', ['ruta' => $ruta, 'categoriaId' => $categoriaId]);
                }

                foreach ($gruposReq as $grupoReq) {
                    $grupos[] = [
                        'nombre' => $grupoReq['nombre'],
                        'categoria' => $categoriaId,
                        'cantidad' => (int)$grupoReq['cantidadEquipo'],
                        'clasificaOro' => (int)$grupoReq['clasificaOro'],
                        'clasificaPlata' => (int)$grupoReq['clasificaPlata'] !== 0 ? (int)$grupoReq['clasificaPlata'] : null,
                        'clasificaBronce' => (int)$grupoReq['clasificaBronce'] !== 0 ? (int)$grupoReq['clasificaBronce'] : null,
                    ];
                }
                $grupoManager->crearGrupos($grupos);

                $this->addFlash('success', "Grupo creado con éxito.");
                return $this->redirectToRoute('admin_torneo_index');
            } catch (AppException $ae) {
                $this->addFlash('danger', $ae->getMessage());
            } catch (\Exception $e) {
                $this->addFlash('danger', "Ocurrió un error al crear el grupo.");
            }
        }
        return $this->render(
            'grupo/crear.html.twig', [
            'torneo' => $torneo,
            'categoria' => $categoria,
            ]
        );
    }

    #[Route('/grupo/{grupoId}', name: 'admin_grupo_ver', methods: ['GET'])]
    public function index(
        string $ruta,
        int $categoriaId,
        int $grupoId,
        TorneoManager $torneoManager,
        GrupoManager $grupoManager,
        TablaManager $tablaManager  
    ): Response {
        $torneo = $torneoManager->obtenerTorneo($ruta);
        $grupo = $grupoManager->obtenerGrupo($grupoId);
        $posiciones = $tablaManager->calcularPosiciones($grupo);
        return $this->render(
            'grupo/index.html.twig', [
            'torneo' => $torneo,
            'grupos' => $grupo,
            'posiciones' => $posiciones,
            ]
        );
    }
}

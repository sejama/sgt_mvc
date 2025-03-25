<?php

namespace App\Controller;

use App\Entity\Grupo;
use App\Exception\AppException;
use App\Manager\CategoriaManager;
use App\Manager\GrupoManager;
use App\Manager\TorneoManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin/torneo/{ruta}/categoria/{categoriaId}/grupo')]
#[IsGranted('ROLE_ADMIN')]
class GrupoController extends AbstractController
{
    #[Route('/', name: 'app_grupos')]
    public function grupos(
        string $ruta,
        int $categoriaId,
        TorneoManager $torneoManager,
        CategoriaManager $categoriaManager,
        GrupoManager $grupoManager
    ): Response {
        $torneo = $torneoManager->obtenerTorneo($ruta);
        $categoria = $categoriaManager->obtenerCategoria($categoriaId);
        $grupos = $categoria->getGrupos();
        $gruposPosiciones = [];
        foreach ($grupos as $grupo) {
            $gruposPosiciones[$grupo->getId()][] = $grupo;
            $gruposPosiciones[$grupo->getId()][] = $grupoManager->calcularPosiciones($grupo);
        }

        $partidos = []; 
        foreach ($categoria->getPartidos() as $partido) {
            if ($partido->getEquipoLocal() == null && $partido->getEquipoVisitante() == null) {
                $partidos[] = $partido;
            }
        }
        return $this->render(
            'grupo/index.html.twig', [
            'torneo' => $torneo,
            'categoria' => $categoria,
            'grupos' => $gruposPosiciones,
            'playOff' => $categoriaManager->armarPlayOff($categoria),
            'partidos' => $partidos,
            ]
        );
    }

    #[Route('/{grupoId}', name: 'app_grupo')]
    public function index(
        string $ruta,
        int $categoriaId,
        int $grupoId,
        TorneoManager $torneoManager,
        GrupoManager $grupoManager,
    ): Response {
        $torneo = $torneoManager->obtenerTorneo($ruta);
        $grupo = $grupoManager->obtenerGrupo($grupoId);
        $posiciones = $grupoManager->calcularPosiciones($grupo);
        return $this->render(
            'grupo/index.html.twig', [
            'torneo' => $torneo,
            'grupo' => $grupo,
            'posiciones' => $posiciones,
            ]
        );
    }

    #[Route('/crear', name: 'app_grupo_crear', methods: ['GET', 'POST'])]
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
                    return $this->redirectToRoute('app_grupo_crear', ['ruta' => $ruta, 'categoriaId' => $categoriaId]);
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
                return $this->redirectToRoute('app_torneo');
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
}

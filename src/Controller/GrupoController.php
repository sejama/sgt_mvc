<?php

namespace App\Controller;

use App\Manager\CategoriaManager;
use App\Manager\GrupoManager;
use App\Manager\TorneoManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/torneo/{ruta}/categoria/{categoriaId}/grupo')]
class GrupoController extends AbstractController
{
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

            $grupos = [];
            $cantidadGrupos = $request->request->get('cantidadGrupos');
            $gruposReq =  $request->request->all('grupos');

            if (count($gruposReq) !== $cantidadGrupos) {
                $this->addFlash('danger', "La cantidad de grupos no coincide con la cantidad ingresada.");
                return $this->redirectToRoute('app_grupo_crear', ['ruta' => $ruta, 'categoriaId' => $categoriaId]);
            }

            foreach ($gruposReq as $grupoReq) {
                $grupos[] = [
                    'nombre' => $grupoReq['nombre'],
                    'categoria' => $categoriaId,
                    'cantidad' => $grupoReq['cantidadEquipo'],
                    'clasificaOro' => $grupoReq['clasificaOro'],
                    'clasificaPlata' => $grupoReq['clasificaPlata'] != '' ? $grupoReq['clasificaPlata'] :  null,
                    'clasificaBronce' => $grupoReq['clasificaBronce'] != '' ? $grupoReq['clasificaBronce'] :  null,
                ];
            }

            $grupoManager->crearGrupos($grupos);

            $this->addFlash('success', "Grupo creado con éxito.");
            return $this->redirectToRoute('app_torneo');
        }
        return $this->render('grupo/crear.html.twig', [
            'torneo' => $torneo,
            'categoria' => $categoria,
        ]);
    }
}

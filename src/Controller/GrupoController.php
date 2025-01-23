<?php

namespace App\Controller;

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
                        'clasificaPlata' => $grupoReq['clasificaPlata'] != '' ? (int)$grupoReq['clasificaPlata'] :  null,
                        'clasificaBronce' => $grupoReq['clasificaBronce'] != '' ? (int)$grupoReq['clasificaBronce'] :  null,
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
        return $this->render('grupo/crear.html.twig', [
            'torneo' => $torneo,
            'categoria' => $categoria,
        ]);
    }
}

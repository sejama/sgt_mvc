<?php

namespace App\Controller;

use App\Enum\Genero;
use App\Exception\AppException;
use App\Manager\TorneoManager;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Throwable;

class TorneoController extends AbstractController
{
    #[Route('/torneo', name: 'app_torneo', methods: ['GET'])]
    public function index(
        TorneoManager $torneoManager
    ): Response
    {
        if ( $this->getUser() !== NULL ){
            $torneos = $torneoManager->obtenerTorneos();//(int)$this->getUser()->getId());
            return $this->render('torneo/index.html.twig', [
                'torneos' => $torneos,
            ]);
        }
        return $this->redirectToRoute('app_login');
        
    }

    #[Route('/torneo/nuevo', name: 'app_torneo_nuevo', methods: ['GET', 'POST'])]
    public function nuevo(
        Request $request,
        TorneoManager $torneoManager,
        LoggerInterface $logger
    ): Response
    {
        if ( $this->getUser() !== NULL ){
            if ($request->isMethod('POST')) {
                try{
                    //var_dump($request->request);die();
                    // Handle the submission of the form
                    $nombre = $request->request->get('nombre');
                    $ruta = $request->request->get('ruta');
                    $descripcion = $request->request->get('descripcion');
                    $fecha_inicio_torneo = $request->request->get('fechaInicioTorneo') . ' ' . $request->request->get('horaInicioTorneo');
                    $fecha_fin_torneo = $request->request->get('fechaFinTorneo') . ' ' . $request->request->get('horaFinTorneo');
                    $fecha_inicio_inscripcion = $request->request->get('fechaInicioInscripcion') . ' ' . $request->request->get('horaInicioInscripcion');
                    $fecha_fin_inscripcion = $request->request->get('fechaFinInscripcion') . ' ' .$request->request->get('horaFinInscripcion');
                    $torneoManager->crearTorneo($nombre, $ruta, $descripcion, $fecha_inicio_torneo, $fecha_fin_torneo, $fecha_inicio_inscripcion, $fecha_fin_inscripcion, $this->getUser());
                    $this->addFlash('success', "Torneo creado con éxito.");
                    return $this->redirectToRoute('app_torneo');
                }catch (AppException $ae){
                    $logger->error($ae->getMessage());
                    $this->addFlash('error', $ae->getMessage());
                    return $this->redirectToRoute('app_torneo');
                }
                catch (Throwable $e) {
                    $logger->error($e->getMessage());
                    $this->addFlash('error', "Ha ocurrido un error inesperado. Por favor, intente nuevamente.");
                    return $this->redirectToRoute('app_torneo');
                }
            }
            foreach (Genero::cases() as $genero) {
                $generos[] = $genero->value;
            }
            return $this->render('torneo/nuevo.html.twig', [
                'generos' => $generos,
            ]);
        }
        return $this->redirectToRoute('app_login');
        
    }
}

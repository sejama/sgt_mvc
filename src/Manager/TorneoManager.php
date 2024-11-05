<?php

declare(strict_types=1);

namespace App\Manager;

use App\Entity\Torneo;
use App\Repository\TorneoRepository;

class TorneoManager
{
    public function __construct(
        private TorneoRepository $torneoRepository,
        private ValidadorManager $validadorManager
    )
    {
    }

    public function obtenerTorneos(): array
    {
        return $this->torneoRepository->findAll();//(['creador' => $userID]);
    }

    public function crearTorneo($nombre, $ruta, $descripcion, $fecha_inicio_torneo, $fecha_fin_torneo, $fecha_inicio_inscripcion, $fecha_fin_inscripcion, $user){
        //$this->validadorManager->validarTorneo($nombre, $ruta, $descripcion, $fecha_inicio_torneo, $fecha_fin_torneo, $fecha_inicio_inscripcion, $fecha_fin_inscripcion);
        $torneo = new Torneo();
        $torneo->setNombre($nombre);
        $torneo->setRuta($ruta);
        $torneo->setDescripcion($descripcion);
        $torneo->setFechaInicioTorneo(new \DateTimeImmutable ($fecha_inicio_torneo), new \DateTimeZone('America/Argentina/Buenos_Aires'));
        $torneo->setFechaFinTorneo(new \DateTimeImmutable($fecha_fin_torneo), new \DateTimeZone('America/Argentina/Buenos_Aires'));
        $torneo->setFechaInicioInscripcion(new \DateTimeImmutable($fecha_inicio_inscripcion), new \DateTimeZone('America/Argentina/Buenos_Aires'));
        $torneo->setFechaFinInscripcion(new \DateTimeImmutable($fecha_fin_inscripcion), new \DateTimeZone('America/Argentina/Buenos_Aires'));
        $torneo->setCreador($user);
        $this->torneoRepository->save($torneo, true);
    }
}
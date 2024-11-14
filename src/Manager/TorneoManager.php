<?php

declare(strict_types=1);

namespace App\Manager;

use App\Entity\Torneo;
use App\Entity\Usuario;
use App\Repository\TorneoRepository;

class TorneoManager
{
    public function __construct(
        private TorneoRepository $torneoRepository,
        private ValidadorManager $validadorManager
    ) {
    }

    public function obtenerTorneos(int $userId): array
    {
        return $this->torneoRepository->findBy(['creador' => $userId]);
    }

    public function obtenerTorneo(string $ruta): Torneo
    {
        return $this->torneoRepository->findOneBy(['ruta' => $ruta]);
    }

    public function crearTorneo(
        string $nombre,
        string $ruta,
        string $descripcion,
        string $fecha_inicio_torneo,
        string $fecha_fin_torneo,
        string $fecha_inicio_inscripcion,
        string $fecha_fin_inscripcion,
        Usuario $user
    ): Torneo {
        $timezone = new \DateTimeZone('America/Argentina/Buenos_Aires');

        $this->validadorManager->validarTorneo(
            $nombre,
            $ruta,
            $descripcion,
            $fecha_inicio_torneo,
            $fecha_fin_torneo,
            $fecha_inicio_inscripcion,
            $fecha_fin_inscripcion,
            $user
        );

        $torneo = new Torneo();
        $torneo->setNombre($nombre);
        $torneo->setRuta($ruta);
        $torneo->setDescripcion($descripcion);
        $torneo->setFechaInicioTorneo(new \DateTimeImmutable($fecha_inicio_torneo), $timezone);
        $torneo->setFechaFinTorneo(new \DateTimeImmutable($fecha_fin_torneo), $timezone);
        $torneo->setFechaInicioInscripcion(new \DateTimeImmutable($fecha_inicio_inscripcion), $timezone);
        $torneo->setFechaFinInscripcion(new \DateTimeImmutable($fecha_fin_inscripcion), $timezone);
        $torneo->setCreador($user);
        $this->torneoRepository->guardar($torneo, false);

        return $torneo;
    }

    public function editarTorneo(
        Torneo $torneo,
        $nombre,
        string $ruta,
        string $descripcion,
        string $fecha_inicio_torneo,
        string $fecha_fin_torneo,
        string $fecha_inicio_inscripcion,
        string $fecha_fin_inscripcion
    ): Torneo {
        $timezone = new \DateTimeZone('America/Argentina/Buenos_Aires');

        $this->validadorManager->validarTorneo(
            $nombre,
            $ruta,
            $descripcion,
            $fecha_inicio_torneo,
            $fecha_fin_torneo,
            $fecha_inicio_inscripcion,
            $fecha_fin_inscripcion,
            $torneo->getCreador()
        );

        $torneo->setNombre($nombre);
        $torneo->setRuta($ruta);
        $torneo->setDescripcion($descripcion);
        $torneo->setFechaInicioTorneo(new \DateTimeImmutable($fecha_inicio_torneo), $timezone);
        $torneo->setFechaFinTorneo(new \DateTimeImmutable($fecha_fin_torneo), $timezone);
        $torneo->setFechaInicioInscripcion(new \DateTimeImmutable($fecha_inicio_inscripcion), $timezone);
        $torneo->setFechaFinInscripcion(new \DateTimeImmutable($fecha_fin_inscripcion), $timezone);

        $this->torneoRepository->guardar($torneo, true);

        return $torneo;
    }

    public function eliminarTorneo(Torneo $torneo): void
    {
        $this->torneoRepository->eliminar($torneo, true);
    }
}
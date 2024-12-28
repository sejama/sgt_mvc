<?php

declare(strict_types=1);

namespace App\Manager;

use App\Entity\Torneo;
use App\Entity\Usuario;
use App\Enum\EstadoTorneo;
use App\Exception\AppException;
use App\Repository\TorneoRepository;

class TorneoManager
{
    public function __construct(
        private TorneoRepository $torneoRepository,
        private ValidadorManager $validadorManager
    ) {
    }

    public function obtenerTorneos(): array
    {
        return $this->torneoRepository->findAll();
    }

    public function obtenerTorneosXCreador(int $userId): array
    {
        return $this->torneoRepository->findBy(['creador' => $userId]);
    }

    public function obtenerTorneo(string $ruta): Torneo
    {
        if (!$torneo =  $this->torneoRepository->findOneBy(['ruta' => $ruta])) {
            throw new AppException('Torneo no encontrado');
        }
        return $torneo;
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

        if ($this->torneoRepository->findOneBy(['nombre' => $nombre])) {
            throw new AppException('El nombre ya se encuentra registrado');
        }
        if ($this->torneoRepository->findOneBy(['ruta' => $ruta])) {
            throw new AppException('La ruta ya se encuentra registrada');
        }
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
        $torneo->setEstado(EstadoTorneo::BORRADOR);
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
        try {
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
            if ($torneo->getNombre() !== $nombre && $this->torneoRepository->findOneBy(['nombre' => $nombre])) {
                throw new AppException('El nombre ya se encuentra registrado');
            }
            if ($torneo->getRuta() !== $ruta && $this->torneoRepository->findOneBy(['ruta' => $ruta])) {
                throw new AppException('La ruta ya se encuentra registrada');
            }
            $torneo->setNombre($nombre);
            $torneo->setRuta($ruta);
            $torneo->setDescripcion($descripcion);
            $torneo->setFechaInicioTorneo(new \DateTimeImmutable($fecha_inicio_torneo), $timezone);
            $torneo->setFechaFinTorneo(new \DateTimeImmutable($fecha_fin_torneo), $timezone);
            $torneo->setFechaInicioInscripcion(new \DateTimeImmutable($fecha_inicio_inscripcion), $timezone);
            $torneo->setFechaFinInscripcion(new \DateTimeImmutable($fecha_fin_inscripcion), $timezone);
            $torneo->setEstado(EstadoTorneo::BORRADOR);

            $this->torneoRepository->guardar($torneo, true);

            return $torneo;
        } catch (AppException $e) {
            throw $e;
        }
    }

    public function editarReglamento(Torneo $torneo, string $reglamento): Torneo
    {
        $torneo->setReglamento($reglamento);
        $this->torneoRepository->guardar($torneo, true);

        return $torneo;
    }

    public function eliminarTorneo(Torneo $torneo): void
    {
        $this->torneoRepository->eliminar($torneo, true);
    }
}

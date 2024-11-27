<?php

declare(strict_types=1);

namespace App\Manager;

use App\Entity\Sede;
use App\Entity\Torneo;
use App\Exception\AppException;
use App\Repository\SedeRepository;

class SedeManager
{
    public function __construct(
        private SedeRepository $sedeRepository,
        private ValidadorManager $validadorManager
    ) {
    }

    public function obtenerSedes(): array
    {
        return $this->sedeRepository->findAll();
    }

    public function obtenerSede(int $id): ?Sede
    {
        return $this->sedeRepository->find($id);
    }

    public function crearSede(
        Torneo $torneo,
        string $nombre,
        string $direccion,
    ): void {

        if ($this->sedeRepository->findOneBy(['torneo' => $torneo, 'nombre' => $nombre])) {
            throw new AppException('Ya existe una sede con ese nombre');
        }

        $this->validadorManager->validarSede(
            $nombre,
            $direccion,
        );

        $sede = new Sede();
        $sede->setTorneo($torneo);
        $sede->setNombre($nombre);
        $sede->setDomicilio($direccion);
        $this->sedeRepository->guardar($sede, false);
    }

    public function editarSede(
        Torneo $torneo,
        Sede $sede,
        string $nombre,
        string $direccion,
    ): void {

        if ($sede->getNombre() !== $nombre && $this->sedeRepository->findOneBy(['torneo' => $torneo, 'nombre' => $nombre])) {
            throw new AppException('Ya existe una sede con ese nombre');
        }

        $this->validadorManager->validarSede(
            $nombre,
            $direccion,
        );

        $sede->setNombre($nombre);
        $sede->setDomicilio($direccion);
        $this->sedeRepository->guardar($sede, false);
    }

    public function eliminarSede(Sede $sede): void
    {
        $this->sedeRepository->eliminar($sede, true);
    }
}

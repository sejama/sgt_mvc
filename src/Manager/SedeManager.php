<?php

declare(strict_types=1);

namespace App\Manager;

use App\Entity\Sede;
use App\Entity\Torneo;
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
        /*
        $this->validadorManager->validarSede(
            $nombre,
            $direccion,
            $telefono,
            $email
        );*/

        $sede = new Sede();
        $sede->setTorneo($torneo);
        $sede->setNombre($nombre);
        $sede->setDomicilio($direccion);
        $this->sedeRepository->guardar($sede, false);
    }

    public function eliminarSede(Sede $sede): void
    {
        $this->sedeRepository->eliminar($sede, true);
    }
}

<?php

declare(strict_types=1);

namespace App\Manager;

use App\Entity\Cancha;
use App\Entity\Sede;
use App\Exception\AppException;
use App\Repository\CanchaRepository;

class CanchaManager
{
    public function __construct(
        private CanchaRepository $canchaRepository,
        private ValidadorManager $validadorManager
    ) {
    }

    public function obtenerCanchas(Sede $sede): array
    {
        return $this->canchaRepository->findBy(['sede' => $sede]);
    }

    public function obtenerCancha(int $id): ?Cancha
    {
        $cancha = $this->canchaRepository->find($id);
        if ($cancha === null) {
            throw new AppException('No se encontró la cancha');
        }
        return $cancha;
    }

    public function crearCancha(Sede $sede, string $nombre, string $descripcion): void
    {
        if ($this->canchaRepository->findOneBy(['sede' => $sede, 'nombre' => $nombre])) {
            throw new AppException('Ya existe una cancha con ese nombre');
        }
        $this->validadorManager->validarCancha($nombre, $descripcion);

        $cancha = new Cancha();
        $cancha->setSede($sede);
        $cancha->setNombre($nombre);
        $cancha->setDescripcion($descripcion);

        $this->canchaRepository->guardar($cancha, true);
    }

    public function editarCancha(Cancha $cancha, string $nombre, string $descripcion): void
    {
        if (
            $cancha->getNombre() !== $nombre &&
            $this->canchaRepository->findOneBy(['sede' => $cancha->getSede(), 'nombre' => $nombre])
        ) {
            throw new AppException('Ya existe una cancha con ese nombre');
        }
        $this->validadorManager->validarCancha($nombre, $descripcion);

        $cancha->setNombre($nombre);
        $cancha->setDescripcion($descripcion);

        $this->canchaRepository->guardar($cancha, true);
    }

    public function eliminarCancha(Cancha $cancha): void
    {
        $this->canchaRepository->eliminar($cancha, true);
    }
}

<?php

declare(strict_types=1);

namespace App\Manager;

use App\Entity\Categoria;
use App\Entity\Equipo;
use App\Exception\AppException;
use App\Repository\EquipoRepository;

class EquipoManager
{
    public function __construct(
        private EquipoRepository $equipoRepository,
        private ValidadorManager $validadorManager
    ) {
    }

    public function obtenerEquipos(): array
    {
        return $this->equipoRepository->findAll();
    }

    public function obtenerEquipo(int $id): ?Equipo
    {
        if (!$equipo = $this->equipoRepository->find($id)) {
            throw new AppException('No se encontró el equipo');
        }
        return $equipo;
    }

    public function obtenerEquiposPorCategoria(Categoria $categoria): array
    {
        return $this->equipoRepository->findBy(['categoria' => $categoria]);
    }

    public function crearEquipo(
        Categoria $categoria,
        string $nombre,
        string $nombreCorto,
        string $pais,
        string $provincia,
        string $localidad
    ): Equipo {

        if ($this->equipoRepository->findOneBy(['categoria' => $categoria, 'nombre' => $nombre])) {
            throw new AppException('Ya existe un equipo con ese nombre');
        }

        if ($this->equipoRepository->findOneBy(['categoria' => $categoria, 'nombreCorto' => $nombreCorto])) {
            throw new AppException('Ya existe un equipo con ese nombre corto');
        }

        $this->validadorManager->validarEquipo(
            $nombre,
            $nombreCorto,
            $pais,
            $provincia,
            $localidad
        );

        $equipo = new Equipo();
        $equipo->setCategoria($categoria);
        $equipo->setNombre($nombre);
        $equipo->setNombreCorto($nombreCorto);
        $equipo->setPais($pais);
        $equipo->setProvincia($provincia);
        $equipo->setLocalidad($localidad);

        $this->equipoRepository->guardar($equipo, false);

        return $equipo;
    }

    public function editarEquipo(
        Equipo $equipo,
        string $nombre,
        string $nombreCorto,
        string $pais,
        string $provincia,
        string $localidad
    ): void {

        if (
            $equipo->getNombre() !== $nombre &&
            $this->equipoRepository->findOneBy(['categoria' => $equipo->getCategoria(), 'nombre' => $nombre])
        ) {
            throw new AppException('Ya existe un equipo con ese nombre');
        }

        if (
            $equipo->getNombreCorto() !== $nombreCorto &&
            $this->equipoRepository->findOneBy(['categoria' => $equipo->getCategoria(), 'nombreCorto' => $nombreCorto])
        ) {
            throw new AppException('Ya existe un equipo con ese nombre corto');
        }

        $this->validadorManager->validarEquipo(
            $nombre,
            $nombreCorto,
            $pais,
            $provincia,
            $localidad
        );

        $equipo->setNombre($nombre);
        $equipo->setNombreCorto($nombreCorto);
        $equipo->setPais($pais);
        $equipo->setProvincia($provincia);
        $equipo->setLocalidad($localidad);

        $this->equipoRepository->guardar($equipo, true);
    }

    public function eliminarEquipo(Equipo $equipo): void
    {
        $this->equipoRepository->eliminar($equipo, true);
    }
}

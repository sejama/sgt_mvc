<?php

declare(strict_types=1);

namespace App\Manager;

use App\Repository\CategoriaRepository;
use App\Entity\Categoria;
use App\Entity\Torneo;
use App\Enum\Genero;
use App\Exception\AppException;

class CategoriaManager
{
    public function __construct(
        private CategoriaRepository $categoriaRepository,
        private ValidadorManager $validadorManager
    ) {
    }

    public function obtenerCategorias(): array
    {
        return $this->categoriaRepository->findAll();
    }

    public function obtenerCategoria(int $id): ?Categoria
    {
        return $this->categoriaRepository->find($id);
    }

    public function obtenerCategoriasPorTorneo(Torneo $torneo): array
    {
        return $this->categoriaRepository->findBy(['torneo' => $torneo]);
    }

    public function crearCategoria(
        Torneo $torneo,
        string $genero,
        string $nombre,
        string $nombreCorto
    ): void {

        if ($this->categoriaRepository->findOneBy(['torneo' => $torneo, 'genero' => $genero, 'nombre' => $nombre])) {
            throw new AppException('Ya existe una categoría con ese nombre y genero');
        }

        if ($this->categoriaRepository->findOneBy(['torneo' => $torneo, 'nombreCorto' => $nombreCorto])) {
            throw new AppException('Ya existe una categoría con ese nombre corto');
        }

        $this->validadorManager->validarCategoria(
            $torneo,
            $genero,
            $nombre,
            $nombreCorto
        );

        $categoria = new Categoria();
        $categoria->setTorneo($torneo);
        $categoria->setGenero(Genero::from($genero));
        $categoria->setNombre($nombre);
        $categoria->setNombreCorto($nombreCorto);
        $this->categoriaRepository->guardar($categoria, false);
    }

    public function editarCategoria(
        Categoria $categoria,
        string $genero,
        string $nombre,
        string $nombreCorto
    ): void {
        if ($categoria->getGenero()->value !== $genero ||  $categoria->getNombre() !== $nombre && $this->categoriaRepository->findOneBy(['torneo' => $categoria->getTorneo(), 'genero' => $genero, 'nombre' => $nombre])) {
            throw new AppException('Ya existe una categoría con ese nombre y genero');
        }

        if ($categoria->getNombreCorto() !== $nombreCorto && $this->categoriaRepository->findOneBy(['torneo' => $categoria->getTorneo(), 'nombreCorto' => $nombreCorto])) {
            throw new AppException('Ya existe una categoría con ese nombre corto');
        }
        $categoria->setGenero(Genero::from($genero));
        $categoria->setNombre($nombre);
        $categoria->setNombreCorto($nombreCorto);
        $this->categoriaRepository->guardar($categoria, true);
    }

    public function editarDisputa(Categoria $categoria, string $disputa): void
    {
        $categoria->setDisputa($disputa);
        $this->categoriaRepository->guardar($categoria, true);
    }

    public function eliminarCategoria(Categoria $categoria): void
    {
        $this->categoriaRepository->eliminar($categoria, true);
    }
}

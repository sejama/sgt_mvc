<?php

declare(strict_types=1);

namespace App\Manager;

use App\Repository\CategoriaRepository;
use App\Entity\Categoria;
use App\Entity\Torneo;
use App\Enum\Genero;

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

    public function crearCategoria(
        Torneo $torneo,
        string $genero,
        string $nombre,
        string $nombreCorto
    ): void {
        /**
         * $this->validadorManager->validarCategoria(
         *   $nombre,
         *   $nombreCorto
        *);
            */
        $categoria = new Categoria();
        $categoria->setTorneo($torneo);
        $categoria->setGenero(Genero::from($genero));
        $categoria->setNombre($nombre);
        $categoria->setNombreCorto($nombreCorto);
        $this->categoriaRepository->guardar($categoria, false);
    }

    public function eliminarCategoria(Categoria $categoria): void
    {
        $this->categoriaRepository->eliminar($categoria, true);
    }
}

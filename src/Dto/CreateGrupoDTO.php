<?php

namespace App\Dto;

readonly class CreateGrupoDTO
{
    public function __construct(
        public string $nombre,
        public int $categoria,
        public int $cantidad,
        public int $clasificaOro,
        public ?int $clasificaPlata = null,
        public ?int $clasificaBronce = null,
    ) {}

    /** @param array<string, mixed> $data */
    public static function fromArray(array $data, int $categoriaId): self
    {
        return new self(
            nombre: $data['nombre'],
            categoria: $categoriaId,
            cantidad: (int)$data['cantidadEquipo'],
            clasificaOro: (int)$data['clasificaOro'],
            clasificaPlata: isset($data['clasificaPlata']) && (int)$data['clasificaPlata'] !== 0
                ? (int)$data['clasificaPlata']
                : null,
            clasificaBronce: isset($data['clasificaBronce']) && (int)$data['clasificaBronce'] !== 0
                ? (int)$data['clasificaBronce']
                : null,
        );
    }
}
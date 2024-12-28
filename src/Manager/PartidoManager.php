<?php

namespace App\Manager;

use App\Entity\Categoria;
use App\Entity\Grupo;
use App\Entity\Partido;
use App\Repository\PartidoRepository;

class PartidoManager
{
    public function __construct(
        private PartidoRepository $partidoRepository,
        private GrupoManager $grupoManager,
    ) {
    }

    public function obtenerPartidosXGrupo(int $grupoId): array
    {
        return $this->partidoRepository->findBy(['grupo' => $grupoId]);
    }

    public function obtenerPartido(int $partidoId): object
    {
        return $this->partidoRepository->find($partidoId);
    }

    public function obtenerPartidos(): array
    {
        return $this->partidoRepository->findAll();
    }

    public function obtenerPartidoXCancha(int $canchaId): array
    {
        return $this->partidoRepository->findBy(['cancha' => $canchaId]);
    }

    public function crearPartidoXCategoria(Categoria $categoria): void
    {
        $grupos = $this->grupoManager->obtenerGrupos($categoria);
        foreach ($grupos as $grupo) {
            $this->crearPartidosXGrupo($grupo);
        }
    }

    public function crearPartidosXGrupo(Grupo $grupo): void
    {
        $equipos = $grupo->getEquipo();
        for ($i = 0; $i < count($equipos); $i++) {
            for ($j = $i + 1; $j < count($equipos); $j++) {
                $partido = new Partido();
                $partido->setCancha(null);
                $partido->setGrupo($grupo);
                $partido->setEquipoLocal($equipos[$i]);
                $partido->setEquipoVisitante($equipos[$j]);

                $this->partidoRepository->guardar($partido);
            }
        }
    }
}
<?php

declare(strict_types=1);

namespace App\Manager;

use App\Repository\CategoriaRepository;
use App\Entity\Categoria;
use App\Entity\Torneo;
use App\Enum\EstadoCategoria;
use App\Enum\EstadoGrupo;
use App\Enum\Genero;
use App\Exception\AppException;
use App\Repository\PartidoRepository;
use App\Utils\GenerarPdf;

class CategoriaManager
{
    public function __construct(
        private CategoriaRepository $categoriaRepository,
        private PartidoRepository $partidoRepository,
        private ValidadorManager $validadorManager,
        private TablaManager $tablaManager
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
        $categoria->setEstado(EstadoCategoria::BORRADOR->value);
        $this->categoriaRepository->guardar($categoria, false);
    }

    public function editarCategoria(
        Categoria $categoria,
        string $genero,
        string $nombre,
        string $nombreCorto
    ): void {
        if ($categoria->getGenero()->value !== $genero ||  $categoria->getNombre() !== $nombre
            
            && $this->categoriaRepository->findOneBy(
                [
                'torneo' => $categoria->getTorneo(),
                'genero' => $genero,
                'nombre' => $nombre
                ]
            )
        ) {
            throw new AppException('Ya existe una categoría con ese nombre y genero');
        }

        if ($categoria->getNombreCorto() !== $nombreCorto
            
            && $this->categoriaRepository->findOneBy(['torneo' => $categoria->getTorneo(), 'nombreCorto' => $nombreCorto])
        ) {
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

    public function guardar(Categoria $categoria, bool $flush = true): void
    {
        $this->categoriaRepository->guardar($categoria, $flush);
    }

    public function eliminarCategoria(int $categoriaId): void
    {
        $categoria = $this->categoriaRepository->find($categoriaId);
        if ($categoria === null) {
            throw new AppException('No se encontró la categoría');
        }
        $this->categoriaRepository->eliminar($categoria, true);
    }

    public function armarPlayOff(Categoria $categoria): void
    {
        $grupos = $categoria->getGrupos();
        $gruposPosiciones = [];
        foreach ($grupos as $grupo) {
            if ($grupo->getEstado() !== EstadoGrupo::FINALIZADO->value) {
                throw new AppException('No se puede armar el play off si no se han finalizado todos los grupos');
            }
            $gruposPosiciones[$grupo->getNombre()] = $this->tablaManager->calcularPosiciones($grupo);
        }

        foreach ($categoria->getPartidos() as $partido) {
            if ($partido->getEquipoLocal() == null && $partido->getEquipoVisitante() == null) {
                if ($partido->getPartidoConfig()->getGrupoEquipo1() !== null && $partido->getPartidoConfig()->getGrupoEquipo2() !== null) {
                    $grupoEquipo1 = $partido->getPartidoConfig()->getGrupoEquipo1();
                    $posicionEquipo1 = $partido->getPartidoConfig()->getPosicionEquipo1();
                    
                    $grupoEquipo2 = $partido->getPartidoConfig()->getGrupoEquipo2();
                    $posicionEquipo2 = $partido->getPartidoConfig()->getPosicionEquipo2();
                    
                    $partido->setEquipoLocal($gruposPosiciones[$grupoEquipo1->getNombre()][$posicionEquipo1 - 1]['equipo']);
                    $partido->setEquipoVisitante($gruposPosiciones[$grupoEquipo2->getNombre()][$posicionEquipo2 - 1]['equipo']);
                    
                    $this->partidoRepository->guardar($partido);

                    $pdf = new GenerarPdf();
                    $pdf->generarPdf($partido, $categoria->getTorneo()->getRuta());
                }
            }
        }

        $categoria->setEstado(EstadoCategoria::ZONAS_CERRADAS->value);
        $this->categoriaRepository->guardar($categoria, true);
    }
}

<?php

declare(strict_types=1);

namespace App\Manager;

use Symfony\Contracts\Service\LazyServiceTrait;
use App\Repository\CategoriaRepository;
use App\Entity\Categoria;
use App\Entity\Torneo;
use App\Enum\EstadoCategoria;
use App\Enum\EstadoGrupo;
use App\Enum\Genero;
use App\Exception\AppException;

class CategoriaManager
{
    private GrupoManager $grupoManager;

    public function __construct(
        private CategoriaRepository $categoriaRepository,
        private ValidadorManager $validadorManager,
    ) {
    }

    /*
    Inyección diferida (Lazy Loading)
    En lugar de inyectar directamente GrupoManager en el constructor de CategoriaManager, puedes usar un contenedor de servicios o un proxy para cargar GrupoManager solo cuando sea necesario. Esto rompe la dependencia circular.
    
    En este caso:

    GrupoManager no se inyecta directamente en el constructor.
    Se utiliza un método setGrupoManager para inyectarlo después de que el contenedor de servicios haya resuelto todas las dependencias.
    En Symfony, puedes configurar esto en services.yaml:

    services:
    App\Manager\CategoriaManager:
        calls:
            - [setGrupoManager, ['@App\Manager\GrupoManager']]
    */
    public function setGrupoManager(GrupoManager $grupoManager): void
    {
        $this->grupoManager = $grupoManager;
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

    public function eliminarCategoria(Categoria $categoria): void
    {
        $this->categoriaRepository->eliminar($categoria, true);
    }

    public function armarPlayOff(Categoria $categoria): array
    {
        $grupos = $categoria->getGrupos();
        $gruposPosiciones = [];
        foreach ($grupos as $grupo) {
            if ($grupo->getEstado() !== EstadoGrupo::FINALIZADO->value) {
                throw new AppException('No se puede armar el play off si no se han finalizado todos los grupos');
            }
            $gruposPosiciones[$grupo->getNombre()] = $this->grupoManager->calcularPosiciones($grupo);
        }

        return $gruposPosiciones;
    }
}

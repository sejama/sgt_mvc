<?php

namespace App\Manager;

use App\Entity\Categoria;
use App\Entity\Equipo;
use App\Entity\Grupo;
use App\Enum\EstadoCategoria;
use App\Enum\EstadoGrupo;
use App\Exception\AppException;
use App\Dto\CreateGrupoDTO;
use App\Repository\EquipoRepository;
use App\Repository\GrupoRepository;

class GrupoManager
{
    public function __construct(
        private GrupoRepository $grupoRepository,
        private EquipoRepository $equipoRepository,
        private CategoriaManager $categoriaManager,
        private ValidadorManager $validadorManager
    ) {
    }

    public function obtenerGrupo(int $id): Grupo
    {
        if (!$grupo = $this->grupoRepository->find($id)) {
            throw new AppException('No se encontró el grupo');
        }
        return $grupo;
    }

    public function obtenerGrupos(Categoria $categoria): array
    {
        return $this->grupoRepository->findBy(['categoria' => $categoria], ['nombre' => 'ASC']);
    }

    /** @param CreateGrupoDTO[] $gruposDTO */
    public function crearGrupos(
        array $gruposDTO,
    ) {
        $totalClasificados = 0;
        $totalEquiposZonas = 0;
        $inicio = 0;

        $categoria = $this->categoriaManager->obtenerCategoria($gruposDTO[0]->categoria);
        $equipos = $categoria->getEquipos();

        $totalEquipos = count($equipos);

        foreach ($gruposDTO as $grupoDTO) {
            $totalEquiposZonas += $grupoDTO->cantidad;
        }
        if ($totalEquiposZonas !== $totalEquipos) {
            throw new AppException(
                'La cantidad de equipos en las zonas no coincide con la cantidad de equipos en la categoría'
            );
        }

        $equipos = [];
        foreach ($categoria->getEquipos() as $equipo) {
            $equipos[] = $equipo;
        }

        foreach ($gruposDTO as $grupoDTO) {
            try {
                $this->validadorManager->validarGrupo($grupoDTO->nombre);

                if (!$grupoDTO->clasificaOro) {
                    throw new AppException('No se puede crear un grupo sin equipos que clasifiquen a oro');
                }

                if ($totalEquipos < $totalClasificados += $grupoDTO->clasificaOro) {
                    throw new AppException('No se puede clasificar más equipos de los que hay en la categoría');
                }

                if ($grupoDTO->clasificaPlata && $totalEquipos < $totalClasificados += $grupoDTO->clasificaPlata) {
                    throw new AppException('No se puede clasificar más equipos de los que hay en la categoría');
                }

                if ($grupoDTO->clasificaBronce && !$grupoDTO->clasificaPlata) {
                    throw new AppException('No se puede clasificar equipos de bronce sin clasificar equipos de plata');
                }

                if ($grupoDTO->clasificaBronce &&  $totalEquipos < $totalClasificados += $grupoDTO->clasificaBronce) {
                    throw new AppException('No se puede clasificar más equipos de los que hay en la categoría');
                }

                $entidad = new Grupo();
                $entidad->setNombre($grupoDTO->nombre);
                $entidad->setCategoria($categoria);
                $entidad->setClasificaOro($grupoDTO->clasificaOro);
                $entidad->setClasificaPlata($grupoDTO->clasificaPlata);
                $entidad->setClasificaBronce($grupoDTO->clasificaBronce);
                $entidad->setEstado(EstadoGrupo::BORRADOR->value);

                $equiposGrupo = array_slice($equipos, $inicio, $inicio += $grupoDTO->cantidad);
                foreach ($equiposGrupo as $equipo) {
                    $entidad->addEquipo($equipo);
                }
                $categoria->setEstado(EstadoCategoria::ZONAS_CREADAS->value);
                $this->categoriaManager->guardar($categoria, false);
                $this->grupoRepository->guardar($entidad);
            } catch (AppException $e) {
                throw new AppException($e->getMessage());
            } catch (\Exception $e) {
                throw new AppException('Error al crear los grupos ' . $e->getMessage());
            }
        }
    }

    public function obtenerEquiposDeCategoriaConGrupo(Categoria $categoria): array
    {
        $equipos = [];
        foreach ($categoria->getEquipos() as $equipo) {
            if ($equipo->getGrupo() === null) {
                continue;
            }

            $equipos[] = $equipo;
        }

        usort($equipos, static function (Equipo $a, Equipo $b): int {
            $grupoA = $a->getGrupo()?->getNombre() ?? '';
            $grupoB = $b->getGrupo()?->getNombre() ?? '';

            $comparacionGrupo = strcmp($grupoA, $grupoB);
            if ($comparacionGrupo !== 0) {
                return $comparacionGrupo;
            }

            return strcmp($a->getNombre() ?? '', $b->getNombre() ?? '');
        });

        return $equipos;
    }

    public function intercambiarEquiposEntreGrupos(Categoria $categoria, int $equipoOrigenId, int $equipoDestinoId): void
    {
        if ($equipoOrigenId <= 0 || $equipoDestinoId <= 0) {
            throw new AppException('Debe seleccionar ambos equipos para poder intercambiarlos.');
        }

        if ($equipoOrigenId === $equipoDestinoId) {
            throw new AppException('Debe seleccionar dos equipos distintos para realizar el intercambio.');
        }

        if ($categoria->getEstado() !== EstadoCategoria::ZONAS_CREADAS->value) {
            throw new AppException('Solo se pueden intercambiar equipos cuando la categoria esta en estado Zonas_creadas.');
        }

        if (count($categoria->getPartidos()) > 0) {
            throw new AppException('No se pueden intercambiar equipos porque ya existen partidos generados para la categoria.');
        }

        $equipoOrigen = null;
        $equipoDestino = null;
        foreach ($categoria->getEquipos() as $equipo) {
            if ($equipo->getId() === $equipoOrigenId) {
                $equipoOrigen = $equipo;
            }

            if ($equipo->getId() === $equipoDestinoId) {
                $equipoDestino = $equipo;
            }
        }

        if ($equipoOrigen === null || $equipoDestino === null) {
            throw new AppException('Alguno de los equipos seleccionados no pertenece a la categoria.');
        }

        $grupoOrigen = $equipoOrigen->getGrupo();
        $grupoDestino = $equipoDestino->getGrupo();

        if ($grupoOrigen === null || $grupoDestino === null) {
            throw new AppException('Solo se pueden intercambiar equipos que ya tengan grupo asignado.');
        }

        if ($grupoOrigen->getId() === $grupoDestino->getId()) {
            throw new AppException('Los equipos ya pertenecen al mismo grupo.');
        }

        $equipoOrigen->setGrupo($grupoDestino);
        $equipoDestino->setGrupo($grupoOrigen);

        $this->equipoRepository->guardar($equipoOrigen, false);
        $this->equipoRepository->guardar($equipoDestino);
    }

}

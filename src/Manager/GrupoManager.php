<?php

namespace App\Manager;

use App\Entity\Categoria;
use App\Entity\Grupo;
use App\Exception\AppException;
use App\Repository\GrupoRepository;

class GrupoManager
{
    public function __construct(
        private GrupoRepository $grupoRepository,
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

    public function crearGrupos(
        array $grupos,
    ) {
        $totalClasificados = 0;
        $totalEquiposZonas = 0;
        $inicio = 0;

        $categoria = $this->categoriaManager->obtenerCategoria($grupos[0]['categoria']);
        $equipos = $categoria->getEquipos();

        $totalEquipos = count($equipos);

        foreach ($grupos as $grupo) {
            $totalEquiposZonas += $grupo['cantidad'];
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

        foreach ($grupos as $grupo) {
            try {
                $this->validadorManager->validarGrupo($grupo['nombre']);

                if (!$grupo['clasificaOro']) {
                    throw new AppException('No se puede crear un grupo sin equipos que clasifiquen a oro');
                }

                if ($totalEquipos < $totalClasificados += $grupo['clasificaOro']) {
                    throw new AppException('No se puede clasificar más equipos de los que hay en la categoría');
                }

                if ($grupo['clasificaPlata'] && $totalEquipos < $totalClasificados += $grupo['clasificaPlata']) {
                    throw new AppException('No se puede clasificar más equipos de los que hay en la categoría');
                }

                if ($grupo['clasificaBronce'] && !$grupo['clasificaPlata']) {
                    throw new AppException('No se puede clasificar equipos de bronce sin clasificar equipos de plata');
                }

                if ($grupo['clasificaBronce'] &&  $totalEquipos < $totalClasificados += $grupo['clasificaBronce']) {
                    throw new AppException('No se puede clasificar más equipos de los que hay en la categoría');
                }

                $entidad = new Grupo();
                $entidad->setNombre($grupo['nombre']);
                $entidad->setCategoria($categoria);
                $entidad->setClasificaOro($grupo['clasificaOro']);
                $entidad->setClasificaPlata($grupo['clasificaPlata']);
                $entidad->setClasificaBronce($grupo['clasificaBronce']);

                $equiposGrupo = array_slice($equipos, $inicio, $inicio += $grupo['cantidad']);
                foreach ($equiposGrupo as $equipo) {
                    $entidad->addEquipo($equipo);
                }
                $this->grupoRepository->guardar($entidad, true);
            } catch (AppException $e) {
                throw new AppException($e->getMessage());
            } catch (\Exception $e) {
                throw new AppException('Error al crear los grupos ' . $e->getMessage());
            }
        }
    }
}
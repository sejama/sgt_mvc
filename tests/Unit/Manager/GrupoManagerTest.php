<?php

namespace App\Tests\Unit\Manager;

use App\Entity\Categoria;
use App\Entity\Equipo;
use App\Entity\Grupo;
use App\Entity\Partido;
use App\Enum\EstadoCategoria;
use App\Exception\AppException;
use App\Manager\CategoriaManager;
use App\Manager\GrupoManager;
use App\Manager\ValidadorManager;
use App\Repository\EquipoRepository;
use App\Repository\GrupoRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class GrupoManagerTest extends KernelTestCase
{
    public function testCrearGruposOK(): void
    {
        $grupoRepository = $this->createMock(GrupoRepository::class);
        $equipoRepository = $this->createMock(EquipoRepository::class);
        $categoriaManager = $this->createMock(CategoriaManager::class);
        $validadorManager = $this->createMock(ValidadorManager::class);

        $grupoManager = new GrupoManager($grupoRepository, $equipoRepository, $categoriaManager, $validadorManager);

        $grupos = [];
        $grupos[] = [
            'nombre' => 'Grupo 1',
            'categoria' => 1,
            'cantidad' => 4,
            'clasificaOro' => 2,
            'clasificaPlata' => 2,
            'clasificaBronce' => null,
        ];

        $grupos[] = [
            'nombre' => 'Grupo 2',
            'categoria' => 1,
            'cantidad' => 4,
            'clasificaOro' => 2,
            'clasificaPlata' => 2,
            'clasificaBronce' => null,
        ];

        $categoria = new Categoria();
        $categoria->setNombre('Categoria 1');
        $categoria->setNombreCorto('C1');

        $equipo1 = new Equipo();
        $equipo1->setNombre('Equipo 1');
        $equipo1->setNombreCorto('E1');
        $equipo1->setCategoria($categoria);

        $equipo2 = new Equipo();
        $equipo2->setNombre('Equipo 2');
        $equipo2->setNombreCorto('E2');
        $equipo2->setCategoria($categoria);

        $equipo3 = new Equipo();
        $equipo3->setNombre('Equipo 3');
        $equipo3->setNombreCorto('E3');
        $equipo3->setCategoria($categoria);

        $equipo4 = new Equipo();
        $equipo4->setNombre('Equipo 4');
        $equipo4->setNombreCorto('E4');
        $equipo4->setCategoria($categoria);

        $equipo5 = new Equipo();
        $equipo5->setNombre('Equipo 5');
        $equipo5->setNombreCorto('E5');
        $equipo5->setCategoria($categoria);

        $equipo6 = new Equipo();
        $equipo6->setNombre('Equipo 6');
        $equipo6->setNombreCorto('E6');
        $equipo6->setCategoria($categoria);

        $equipo7 = new Equipo();
        $equipo7->setNombre('Equipo 7');
        $equipo7->setNombreCorto('E7');
        $equipo7->setCategoria($categoria);

        $equipo8 = new Equipo();
        $equipo8->setNombre('Equipo 8');
        $equipo8->setNombreCorto('E8');
        $equipo8->setCategoria($categoria);

        $categoria->addEquipo($equipo1);
        $categoria->addEquipo($equipo2);
        $categoria->addEquipo($equipo3);
        $categoria->addEquipo($equipo4);
        $categoria->addEquipo($equipo5);
        $categoria->addEquipo($equipo6);
        $categoria->addEquipo($equipo7);
        $categoria->addEquipo($equipo8);

        $categoriaManager
            ->expects($this->once())
            ->method('obtenerCategoria')
            ->with($grupos[0]['categoria'])
            ->willReturn($categoria);

        $grupoRepository
            ->expects($this->exactly(count($grupos)))
            ->method('guardar')
            ->with($this->isInstanceOf(Grupo::class));

        $grupoManager->crearGrupos($grupos);
    }

    public function testIntercambiarEquiposEntreGruposOK(): void
    {
        $grupoRepository = $this->createMock(GrupoRepository::class);
        $equipoRepository = $this->createMock(EquipoRepository::class);
        $categoriaManager = $this->createMock(CategoriaManager::class);
        $validadorManager = $this->createMock(ValidadorManager::class);

        $grupoManager = new GrupoManager($grupoRepository, $equipoRepository, $categoriaManager, $validadorManager);

        $categoria = new Categoria();
        $categoria->setNombre('Categoria 1');
        $categoria->setNombreCorto('C1');
        $categoria->setEstado(EstadoCategoria::ZONAS_CREADAS->value);

        $grupoA = new Grupo();
        $grupoA->setNombre('A');
        $this->setEntityId($grupoA, 10);

        $grupoB = new Grupo();
        $grupoB->setNombre('B');
        $this->setEntityId($grupoB, 20);

        $equipoA = new Equipo();
        $equipoA->setNombre('Equipo A');
        $equipoA->setNombreCorto('EA');
        $equipoA->setCategoria($categoria);
        $equipoA->setGrupo($grupoA);
        $this->setEntityId($equipoA, 101);

        $equipoB = new Equipo();
        $equipoB->setNombre('Equipo B');
        $equipoB->setNombreCorto('EB');
        $equipoB->setCategoria($categoria);
        $equipoB->setGrupo($grupoB);
        $this->setEntityId($equipoB, 102);

        $categoria->addEquipo($equipoA);
        $categoria->addEquipo($equipoB);

        $equipoRepository
            ->expects($this->exactly(2))
            ->method('guardar');

        $grupoManager->intercambiarEquiposEntreGrupos($categoria, 101, 102);

        self::assertSame($grupoB, $equipoA->getGrupo());
        self::assertSame($grupoA, $equipoB->getGrupo());
    }

    public function testIntercambiarEquiposEntreGruposFallaSiYaHayPartidosGenerados(): void
    {
        $grupoRepository = $this->createMock(GrupoRepository::class);
        $equipoRepository = $this->createMock(EquipoRepository::class);
        $categoriaManager = $this->createMock(CategoriaManager::class);
        $validadorManager = $this->createMock(ValidadorManager::class);

        $grupoManager = new GrupoManager($grupoRepository, $equipoRepository, $categoriaManager, $validadorManager);

        $categoria = new Categoria();
        $categoria->setNombre('Categoria 1');
        $categoria->setNombreCorto('C1');
        $categoria->setEstado(EstadoCategoria::ZONAS_CREADAS->value);
        $categoria->addPartido(new Partido());

        $grupoA = new Grupo();
        $grupoA->setNombre('A');
        $this->setEntityId($grupoA, 10);

        $grupoB = new Grupo();
        $grupoB->setNombre('B');
        $this->setEntityId($grupoB, 20);

        $equipoA = new Equipo();
        $equipoA->setNombre('Equipo A');
        $equipoA->setNombreCorto('EA');
        $equipoA->setCategoria($categoria);
        $equipoA->setGrupo($grupoA);
        $this->setEntityId($equipoA, 101);

        $equipoB = new Equipo();
        $equipoB->setNombre('Equipo B');
        $equipoB->setNombreCorto('EB');
        $equipoB->setCategoria($categoria);
        $equipoB->setGrupo($grupoB);
        $this->setEntityId($equipoB, 102);

        $categoria->addEquipo($equipoA);
        $categoria->addEquipo($equipoB);

        $equipoRepository
            ->expects($this->never())
            ->method('guardar');

        $this->expectException(AppException::class);
        $this->expectExceptionMessage('No se pueden intercambiar equipos porque ya existen partidos generados para la categoria.');

        $grupoManager->intercambiarEquiposEntreGrupos($categoria, 101, 102);
    }

    private function setEntityId(object $entity, int $id): void
    {
        $reflection = new \ReflectionProperty($entity, 'id');
        $reflection->setAccessible(true);
        $reflection->setValue($entity, $id);
    }
}

<?php

namespace App\Tests\Unit\Manager;

use App\Entity\Categoria;
use App\Entity\Equipo;
use App\Entity\Torneo;
use App\Exception\AppException;
use App\Manager\EquipoManager;
use App\Manager\ValidadorManager;
use App\Repository\EquipoRepository;
use App\Repository\PartidoRepository;
use PHPUnit\Framework\TestCase;

class EquipoManagerTest extends TestCase
{
   public function testObtenerEquipos(): void
   {
        $equipoManager = new EquipoManager(
            $this->createMock(EquipoRepository::class),
            $this->createMock(PartidoRepository::class),
            $this->createMock(ValidadorManager::class),
        );

        $this->assertIsArray($equipoManager->obtenerEquipos());
        $this->assertEquals([], $equipoManager->obtenerEquipos());
   }

    public function testObtenerEquipoOk(): void
    {
        $equipoRepository = $this->createMock(EquipoRepository::class);
        $validadorManager = $this->createMock(ValidadorManager::class);
        $partidoRepository = $this->createMock(PartidoRepository::class);

        $equipoManager = new EquipoManager(
            $equipoRepository,
            $partidoRepository,
            $validadorManager,
        );

        $equipo = $this->createMock(Equipo::class);
        $equipoRepository->method('find')->willReturn($equipo);

        $this->assertEquals($equipo, $equipoManager->obtenerEquipo(1));
    }

    public function testObtenerEquipoError(): void
    {
        $this->expectExceptionMessage('No se encontró el equipo');

        $equipoRepository = $this->createMock(EquipoRepository::class);
        $partidoRepository = $this->createMock(PartidoRepository::class);
        $validadorManager = $this->createMock(ValidadorManager::class);

        $equipoManager = new EquipoManager(
            $equipoRepository,
            $partidoRepository,
            $validadorManager,
        );

        $equipoRepository->method('find')->willReturn(null);
        $equipoManager->obtenerEquipo(1);
    }

    public function testObtenerEquiposPorCategoria(): void
    {
        $equipoRepository = $this->createMock(EquipoRepository::class);
        $partidoRepository = $this->createMock(PartidoRepository::class);
        $validadorManager = $this->createMock(ValidadorManager::class);

        $equipoManager = new EquipoManager(
            $equipoRepository,
            $partidoRepository,
            $validadorManager,
        );

        $categoria = $this->createMock(Categoria::class);

        $this->assertIsArray($equipoManager->obtenerEquiposPorCategoria($categoria));
        $this->assertEquals([], $equipoManager->obtenerEquiposPorCategoria($categoria));
    }

    public function testCrearEquipoOk(): void
    {
        $equipoRepository = $this->createMock(EquipoRepository::class);
        $partidoRepository = $this->createMock(PartidoRepository::class);
        $validadorManager = $this->createMock(ValidadorManager::class);

        $equipoManager = new EquipoManager(
            $equipoRepository,
            $partidoRepository,
            $validadorManager,
        );

        $equipoRepository->expects($this->exactly(2))
        ->method('findOneBy')
        ->willReturn(null);

        $validadorManager->expects($this->once())
        ->method('validarEquipo');

        $torneo = new Torneo();
        $torneo->setRuta('ruta');

        $categoria = new Categoria();
        $categoria->setTorneo($torneo);

        $equipoRepository->expects($this->once())
        ->method('buscarEquiposXTorneo')
        ->with('ruta')
        ->willReturn([]);

        $equipoManager->crearEquipo(
            $categoria,
            'nombre',
            'nombreCorto',
            'pais',
            'provincia',
            'localidad'
        );
    }

    public function testCrearEquipoErrorNombre(): void
    {
        $this->expectExceptionMessage('Ya existe un equipo con ese nombre');

        $equipoRepository = $this->createMock(EquipoRepository::class);
        $partidoRepository = $this->createMock(PartidoRepository::class);
        $validadorManager = $this->createMock(ValidadorManager::class);

        $equipoManager = new EquipoManager(
            $equipoRepository,
            $partidoRepository,
            $validadorManager,
        );

        $equipoRepository->expects($this->once())
            ->method('findOneBy')->willReturn(new Equipo());

        $categoria = new Categoria();

        $this->expectException(AppException::class);
        $this->expectExceptionMessage('Ya existe un equipo con ese nombre');
        $equipoManager->crearEquipo(
            $categoria,
            'nombre',
            'nombreCorto',
            'pais',
            'provincia',
            'localidad'
        );
    }

    public function testCrearEquipoErrorNombreCorto(): void
    {
        $this->expectExceptionMessage('Ya existe un equipo con ese nombre corto');

        $equipoRepository = $this->createMock(EquipoRepository::class);
        $partidoRepository = $this->createMock(PartidoRepository::class);
        $validadorManager = $this->createMock(ValidadorManager::class);

        $equipoManager = new EquipoManager(
            $equipoRepository,
            $partidoRepository,
            $validadorManager,
        );

        $equipoRepository->expects($this->exactly(2))
            ->method('findOneBy')
            ->willReturnOnConsecutiveCalls(null, new Equipo());

        $categoria = new Categoria();

        $this->expectException(AppException::class);
        $this->expectExceptionMessage('Ya existe un equipo con ese nombre corto');
        $equipoManager->crearEquipo(
            $categoria,
            'nombre',
            'nombreCorto',
            'pais',
            'provincia',
            'localidad'
        );
    }

    public function testEditarEquipoOk(): void
    {
        $equipoRepository = $this->createMock(EquipoRepository::class);
        $partidoRepository = $this->createMock(PartidoRepository::class);
        $validadorManager = $this->createMock(ValidadorManager::class);

        $equipoManager = new EquipoManager(
            $equipoRepository,
            $partidoRepository,
            $validadorManager,
        );

        $equipo = new Equipo();
        $equipo->setCategoria(new Categoria());

        $equipoRepository->expects($this->exactly(2))
            ->method('findOneBy')
            ->willReturn(null);

        $validadorManager->expects($this->once())
            ->method('validarEquipo');

        $equipoManager->editarEquipo(
            $equipo,
            'nombre',
            'nombreCorto',
            'pais',
            'provincia',
            'localidad'
        );
    }

    public function testEditarEquipoErrorNombre(): void
    {
        $this->expectExceptionMessage('Ya existe un equipo con ese nombre');

        $equipoRepository = $this->createMock(EquipoRepository::class);
        $partidoRepository = $this->createMock(PartidoRepository::class);
        $validadorManager = $this->createMock(ValidadorManager::class);

        $equipoManager = new EquipoManager(
            $equipoRepository,
            $partidoRepository,
            $validadorManager,
        );

        $equipo = new Equipo();

        $equipoRepository->expects($this->once())
            ->method('findOneBy')
            ->willReturn(new Equipo());

        $this->expectException(AppException::class);
        $this->expectExceptionMessage('Ya existe un equipo con ese nombre');
        $equipoManager->editarEquipo(
            $equipo,
            'nombre',
            'nombreCorto',
            'pais',
            'provincia',
            'localidad'
        );
    }

    public function testEditarEquipoErrorNombreCorto(): void
    {
        $this->expectExceptionMessage('Ya existe un equipo con ese nombre corto');

        $equipoRepository = $this->createMock(EquipoRepository::class);
        $partidoRepository = $this->createMock(PartidoRepository::class);
        $validadorManager = $this->createMock(ValidadorManager::class);

        $equipoManager = new EquipoManager(
            $equipoRepository,
            $partidoRepository,
            $validadorManager,
        );

        $equipo = new Equipo();

        $equipoRepository->expects($this->exactly(2))
            ->method('findOneBy')
            ->willReturnOnConsecutiveCalls(null, new Equipo());

        $this->expectException(AppException::class);
        $this->expectExceptionMessage('Ya existe un equipo con ese nombre corto');
        $equipoManager->editarEquipo(
            $equipo,
            'nombre',
            'nombreCorto',
            'pais',
            'provincia',
            'localidad'
        );
    }

    public function testEliminarEquipoOk(): void
    {
        $equipoRepository = $this->createMock(EquipoRepository::class);
        $partidoRepository = $this->createMock(PartidoRepository::class);
        $validadorManager = $this->createMock(ValidadorManager::class);

        $equipoManager = new EquipoManager(
            $equipoRepository,
            $partidoRepository,
            $validadorManager,
        );

        $equipo = new Equipo();

        $equipoRepository->expects($this->once())
            ->method('eliminar');

        $equipoManager->eliminarEquipo($equipo);
    }
}

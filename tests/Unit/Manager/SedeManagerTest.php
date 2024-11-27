<?php

namespace App\Tests\Unit\Manager;

use App\Entity\Sede;
use App\Entity\Torneo;
use App\Exception\AppException;
use App\Manager\SedeManager;
use App\Manager\ValidadorManager;
use App\Repository\SedeRepository;
use PHPUnit\Framework\TestCase;


class SedeManagerTest extends TestCase
{
    public function testObtenerSedesOk(): void
    {
        $sedeRepository = $this->createMock(SedeRepository::class);
        $validadorManager = $this->createMock(ValidadorManager::class);

        $sedeManager = new SedeManager(
            $sedeRepository,
            $validadorManager,
        );

        $this->assertIsArray($sedeManager->obtenerSedes());
        $this->assertEquals([], $sedeManager->obtenerSedes());
    }

    public function testObtenerSedeOk(): void
    {
        $sedeRepository = $this->createMock(SedeRepository::class);
        $validadorManager = $this->createMock(ValidadorManager::class);

        $sedeManager = new SedeManager(
            $sedeRepository,
            $validadorManager,
        );

        $sede = $this->createMock(Sede::class);
        $sedeRepository->method('find')->willReturn($sede);

        $this->assertEquals($sede, $sedeManager->obtenerSede(1));
    }

    public function testObtenerSedeNoEncontrada(): void
    {
        $sedeRepository = $this->createMock(SedeRepository::class);
        $validadorManager = $this->createMock(ValidadorManager::class);

        $sedeManager = new SedeManager(
            $sedeRepository,
            $validadorManager,
        );

        $this->assertNull($sedeManager->obtenerSede(1));
    }

    public function testCrearSedeOk(): void
    {
        $sedeRepository = $this->createMock(SedeRepository::class);
        $validadorManager = $this->createMock(ValidadorManager::class);

        $sedeManager = new SedeManager(
            $sedeRepository,
            $validadorManager,
        );

        $torneo = $this->createMock(Torneo::class);
        $sedeRepository->expects($this->once())->method('findOneBy');
        $sedeRepository->expects($this->once())->method('guardar');

        $sedeManager->crearSede($torneo, 'nombre', 'direccion');
    }

    public function testCrearSedeExistente(): void
    {
        $sedeRepository = $this->createMock(SedeRepository::class);
        $validadorManager = $this->createMock(ValidadorManager::class);

        $sedeManager = new SedeManager(
            $sedeRepository,
            $validadorManager,
        );

        $torneo = $this->createMock(Torneo::class);
        $sedeRepository->method('findOneBy')->willReturn($this->createMock(Sede::class));

        $this->expectException(AppException::class);
        $this->expectExceptionMessage('Ya existe una sede con ese nombre');

        $sedeManager->crearSede($torneo, 'nombre', 'direccion');
    }
}

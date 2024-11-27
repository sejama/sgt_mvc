<?php

namespace App\Tests\Unit\Manager;

use App\Entity\Cancha;
use App\Entity\Sede;
use App\Exception\AppException;
use App\Manager\CanchaManager;
use App\Manager\ValidadorManager;
use App\Repository\CanchaRepository;
use PHPUnit\Framework\TestCase;

class CanchaManagerTest extends TestCase
{
    public function testObtenerCanchas(): void
    {
        $canchaRepository = $this->createMock(CanchaRepository::class);
        $validadorManager = $this->createMock(ValidadorManager::class);

        $canchaManager = new CanchaManager(
            $canchaRepository,
            $validadorManager,
        );

        $sede = $this->createMock(Sede::class);

        $this->assertIsArray($canchaManager->obtenerCanchas($sede));
        $this->assertEquals([], $canchaManager->obtenerCanchas($sede));
    }

    public function testObtenerCanchaOk(): void
    {
        $canchaRepository = $this->createMock(CanchaRepository::class);
        $validadorManager = $this->createMock(ValidadorManager::class);

        $canchaManager = new CanchaManager(
            $canchaRepository,
            $validadorManager,
        );

        $cancha = $this->createMock(Cancha::class);
        $canchaRepository->method('find')->willReturn($cancha);

        $this->assertEquals($cancha, $canchaManager->obtenerCancha(1));
    }

    public function testObtenerCanchaError(): void
    {
        $this->expectExceptionMessage('No se encontró la cancha');

        $canchaRepository = $this->createMock(CanchaRepository::class);
        $validadorManager = $this->createMock(ValidadorManager::class);

        $canchaManager = new CanchaManager(
            $canchaRepository,
            $validadorManager,
        );

        $canchaRepository->method('find')->willReturn(null);

        $this->expectException(AppException::class);
        $this->expectExceptionMessage('No se encontró la cancha');
        $canchaManager->obtenerCancha(1);
    }

    public function testcrearCanchaOk(): void
    {
        $canchaRepository = $this->createMock(CanchaRepository::class);
        $validadorManager = $this->createMock(ValidadorManager::class);

        $canchaManager = new CanchaManager(
            $canchaRepository,
            $validadorManager,
        );

        $sede = $this->createMock(Sede::class);

        $canchaRepository->expects($this->once())->method('findOneBy')->willReturn(null);
        $validadorManager->expects($this->once())->method('validarCancha');
        $canchaRepository->expects($this->once())->method('guardar');

        $canchaManager->crearCancha($sede, 'nombre', 'descripcion');
    }

    public function testcrearCanchaError(): void
    {

        $canchaRepository = $this->createMock(CanchaRepository::class);
        $validadorManager = $this->createMock(ValidadorManager::class);

        $canchaManager = new CanchaManager(
            $canchaRepository,
            $validadorManager,
        );

        $sede = $this->createMock(Sede::class);

        $canchaRepository->expects($this->once())
            ->method('findOneBy')->with(['sede' => $sede, 'nombre' => 'nombre'])
            ->willReturn($this->createMock(Cancha::class));

        $this->expectException(AppException::class);
        $this->expectExceptionMessage('Ya existe una cancha con ese nombre');
        $canchaManager->crearCancha($sede, 'nombre', 'descripcion');
    }

    public function testeditarCanchaOk(): void
    {
        $canchaRepository = $this->createMock(CanchaRepository::class);
        $validadorManager = $this->createMock(ValidadorManager::class);

        $canchaManager = new CanchaManager(
            $canchaRepository,
            $validadorManager,
        );

        $cancha = $this->createMock(Cancha::class);

        $canchaRepository->expects($this->once())->method('findOneBy')->willReturn(null);
        $validadorManager->expects($this->once())->method('validarCancha');
        $canchaRepository->expects($this->once())->method('guardar');

        $canchaManager->editarCancha($cancha, 'nombre', 'descripcion');
    }

    public function testeditarCanchaError(): void
    {
        $this->expectException(AppException::class);
        $this->expectExceptionMessage('Ya existe una cancha con ese nombre');

        $canchaRepository = $this->createMock(CanchaRepository::class);
        $validadorManager = $this->createMock(ValidadorManager::class);

        $canchaManager = new CanchaManager(
            $canchaRepository,
            $validadorManager,
        );

        $cancha = $this->createMock(Cancha::class);

        $canchaRepository->expects($this->once())
            ->method('findOneBy')
            ->with(['sede' => $cancha->getSede(), 'nombre' => 'nombre'])
            ->willReturn($this->createMock(Cancha::class));

        $canchaManager->editarCancha($cancha, 'nombre', 'descripcion');
    }

    public function testeliminarCancha(): void
    {
        $canchaRepository = $this->createMock(CanchaRepository::class);
        $validadorManager = $this->createMock(ValidadorManager::class);

        $canchaManager = new CanchaManager(
            $canchaRepository,
            $validadorManager,
        );

        $cancha = $this->createMock(Cancha::class);

        $canchaRepository->expects($this->once())->method('eliminar');

        $canchaManager->eliminarCancha($cancha);
    }
}

<?php

declare(strict_types=1);

namespace App\Tests\Unit\Manager;

use App\Entity\Cancha;
use App\Entity\Sede;
use App\Entity\Torneo;
use App\Exception\AppException;
use App\Manager\CanchaManager;
use App\Manager\ValidadorManager;
use App\Repository\CanchaRepository;
use PHPUnit\Framework\TestCase;

class CanchaManagerTest extends TestCase
{
    public function testObtenerCanchaDevuelveLaEntidadSolicitada(): void
    {
        $cancha = (new Cancha())
            ->setNombre('Cancha 1')
            ->setDescripcion('Descripcion 1');
        $this->setEntityId($cancha, 15);

        $canchaRepository = $this->createMock(CanchaRepository::class);
        $canchaRepository->expects($this->once())
            ->method('find')
            ->with(15)
            ->willReturn($cancha);

        $manager = new CanchaManager(
            $canchaRepository,
            $this->createMock(ValidadorManager::class)
        );

        self::assertSame($cancha, $manager->obtenerCancha(15));
    }

    public function testObtenerCanchaLanzaExcepcionSiNoExiste(): void
    {
        $canchaRepository = $this->createMock(CanchaRepository::class);
        $canchaRepository->expects($this->once())
            ->method('find')
            ->with(999)
            ->willReturn(null);

        $manager = new CanchaManager(
            $canchaRepository,
            $this->createMock(ValidadorManager::class)
        );

        $this->expectException(AppException::class);
        $this->expectExceptionMessage('No se encontró la cancha');

        $manager->obtenerCancha(999);
    }

    public function testCrearCanchaPersisteUnaNuevaEntidad(): void
    {
        $sede = $this->crearSede();

        $canchaRepository = $this->createMock(CanchaRepository::class);
        $canchaRepository->expects($this->once())
            ->method('findOneBy')
            ->with(['sede' => $sede, 'nombre' => 'Cancha Nueva'])
            ->willReturn(null);
        $canchaRepository->expects($this->once())
            ->method('guardar')
            ->with($this->callback(function (Cancha $cancha) use ($sede): bool {
                self::assertSame($sede, $cancha->getSede());
                self::assertSame('Cancha Nueva', $cancha->getNombre());
                self::assertSame('Descripcion nueva', $cancha->getDescripcion());

                return true;
            }), true);

        $validadorManager = $this->createMock(ValidadorManager::class);
        $validadorManager->expects($this->once())
            ->method('validarCancha')
            ->with('Cancha Nueva', 'Descripcion nueva');

        $manager = new CanchaManager($canchaRepository, $validadorManager);

        $manager->crearCancha($sede, 'Cancha Nueva', 'Descripcion nueva');
    }

    public function testCrearCanchaLanzaExcepcionSiYaExisteOtraConEseNombre(): void
    {
        $sede = $this->crearSede();

        $canchaRepository = $this->createMock(CanchaRepository::class);
        $canchaRepository->expects($this->once())
            ->method('findOneBy')
            ->with(['sede' => $sede, 'nombre' => 'Cancha Repetida'])
            ->willReturn(new Cancha());
        $canchaRepository->expects($this->never())
            ->method('guardar');

        $validadorManager = $this->createMock(ValidadorManager::class);
        $validadorManager->expects($this->never())
            ->method('validarCancha');

        $manager = new CanchaManager($canchaRepository, $validadorManager);

        $this->expectException(AppException::class);
        $this->expectExceptionMessage('Ya existe una cancha con ese nombre');

        $manager->crearCancha($sede, 'Cancha Repetida', 'Descripcion repetida');
    }

    public function testEditarCanchaActualizaLaEntidadSinConsultarDuplicadosSiNoCambiaElNombre(): void
    {
        $sede = $this->crearSede();
        $cancha = (new Cancha())
            ->setNombre('Cancha Original')
            ->setDescripcion('Descripcion original')
            ->setSede($sede);

        $canchaRepository = $this->createMock(CanchaRepository::class);
        $canchaRepository->expects($this->never())
            ->method('findOneBy');
        $canchaRepository->expects($this->once())
            ->method('guardar')
            ->with($cancha, true);

        $validadorManager = $this->createMock(ValidadorManager::class);
        $validadorManager->expects($this->once())
            ->method('validarCancha')
            ->with('Cancha Original', 'Descripcion editada');

        $manager = new CanchaManager($canchaRepository, $validadorManager);

        $manager->editarCancha($cancha, 'Cancha Original', 'Descripcion editada');

        self::assertSame('Cancha Original', $cancha->getNombre());
        self::assertSame('Descripcion editada', $cancha->getDescripcion());
    }

    public function testEditarCanchaLanzaExcepcionSiElNuevoNombreYaExiste(): void
    {
        $sede = $this->crearSede();
        $cancha = (new Cancha())
            ->setNombre('Cancha Objetivo')
            ->setDescripcion('Descripcion objetivo')
            ->setSede($sede);

        $canchaRepository = $this->createMock(CanchaRepository::class);
        $canchaRepository->expects($this->once())
            ->method('findOneBy')
            ->with(['sede' => $sede, 'nombre' => 'Cancha Duplicada'])
            ->willReturn(new Cancha());
        $canchaRepository->expects($this->never())
            ->method('guardar');

        $validadorManager = $this->createMock(ValidadorManager::class);
        $validadorManager->expects($this->never())
            ->method('validarCancha');

        $manager = new CanchaManager($canchaRepository, $validadorManager);

        $this->expectException(AppException::class);
        $this->expectExceptionMessage('Ya existe una cancha con ese nombre');

        $manager->editarCancha($cancha, 'Cancha Duplicada', 'Descripcion editada');
    }

    public function testEliminarCanchaDelegaEnElRepositorio(): void
    {
        $cancha = (new Cancha())
            ->setNombre('Cancha a borrar')
            ->setDescripcion('Descripcion borrar');

        $canchaRepository = $this->createMock(CanchaRepository::class);
        $canchaRepository->expects($this->once())
            ->method('eliminar')
            ->with($cancha, true);

        $manager = new CanchaManager(
            $canchaRepository,
            $this->createMock(ValidadorManager::class)
        );

        $manager->eliminarCancha($cancha);
    }

    public function testObtenerSedesYCanchasByTorneoDelegaEnElRepositorio(): void
    {
        $canchaRepository = $this->createMock(CanchaRepository::class);
        $canchaRepository->expects($this->once())
            ->method('buscarSedesYCanchasByTorneo')
            ->with('ruta-torneo')
            ->willReturn([
                ['sede' => 'Sede 1', 'id' => 1, 'cancha' => 'Cancha 1'],
            ]);

        $manager = new CanchaManager(
            $canchaRepository,
            $this->createMock(ValidadorManager::class)
        );

        self::assertSame([
            ['sede' => 'Sede 1', 'id' => 1, 'cancha' => 'Cancha 1'],
        ], $manager->obtenerSedesYCanchasByTorneo('ruta-torneo'));
    }

    private function crearSede(): Sede
    {
        $torneo = (new Torneo())
            ->setNombre('Torneo Unitario')
            ->setRuta('torneo-unitario')
            ->setDescripcion('Descripcion torneo')
            ->setFechaInicioInscripcion(new \DateTimeImmutable('2026-01-01 10:00:00'))
            ->setFechaFinInscripcion(new \DateTimeImmutable('2026-01-10 10:00:00'))
            ->setFechaInicioTorneo(new \DateTimeImmutable('2026-02-01 10:00:00'))
            ->setFechaFinTorneo(new \DateTimeImmutable('2026-02-20 10:00:00'))
            ->setEstado('activo');

        return (new Sede())
            ->setNombre('Sede Unitario')
            ->setDomicilio('Calle 123')
            ->setTorneo($torneo);
    }

    private function setEntityId(object $entity, int $id): void
    {
        $reflection = new \ReflectionProperty($entity, 'id');
        $reflection->setAccessible(true);
        $reflection->setValue($entity, $id);
    }
}

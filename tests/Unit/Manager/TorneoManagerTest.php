<?php

namespace App\Tests\Unit\Manager;

use App\Entity\Torneo;
use App\Entity\Usuario;
use App\Exception\AppException;
use App\Manager\TorneoManager;
use App\Manager\ValidadorManager;
use App\Repository\TorneoRepository;
use App\Repository\UsuarioRepository;
use PHPUnit\Framework\TestCase;

class TorneoManagerTest extends TestCase
{
    public function testObtenerTorneos(): void
    {
        $torneoRepository = $this->createMock(TorneoRepository::class);
        $validadorManager = $this->createMock(ValidadorManager::class);
        $torneoManager = new TorneoManager(
            $torneoRepository,
            $validadorManager,
        );

        $this->assertIsArray($torneoManager->obtenerTorneos(1));
        $this->assertEquals([], $torneoManager->obtenerTorneos(1));
    }

    public function testObtenerTorneoNoEncontrado(): void
    {
        $torneoRepository = $this->createMock(TorneoRepository::class);
        $validadorManager = $this->createMock(ValidadorManager::class);
        $torneoManager = new TorneoManager(
            $torneoRepository,
            $validadorManager,
        );

        $this->expectException(AppException::class);
        $this->expectExceptionMessage('Torneo no encontrado');
        $torneoManager->obtenerTorneo('ruta');
    }

    public function testObtenerTorneoOk(): void
    {
        $torneoRepository = $this->createMock(TorneoRepository::class);
        $validadorManager = $this->createMock(ValidadorManager::class);
        $torneoManager = new TorneoManager(
            $torneoRepository,
            $validadorManager,
        );

        $torneo = new Torneo();
        $torneoRepository->method('findOneBy')->willReturn($torneo);

        $this->assertEquals($torneo, $torneoManager->obtenerTorneo('ruta'));
    }

    public function testCrearTorneoNombreExistente(): void
    {
        $torneoRepository = $this->createMock(TorneoRepository::class);
        $validadorManager = $this->createMock(ValidadorManager::class);
        $torneoManager = new TorneoManager(
            $torneoRepository,
            $validadorManager,
        );

        $torneoRepository->method('findOneBy')->with(['nombre' => 'nombre'])->willReturn(new Torneo());

        $this->expectException(AppException::class);
        $this->expectExceptionMessage('El nombre ya se encuentra registrado');
        $torneoManager->crearTorneo(
            'nombre',
            'ruta',
            'descripcion',
            '2021-01-01 00:00', // Inicio Torneo
            '2021-01-01 00:00', // Fin Torneo
            '2021-01-01 00:00', // Inicio Inscripcion
            '2021-01-01 00:00', // Fin Inscripcion
            new Usuario(),
        );
    }

    public function testCrearTorneoRutaExistente(): void
    {
        $torneoRepository = $this->createMock(TorneoRepository::class);
        $validadorManager = $this->createMock(ValidadorManager::class);
        $torneoManager = new TorneoManager(
            $torneoRepository,
            $validadorManager,
        );

        $torneoRepository->expects($this->exactly(2))
            ->method('findOneBy')
            ->willReturnOnConsecutiveCalls(
                null,
                new Torneo(),
            );

        $this->expectException(AppException::class);
        $this->expectExceptionMessage('La ruta ya se encuentra registrada');
        $torneoManager->crearTorneo(
            'nombre',
            'ruta',
            'descripcion',
            '2021-01-01 00:00', // Inicio Torneo
            '2021-01-01 00:00', // Fin Torneo
            '2021-01-01 00:00', // Inicio Inscripcion
            '2021-01-01 00:00', // Fin Inscripcion
            new Usuario(),
        );
    }

    public function testCreartorneoNombreErrorCorto(): void
    {
        $torneoRepository = $this->createMock(TorneoRepository::class);
        $validadorManager = $this->createMock(ValidadorManager::class);
        $torneoManager = new TorneoManager(
            $torneoRepository,
            $validadorManager,
        );

        $validadorManager->method('validarTorneo')->with(
            'n',
            'ruta',
            'descripcion',
            '2021-01-01 00:00', // Inicio Torneo
            '2021-01-01 00:00', // Fin Torneo
            '2021-01-01 00:00', // Inicio Inscripcion
            '2021-01-01 00:00', // Fin Inscripcion
            new Usuario(),
        )->willThrowException(new AppException('El Nombre debe tener entre 3 y 128 caracteres'));
        $this->expectException(AppException::class);
        $this->expectExceptionMessage('El Nombre debe tener entre 3 y 128 caracteres');
        $torneoManager->crearTorneo(
            'n',
            'ruta',
            'descripcion',
            '2021-01-01 00:00', // Inicio Torneo
            '2021-01-01 00:00', // Fin Torneo
            '2021-01-01 00:00', // Inicio Inscripcion
            '2021-01-01 00:00', // Fin Inscripcion
            new Usuario(),
        );
    }

    public function testCreartorneoNombreErrorLargo(): void
    {
        $torneoRepository = $this->createMock(TorneoRepository::class);
        $validadorManager = $this->createMock(ValidadorManager::class);
        $torneoManager = new TorneoManager(
            $torneoRepository,
            $validadorManager,
        );

        $validadorManager->method('validarTorneo')->with(
            'nombre',
            'ruta',
            'descripcion',
            '2021-01-01 00:00', // Inicio Torneo
            '2021-01-01 00:00', // Fin Torneo
            '2021-01-01 00:00', // Inicio Inscripcion
            '2021-01-01 00:00', // Fin Inscripcion
            new Usuario(),
        )->willThrowException(new AppException('El Nombre debe tener entre 3 y 128 caracteres'));
        $this->expectException(AppException::class);
        $this->expectExceptionMessage('El Nombre debe tener entre 3 y 128 caracteres');
        $torneoManager->crearTorneo(
            'nombre',
            'ruta',
            'descripcion',
            '2021-01-01 00:00', // Inicio Torneo
            '2021-01-01 00:00', // Fin Torneo
            '2021-01-01 00:00', // Inicio Inscripcion
            '2021-01-01 00:00', // Fin Inscripcion
            new Usuario(),
        );
    }

    public function testCreartorneoNombreCortoError(): void
    {
        $torneoRepository = $this->createMock(TorneoRepository::class);
        $validadorManager = $this->createMock(ValidadorManager::class);
        $torneoManager = new TorneoManager(
            $torneoRepository,
            $validadorManager,
        );

        $validadorManager->method('validarTorneo')->with(
            'nombre',
            'ru',
            'descripcion',
            '2021-01-01 00:00', // Inicio Torneo
            '2021-01-01 00:00', // Fin Torneo
            '2021-01-01 00:00', // Inicio Inscripcion
            '2021-01-01 00:00', // Fin Inscripcion
            new Usuario(),
        )->willThrowException(new AppException('El Nombre Corto debe tener entre 3 y 32 caracteres'));
        $this->expectException(AppException::class);
        $this->expectExceptionMessage('El Nombre Corto debe tener entre 3 y 32 caracteres');
        $torneoManager->crearTorneo(
            'nombre',
            'ru',
            'descripcion',
            '2021-01-01 00:00', // Inicio Torneo
            '2021-01-01 00:00', // Fin Torneo
            '2021-01-01 00:00', // Inicio Inscripcion
            '2021-01-01 00:00', // Fin Inscripcion
            new Usuario(),
        );
    }

    public function testCrearTorneoOK(): void
    {
        $torneoRepository = $this->createMock(TorneoRepository::class);
        $validadorManager = $this->createMock(ValidadorManager::class);
        $torneoManager = new TorneoManager(
            $torneoRepository,
            $validadorManager,
        );

        $user = new Usuario();
        $torneo = $torneoManager->crearTorneo(
            'nombre',
            'ruta',
            'descripcion',
            '2021-01-01 00:00', // Inicio Torneo
            '2021-01-01 00:00', // Fin Torneo
            '2021-01-01 00:00', // Inicio Inscripcion
            '2021-01-01 00:00', // Fin Inscripcion
            $user,
        );

        $this->assertInstanceOf(Torneo::class, $torneo);
    }
}

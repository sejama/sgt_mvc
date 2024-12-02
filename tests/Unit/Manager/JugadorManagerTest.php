<?php

namespace App\Tests\Unit\Manager;

use App\Entity\Equipo;
use App\Entity\Jugador;
use App\Manager\JugadorManager;
use App\Manager\ValidadorManager;
use App\Repository\JugadorRepository;
use PHPUnit\Framework\TestCase;

class JugadorManagerTest extends TestCase
{
    public function testObtenerJugadores(): void
    {
        $jugadorRepository = $this->createMock(JugadorRepository::class);
        $validadorManager = $this->createMock(ValidadorManager::class);

        $jugadorManager = new JugadorManager($jugadorRepository, $validadorManager);

        $this->assertIsArray($jugadorManager->obtenerJugadores());
        $this->assertEquals([], $jugadorManager->obtenerJugadores());
    }

    public function testObtenerJugador(): void
    {
        $jugadorRepository = $this->createMock(JugadorRepository::class);
        $validadorManager = $this->createMock(ValidadorManager::class);

        $jugadorManager = new JugadorManager($jugadorRepository, $validadorManager);

        $jugadorRepository->expects($this->once())
            ->method('find')
            ->willReturn($this->createMock(Jugador::class));

        $this->assertInstanceOf(Jugador::class, $jugadorManager->obtenerJugador(1));
    }

    public function testObtenerJugadorNoEncontrado(): void
    {
        $jugadorRepository = $this->createMock(JugadorRepository::class);
        $validadorManager = $this->createMock(ValidadorManager::class);

        $jugadorManager = new JugadorManager($jugadorRepository, $validadorManager);

        $jugadorRepository->expects($this->once())
            ->method('find')
            ->willReturn(null);

        $this->expectExceptionMessage('No se encontró el jugador');
        $jugadorManager->obtenerJugador(1);
    }

    public function testObtenerJugadoresPorEquipo(): void
    {
        $jugadorRepository = $this->createMock(JugadorRepository::class);
        $validadorManager = $this->createMock(ValidadorManager::class);

        $jugadorManager = new JugadorManager($jugadorRepository, $validadorManager);

        $jugadorRepository->expects($this->once())
            ->method('findBy')
            ->willReturn([$this->createMock(Jugador::class)]);

        $this->assertIsArray($jugadorManager->obtenerJugadoresPorEquipo($this->createMock(Equipo::class)));
    }

    public function testCrearJugador(): void
    {
        $jugadorRepository = $this->createMock(JugadorRepository::class);
        $validadorManager = $this->createMock(ValidadorManager::class);

        $jugadorManager = new JugadorManager($jugadorRepository, $validadorManager);

        $equipo = new Equipo();

        $jugadorRepository->expects($this->once())
            ->method('findOneBy')
            ->willReturn(null);

        $validadorManager->expects($this->once())
            ->method('validarJugador');

        $jugadorManager->crearJugador(
            $equipo,
            'nombre',
            'apellido',
            'tipoDocumento',
            'numeroDocumento',
            '2000-01-01',
            'tipo',
            true,
            'email',
            'celular'
        );
    }

    public function testCrearJugadorExistente(): void
    {
        $jugadorRepository = $this->createMock(JugadorRepository::class);
        $validadorManager = $this->createMock(ValidadorManager::class);

        $jugadorManager = new JugadorManager($jugadorRepository, $validadorManager);

        $equipo = new Equipo();

        $jugadorRepository->expects($this->once())
            ->method('findOneBy')
            ->willReturn($this->createMock(Jugador::class));

        $this->expectExceptionMessage('Ya existe un jugador con ese DNI');
        $jugadorManager->crearJugador(
            $equipo,
            'nombre',
            'apellido',
            'tipoDocumento',
            'numeroDocumento',
            '2000-01-01',
            'tipo',
            true,
            'email',
            'celular'
        );
    }

    public function testEditarJugador(): void
    {
        $jugadorRepository = $this->createMock(JugadorRepository::class);
        $validadorManager = $this->createMock(ValidadorManager::class);

        $jugadorManager = new JugadorManager($jugadorRepository, $validadorManager);

        $jugador = new Jugador();

        $jugadorRepository->expects($this->once())
            ->method('findOneBy')
            ->willReturn(null);

        $validadorManager->expects($this->once())
            ->method('validarJugador');

        $jugadorManager->editarJugador(
            $jugador,
            'nombre',
            'apellido',
            'tipoDocumento',
            'numeroDocumento',
            '2000-01-01',
            'tipo',
            true,
            'email',
            'celular'
        );
    }

    public function testEditarJugadorExistente(): void
    {
        $jugadorRepository = $this->createMock(JugadorRepository::class);
        $validadorManager = $this->createMock(ValidadorManager::class);

        $jugadorManager = new JugadorManager($jugadorRepository, $validadorManager);

        $jugador = new Jugador();

        $jugadorRepository->expects($this->once())
            ->method('findOneBy')
            ->willReturn($this->createMock(Jugador::class));

        $this->expectExceptionMessage('Ya existe un jugador con ese DNI');
        $jugadorManager->editarJugador(
            $jugador,
            'nombre',
            'apellido',
            'tipoDocumento',
            'numeroDocumento',
            '2000-01-01',
            'tipo',
            true,
            'email',
            'celular'
        );
    }

    public function testEliminarJugador(): void
    {
        $jugadorRepository = $this->createMock(JugadorRepository::class);
        $validadorManager = $this->createMock(ValidadorManager::class);

        $jugadorManager = new JugadorManager($jugadorRepository, $validadorManager);

        $jugador = new Jugador();

        $jugadorRepository->expects($this->once())
            ->method('eliminar');

        $jugadorManager->eliminarJugador($jugador);
    }


}

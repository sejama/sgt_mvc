<?php

declare(strict_types=1);

namespace App\Tests\Unit\Manager;

use App\Entity\Cancha;
use App\Entity\Categoria;
use App\Entity\Equipo;
use App\Entity\Grupo;
use App\Entity\Partido;
use App\Entity\PartidoConfig;
use App\Entity\Sede;
use App\Entity\Torneo;
use App\Entity\Usuario;
use App\Enum\EstadoEquipo;
use App\Enum\EstadoPartido;
use App\Enum\Genero;
use App\Exception\AppException;
use App\Manager\CanchaManager;
use App\Manager\GrupoManager;
use App\Manager\PartidoManager;
use App\Manager\ValidadorPartidoManager;
use App\Repository\EquipoRepository;
use App\Repository\PartidoConfigRepository;
use App\Repository\PartidoRepository;
use PHPUnit\Framework\TestCase;

class PartidoManagerTest extends TestCase
{
    public function testObtenerWrappersBasicosDeleganEnRepositoriosYManagers(): void
    {
        $partidoRepository = $this->createMock(PartidoRepository::class);
        $canchaManager = $this->createMock(CanchaManager::class);

        $partido = new Partido();

        $partidoRepository->expects($this->exactly(2))
            ->method('findBy')
            ->withConsecutive(
                [['grupo' => 10]],
                [['cancha' => 99]]
            )
            ->willReturnOnConsecutiveCalls([$partido], [$partido]);

        $partidoRepository->expects($this->once())
            ->method('find')
            ->with(11)
            ->willReturn($partido);

        $partidoRepository->expects($this->once())
            ->method('obternerPartidoxRutaNumero')
            ->with('ruta-test', 12)
            ->willReturn($partido);

        $partidoRepository->expects($this->once())
            ->method('buscarPartidosXTorneo')
            ->with('ruta-test')
            ->willReturn([$partido]);

        $partidoRepository->expects($this->once())
            ->method('obtenerPartidosXCategoriaClasificatorio')
            ->with(13)
            ->willReturn([['id' => 1]]);

        $canchaManager->expects($this->once())
            ->method('obtenerSedesYCanchasByTorneo')
            ->with('ruta-test')
            ->willReturn(['Sede A' => ['Cancha 1']]);

        $manager = new PartidoManager(
            $canchaManager,
            $this->createMock(GrupoManager::class),
            $this->createMock(EquipoRepository::class),
            $partidoRepository,
            $this->createMock(PartidoConfigRepository::class),
            $this->createMock(ValidadorPartidoManager::class)
        );

        $categoria = new Categoria();
        $this->setEntityId($categoria, 13);

        $this->assertSame([$partido], $manager->obtenerPartidosXGrupo(10));
        $this->assertSame($partido, $manager->obtenerPartidoxId(11));
        $this->assertSame($partido, $manager->obtenerPartido('ruta-test', 12));
        $this->assertSame([$partido], $manager->obtenerPartidosXTorneo('ruta-test'));
        $this->assertSame([['id' => 1]], $manager->obtenerPartidosXCategoriaClasificatorio($categoria));
        $this->assertSame([$partido], $manager->obtenerPartidoXCancha(99));
        $this->assertSame(['Sede A' => ['Cancha 1']], $manager->obtenerSedesyCanchasXTorneo('ruta-test'));
    }

    public function testObtenerPartidosSinAsignarXTorneoAgrupaPorTipo(): void
    {
        $partidoRepository = $this->createMock(PartidoRepository::class);

        $partidoRepository->expects($this->once())
            ->method('buscarPartidosSinAsignarXTorneo')
            ->with('ruta-test')
            ->willReturn([['id' => 1]]);

        $partidoRepository->expects($this->once())
            ->method('buscarPartidosPlayOffGrupoXTorneo')
            ->with('ruta-test')
            ->willReturn([['id' => 2]]);

        $partidoRepository->expects($this->once())
            ->method('buscarPartidosPlayOffFinalesXTorneo')
            ->with('ruta-test')
            ->willReturn([['id' => 3]]);

        $manager = new PartidoManager(
            $this->createMock(CanchaManager::class),
            $this->createMock(GrupoManager::class),
            $this->createMock(EquipoRepository::class),
            $partidoRepository,
            $this->createMock(PartidoConfigRepository::class),
            $this->createMock(ValidadorPartidoManager::class)
        );

        $resultado = $manager->obtenerPartidosSinAsignarXTorneo('ruta-test');

        $this->assertSame([['id' => 1]], $resultado['clasificatorios']);
        $this->assertSame([['id' => 2]], $resultado['eliminatorias']);
        $this->assertSame([['id' => 3]], $resultado['finales']);
    }

    public function testObtenerPartidosProgramadosXTorneoOrdenaPorHora(): void
    {
        $partidoRepository = $this->createMock(PartidoRepository::class);

        $partidoRepository->method('buscarPartidosProgramadosClasificatorioXTorneo')
            ->with('ruta-test')
            ->willReturn([
                [
                    'id' => 1,
                    'sede' => 'Sede A',
                    'cancha' => 'Cancha 1',
                    'horario' => new \DateTimeImmutable('2026-03-25 12:00:00'),
                ],
                [
                    'id' => 2,
                    'sede' => 'Sede A',
                    'cancha' => 'Cancha 1',
                    'horario' => new \DateTimeImmutable('2026-03-25 10:00:00'),
                ],
            ]);

        $partidoRepository->method('buscarPartidosProgramadosPlayOffXTorneo')
            ->with('ruta-test')
            ->willReturn([
                [
                    'id' => 3,
                    'sede' => 'Sede A',
                    'cancha' => 'Cancha 2',
                    'horario' => new \DateTimeImmutable('2026-03-25 11:00:00'),
                ],
            ]);

        $partidoRepository->method('buscarPartidosProgramadosPlayOffFinalesXTorneo')
            ->with('ruta-test')
            ->willReturn([
                [
                    'id' => 4,
                    'sede' => 'Sede B',
                    'cancha' => 'Cancha Final',
                    'horario' => new \DateTimeImmutable('2026-03-26 18:00:00'),
                ],
            ]);

        $manager = new PartidoManager(
            $this->createMock(CanchaManager::class),
            $this->createMock(GrupoManager::class),
            $this->createMock(EquipoRepository::class),
            $partidoRepository,
            $this->createMock(PartidoConfigRepository::class),
            $this->createMock(ValidadorPartidoManager::class)
        );

        $resultado = $manager->obtenerPartidosProgramadosXTorneo('ruta-test');

        $this->assertCount(2, $resultado['Sede A']);
        $this->assertSame('10:00', $resultado['Sede A']['Cancha 1']['2026-03-25'][0]['hora']);
        $this->assertSame('12:00', $resultado['Sede A']['Cancha 1']['2026-03-25'][1]['hora']);
        $this->assertSame('18:00', $resultado['Sede B']['Cancha Final']['2026-03-26'][0]['hora']);
    }

    public function testObtenerPartidosProgramadosXTorneoIgnoraRegistrosIncompletos(): void
    {
        $partidoRepository = $this->createMock(PartidoRepository::class);

        $partidoRepository->method('buscarPartidosProgramadosClasificatorioXTorneo')
            ->with('ruta-test')
            ->willReturn([
                [
                    'id' => 1,
                    'sede' => 'Sede A',
                    'cancha' => 'Cancha 1',
                    'horario' => new \DateTimeImmutable('2026-03-25 12:00:00'),
                ],
                [
                    'id' => 2,
                    'sede' => 'Sede A',
                    'horario' => new \DateTimeImmutable('2026-03-25 13:00:00'),
                ],
            ]);

        $partidoRepository->method('buscarPartidosProgramadosPlayOffXTorneo')
            ->with('ruta-test')
            ->willReturn([]);

        $partidoRepository->method('buscarPartidosProgramadosPlayOffFinalesXTorneo')
            ->with('ruta-test')
            ->willReturn([]);

        $manager = new PartidoManager(
            $this->createMock(CanchaManager::class),
            $this->createMock(GrupoManager::class),
            $this->createMock(EquipoRepository::class),
            $partidoRepository,
            $this->createMock(PartidoConfigRepository::class),
            $this->createMock(ValidadorPartidoManager::class)
        );

        $resultado = $manager->obtenerPartidosProgramadosXTorneo('ruta-test');

        $this->assertCount(1, $resultado['Sede A']['Cancha 1']['2026-03-25']);
        $this->assertSame(1, $resultado['Sede A']['Cancha 1']['2026-03-25'][0]['id']);
    }

    public function testObtenerPartidosEliminatoriaPostClasificatorioSeparaPorNombre(): void
    {
        $partidoRepository = $this->createMock(PartidoRepository::class);

        $categoria = new Categoria();
        $this->setEntityId($categoria, 99);

        $partidoRepository->expects($this->once())
            ->method('obtenerPartidosXCategoriaEliminatoriaPostClasificatorio')
            ->with(99)
            ->willReturn([
                ['nombre' => 'Semifinal Oro'],
                ['nombre' => 'Semifinal Plata'],
                ['nombre' => 'Semifinal Bronce'],
                ['nombre' => 'Reclasificacion General'],
            ]);

        $manager = new PartidoManager(
            $this->createMock(CanchaManager::class),
            $this->createMock(GrupoManager::class),
            $this->createMock(EquipoRepository::class),
            $partidoRepository,
            $this->createMock(PartidoConfigRepository::class),
            $this->createMock(ValidadorPartidoManager::class)
        );

        $resultado = $manager->obtenerPartidosXCategoriaEliminatoriaPostClasificatorio($categoria);

        $this->assertCount(1, $resultado['oro']);
        $this->assertCount(1, $resultado['plata']);
        $this->assertCount(1, $resultado['bronce']);
        $this->assertCount(1, $resultado['general']);
    }

    public function testEditarPartidoLanzaErrorSiCanchaHorarioYaOcupados(): void
    {
        $partidoRepository = $this->createMock(PartidoRepository::class);

        $partidoRepository->expects($this->once())
            ->method('buscarPartidoXCanchaHorario')
            ->willReturn(new Partido());

        $manager = new PartidoManager(
            $this->createMock(CanchaManager::class),
            $this->createMock(GrupoManager::class),
            $this->createMock(EquipoRepository::class),
            $partidoRepository,
            $this->createMock(PartidoConfigRepository::class),
            $this->createMock(ValidadorPartidoManager::class)
        );

        $this->expectException(AppException::class);
        $this->expectExceptionMessage('Ya existe un partido programado en esa cancha y horario');

        $manager->editarPartido('ruta-test', 1, 2, '2026-05-01 10:30');
    }

    public function testEditarPartidoProgramaPartidoYActivaEquiposBorrador(): void
    {
        $partidoRepository = $this->createMock(PartidoRepository::class);
        $equipoRepository = $this->createMock(EquipoRepository::class);
        $canchaManager = $this->createMock(CanchaManager::class);

        $torneo = (new Torneo())
            ->setNombre('Torneo')
            ->setRuta('ruta-test')
            ->setFechaInicioInscripcion(new \DateTimeImmutable('2026-01-01 00:00:00'))
            ->setFechaFinInscripcion(new \DateTimeImmutable('2026-01-02 00:00:00'))
            ->setFechaInicioTorneo(new \DateTimeImmutable('2026-01-03 00:00:00'))
            ->setFechaFinTorneo(new \DateTimeImmutable('2026-01-04 00:00:00'))
            ->setCreador((new Usuario())
                ->setNombre('Creador')
                ->setApellido('Test')
                ->setEmail('creador@example.com')
                ->setUsername('creador')
                ->setRoles(['ROLE_USER']))
            ->setEstado('Activo');

        $categoria = (new Categoria())
            ->setNombre('Categoria')
            ->setNombreCorto('CAT')
            ->setGenero(Genero::MASCULINO)
            ->setEstado('borrador')
            ->setTorneo($torneo);

        $sede = (new Sede())
            ->setNombre('Sede')
            ->setDomicilio('Domicilio')
            ->setTorneo($torneo);

        $cancha = (new Cancha())
            ->setNombre('Cancha')
            ->setDescripcion('Descripcion')
            ->setSede($sede);

        $equipoLocal = (new Equipo())
            ->setNombre('Equipo Local')
            ->setNombreCorto('EL')
            ->setPais('Argentina')
            ->setProvincia('Mendoza')
            ->setLocalidad('Capital')
            ->setCategoria($categoria)
            ->setEstado(EstadoEquipo::BORRADOR->value);

        $equipoVisitante = (new Equipo())
            ->setNombre('Equipo Visitante')
            ->setNombreCorto('EV')
            ->setPais('Argentina')
            ->setProvincia('Cordoba')
            ->setLocalidad('Centro')
            ->setCategoria($categoria)
            ->setEstado(EstadoEquipo::BORRADOR->value);

        $partido = (new Partido())
            ->setCategoria($categoria)
            ->setEquipoLocal($equipoLocal)
            ->setEquipoVisitante($equipoVisitante)
            ->setEstado('Borrador')
            ->setTipo('Clasificatorio')
            ->setNumero(7);

        $partidoRepository->expects($this->once())
            ->method('buscarPartidoXCanchaHorario')
            ->willReturn(null);

        $partidoRepository->expects($this->once())
            ->method('find')
            ->with(7)
            ->willReturn($partido);

        $canchaManager->expects($this->once())
            ->method('obtenerCancha')
            ->with(5)
            ->willReturn($cancha);

        $partidoRepository->expects($this->once())
            ->method('guardar')
            ->with($partido);

        $equipoRepository->expects($this->exactly(2))
            ->method('guardar');

        $manager = new PartidoManager(
            $canchaManager,
            $this->createMock(GrupoManager::class),
            $equipoRepository,
            $partidoRepository,
            $this->createMock(PartidoConfigRepository::class),
            $this->createMock(ValidadorPartidoManager::class)
        );

        $manager->editarPartido('ruta-test', 7, 5, '2026-05-01 10:30');

        $this->assertSame('Programado', $partido->getEstado());
        $this->assertSame(EstadoEquipo::ACTIVO->value, $equipoLocal->getEstado());
        $this->assertSame(EstadoEquipo::ACTIVO->value, $equipoVisitante->getEstado());
        $this->assertSame('2026-05-01 10:00', $partido->getHorario()?->format('Y-m-d H:i'));
        $this->assertSame($cancha, $partido->getCancha());
    }

    public function testCrearPartidosXGrupoCreaCrucesYActivaEquiposBorrador(): void
    {
        $partidoRepository = $this->createMock(PartidoRepository::class);
        $equipoRepository = $this->createMock(EquipoRepository::class);

        $torneo = (new Torneo())->setRuta('ruta-test');
        $categoria = (new Categoria())->setTorneo($torneo);
        $grupo = (new Grupo())
            ->setNombre('Grupo A')
            ->setCategoria($categoria);

        $equipoA = (new Equipo())
            ->setNombre('Equipo A')
            ->setNombreCorto('EA')
            ->setEstado(EstadoEquipo::BORRADOR->value)
            ->setCategoria($categoria)
            ->setNumero(1);

        $equipoB = (new Equipo())
            ->setNombre('Equipo B')
            ->setNombreCorto('EB')
            ->setEstado(EstadoEquipo::ACTIVO->value)
            ->setCategoria($categoria)
            ->setNumero(2);

        $grupo->addEquipo($equipoA);
        $grupo->addEquipo($equipoB);

        $partidoRepository->expects($this->once())
            ->method('buscarPartidosXTorneo')
            ->with('ruta-test')
            ->willReturn([]);

        $partidoRepository->expects($this->once())
            ->method('guardar')
            ->with($this->isInstanceOf(Partido::class));

        $equipoRepository->expects($this->exactly(3))
            ->method('guardar');

        $manager = new PartidoManager(
            $this->createMock(CanchaManager::class),
            $this->createMock(GrupoManager::class),
            $equipoRepository,
            $partidoRepository,
            $this->createMock(PartidoConfigRepository::class),
            $this->createMock(ValidadorPartidoManager::class)
        );

        $manager->crearPartidosXGrupo($grupo);

        $this->assertSame(EstadoEquipo::ACTIVO->value, $equipoA->getEstado());
        $this->assertSame(EstadoEquipo::ACTIVO->value, $equipoB->getEstado());
    }

    public function testCargarResultadoActualizaPartidoYAvanzaLlave(): void
    {
        $partidoRepository = $this->createMock(PartidoRepository::class);
        $partidoConfigRepository = $this->createMock(PartidoConfigRepository::class);

        $manager = new PartidoManager(
            $this->createMock(CanchaManager::class),
            $this->createMock(GrupoManager::class),
            $this->createMock(EquipoRepository::class),
            $partidoRepository,
            $partidoConfigRepository,
            $this->createMock(ValidadorPartidoManager::class)
        );

        $equipoLocal = (new Equipo())->setNombre('Local')->setNombreCorto('LOC');
        $equipoVisitante = (new Equipo())->setNombre('Visitante')->setNombreCorto('VIS');

        $partido = (new Partido())
            ->setEquipoLocal($equipoLocal)
            ->setEquipoVisitante($equipoVisitante);

        $partidoSiguiente = new Partido();

        $config = (new PartidoConfig())
            ->setPartido($partidoSiguiente)
            ->setGanadorPartido1($partido)
            ->setPerdedorPartido2($partido);

        $partidoConfigRepository->expects($this->once())
            ->method('obtenerPartidoConfigXGanadorPartido')
            ->with($partido)
            ->willReturn($config);

        $partidoRepository->expects($this->exactly(2))
            ->method('guardar');

        $manager->cargarResultado($partido, ['25', '20', '15'], ['10', '25', '12']);

        $this->assertSame(EstadoPartido::FINALIZADO->value, $partido->getEstado());
        $this->assertSame($equipoLocal, $partidoSiguiente->getEquipoLocal());
        $this->assertSame($equipoVisitante, $partidoSiguiente->getEquipoVisitante());
    }

    public function testCargarResultadoSinPartidoSiguienteSoloGuardaPartidoActual(): void
    {
        $partidoRepository = $this->createMock(PartidoRepository::class);
        $partidoConfigRepository = $this->createMock(PartidoConfigRepository::class);

        $manager = new PartidoManager(
            $this->createMock(CanchaManager::class),
            $this->createMock(GrupoManager::class),
            $this->createMock(EquipoRepository::class),
            $partidoRepository,
            $partidoConfigRepository,
            $this->createMock(ValidadorPartidoManager::class)
        );

        $equipoLocal = (new Equipo())->setNombre('Local')->setNombreCorto('LOC');
        $equipoVisitante = (new Equipo())->setNombre('Visitante')->setNombreCorto('VIS');

        $partido = (new Partido())
            ->setEquipoLocal($equipoLocal)
            ->setEquipoVisitante($equipoVisitante);

        $partidoConfigRepository->expects($this->once())
            ->method('obtenerPartidoConfigXGanadorPartido')
            ->with($partido)
            ->willReturn(null);

        $partidoRepository->expects($this->once())
            ->method('guardar')
            ->with($partido);

        $manager->cargarResultado($partido, ['18', '20', null], ['25', '25', null]);

        $this->assertSame(EstadoPartido::FINALIZADO->value, $partido->getEstado());
    }

    public function testCargarResultadoAvanzaLlaveCuandoConfigReferenciaGanador2YPerdedor1(): void
    {
        $partidoRepository = $this->createMock(PartidoRepository::class);
        $partidoConfigRepository = $this->createMock(PartidoConfigRepository::class);

        $manager = new PartidoManager(
            $this->createMock(CanchaManager::class),
            $this->createMock(GrupoManager::class),
            $this->createMock(EquipoRepository::class),
            $partidoRepository,
            $partidoConfigRepository,
            $this->createMock(ValidadorPartidoManager::class)
        );

        $equipoLocal = (new Equipo())->setNombre('Local')->setNombreCorto('LOC');
        $equipoVisitante = (new Equipo())->setNombre('Visitante')->setNombreCorto('VIS');

        $partido = (new Partido())
            ->setEquipoLocal($equipoLocal)
            ->setEquipoVisitante($equipoVisitante);

        $partidoSiguiente = new Partido();

        $config = (new PartidoConfig())
            ->setPartido($partidoSiguiente)
            ->setGanadorPartido2($partido)
            ->setPerdedorPartido1($partido);

        $partidoConfigRepository->expects($this->once())
            ->method('obtenerPartidoConfigXGanadorPartido')
            ->with($partido)
            ->willReturn($config);

        $partidoRepository->expects($this->exactly(2))
            ->method('guardar');

        $manager->cargarResultado($partido, ['15', '17', null], ['25', '25', null]);

        $this->assertSame($equipoVisitante, $partidoSiguiente->getEquipoVisitante());
        $this->assertSame($equipoLocal, $partidoSiguiente->getEquipoLocal());
    }

    private function setEntityId(object $entity, int $id): void
    {
        $reflection = new \ReflectionClass($entity);
        $property = $reflection->getProperty('id');
        $property->setAccessible(true);
        $property->setValue($entity, $id);
    }
}

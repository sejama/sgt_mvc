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
use App\Enum\TipoPartido;
use App\Exception\AppException;
use App\Manager\CanchaManager;
use App\Manager\GrupoManager;
use App\Manager\PartidoManager;
use App\Manager\ValidadorPartidoManager;
use App\Repository\CategoriaRepository;
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
            $this->createMock(CategoriaRepository::class),
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
            $this->createMock(CategoriaRepository::class),
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
            $this->createMock(CategoriaRepository::class),
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
            $this->createMock(CategoriaRepository::class),
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
            $this->createMock(CategoriaRepository::class),
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
            $this->createMock(CategoriaRepository::class),
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
            $this->createMock(CategoriaRepository::class),
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
            ->with($this->isInstanceOf(Partido::class), false);

        $partidoRepository->expects($this->once())
            ->method('flush');

        $equipoRepository->expects($this->once())
            ->method('guardar')
            ->with($equipoA, false);

        $manager = new PartidoManager(
            $this->createMock(CanchaManager::class),
            $this->createMock(GrupoManager::class),
            $this->createMock(CategoriaRepository::class),
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
            $this->createMock(CategoriaRepository::class),
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
            $this->createMock(CategoriaRepository::class),
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
            $this->createMock(CategoriaRepository::class),
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

    public function testCrearPartidoManualConConfiguracionPorGrupos(): void
    {
        $partidoRepository = $this->createMock(PartidoRepository::class);
        $categoriaRepository = $this->createMock(CategoriaRepository::class);
        $grupoManager = $this->createMock(GrupoManager::class);
        $equipoRepository = $this->createMock(EquipoRepository::class);
        $partidoConfigRepository = $this->createMock(PartidoConfigRepository::class);

        $torneo = (new Torneo())->setRuta('ruta-test');
        $categoria = (new Categoria())
            ->setNombre('Cat')
            ->setNombreCorto('CAT')
            ->setGenero(Genero::MASCULINO)
            ->setEstado('Activa')
            ->setTorneo($torneo);
        $this->setEntityId($categoria, 10);

        $grupo1 = (new Grupo())->setNombre('A')->setCategoria($categoria);
        $grupo2 = (new Grupo())->setNombre('B')->setCategoria($categoria);
        $this->setEntityId($grupo1, 21);
        $this->setEntityId($grupo2, 22);

        $equipoLocal = (new Equipo())->setNombre('Local')->setNombreCorto('LOC')->setCategoria($categoria);
        $equipoVisitante = (new Equipo())->setNombre('Visita')->setNombreCorto('VIS')->setCategoria($categoria);
        $this->setEntityId($equipoLocal, 31);
        $this->setEntityId($equipoVisitante, 32);

        $categoriaRepository->expects($this->once())
            ->method('find')
            ->with(10)
            ->willReturn($categoria);

        $equipoRepository->expects($this->exactly(2))
            ->method('find')
            ->withConsecutive([31], [32])
            ->willReturnOnConsecutiveCalls($equipoLocal, $equipoVisitante);

        $grupoManager->expects($this->exactly(2))
            ->method('obtenerGrupo')
            ->withConsecutive([21], [22])
            ->willReturnOnConsecutiveCalls($grupo1, $grupo2);

        $partidoRepository->expects($this->once())
            ->method('buscarPartidosXTorneo')
            ->with('ruta-test')
            ->willReturn([]);

        $partidoRepository->expects($this->once())
            ->method('guardar')
            ->with($this->isInstanceOf(Partido::class));

        $partidoConfigRepository->expects($this->once())
            ->method('guardar')
            ->with($this->callback(function (PartidoConfig $config) use ($grupo1, $grupo2): bool {
                return $config->getNombre() === 'Semi Final Oro'
                    && $config->getGrupoEquipo1() === $grupo1
                    && $config->getGrupoEquipo2() === $grupo2
                    && $config->getPosicionEquipo1() === 1
                    && $config->getPosicionEquipo2() === 2;
            }));

        $manager = new PartidoManager(
            $this->createMock(CanchaManager::class),
            $grupoManager,
            $categoriaRepository,
            $equipoRepository,
            $partidoRepository,
            $partidoConfigRepository,
            $this->createMock(ValidadorPartidoManager::class)
        );

        $partido = $manager->crearPartidoManual('ruta-test', [
            'crear_categoriaId' => '10',
            'crear_tipo' => TipoPartido::ELIMINATORIO->value,
            'crear_equipoLocalId' => '31',
            'crear_equipoVisitanteId' => '32',
            'crear_usarConfig' => '1',
            'crear_config_nombre' => 'Semi Final Oro',
            'crear_config_origen' => 'grupos',
            'crear_config_grupoEquipo1Id' => '21',
            'crear_config_posicionEquipo1' => '1',
            'crear_config_grupoEquipo2Id' => '22',
            'crear_config_posicionEquipo2' => '2',
        ]);

        $this->assertSame(1, $partido->getNumero());
        $this->assertSame(TipoPartido::ELIMINATORIO->value, $partido->getTipo());
        $this->assertSame($categoria, $partido->getCategoria());
        $this->assertSame($equipoLocal, $partido->getEquipoLocal());
        $this->assertSame($equipoVisitante, $partido->getEquipoVisitante());
    }

    public function testEditarPartidoManualConConfiguracionPorGanadores(): void
    {
        $partidoRepository = $this->createMock(PartidoRepository::class);
        $categoriaRepository = $this->createMock(CategoriaRepository::class);
        $equipoRepository = $this->createMock(EquipoRepository::class);
        $partidoConfigRepository = $this->createMock(PartidoConfigRepository::class);

        $torneo = (new Torneo())->setRuta('ruta-test');
        $categoria = (new Categoria())
            ->setNombre('Cat')
            ->setNombreCorto('CAT')
            ->setGenero(Genero::FEMENINO)
            ->setEstado('Activa')
            ->setTorneo($torneo);
        $this->setEntityId($categoria, 77);

        $equipoLocal = (new Equipo())->setNombre('Local')->setNombreCorto('LOC')->setCategoria($categoria);
        $equipoVisitante = (new Equipo())->setNombre('Visita')->setNombreCorto('VIS')->setCategoria($categoria);
        $this->setEntityId($equipoLocal, 101);
        $this->setEntityId($equipoVisitante, 102);

        $partido = (new Partido())
            ->setCategoria($categoria)
            ->setTipo(TipoPartido::CLASIFICATORIO->value)
            ->setEstado(EstadoPartido::BORRADOR->value)
            ->setNumero(9);
        $this->setEntityId($partido, 88);

        $ganador1 = (new Partido())->setNumero(3);
        $ganador2 = (new Partido())->setNumero(4);
        $this->setEntityId($ganador1, 301);
        $this->setEntityId($ganador2, 302);

        $partidoRepository->expects($this->exactly(3))
            ->method('find')
            ->withConsecutive([88], [301], [302])
            ->willReturnOnConsecutiveCalls($partido, $ganador1, $ganador2);

        $categoriaRepository->expects($this->once())
            ->method('find')
            ->with(77)
            ->willReturn($categoria);

        $equipoRepository->expects($this->exactly(2))
            ->method('find')
            ->withConsecutive([101], [102])
            ->willReturnOnConsecutiveCalls($equipoLocal, $equipoVisitante);

        $partidoRepository->expects($this->once())
            ->method('guardar')
            ->with($partido);

        $partidoConfigRepository->expects($this->once())
            ->method('guardar')
            ->with($this->callback(function (PartidoConfig $config) use ($partido, $ganador1, $ganador2): bool {
                return $config->getPartido() === $partido
                    && $config->getNombre() === 'Final Oro'
                    && $config->getGanadorPartido1() === $ganador1
                    && $config->getGanadorPartido2() === $ganador2;
            }));

        $manager = new PartidoManager(
            $this->createMock(CanchaManager::class),
            $this->createMock(GrupoManager::class),
            $categoriaRepository,
            $equipoRepository,
            $partidoRepository,
            $partidoConfigRepository,
            $this->createMock(ValidadorPartidoManager::class)
        );

        $editado = $manager->editarPartidoManual('ruta-test', [
            'editar_partidoId' => '88',
            'editar_categoriaId' => '77',
            'editar_tipo' => TipoPartido::ELIMINATORIO->value,
            'editar_equipoLocalId' => '101',
            'editar_equipoVisitanteId' => '102',
            'editar_usarConfig' => '1',
            'editar_config_nombre' => 'Final Oro',
            'editar_config_origen' => 'ganadores',
            'editar_config_ganadorPartido1Id' => '301',
            'editar_config_ganadorPartido2Id' => '302',
        ]);

        $this->assertSame($partido, $editado);
        $this->assertSame(TipoPartido::ELIMINATORIO->value, $partido->getTipo());
        $this->assertSame($equipoLocal, $partido->getEquipoLocal());
        $this->assertSame($equipoVisitante, $partido->getEquipoVisitante());
    }

    private function setEntityId(object $entity, int $id): void
    {
        $reflection = new \ReflectionClass($entity);
        $property = $reflection->getProperty('id');
        $property->setAccessible(true);
        $property->setValue($entity, $id);
    }
}

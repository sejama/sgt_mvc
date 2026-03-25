<?php

declare(strict_types=1);

namespace App\Tests\Integration\Repository;

use App\Entity\Cancha;
use App\Entity\Categoria;
use App\Entity\Equipo;
use App\Entity\Grupo;
use App\Entity\Partido;
use App\Entity\Sede;
use App\Entity\Torneo;
use App\Entity\Usuario;
use App\Enum\Genero;
use App\Repository\PartidoRepository;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class PartidoRepositoryIntegrationTest extends KernelTestCase
{
    private EntityManagerInterface $entityManager;

    private PartidoRepository $partidoRepository;

    protected function setUp(): void
    {
        self::bootKernel();

        /** @var ManagerRegistry $registry */
        $registry = static::getContainer()->get(ManagerRegistry::class);

        $this->entityManager = $registry->getManager();

        $partidoRepository = $registry->getRepository(Partido::class);
        self::assertInstanceOf(PartidoRepository::class, $partidoRepository);
        $this->partidoRepository = $partidoRepository;
    }

    protected function tearDown(): void
    {
        $this->purgeDatabase($this->entityManager->getConnection());
        $this->entityManager->clear();

        parent::tearDown();
    }

    private function purgeDatabase(Connection $connection): void
    {
        $tables = [
            'partido_config',
            'partido',
            'jugador',
            'equipo',
            'grupo',
            'cancha',
            'sede',
            'categoria',
            'torneo',
            'usuario',
        ];

        $connection->executeStatement('SET FOREIGN_KEY_CHECKS=0');

        try {
            foreach ($tables as $table) {
                $connection->executeStatement(sprintf('TRUNCATE TABLE `%s`', $table));
            }
        } finally {
            $connection->executeStatement('SET FOREIGN_KEY_CHECKS=1');
        }
    }

    public function testObternerPartidoxRutaNumeroYBuscarPartidosXTorneo(): void
    {
        $suffix = substr(md5(uniqid('', true)), 0, 8);
        $ruta = $this->buildRuta('it-partido', $suffix);

        $creador = $this->crearUsuario('it_partido_user_' . $suffix);
        $torneo = $this->crearTorneo($creador, $ruta, $suffix);
        $categoria = $this->crearCategoria($torneo, $suffix);

        $partido = (new Partido())
            ->setCategoria($categoria)
            ->setNumero(77)
            ->setTipo('clasificatorio')
            ->setEstado('Pendiente');

        $this->partidoRepository->guardar($partido, true);

        $encontrado = $this->partidoRepository->obternerPartidoxRutaNumero($ruta, 77);
        self::assertInstanceOf(Partido::class, $encontrado);
        self::assertSame(77, $encontrado->getNumero());
        self::assertSame($ruta, $encontrado->getCategoria()?->getTorneo()?->getRuta());

        $partidosTorneo = $this->partidoRepository->buscarPartidosXTorneo($ruta);
        self::assertNotEmpty($partidosTorneo);
        self::assertGreaterThanOrEqual(1, count($partidosTorneo));
        self::assertSame(77, $partidosTorneo[0]->getNumero());
    }

    public function testBuscarPartidoXCanchaHorarioRetornaPartido(): void
    {
        $suffix = substr(md5(uniqid('', true)), 0, 8);
        $ruta = $this->buildRuta('it-partido-cancha', $suffix);
        $horario = new \DateTimeImmutable('2026-03-10 18:30:00');

        $creador = $this->crearUsuario('it_partido_cancha_user_' . $suffix);
        $torneo = $this->crearTorneo($creador, $ruta, $suffix);
        $categoria = $this->crearCategoria($torneo, $suffix);
        $sede = $this->crearSede($torneo, $suffix);
        $cancha = $this->crearCancha($sede, $suffix);

        $partido = (new Partido())
            ->setCategoria($categoria)
            ->setCancha($cancha)
            ->setHorario($horario)
            ->setNumero(88)
            ->setTipo('clasificatorio')
            ->setEstado('Pendiente');

        $this->partidoRepository->guardar($partido, true);

        $encontrado = $this->partidoRepository->buscarPartidoXCanchaHorario((int) $cancha->getId(), $horario);
        self::assertInstanceOf(Partido::class, $encontrado);
        self::assertSame(88, $encontrado->getNumero());
        self::assertSame((int) $cancha->getId(), (int) $encontrado->getCancha()?->getId());
    }

    public function testBuscarPartidoXCanchaHorarioSinResultadoRetornaNull(): void
    {
        $suffix = substr(md5(uniqid('', true)), 0, 8);
        $ruta = $this->buildRuta('it-partido-cancha-null', $suffix);
        $horarioPartido = new \DateTimeImmutable('2026-03-10 19:30:00');
        $horarioConsulta = new \DateTimeImmutable('2026-03-10 21:00:00');

        $creador = $this->crearUsuario('it_partido_cancha_null_user_' . $suffix);
        $torneo = $this->crearTorneo($creador, $ruta, $suffix);
        $categoria = $this->crearCategoria($torneo, $suffix);
        $sede = $this->crearSede($torneo, $suffix);
        $cancha = $this->crearCancha($sede, $suffix);

        $partido = (new Partido())
            ->setCategoria($categoria)
            ->setCancha($cancha)
            ->setHorario($horarioPartido)
            ->setNumero(99)
            ->setTipo('clasificatorio')
            ->setEstado('Pendiente');

        $this->partidoRepository->guardar($partido, true);

        $encontrado = $this->partidoRepository->buscarPartidoXCanchaHorario((int) $cancha->getId(), $horarioConsulta);
        self::assertNull($encontrado);
    }

    public function testBuscarPartidosSinAsignarXTorneoFiltraCanceladosYConCancha(): void
    {
        $suffix = substr(md5(uniqid('', true)), 0, 8);
        $ruta = $this->buildRuta('it-partido-sin-asignar', $suffix);

        $creador = $this->crearUsuario('it_partido_sin_asignar_user_' . $suffix);
        $torneo = $this->crearTorneo($creador, $ruta, $suffix);
        $categoria = $this->crearCategoria($torneo, $suffix);
        $grupo = $this->crearGrupo($categoria, $suffix);
        $equipoLocal = $this->crearEquipo($categoria, $grupo, 'Local', 1, $suffix);
        $equipoVisitante = $this->crearEquipo($categoria, $grupo, 'Visitante', 2, $suffix);
        $sede = $this->crearSede($torneo, $suffix);
        $cancha = $this->crearCancha($sede, $suffix);

        $partidoSinCancha = (new Partido())
            ->setCategoria($categoria)
            ->setGrupo($grupo)
            ->setEquipoLocal($equipoLocal)
            ->setEquipoVisitante($equipoVisitante)
            ->setNumero(101)
            ->setTipo('clasificatorio')
            ->setEstado('Pendiente');

        $partidoCancelado = (new Partido())
            ->setCategoria($categoria)
            ->setGrupo($grupo)
            ->setEquipoLocal($equipoLocal)
            ->setEquipoVisitante($equipoVisitante)
            ->setNumero(102)
            ->setTipo('clasificatorio')
            ->setEstado('Cancelado');

        $partidoConCancha = (new Partido())
            ->setCategoria($categoria)
            ->setGrupo($grupo)
            ->setCancha($cancha)
            ->setEquipoLocal($equipoLocal)
            ->setEquipoVisitante($equipoVisitante)
            ->setNumero(103)
            ->setTipo('clasificatorio')
            ->setEstado('Pendiente');

        $this->partidoRepository->guardar($partidoSinCancha, true);
        $this->partidoRepository->guardar($partidoCancelado, true);
        $this->partidoRepository->guardar($partidoConCancha, true);

        $resultados = $this->partidoRepository->buscarPartidosSinAsignarXTorneo($ruta);

        self::assertCount(1, $resultados);
        self::assertSame(101, (int) $resultados[0]['numero']);
        self::assertSame($equipoLocal->getNombre(), $resultados[0]['equipoLocal']);
        self::assertSame($equipoVisitante->getNombre(), $resultados[0]['equipoVisitante']);
        self::assertSame($grupo->getNombre(), $resultados[0]['grupo']);
        self::assertSame($categoria->getNombre(), $resultados[0]['categoria']);
    }

    public function testBuscarPartidosProgramadosClasificatorioXTorneoRetornaCamposEsperados(): void
    {
        $suffix = substr(md5(uniqid('', true)), 0, 8);
        $ruta = $this->buildRuta('it-partido-programado', $suffix);
        $horario = new \DateTimeImmutable('2026-04-11 10:15:00');

        $creador = $this->crearUsuario('it_partido_programado_user_' . $suffix);
        $torneo = $this->crearTorneo($creador, $ruta, $suffix);
        $categoria = $this->crearCategoria($torneo, $suffix);
        $grupo = $this->crearGrupo($categoria, $suffix);
        $equipoLocal = $this->crearEquipo($categoria, $grupo, 'ProgLocal', 11, $suffix);
        $equipoVisitante = $this->crearEquipo($categoria, $grupo, 'ProgVisitante', 12, $suffix);
        $sede = $this->crearSede($torneo, $suffix);
        $cancha = $this->crearCancha($sede, $suffix);

        $partido = (new Partido())
            ->setCategoria($categoria)
            ->setGrupo($grupo)
            ->setCancha($cancha)
            ->setEquipoLocal($equipoLocal)
            ->setEquipoVisitante($equipoVisitante)
            ->setHorario($horario)
            ->setNumero(201)
            ->setTipo('clasificatorio')
            ->setEstado('Pendiente');

        $this->partidoRepository->guardar($partido, true);

        // Carga de control en otro torneo para verificar que la consulta sí filtra por ruta.
        $suffixOtro = substr(md5(uniqid('', true)), 0, 8);
        $rutaOtro = $this->buildRuta('it-partido-programado-otro', $suffixOtro);
        $creadorOtro = $this->crearUsuario('it_partido_programado_otro_user_' . $suffixOtro);
        $torneoOtro = $this->crearTorneo($creadorOtro, $rutaOtro, $suffixOtro);
        $categoriaOtra = $this->crearCategoria($torneoOtro, $suffixOtro);
        $grupoOtro = $this->crearGrupo($categoriaOtra, $suffixOtro);
        $equipoLocalOtro = $this->crearEquipo($categoriaOtra, $grupoOtro, 'OtroLocal', 21, $suffixOtro);
        $equipoVisitanteOtro = $this->crearEquipo($categoriaOtra, $grupoOtro, 'OtroVisitante', 22, $suffixOtro);
        $sedeOtra = $this->crearSede($torneoOtro, $suffixOtro);
        $canchaOtra = $this->crearCancha($sedeOtra, $suffixOtro);

        $partidoOtro = (new Partido())
            ->setCategoria($categoriaOtra)
            ->setGrupo($grupoOtro)
            ->setCancha($canchaOtra)
            ->setEquipoLocal($equipoLocalOtro)
            ->setEquipoVisitante($equipoVisitanteOtro)
            ->setHorario(new \DateTimeImmutable('2026-04-11 11:30:00'))
            ->setNumero(202)
            ->setTipo('clasificatorio')
            ->setEstado('Pendiente');

        $this->partidoRepository->guardar($partidoOtro, true);

        $resultados = $this->partidoRepository->buscarPartidosProgramadosClasificatorioXTorneo($ruta);

        self::assertCount(1, $resultados);
        self::assertSame(201, (int) $resultados[0]['numero']);
        self::assertSame($sede->getNombre(), $resultados[0]['sede']);
        self::assertSame($cancha->getNombre(), $resultados[0]['cancha']);
        self::assertSame($equipoLocal->getNombre(), $resultados[0]['equipoLocal']);
        self::assertSame($equipoVisitante->getNombre(), $resultados[0]['equipoVisitante']);
        self::assertSame($grupo->getNombre(), $resultados[0]['grupo']);
        self::assertSame($categoria->getNombre(), $resultados[0]['categoria']);
    }

    public function testBuscarPartidosPlayOffGrupoXTorneoRetornaPartidoConfigurado(): void
    {
        $suffix = substr(md5(uniqid('', true)), 0, 8);
        $ruta = $this->buildRuta('it-partido-playoff-grupo', $suffix);

        $creador = $this->crearUsuario('it_partido_playoff_user_' . $suffix);
        $torneo = $this->crearTorneo($creador, $ruta, $suffix);
        $categoria = $this->crearCategoria($torneo, $suffix);
        $grupo1 = $this->crearGrupo($categoria, $suffix . 'a');
        $grupo2 = $this->crearGrupo($categoria, $suffix . 'b');

        $partido = (new Partido())
            ->setCategoria($categoria)
            ->setNumero(301)
            ->setTipo('playoff')
            ->setEstado('Pendiente');

        $this->partidoRepository->guardar($partido, true);

        $nombrePlayoff = 'PlayOff ' . strtoupper(substr($suffix, 0, 4));
        $this->crearPartidoConfigPlayoff($partido, $grupo1, $grupo2, $nombrePlayoff);

        $resultados = $this->partidoRepository->buscarPartidosPlayOffGrupoXTorneo($ruta);

        self::assertCount(1, $resultados);
        self::assertSame(301, (int) $resultados[0]['numero']);
        self::assertSame($nombrePlayoff, $resultados[0]['nombre']);
        self::assertSame($grupo1->getNombre() . '-1', $resultados[0]['equipoLocal']);
        self::assertSame($grupo2->getNombre() . '-2', $resultados[0]['equipoVisitante']);
        self::assertSame($categoria->getNombre(), $resultados[0]['categoria']);
    }

    public function testBuscarPartidosProgramadosPlayOffXTorneoCubreDefinidosYNoDefinidosYFiltraRuta(): void
    {
        $suffix = substr(md5(uniqid('', true)), 0, 8);
        $ruta = $this->buildRuta('it-partido-prog-playoff', $suffix);

        $creador = $this->crearUsuario('it_partido_prog_playoff_user_' . $suffix);
        $torneo = $this->crearTorneo($creador, $ruta, $suffix);
        $categoria = $this->crearCategoria($torneo, $suffix);
        $grupo1 = $this->crearGrupo($categoria, $suffix . 'a');
        $grupo2 = $this->crearGrupo($categoria, $suffix . 'b');
        $grupoDef1 = $this->crearGrupo($categoria, $suffix . 'c');
        $grupoDef2 = $this->crearGrupo($categoria, $suffix . 'd');
        $equipoLocal = $this->crearEquipo($categoria, $grupo1, 'POLocal', 31, $suffix);
        $equipoVisitante = $this->crearEquipo($categoria, $grupo2, 'POVisitante', 32, $suffix);
        $sede = $this->crearSede($torneo, $suffix);
        $cancha = $this->crearCancha($sede, $suffix);

        // Escenario 1: equipos definidos (debe devolver nombres de equipos)
        $partidoConEquipos = (new Partido())
            ->setCategoria($categoria)
            ->setCancha($cancha)
            ->setHorario(new \DateTimeImmutable('2026-05-01 10:00:00'))
            ->setEquipoLocal($equipoLocal)
            ->setEquipoVisitante($equipoVisitante)
            ->setNumero(401)
            ->setTipo('playoff')
            ->setEstado('Pendiente');
        $this->partidoRepository->guardar($partidoConEquipos, true);
        $this->crearPartidoConfigPlayoff($partidoConEquipos, $grupoDef1, $grupoDef2, 'PO Definidos');

        // Escenario 2: equipos no definidos (debe devolver Grupo + posicion)
        $partidoSinEquipos = (new Partido())
            ->setCategoria($categoria)
            ->setCancha($cancha)
            ->setHorario(new \DateTimeImmutable('2026-05-01 11:00:00'))
            ->setNumero(402)
            ->setTipo('playoff')
            ->setEstado('Pendiente');
        $this->partidoRepository->guardar($partidoSinEquipos, true);
        $this->crearPartidoConfigPlayoff($partidoSinEquipos, $grupo1, $grupo2, 'PO No Definidos');

        // Carga de control en otro torneo para verificar filtro por ruta.
        $suffixOtro = substr(md5(uniqid('', true)), 0, 8);
        $rutaOtro = $this->buildRuta('it-partido-prog-playoff-otro', $suffixOtro);
        $creadorOtro = $this->crearUsuario('it_partido_prog_playoff_otro_user_' . $suffixOtro);
        $torneoOtro = $this->crearTorneo($creadorOtro, $rutaOtro, $suffixOtro);
        $categoriaOtra = $this->crearCategoria($torneoOtro, $suffixOtro);
        $grupoOtro1 = $this->crearGrupo($categoriaOtra, $suffixOtro . 'a');
        $grupoOtro2 = $this->crearGrupo($categoriaOtra, $suffixOtro . 'b');
        $sedeOtra = $this->crearSede($torneoOtro, $suffixOtro);
        $canchaOtra = $this->crearCancha($sedeOtra, $suffixOtro);

        $partidoOtroTorneo = (new Partido())
            ->setCategoria($categoriaOtra)
            ->setCancha($canchaOtra)
            ->setHorario(new \DateTimeImmutable('2026-05-01 12:00:00'))
            ->setNumero(499)
            ->setTipo('playoff')
            ->setEstado('Pendiente');
        $this->partidoRepository->guardar($partidoOtroTorneo, true);
        $this->crearPartidoConfigPlayoff($partidoOtroTorneo, $grupoOtro1, $grupoOtro2, 'PO Otro Torneo');

        $resultados = $this->partidoRepository->buscarPartidosProgramadosPlayOffXTorneo($ruta);

        self::assertCount(2, $resultados);

        $porNumero = [];
        foreach ($resultados as $fila) {
            $porNumero[(int) $fila['numero']] = $fila;
        }

        self::assertArrayHasKey(401, $porNumero);
        self::assertArrayHasKey(402, $porNumero);
        self::assertArrayNotHasKey(499, $porNumero);

        self::assertSame($equipoLocal->getNombre(), $porNumero[401]['equipoLocal']);
        self::assertSame($equipoVisitante->getNombre(), $porNumero[401]['equipoVisitante']);
        self::assertSame($grupo1->getNombre() . ' 1', $porNumero[402]['equipoLocal']);
        self::assertSame($grupo2->getNombre() . ' 2', $porNumero[402]['equipoVisitante']);
    }

    public function testBuscarPartidosProgramadosPlayOffFinalesXTorneoFiltraRutaConEquiposDefinidos(): void
    {
        $suffix = substr(md5(uniqid('', true)), 0, 8);
        $ruta = $this->buildRuta('it-partido-prog-finales', $suffix);

        $creador = $this->crearUsuario('it_partido_prog_finales_user_' . $suffix);
        $torneo = $this->crearTorneo($creador, $ruta, $suffix);
        $categoria = $this->crearCategoria($torneo, $suffix);
        $grupo1 = $this->crearGrupo($categoria, $suffix . 'a');
        $grupo2 = $this->crearGrupo($categoria, $suffix . 'b');
        $equipoLocal = $this->crearEquipo($categoria, $grupo1, 'PFLocal', 41, $suffix);
        $equipoVisitante = $this->crearEquipo($categoria, $grupo2, 'PFVisitante', 42, $suffix);
        $sede = $this->crearSede($torneo, $suffix);
        $cancha = $this->crearCancha($sede, $suffix);

        $partido = (new Partido())
            ->setCategoria($categoria)
            ->setCancha($cancha)
            ->setHorario(new \DateTimeImmutable('2026-05-02 10:00:00'))
            ->setEquipoLocal($equipoLocal)
            ->setEquipoVisitante($equipoVisitante)
            ->setNumero(501)
            ->setTipo('playoff')
            ->setEstado('Pendiente');
        $this->partidoRepository->guardar($partido, true);
        $this->crearPartidoConfigPlayoff($partido, $grupo1, $grupo2, 'PF Definidos');

        // Carga de control en otro torneo para validar filtro por ruta.
        $suffixOtro = substr(md5(uniqid('', true)), 0, 8);
        $rutaOtro = $this->buildRuta('it-partido-prog-finales-otro', $suffixOtro);
        $creadorOtro = $this->crearUsuario('it_partido_prog_finales_otro_user_' . $suffixOtro);
        $torneoOtro = $this->crearTorneo($creadorOtro, $rutaOtro, $suffixOtro);
        $categoriaOtra = $this->crearCategoria($torneoOtro, $suffixOtro);
        $grupoOtro1 = $this->crearGrupo($categoriaOtra, $suffixOtro . 'a');
        $grupoOtro2 = $this->crearGrupo($categoriaOtra, $suffixOtro . 'b');
        $equipoLocalOtro = $this->crearEquipo($categoriaOtra, $grupoOtro1, 'PFOtroLocal', 43, $suffixOtro);
        $equipoVisitanteOtro = $this->crearEquipo($categoriaOtra, $grupoOtro2, 'PFOtroVisitante', 44, $suffixOtro);
        $sedeOtra = $this->crearSede($torneoOtro, $suffixOtro);
        $canchaOtra = $this->crearCancha($sedeOtra, $suffixOtro);

        $partidoOtro = (new Partido())
            ->setCategoria($categoriaOtra)
            ->setCancha($canchaOtra)
            ->setHorario(new \DateTimeImmutable('2026-05-02 11:00:00'))
            ->setEquipoLocal($equipoLocalOtro)
            ->setEquipoVisitante($equipoVisitanteOtro)
            ->setNumero(599)
            ->setTipo('playoff')
            ->setEstado('Pendiente');
        $this->partidoRepository->guardar($partidoOtro, true);
        $this->crearPartidoConfigPlayoff($partidoOtro, $grupoOtro1, $grupoOtro2, 'PF Otro Torneo');

        $resultados = $this->partidoRepository->buscarPartidosProgramadosPlayOffFinalesXTorneo($ruta);

        self::assertCount(1, $resultados);
        self::assertSame(501, (int) $resultados[0]['numero']);
        self::assertSame($equipoLocal->getNombre(), $resultados[0]['equipoLocal']);
        self::assertSame($equipoVisitante->getNombre(), $resultados[0]['equipoVisitante']);
        self::assertSame($sede->getNombre(), $resultados[0]['sede']);
        self::assertSame($cancha->getNombre(), $resultados[0]['cancha']);
    }

    public function testBuscarPartidosProgramadosPlayOffFinalesXTorneoSinEquiposDefinidosUsaGanadores(): void
    {
        $requiredColumns = ['ganador_partido1_id', 'ganador_partido2_id'];
        if (!$this->hasPartidoConfigColumns($requiredColumns)) {
            self::markTestSkipped('La tabla partido_config no tiene columnas de ganadores para finales PlayOff en este esquema.');
        }

        $suffix = substr(md5(uniqid('', true)), 0, 8);
        $ruta = $this->buildRuta('it-partido-final-ganador', $suffix);

        $creador = $this->crearUsuario('it_partido_final_ganador_user_' . $suffix);
        $torneo = $this->crearTorneo($creador, $ruta, $suffix);
        $categoria = $this->crearCategoria($torneo, $suffix);
        $grupo1 = $this->crearGrupo($categoria, $suffix . 'a');
        $grupo2 = $this->crearGrupo($categoria, $suffix . 'b');
        $sede = $this->crearSede($torneo, $suffix);
        $cancha = $this->crearCancha($sede, $suffix);

        $semi1 = (new Partido())
            ->setCategoria($categoria)
            ->setCancha($cancha)
            ->setHorario(new \DateTimeImmutable('2026-05-03 10:00:00'))
            ->setNumero(601)
            ->setTipo('playoff')
            ->setEstado('Pendiente');
        $this->partidoRepository->guardar($semi1, true);

        $semi2 = (new Partido())
            ->setCategoria($categoria)
            ->setCancha($cancha)
            ->setHorario(new \DateTimeImmutable('2026-05-03 11:00:00'))
            ->setNumero(602)
            ->setTipo('playoff')
            ->setEstado('Pendiente');
        $this->partidoRepository->guardar($semi2, true);

        $this->crearPartidoConfigNombreMinimo($semi1, 'Semi A');
        $this->crearPartidoConfigNombreMinimo($semi2, 'Semi B');

        $final = (new Partido())
            ->setCategoria($categoria)
            ->setCancha($cancha)
            ->setHorario(new \DateTimeImmutable('2026-05-03 12:00:00'))
            ->setNumero(603)
            ->setTipo('playoff')
            ->setEstado('Pendiente');
        $this->partidoRepository->guardar($final, true);

        $this->crearPartidoConfigFinalConGanadores($final, $semi1, $semi2, 'Final Oro');

        $resultados = $this->partidoRepository->buscarPartidosProgramadosPlayOffFinalesXTorneo($ruta);

        self::assertNotEmpty($resultados);

        $porNumero = [];
        foreach ($resultados as $fila) {
            $porNumero[(int) $fila['numero']] = $fila;
        }

        self::assertArrayHasKey(603, $porNumero);
        self::assertSame('Ganador Semi A', $porNumero[603]['equipoLocal']);
        self::assertSame('Ganador Semi B', $porNumero[603]['equipoVisitante']);
    }

    public function testBuscarPartidosPlayOffFinalesXTorneoSinProgramarConGanadoresYFiltroRuta(): void
    {
        $requiredColumns = ['ganador_partido1_id', 'ganador_partido2_id'];
        if (!$this->hasPartidoConfigColumns($requiredColumns)) {
            self::markTestSkipped('La tabla partido_config no tiene columnas de ganadores para finales PlayOff en este esquema.');
        }

        $suffix = substr(md5(uniqid('', true)), 0, 8);
        $ruta = $this->buildRuta('it-partido-finales-sp', $suffix);

        $creador = $this->crearUsuario('it_partido_finales_sp_user_' . $suffix);
        $torneo = $this->crearTorneo($creador, $ruta, $suffix);
        $categoria = $this->crearCategoria($torneo, $suffix);

        $semi1 = (new Partido())
            ->setCategoria($categoria)
            ->setNumero(701)
            ->setTipo('playoff')
            ->setEstado('Pendiente');
        $this->partidoRepository->guardar($semi1, true);

        $semi2 = (new Partido())
            ->setCategoria($categoria)
            ->setNumero(702)
            ->setTipo('playoff')
            ->setEstado('Pendiente');
        $this->partidoRepository->guardar($semi2, true);

        $this->crearPartidoConfigNombreMinimo($semi1, 'Semi C');
        $this->crearPartidoConfigNombreMinimo($semi2, 'Semi D');

        $finalSinProgramar = (new Partido())
            ->setCategoria($categoria)
            ->setNumero(703)
            ->setTipo('playoff')
            ->setEstado('Pendiente');
        $this->partidoRepository->guardar($finalSinProgramar, true);
        $this->crearPartidoConfigFinalConGanadores($finalSinProgramar, $semi1, $semi2, 'Final Plata');

        // Control de otro torneo para validar filtro por ruta.
        $suffixOtro = substr(md5(uniqid('', true)), 0, 8);
        $rutaOtro = $this->buildRuta('it-partido-finales-sp-otro', $suffixOtro);
        $creadorOtro = $this->crearUsuario('it_partido_finales_sp_otro_user_' . $suffixOtro);
        $torneoOtro = $this->crearTorneo($creadorOtro, $rutaOtro, $suffixOtro);
        $categoriaOtra = $this->crearCategoria($torneoOtro, $suffixOtro);

        $semiOtro1 = (new Partido())
            ->setCategoria($categoriaOtra)
            ->setNumero(791)
            ->setTipo('playoff')
            ->setEstado('Pendiente');
        $this->partidoRepository->guardar($semiOtro1, true);

        $semiOtro2 = (new Partido())
            ->setCategoria($categoriaOtra)
            ->setNumero(792)
            ->setTipo('playoff')
            ->setEstado('Pendiente');
        $this->partidoRepository->guardar($semiOtro2, true);

        $this->crearPartidoConfigNombreMinimo($semiOtro1, 'Semi X');
        $this->crearPartidoConfigNombreMinimo($semiOtro2, 'Semi Y');

        $finalOtro = (new Partido())
            ->setCategoria($categoriaOtra)
            ->setNumero(793)
            ->setTipo('playoff')
            ->setEstado('Pendiente');
        $this->partidoRepository->guardar($finalOtro, true);
        $this->crearPartidoConfigFinalConGanadores($finalOtro, $semiOtro1, $semiOtro2, 'Final Otro');

        $resultados = $this->partidoRepository->buscarPartidosPlayOffFinalesXTorneo($ruta);

        self::assertCount(1, $resultados);
        self::assertSame(703, (int) $resultados[0]['numero']);
        self::assertSame('Final Plata', $resultados[0]['nombre']);
        self::assertSame('Ganador Semi C', $resultados[0]['equipoPartidoLocalGanador']);
        self::assertSame('Ganador Semi D', $resultados[0]['equipoPartidoVisitanteGanador']);
        self::assertSame($categoria->getNombre(), $resultados[0]['categoria']);
    }

    public function testObtenerPartidoXNumeroYObtenerPartidosXCategoriaClasificatorio(): void
    {
        $suffix = substr(md5(uniqid('', true)), 0, 8);
        $ruta = $this->buildRuta('it-partido-por-num-cat', $suffix);

        $creador = $this->crearUsuario('it_partido_num_cat_user_' . $suffix);
        $torneo = $this->crearTorneo($creador, $ruta, $suffix);
        $categoria = $this->crearCategoria($torneo, $suffix);
        $grupo = $this->crearGrupo($categoria, $suffix);
        $equipoLocal = $this->crearEquipo($categoria, $grupo, 'CLocal', 51, $suffix);
        $equipoVisitante = $this->crearEquipo($categoria, $grupo, 'CVisitante', 52, $suffix);

        $partidoClasificatorio = (new Partido())
            ->setCategoria($categoria)
            ->setGrupo($grupo)
            ->setEquipoLocal($equipoLocal)
            ->setEquipoVisitante($equipoVisitante)
            ->setNumero(801)
            ->setTipo('Clasificatorio')
            ->setEstado('Pendiente');

        $this->partidoRepository->guardar($partidoClasificatorio, true);

        $porNumero = $this->partidoRepository->obtenerPartidoXNumero(801);
        self::assertInstanceOf(Partido::class, $porNumero);
        self::assertSame(801, $porNumero->getNumero());

        $porCategoria = $this->partidoRepository->obtenerPartidosXCategoriaClasificatorio((int) $categoria->getId());
        self::assertNotEmpty($porCategoria);

        $primero = $porCategoria[0];
        self::assertSame($equipoLocal->getNombre(), $primero['Local']);
        self::assertSame($equipoVisitante->getNombre(), $primero['Visitante']);
        self::assertSame('Clasificatorio', $primero['nombre']);
    }

    public function testObtenerPartidosXCategoriaEliminatoriaPostClasificatorioCubreDefinidoYGanador(): void
    {
        $requiredColumns = ['ganador_partido1_id', 'ganador_partido2_id'];
        if (!$this->hasPartidoConfigColumns($requiredColumns)) {
            self::markTestSkipped('La tabla partido_config no tiene columnas de ganadores para eliminatoria post-clasificatorio.');
        }

        $suffix = substr(md5(uniqid('', true)), 0, 8);
        $ruta = $this->buildRuta('it-partido-elim-post', $suffix);

        $creador = $this->crearUsuario('it_partido_elim_post_user_' . $suffix);
        $torneo = $this->crearTorneo($creador, $ruta, $suffix);
        $categoria = $this->crearCategoria($torneo, $suffix);
        $grupo1 = $this->crearGrupo($categoria, $suffix . 'a');
        $grupo2 = $this->crearGrupo($categoria, $suffix . 'b');
        $equipoLocal = $this->crearEquipo($categoria, $grupo1, 'EPLocal', 61, $suffix);
        $equipoVisitante = $this->crearEquipo($categoria, $grupo2, 'EPVisitante', 62, $suffix);

        $definido = (new Partido())
            ->setCategoria($categoria)
            ->setEquipoLocal($equipoLocal)
            ->setEquipoVisitante($equipoVisitante)
            ->setNumero(901)
            ->setTipo('playoff')
            ->setEstado('Pendiente');
        $this->partidoRepository->guardar($definido, true);
        $this->crearPartidoConfigPlayoff($definido, $grupo1, $grupo2, 'Elim Definido');

        $semi1 = (new Partido())
            ->setCategoria($categoria)
            ->setNumero(902)
            ->setTipo('playoff')
            ->setEstado('Pendiente');
        $this->partidoRepository->guardar($semi1, true);

        $semi2 = (new Partido())
            ->setCategoria($categoria)
            ->setNumero(903)
            ->setTipo('playoff')
            ->setEstado('Pendiente');
        $this->partidoRepository->guardar($semi2, true);

        $this->crearPartidoConfigNombreMinimo($semi1, 'Semi E');
        $this->crearPartidoConfigNombreMinimo($semi2, 'Semi F');

        $finalGanadores = (new Partido())
            ->setCategoria($categoria)
            ->setNumero(904)
            ->setTipo('playoff')
            ->setEstado('Pendiente');
        $this->partidoRepository->guardar($finalGanadores, true);
        $this->crearPartidoConfigFinalConGanadores($finalGanadores, $semi1, $semi2, 'Elim Ganadores');

        $resultados = $this->partidoRepository->obtenerPartidosXCategoriaEliminatoriaPostClasificatorio((int) $categoria->getId());

        self::assertGreaterThanOrEqual(2, count($resultados));

        $porNumero = [];
        foreach ($resultados as $fila) {
            $porNumero[(int) $fila['partidoID']] = $fila;
        }

        self::assertArrayHasKey((int) $definido->getId(), $porNumero);
        self::assertArrayHasKey((int) $finalGanadores->getId(), $porNumero);
        self::assertSame($equipoLocal->getNombre(), $porNumero[(int) $definido->getId()]['Local']);
        self::assertSame($equipoVisitante->getNombre(), $porNumero[(int) $definido->getId()]['Visitante']);
        self::assertSame('Ganador Semi E', $porNumero[(int) $finalGanadores->getId()]['Local']);
        self::assertSame('Ganador Semi F', $porNumero[(int) $finalGanadores->getId()]['Visitante']);
    }

    private function crearUsuario(string $username): Usuario
    {
        $usuario = (new Usuario())
            ->setUsername($username)
            ->setEmail($username . '@example.com')
            ->setPassword('hashed-password')
            ->setRoles(['ROLE_ADMIN', 'ROLE_USER']);

        $this->entityManager->persist($usuario);
        $this->entityManager->flush();

        return $usuario;
    }

    private function crearTorneo(Usuario $creador, string $ruta, string $suffix): Torneo
    {
        $torneo = (new Torneo())
            ->setNombre('IT Torneo Partido ' . $suffix)
            ->setRuta($ruta)
            ->setDescripcion('Torneo para integration test de PartidoRepository')
            ->setFechaInicioInscripcion(new \DateTimeImmutable('2026-01-01 10:00:00'))
            ->setFechaFinInscripcion(new \DateTimeImmutable('2026-01-10 10:00:00'))
            ->setFechaInicioTorneo(new \DateTimeImmutable('2026-02-01 10:00:00'))
            ->setFechaFinTorneo(new \DateTimeImmutable('2026-02-20 10:00:00'))
            ->setEstado('activo')
            ->setCreador($creador);

        $this->entityManager->persist($torneo);
        $this->entityManager->flush();

        return $torneo;
    }

    private function buildRuta(string $prefix, string $suffix): string
    {
        $maxLength = 32;
        $normalizedPrefix = trim($prefix, '-');
        $availablePrefixLength = $maxLength - 1 - strlen($suffix);

        if ($availablePrefixLength < 1) {
            return 't-' . substr($suffix, 0, $maxLength - 2);
        }

        return substr($normalizedPrefix, 0, $availablePrefixLength) . '-' . $suffix;
    }

    private function crearCategoria(Torneo $torneo, string $suffix): Categoria
    {
        $categoria = (new Categoria())
            ->setNombre('IT Categoria ' . $suffix)
            ->setNombreCorto('IT' . strtoupper(substr($suffix, 0, 4)))
            ->setGenero(Genero::MASCULINO)
            ->setEstado('activo')
            ->setTorneo($torneo);

        $this->entityManager->persist($categoria);
        $this->entityManager->flush();

        return $categoria;
    }

    private function crearSede(Torneo $torneo, string $suffix): Sede
    {
        $sede = (new Sede())
            ->setNombre('IT Sede ' . $suffix)
            ->setDomicilio('Calle Test 123')
            ->setTorneo($torneo);

        $this->entityManager->persist($sede);
        $this->entityManager->flush();

        return $sede;
    }

    private function crearCancha(Sede $sede, string $suffix): Cancha
    {
        $cancha = (new Cancha())
            ->setNombre('IT Cancha ' . $suffix)
            ->setDescripcion('Cancha de test')
            ->setSede($sede);

        $this->entityManager->persist($cancha);
        $this->entityManager->flush();

        return $cancha;
    }

    private function crearGrupo(Categoria $categoria, string $suffix): Grupo
    {
        $grupo = (new Grupo())
            ->setNombre('Grupo ' . strtoupper(substr($suffix, 0, 4)))
            ->setClasificaOro(2)
            ->setClasificaPlata(0)
            ->setClasificaBronce(0)
            ->setEstado('activo')
            ->setCategoria($categoria);

        $this->entityManager->persist($grupo);
        $this->entityManager->flush();

        return $grupo;
    }

    private function crearEquipo(Categoria $categoria, Grupo $grupo, string $nombreBase, int $numero, string $suffix): Equipo
    {
        $equipo = (new Equipo())
            ->setNombre($nombreBase . ' ' . strtoupper(substr($suffix, 0, 4)))
            ->setNombreCorto(substr(strtoupper($nombreBase), 0, 3) . $numero)
            ->setCategoria($categoria)
            ->setGrupo($grupo)
            ->setEstado('activo')
            ->setNumero($numero);

        $this->entityManager->persist($equipo);
        $this->entityManager->flush();

        return $equipo;
    }

    private function crearPartidoConfigPlayoff(Partido $partido, Grupo $grupo1, Grupo $grupo2, string $nombre): void
    {
        $connection = $this->entityManager->getConnection();
        $columns = $connection->createSchemaManager()->listTableColumns('partido_config');

        $resolveColumn = static function (array $columnCandidates) use ($columns): ?string {
            foreach ($columnCandidates as $candidate) {
                if (isset($columns[$candidate])) {
                    return $candidate;
                }
            }

            return null;
        };

        $columnPartidoId = $resolveColumn(['partido_id']);
        $columnNombre = $resolveColumn(['nombre']);
        $columnGrupo1 = $resolveColumn(['grupo_equipo1_id']);
        $columnPos1 = $resolveColumn(['posicion_equipo1']);
        $columnGrupo2 = $resolveColumn(['grupo_equipo2_id']);
        $columnPos2 = $resolveColumn(['posicion_equipo2']);

        if (
            $columnPartidoId === null
            || $columnNombre === null
            || $columnGrupo1 === null
            || $columnPos1 === null
            || $columnGrupo2 === null
            || $columnPos2 === null
        ) {
            self::markTestSkipped('La tabla partido_config no tiene columnas mínimas esperadas para escenario PlayOff.');
        }

        $data = [
            $columnPartidoId => (int) $partido->getId(),
            $columnNombre => $nombre,
            $columnGrupo1 => (int) $grupo1->getId(),
            $columnPos1 => 1,
            $columnGrupo2 => (int) $grupo2->getId(),
            $columnPos2 => 2,
        ];

        $columnCreatedAt = $resolveColumn(['created_at']);
        $columnUpdatedAt = $resolveColumn(['updated_at']);
        $now = (new \DateTimeImmutable('now'))->format('Y-m-d H:i:s');

        if ($columnCreatedAt !== null) {
            $data[$columnCreatedAt] = $now;
        }

        if ($columnUpdatedAt !== null) {
            $data[$columnUpdatedAt] = $now;
        }

        $connection->insert('partido_config', $data);
    }

    private function crearPartidoConfigNombreMinimo(Partido $partido, string $nombre): void
    {
        $connection = $this->entityManager->getConnection();
        $columns = $connection->createSchemaManager()->listTableColumns('partido_config');

        if (!isset($columns['partido_id']) || !isset($columns['nombre'])) {
            self::markTestSkipped('La tabla partido_config no tiene columnas mínimas (partido_id, nombre).');
        }

        $data = [
            'partido_id' => (int) $partido->getId(),
            'nombre' => $nombre,
        ];

        $now = (new \DateTimeImmutable('now'))->format('Y-m-d H:i:s');
        if (isset($columns['created_at'])) {
            $data['created_at'] = $now;
        }
        if (isset($columns['updated_at'])) {
            $data['updated_at'] = $now;
        }

        $connection->insert('partido_config', $data);
    }

    private function crearPartidoConfigFinalConGanadores(Partido $partidoFinal, Partido $partido1, Partido $partido2, string $nombre): void
    {
        $connection = $this->entityManager->getConnection();
        $columns = $connection->createSchemaManager()->listTableColumns('partido_config');

        if (
            !isset($columns['partido_id'])
            || !isset($columns['nombre'])
            || !isset($columns['ganador_partido1_id'])
            || !isset($columns['ganador_partido2_id'])
        ) {
            self::markTestSkipped('La tabla partido_config no tiene columnas mínimas para finales con ganadores.');
        }

        $data = [
            'partido_id' => (int) $partidoFinal->getId(),
            'nombre' => $nombre,
            'ganador_partido1_id' => (int) $partido1->getId(),
            'ganador_partido2_id' => (int) $partido2->getId(),
        ];

        $now = (new \DateTimeImmutable('now'))->format('Y-m-d H:i:s');
        if (isset($columns['created_at'])) {
            $data['created_at'] = $now;
        }
        if (isset($columns['updated_at'])) {
            $data['updated_at'] = $now;
        }

        $connection->insert('partido_config', $data);
    }

    /**
     * @param string[] $requiredColumns
     */
    private function hasPartidoConfigColumns(array $requiredColumns): bool
    {
        $columns = $this->entityManager->getConnection()->createSchemaManager()->listTableColumns('partido_config');

        foreach ($requiredColumns as $requiredColumn) {
            if (!isset($columns[$requiredColumn])) {
                return false;
            }
        }

        return true;
    }
}

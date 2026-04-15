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

class PartidoRepositoryIntegrationTest extends PartidoRepositoryIntegrationTestCase
{
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

        $encontrado = $this->partidoRepository->buscarPartidoXCanchaHorario($ruta, 0, (int) $cancha->getId(), $horario);
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

        $encontrado = $this->partidoRepository->buscarPartidoXCanchaHorario($ruta, 0, (int) $cancha->getId(), $horarioConsulta);
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
}

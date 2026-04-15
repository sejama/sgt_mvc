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

class PartidoRepositoryPlayoffIntegrationTest extends PartidoRepositoryIntegrationTestCase
{
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
}

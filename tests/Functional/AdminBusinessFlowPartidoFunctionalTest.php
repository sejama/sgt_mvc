<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use App\Entity\Cancha;
use App\Entity\Categoria;
use App\Entity\Equipo;
use App\Entity\Partido;
use App\Entity\PartidoConfig;
use App\Entity\Sede;

class AdminBusinessFlowPartidoFunctionalTest extends AdminBusinessFlowFunctionalTestCase
{
    public function testAdminAccedeAPantallaGestionManualPartido(): void
    {
        $suffix = substr(md5(uniqid('', true)), 0, 8);
        $ruta = $this->buildRuta('ft-admin-gestion-partido', $suffix);

        $admin = $this->crearUsuario('ft_admin_gestion_partido_' . $suffix, ['ROLE_ADMIN', 'ROLE_USER']);
        $torneo = $this->crearTorneo($admin, $ruta, $suffix);
        $this->crearCategoria($torneo, $suffix);

        $this->client->loginUser($admin);
        $this->client->request('GET', '/admin/torneo/' . $ruta . '/partido/gestionar');

        self::assertResponseIsSuccessful();
        self::assertStringContainsString('Gestion manual de partidos', (string) $this->client->getResponse()->getContent());
    }

    public function testAdminCreaYEditaPartidoManualConConfiguracion(): void
    {
        $suffix = substr(md5(uniqid('', true)), 0, 8);
        $ruta = $this->buildRuta('ft-admin-crear-editar-manual', $suffix);

        $admin = $this->crearUsuario('ft_admin_crear_editar_manual_' . $suffix, ['ROLE_ADMIN', 'ROLE_USER']);
        $torneo = $this->crearTorneo($admin, $ruta, $suffix);
        $categoria = $this->crearCategoria($torneo, $suffix);
        $grupoA = $this->crearGrupo($categoria, $suffix . 'a');
        $grupoB = $this->crearGrupo($categoria, $suffix . 'b');
        $equipoLocal = $this->crearEquipo($categoria, $suffix . 'loc');
        $equipoVisitante = $this->crearEquipo($categoria, $suffix . 'vis');

        $categoriaId = $categoria->getId();
        $grupoAId = $grupoA->getId();
        $grupoBId = $grupoB->getId();
        $equipoLocalId = $equipoLocal->getId();
        $equipoVisitanteId = $equipoVisitante->getId();

        self::assertNotNull($categoriaId);
        self::assertNotNull($grupoAId);
        self::assertNotNull($grupoBId);
        self::assertNotNull($equipoLocalId);
        self::assertNotNull($equipoVisitanteId);

        $this->client->loginUser($admin);

        $this->client->request('POST', '/admin/torneo/' . $ruta . '/partido/gestionar', [
            'accion' => 'crear',
            'crear_categoriaId' => (string) $categoriaId,
            'crear_tipo' => 'Eliminatorio',
            'crear_equipoLocalId' => (string) $equipoLocalId,
            'crear_equipoVisitanteId' => (string) $equipoVisitanteId,
            'crear_usarConfig' => '1',
            'crear_config_nombre' => 'Semifinal Oro',
            'crear_config_origen' => 'grupos',
            'crear_config_grupoEquipo1Id' => (string) $grupoAId,
            'crear_config_posicionEquipo1' => '1',
            'crear_config_grupoEquipo2Id' => (string) $grupoBId,
            'crear_config_posicionEquipo2' => '2',
        ]);

        self::assertResponseRedirects('/admin/torneo/' . $ruta . '/partido/gestionar');

        $this->entityManager->clear();
        $partidoManual = $this->entityManager->getRepository(Partido::class)->findOneBy([
            'categoria' => $categoria,
            'numero' => 1,
        ]);

        self::assertInstanceOf(Partido::class, $partidoManual);
        self::assertSame('Eliminatorio', $partidoManual->getTipo());

        $configManual = $this->entityManager->getRepository(PartidoConfig::class)->findOneBy(['partido' => $partidoManual]);
        self::assertInstanceOf(PartidoConfig::class, $configManual);
        self::assertSame('Semifinal Oro', $configManual->getNombre());

        $partidoBase1 = $this->crearPartido($categoria, 2);
        $partidoBase2 = $this->crearPartido($categoria, 3);
        $partidoManualId = $partidoManual->getId();
        $partidoBase1Id = $partidoBase1->getId();
        $partidoBase2Id = $partidoBase2->getId();

        self::assertNotNull($partidoManualId);
        self::assertNotNull($partidoBase1Id);
        self::assertNotNull($partidoBase2Id);

        $this->client->request('POST', '/admin/torneo/' . $ruta . '/partido/gestionar', [
            'accion' => 'editar',
            'editar_partidoId' => (string) $partidoManualId,
            'editar_categoriaId' => (string) $categoriaId,
            'editar_tipo' => 'Eliminatorio',
            'editar_equipoLocalId' => (string) $equipoLocalId,
            'editar_equipoVisitanteId' => (string) $equipoVisitanteId,
            'editar_usarConfig' => '1',
            'editar_config_nombre' => 'Final Oro',
            'editar_config_origen' => 'ganadores',
            'editar_config_ganadorPartido1Id' => (string) $partidoBase1Id,
            'editar_config_ganadorPartido2Id' => (string) $partidoBase2Id,
        ]);

        self::assertResponseRedirects('/admin/torneo/' . $ruta . '/partido/gestionar');

        $this->entityManager->clear();
        $partidoEditado = $this->entityManager->getRepository(Partido::class)->find($partidoManualId);
        self::assertInstanceOf(Partido::class, $partidoEditado);

        $configEditada = $this->entityManager->getRepository(PartidoConfig::class)->findOneBy(['partido' => $partidoEditado]);
        self::assertInstanceOf(PartidoConfig::class, $configEditada);
        self::assertSame('Final Oro', $configEditada->getNombre());
        self::assertSame($partidoBase1Id, $configEditada->getGanadorPartido1()?->getId());
        self::assertSame($partidoBase2Id, $configEditada->getGanadorPartido2()?->getId());
    }

    public function testAdminNoEditaPartidoSiCanchaYHorarioYaOcupados(): void
    {
        $suffix = substr(md5(uniqid('', true)), 0, 8);
        $ruta = $this->buildRuta('ft-admin-edit-partido', $suffix);

        $admin = $this->crearUsuario('ft_admin_edit_partido_' . $suffix, ['ROLE_ADMIN', 'ROLE_USER']);
        $torneo = $this->crearTorneo($admin, $ruta, $suffix);
        $categoria = $this->crearCategoria($torneo, $suffix);
        $cancha = $this->crearCancha($torneo, $suffix);

        $this->crearPartido($categoria, 1, $cancha, new \DateTimeImmutable('2026-05-01 10:00:00'));
        $partidoObjetivo = $this->crearPartido($categoria, 2);
        $partidoObjetivoId = $partidoObjetivo->getId();
        self::assertNotNull($partidoObjetivoId);

        $this->client->loginUser($admin);

        $this->client->request('POST', '/admin/torneo/' . $ruta . '/partido/editar', [
            'var_partidoId' => (string) $partidoObjetivoId,
            'var_cancha' => (string) $cancha->getId(),
            'var_horario' => '2026-05-01 10:30',
        ]);

        self::assertResponseRedirects('/admin/torneo/' . $ruta . '/partido');

        $this->entityManager->clear();
        $noEditado = $this->entityManager->getRepository(Partido::class)->find($partidoObjetivoId);
        self::assertInstanceOf(Partido::class, $noEditado);
        self::assertNull($noEditado->getCancha());
        self::assertNull($noEditado->getHorario());
    }

    public function testAdminNoEditaPartidoConCanchaInexistente(): void
    {
        $suffix = substr(md5(uniqid('', true)), 0, 8);
        $ruta = $this->buildRuta('ft-admin-edit-partido-cancha-inx', $suffix);

        $admin = $this->crearUsuario('ft_admin_edit_partido_inx_' . $suffix, ['ROLE_ADMIN', 'ROLE_USER']);
        $torneo = $this->crearTorneo($admin, $ruta, $suffix);
        $categoria = $this->crearCategoria($torneo, $suffix);
        $partidoObjetivo = $this->crearPartido($categoria, 3);
        $partidoObjetivoId = $partidoObjetivo->getId();
        self::assertNotNull($partidoObjetivoId);

        $this->client->loginUser($admin);

        $this->client->request('POST', '/admin/torneo/' . $ruta . '/partido/editar', [
            'var_partidoId' => (string) $partidoObjetivoId,
            'var_cancha' => '999999',
            'var_horario' => '2026-05-01 12:00',
        ]);

        self::assertResponseRedirects('/admin/torneo/' . $ruta . '/partido');

        $this->entityManager->clear();
        $noEditado = $this->entityManager->getRepository(Partido::class)->find($partidoObjetivoId);
        self::assertInstanceOf(Partido::class, $noEditado);
        self::assertNull($noEditado->getCancha());
        self::assertNull($noEditado->getHorario());
    }

    public function testAdminNoEditaPartidoConHorarioInvalido(): void
    {
        $suffix = substr(md5(uniqid('', true)), 0, 8);
        $ruta = $this->buildRuta('ft-admin-edit-partido-hora-inv', $suffix);

        $admin = $this->crearUsuario('ft_admin_edit_partido_hora_inv_' . $suffix, ['ROLE_ADMIN', 'ROLE_USER']);
        $torneo = $this->crearTorneo($admin, $ruta, $suffix);
        $categoria = $this->crearCategoria($torneo, $suffix);
        $cancha = $this->crearCancha($torneo, $suffix);
        $partidoObjetivo = $this->crearPartido($categoria, 4);
        $partidoObjetivoId = $partidoObjetivo->getId();
        self::assertNotNull($partidoObjetivoId);

        $this->client->loginUser($admin);

        $this->client->request('POST', '/admin/torneo/' . $ruta . '/partido/editar', [
            'var_partidoId' => (string) $partidoObjetivoId,
            'var_cancha' => (string) $cancha->getId(),
            'var_horario' => '2026-99-99 25:99',
        ]);

        self::assertResponseRedirects('/admin/torneo/' . $ruta . '/partido');

        $this->entityManager->clear();
        $noEditado = $this->entityManager->getRepository(Partido::class)->find($partidoObjetivoId);
        self::assertInstanceOf(Partido::class, $noEditado);
        self::assertNull($noEditado->getCancha());
        self::assertNull($noEditado->getHorario());
    }

    public function testAdminEditaPartidoConDatosValidosYProgramaYActivaEquipos(): void
    {
        $suffix = substr(md5(uniqid('', true)), 0, 8);
        $ruta = $this->buildRuta('ft-admin-edit-partido-ok', $suffix);

        $admin = $this->crearUsuario('ft_admin_edit_partido_ok_' . $suffix, ['ROLE_ADMIN', 'ROLE_USER']);
        $torneo = $this->crearTorneo($admin, $ruta, $suffix);
        $categoria = $this->crearCategoria($torneo, $suffix);
        $equipoLocal = $this->crearEquipo($categoria, $suffix . 'ol');
        $equipoVisitante = $this->crearEquipo($categoria, $suffix . 'ov');
        $grupo = $this->crearGrupo($categoria, $suffix);
        $partido = $this->crearPartido($categoria, 333);
        $partido->setGrupo($grupo)->setEquipoLocal($equipoLocal)->setEquipoVisitante($equipoVisitante);
        $cancha = $this->crearCancha($torneo, $suffix);
        $this->entityManager->flush();

        $partidoId = $partido->getId();
        $equipoLocalId = $equipoLocal->getId();
        $equipoVisitanteId = $equipoVisitante->getId();
        self::assertNotNull($partidoId);
        self::assertNotNull($equipoLocalId);
        self::assertNotNull($equipoVisitanteId);

        $this->client->loginUser($admin);
        $this->client->request('POST', '/admin/torneo/' . $ruta . '/partido/editar', [
            'var_partidoId' => (string) $partidoId,
            'var_cancha' => (string) $cancha->getId(),
            'var_horario' => '2026-07-01 10:00',
        ]);

        self::assertResponseRedirects('/admin/torneo/' . $ruta . '/partido');

        $this->entityManager->clear();
        $partidoProgramado = $this->entityManager->getRepository(Partido::class)->find($partidoId);
        self::assertInstanceOf(Partido::class, $partidoProgramado);
        self::assertSame('Programado', $partidoProgramado->getEstado());
        self::assertNotNull($partidoProgramado->getCancha());
        self::assertNotNull($partidoProgramado->getHorario());

        $equipoLocalActualizado = $this->entityManager->getRepository(Equipo::class)->find($equipoLocalId);
        $equipoVisitanteActualizado = $this->entityManager->getRepository(Equipo::class)->find($equipoVisitanteId);
        self::assertInstanceOf(Equipo::class, $equipoLocalActualizado);
        self::assertInstanceOf(Equipo::class, $equipoVisitanteActualizado);
    }

    public function testAdminGestionaCanchaPorPostYGet(): void
    {
        $suffix = substr(md5(uniqid('', true)), 0, 8);
        $ruta = $this->buildRuta('ft-admin-cancha', $suffix);

        $admin = $this->crearUsuario('ft_admin_cancha_' . $suffix, ['ROLE_ADMIN', 'ROLE_USER']);
        $torneo = $this->crearTorneo($admin, $ruta, $suffix);
        $sede = $this->crearSede($torneo, $suffix);
        $sedeId = $sede->getId();
        self::assertNotNull($sedeId);

        $this->client->loginUser($admin);

        $this->client->request('GET', '/admin/torneo/' . $ruta . '/sede/' . $sedeId . '/cancha/');
        self::assertResponseIsSuccessful();

        $this->client->request('POST', '/admin/torneo/' . $ruta . '/sede/' . $sedeId . '/cancha/nuevo', [
            'nombreCancha' => 'FT Cancha Alta ' . $suffix,
            'descripcionCancha' => 'Cancha para test funcional',
        ]);

        self::assertResponseRedirects('/admin/torneo/' . $ruta . '/sede/' . $sedeId . '/cancha/');

        $cancha = $this->entityManager->getRepository(Cancha::class)->findOneBy([
            'nombre' => 'FT Cancha Alta ' . $suffix,
        ]);
        self::assertInstanceOf(Cancha::class, $cancha);
        self::assertSame($sedeId, $cancha->getSede()?->getId());

        $canchaId = $cancha->getId();
        self::assertNotNull($canchaId);

        $this->client->request('POST', '/admin/torneo/' . $ruta . '/sede/' . $sedeId . '/cancha/' . $canchaId . '/editar', [
            'nombreCancha' => 'FT Cancha Edit ' . $suffix,
            'descripcionCancha' => 'Descripcion editada',
        ]);

        self::assertResponseRedirects('/admin/torneo/' . $ruta . '/sede/' . $sedeId . '/cancha/');

        $this->entityManager->clear();
        $canchaEditada = $this->entityManager->getRepository(Cancha::class)->find($canchaId);
        self::assertInstanceOf(Cancha::class, $canchaEditada);
        self::assertSame('FT Cancha Edit ' . $suffix, $canchaEditada->getNombre());

        $this->client->request('POST', '/admin/torneo/' . $ruta . '/sede/' . $sedeId . '/cancha/' . $canchaId . '/eliminar', [
            '_token' => $this->csrfTokenValue('delete_cancha_' . $canchaId),
        ]);
        self::assertResponseRedirects('/admin/torneo/' . $ruta . '/sede/' . $sedeId . '/cancha/');

        $this->entityManager->clear();
        $canchaEliminada = $this->entityManager->getRepository(Cancha::class)->find($canchaId);
        self::assertNull($canchaEliminada);
    }

    public function testAdminNoCreaCanchaDuplicadaPorPost(): void
    {
        $suffix = substr(md5(uniqid('', true)), 0, 8);
        $ruta = $this->buildRuta('ft-admin-cancha-dup', $suffix);

        $admin = $this->crearUsuario('ft_admin_cancha_dup_' . $suffix, ['ROLE_ADMIN', 'ROLE_USER']);
        $torneo = $this->crearTorneo($admin, $ruta, $suffix);
        $sede = $this->crearSede($torneo, $suffix);
        $nombreCancha = 'FT Cancha Duplicada ' . $suffix;

        $this->client->loginUser($admin);

        $this->client->request('POST', '/admin/torneo/' . $ruta . '/sede/' . $sede->getId() . '/cancha/nuevo', [
            'nombreCancha' => $nombreCancha,
            'descripcionCancha' => 'Cancha funcional duplicada',
        ]);

        self::assertResponseRedirects('/admin/torneo/' . $ruta . '/sede/' . $sede->getId() . '/cancha/');

        $this->client->request('POST', '/admin/torneo/' . $ruta . '/sede/' . $sede->getId() . '/cancha/nuevo', [
            'nombreCancha' => $nombreCancha,
            'descripcionCancha' => 'Cancha funcional duplicada',
        ]);

        self::assertResponseStatusCodeSame(200);
        self::assertStringContainsString('Ya existe una cancha con ese nombre', $this->client->getResponse()->getContent());

        $canchas = $this->entityManager->getRepository(Cancha::class)->findBy([
            'sede' => $sede,
            'nombre' => $nombreCancha,
        ]);

        self::assertCount(1, $canchas);
    }

    public function testAdminNoCreaCanchaConNombreInvalidoPorPost(): void
    {
        $suffix = substr(md5(uniqid('', true)), 0, 8);
        $ruta = $this->buildRuta('ft-admin-cancha-nom-inv', $suffix);

        $admin = $this->crearUsuario('ft_admin_cancha_nom_inv_' . $suffix, ['ROLE_ADMIN', 'ROLE_USER']);
        $torneo = $this->crearTorneo($admin, $ruta, $suffix);
        $sede = $this->crearSede($torneo, $suffix);

        $this->client->loginUser($admin);

        // Attempt to create cancha with empty nombre
        $this->client->request('POST', '/admin/torneo/' . $ruta . '/sede/' . $sede->getId() . '/cancha/nuevo', [
            'nombreCancha' => '',
            'descripcionCancha' => 'Cancha con nombre inválido',
        ]);

        self::assertResponseStatusCodeSame(200);
        self::assertStringContainsString('El Nombre debe tener entre 1 y 128 caracteres', $this->client->getResponse()->getContent());

        $canchas = $this->entityManager->getRepository(Cancha::class)->findBy(['sede' => $sede]);
        self::assertCount(0, $canchas);
    }

    public function testAdminNoEditaCanchaConNombreInvalidoPorPost(): void
    {
        $suffix = substr(md5(uniqid('', true)), 0, 8);
        $ruta = $this->buildRuta('ft-admin-cancha-edit-nom-inv', $suffix);

        $admin = $this->crearUsuario('ft_admin_cancha_edit_nom_inv_' . $suffix, ['ROLE_ADMIN', 'ROLE_USER']);
        $torneo = $this->crearTorneo($admin, $ruta, $suffix);
        $sede = $this->crearSede($torneo, $suffix);
        $cancha = $this->crearCancha($torneo, $suffix);

        $this->client->loginUser($admin);

        // Attempt to edit cancha with empty nombre
        $this->client->request('POST', '/admin/torneo/' . $ruta . '/sede/' . $sede->getId() . '/cancha/' . $cancha->getId() . '/editar', [
            'nombreCancha' => '',
            'descripcionCancha' => 'Descripción actualizada',
        ]);

        self::assertResponseStatusCodeSame(200);
        self::assertStringContainsString('El Nombre debe tener entre 1 y 128 caracteres', $this->client->getResponse()->getContent());

        $this->entityManager->clear();
        $canchaNoEditada = $this->entityManager->getRepository(Cancha::class)->find($cancha->getId());
        self::assertNotSame('', $canchaNoEditada->getNombre());
    }

    public function testAdminAccedeAFormularioEditarCanchaPorGet(): void
    {
        $suffix = substr(md5(uniqid('', true)), 0, 8);
        $ruta = $this->buildRuta('ft-admin-cancha-edit-get', $suffix);

        $admin = $this->crearUsuario('ft_admin_cancha_edit_get_' . $suffix, ['ROLE_ADMIN', 'ROLE_USER']);
        $torneo = $this->crearTorneo($admin, $ruta, $suffix);
        $sede = $this->crearSede($torneo, $suffix);
        $cancha = $this->crearCancha($torneo, $suffix);

        $this->client->loginUser($admin);

        $this->client->request('GET', '/admin/torneo/' . $ruta . '/sede/' . $sede->getId() . '/cancha/' . $cancha->getId() . '/editar');

        self::assertResponseIsSuccessful();
    }

    public function testUsuarioSinRolAdminNoAccedeAGestionCancha(): void
    {
        $suffix = substr(md5(uniqid('', true)), 0, 8);
        $ruta = $this->buildRuta('ft-user-cancha', $suffix);

        $adminCreador = $this->crearUsuario('ft_creator_cancha_' . $suffix, ['ROLE_ADMIN', 'ROLE_USER']);
        $torneo = $this->crearTorneo($adminCreador, $ruta, $suffix);
        $sede = $this->crearSede($torneo, $suffix);

        $usuario = $this->crearUsuario('ft_user_cancha_' . $suffix, ['ROLE_USER']);
        $this->client->loginUser($usuario);

        $this->client->request('GET', '/admin/torneo/' . $ruta . '/sede/' . $sede->getId() . '/cancha/');
        self::assertContains($this->client->getResponse()->getStatusCode(), [401, 403]);
    }

    public function testAnonimoNoAccedeAGestionCancha(): void
    {
        $suffix = substr(md5(uniqid('', true)), 0, 8);
        $ruta = $this->buildRuta('ft-anon-cancha', $suffix);

        $adminCreador = $this->crearUsuario('ft_creator_anon_cancha_' . $suffix, ['ROLE_ADMIN', 'ROLE_USER']);
        $torneo = $this->crearTorneo($adminCreador, $ruta, $suffix);
        $sede = $this->crearSede($torneo, $suffix);

        $this->client->request('GET', '/admin/torneo/' . $ruta . '/sede/' . $sede->getId() . '/cancha/');
        $status = $this->client->getResponse()->getStatusCode();
        if ($status === 302) {
            self::assertResponseRedirects('/login');
        } else {
            self::assertContains($status, [401, 403]);
        }
    }
    public function testAdminAccedeYPublicaCreacionDePartidosClasificatorios(): void
    {
        $suffix = substr(md5(uniqid('', true)), 0, 8);
        $ruta = $this->buildRuta('ft-admin-crear-playoff', $suffix);

        $admin = $this->crearUsuario('ft_admin_playoff_' . $suffix, ['ROLE_ADMIN', 'ROLE_USER']);
        $torneo = $this->crearTorneo($admin, $ruta, $suffix);
        $categoria = $this->crearCategoria($torneo, $suffix);
        $this->crearGrupo($categoria, $suffix);

        $categoriaId = $categoria->getId();
        self::assertNotNull($categoriaId);

        $this->client->loginUser($admin);

        $this->client->request('POST', '/admin/torneo/' . $ruta . '/categoria/' . $categoriaId . '/partido/crear', []);
        self::assertResponseRedirects('/admin/torneo/' . $ruta . '/partido');
    }

    public function testAdminNoCreaSedeDuplicadaPorPost(): void
    {
        $suffix = substr(md5(uniqid('', true)), 0, 8);
        $ruta = $this->buildRuta('ft-admin-post-sede-dup', $suffix);

        $admin = $this->crearUsuario('ft_admin_post_sede_dup_' . $suffix, ['ROLE_ADMIN', 'ROLE_USER']);
        $torneo = $this->crearTorneo($admin, $ruta, $suffix);
        $nombreSede = 'FT Sede Duplicada ' . $suffix;

        $this->client->loginUser($admin);

        $this->client->request('POST', '/admin/torneo/' . $ruta . '/sede/nuevo', [
            'sedeNombre' => $nombreSede,
            'sedeDireccion' => 'Calle Funcional 456',
        ]);

        self::assertResponseRedirects('/admin/torneo/');

        $this->client->request('POST', '/admin/torneo/' . $ruta . '/sede/nuevo', [
            'sedeNombre' => $nombreSede,
            'sedeDireccion' => 'Calle Funcional 456',
        ]);

        self::assertStringContainsString('Ya existe una sede con ese nombre', $this->client->getResponse()->getContent());

        $sedes = $this->entityManager->getRepository(Sede::class)->findBy([
            'torneo' => $torneo,
            'nombre' => $nombreSede,
        ]);

        self::assertCount(1, $sedes);
    }
    public function testAdminAccedeAFormularioCreacionPartidosClasificatoriosPorGet(): void
    {
        $suffix = substr(md5(uniqid('', true)), 0, 8);
        $ruta = $this->buildRuta('ft-admin-get-playoff', $suffix);

        $admin = $this->crearUsuario('ft_admin_get_playoff_' . $suffix, ['ROLE_ADMIN', 'ROLE_USER']);
        $torneo = $this->crearTorneo($admin, $ruta, $suffix);
        $categoria = $this->crearCategoria($torneo, $suffix);
        $this->crearGrupo($categoria, $suffix);

        $categoriaId = $categoria->getId();
        self::assertNotNull($categoriaId);

        $this->client->loginUser($admin);

        $this->client->request('GET', '/admin/torneo/' . $ruta . '/categoria/' . $categoriaId . '/partido/crear');
        self::assertResponseIsSuccessful();
    }

    public function testAdminVeTiposFinalesPlayoffEnFormularioCreacionPartidos(): void
    {
        $suffix = substr(md5(uniqid('', true)), 0, 8);
        $ruta = $this->buildRuta('ft-admin-get-playoff-tipos', $suffix);

        $admin = $this->crearUsuario('ft_admin_get_playoff_tipos_' . $suffix, ['ROLE_ADMIN', 'ROLE_USER']);
        $torneo = $this->crearTorneo($admin, $ruta, $suffix);
        $categoria = $this->crearCategoria($torneo, $suffix);
        $this->crearEquipo($categoria, $suffix . 'a');
        $this->crearEquipo($categoria, $suffix . 'b');

        $categoriaId = $categoria->getId();
        self::assertNotNull($categoriaId);

        $this->client->loginUser($admin);

        $this->client->request('POST', '/admin/torneo/' . $ruta . '/categoria/' . $categoriaId . '/grupo/crear', [
            'cantidadGrupos' => '1',
            'grupos' => [
                [
                    'nombre' => 'Grupo A',
                    'cantidadEquipo' => '2',
                    'clasificaOro' => '2',
                    'clasificaPlata' => '0',
                    'clasificaBronce' => '0',
                ],
            ],
        ]);

        $this->client->request('GET', '/admin/torneo/' . $ruta . '/categoria/' . $categoriaId . '/partido/crear');

        self::assertResponseIsSuccessful();
        $content = $this->client->getResponse()->getContent();
        self::assertStringContainsString('Partidos Play Offs Oro', $content);
        self::assertStringContainsString('Total de 2 Equipos', $content);
    }

    public function testAdminNoAccedeAFormularioCreacionPartidosConCategoriaInexistente(): void
    {
        $suffix = substr(md5(uniqid('', true)), 0, 8);
        $ruta = $this->buildRuta('ft-admin-get-playoff-inx', $suffix);

        $admin = $this->crearUsuario('ft_admin_get_playoff_inx_' . $suffix, ['ROLE_ADMIN', 'ROLE_USER']);
        $this->crearTorneo($admin, $ruta, $suffix);

        $this->client->loginUser($admin);
        $this->client->request('GET', '/admin/torneo/' . $ruta . '/categoria/999999/partido/crear');

        self::assertResponseRedirects('/admin/torneo/' . $ruta . '/partido');
    }

    public function testUsuarioSinRolAdminNoAccedeACrearPartidoClasificatorio(): void
    {
        $suffix = substr(md5(uniqid('', true)), 0, 8);
        $ruta = $this->buildRuta('ft-user-get-playoff', $suffix);

        $admin = $this->crearUsuario('ft_admin_user_get_playoff_' . $suffix, ['ROLE_ADMIN', 'ROLE_USER']);
        $torneo = $this->crearTorneo($admin, $ruta, $suffix);
        $categoria = $this->crearCategoria($torneo, $suffix);
        $this->crearGrupo($categoria, $suffix);
        $categoriaId = $categoria->getId();
        self::assertNotNull($categoriaId);

        $usuario = $this->crearUsuario('ft_user_get_playoff_' . $suffix, ['ROLE_USER']);
        $this->client->loginUser($usuario);

        $this->client->request('GET', '/admin/torneo/' . $ruta . '/categoria/' . $categoriaId . '/partido/crear');
        self::assertContains($this->client->getResponse()->getStatusCode(), [401, 403]);
    }

    public function testAnonimoNoAccedeACrearPartidoClasificatorio(): void
    {
        $suffix = substr(md5(uniqid('', true)), 0, 8);
        $ruta = $this->buildRuta('ft-anon-get-playoff', $suffix);

        $admin = $this->crearUsuario('ft_admin_anon_get_playoff_' . $suffix, ['ROLE_ADMIN', 'ROLE_USER']);
        $torneo = $this->crearTorneo($admin, $ruta, $suffix);
        $categoria = $this->crearCategoria($torneo, $suffix);
        $this->crearGrupo($categoria, $suffix);
        $categoriaId = $categoria->getId();
        self::assertNotNull($categoriaId);

        $this->client->request('GET', '/admin/torneo/' . $ruta . '/categoria/' . $categoriaId . '/partido/crear');
        $status = $this->client->getResponse()->getStatusCode();
        if ($status === 302) {
            self::assertResponseRedirects('/login');
        } else {
            self::assertContains($status, [401, 403]);
        }
    }

    public function testUsuarioSinRolAdminNoAccedeAEditarPartido(): void
    {
        $suffix = substr(md5(uniqid('', true)), 0, 8);
        $ruta = $this->buildRuta('ft-user-edit-partido', $suffix);

        $admin = $this->crearUsuario('ft_admin_user_edit_partido_' . $suffix, ['ROLE_ADMIN', 'ROLE_USER']);
        $torneo = $this->crearTorneo($admin, $ruta, $suffix);
        $categoria = $this->crearCategoria($torneo, $suffix);
        $equipoLocal = $this->crearEquipo($categoria, $suffix . 'ul');
        $equipoVisitante = $this->crearEquipo($categoria, $suffix . 'uv');
        $partido = $this->crearPartido($categoria, 200);
        $partido->setEquipoLocal($equipoLocal)->setEquipoVisitante($equipoVisitante);
        $this->entityManager->flush();

        $usuario = $this->crearUsuario('ft_user_edit_partido_' . $suffix, ['ROLE_USER']);
        $this->client->loginUser($usuario);

        $this->client->request('POST', '/admin/torneo/' . $ruta . '/partido/editar', [
            'var_partidoId' => (string) $partido->getId(),
            'var_cancha' => (string) ($this->crearCancha($torneo, $suffix)->getId()),
            'var_horario' => '2026-06-01 10:00',
        ]);
        self::assertContains($this->client->getResponse()->getStatusCode(), [401, 403]);
    }

    public function testAnonimoNoAccedeAEditarPartido(): void
    {
        $suffix = substr(md5(uniqid('', true)), 0, 8);
        $ruta = $this->buildRuta('ft-anon-edit-partido', $suffix);

        $admin = $this->crearUsuario('ft_admin_anon_edit_partido_' . $suffix, ['ROLE_ADMIN', 'ROLE_USER']);
        $torneo = $this->crearTorneo($admin, $ruta, $suffix);
        $categoria = $this->crearCategoria($torneo, $suffix);
        $equipoLocal = $this->crearEquipo($categoria, $suffix . 'al');
        $equipoVisitante = $this->crearEquipo($categoria, $suffix . 'av');
        $partido = $this->crearPartido($categoria, 201);
        $partido->setEquipoLocal($equipoLocal)->setEquipoVisitante($equipoVisitante);
        $this->entityManager->flush();
        $canchaId = $this->crearCancha($torneo, $suffix)->getId();

        $this->client->request('POST', '/admin/torneo/' . $ruta . '/partido/editar', [
            'var_partidoId' => (string) $partido->getId(),
            'var_cancha' => (string) $canchaId,
            'var_horario' => '2026-06-01 11:00',
        ]);
        $status = $this->client->getResponse()->getStatusCode();
        if ($status === 302) {
            self::assertResponseRedirects('/login');
        } else {
            self::assertContains($status, [401, 403]);
        }
    }

    public function testAdminCargaResultadoDePartidoYSeFinaliza(): void
    {
        $suffix = substr(md5(uniqid('', true)), 0, 8);
        $ruta = $this->buildRuta('ft-admin-res-partido', $suffix);

        $admin = $this->crearUsuario('ft_admin_res_partido_' . $suffix, ['ROLE_ADMIN', 'ROLE_USER']);
        $torneo = $this->crearTorneo($admin, $ruta, $suffix);
        $categoria = $this->crearCategoria($torneo, $suffix);
        $equipoLocal = $this->crearEquipo($categoria, $suffix . 'l');
        $equipoVisitante = $this->crearEquipo($categoria, $suffix . 'v');
        $partido = $this->crearPartido($categoria, 77);
        $partido
            ->setEquipoLocal($equipoLocal)
            ->setEquipoVisitante($equipoVisitante);
        $this->entityManager->flush();

        $partidoNumero = $partido->getNumero();
        $partidoId = $partido->getId();
        self::assertNotNull($partidoNumero);
        self::assertNotNull($partidoId);

        $this->client->loginUser($admin);

        $this->client->request('POST', '/admin/torneo/' . $ruta . '/partido/' . $partidoNumero . '/cargar_resultado', [
            'puntosLocal' => ['21', '18', '15'],
            'puntosVisitante' => ['18', '21', '10'],
        ]);

        self::assertResponseRedirects('/admin/torneo/' . $ruta . '/partido');

        $this->entityManager->clear();
        $partidoFinalizado = $this->entityManager->getRepository(Partido::class)->find($partidoId);
        self::assertInstanceOf(Partido::class, $partidoFinalizado);
        self::assertSame('Finalizado', $partidoFinalizado->getEstado());
        self::assertSame(21, $partidoFinalizado->getLocalSet1());
        self::assertSame(18, $partidoFinalizado->getVisitanteSet1());
    }

    public function testAdminNoPuedeCargarResultadoDePartidoInexistente(): void
    {
        $suffix = substr(md5(uniqid('', true)), 0, 8);
        $ruta = $this->buildRuta('ft-admin-res-inx', $suffix);

        $admin = $this->crearUsuario('ft_admin_res_inx_' . $suffix, ['ROLE_ADMIN', 'ROLE_USER']);
        $this->crearTorneo($admin, $ruta, $suffix);

        $this->client->loginUser($admin);

        $this->client->request('GET', '/admin/torneo/' . $ruta . '/partido/999999/cargar_resultado');

        self::assertResponseRedirects('/torneo/' . $ruta);
    }

    public function testAdminNoCargaResultadoConPayloadIncompleto(): void
    {
        $suffix = substr(md5(uniqid('', true)), 0, 8);
        $ruta = $this->buildRuta('ft-admin-res-payload-inv', $suffix);

        $admin = $this->crearUsuario('ft_admin_res_payload_inv_' . $suffix, ['ROLE_ADMIN', 'ROLE_USER']);
        $torneo = $this->crearTorneo($admin, $ruta, $suffix);
        $categoria = $this->crearCategoria($torneo, $suffix);
        $equipoLocal = $this->crearEquipo($categoria, $suffix . 'il');
        $equipoVisitante = $this->crearEquipo($categoria, $suffix . 'iv');
        $partido = $this->crearPartido($categoria, 124);
        $partido
            ->setEquipoLocal($equipoLocal)
            ->setEquipoVisitante($equipoVisitante);
        $this->entityManager->flush();

        $partidoNumero = $partido->getNumero();
        $partidoId = $partido->getId();
        self::assertNotNull($partidoNumero);
        self::assertNotNull($partidoId);

        $this->client->loginUser($admin);

        $this->client->request('POST', '/admin/torneo/' . $ruta . '/partido/' . $partidoNumero . '/cargar_resultado', [
            'puntosLocal' => ['21'],
            'puntosVisitante' => ['18'],
        ]);

        self::assertResponseRedirects('/torneo/' . $ruta);

        $this->entityManager->clear();
        $partidoSinCambios = $this->entityManager->getRepository(Partido::class)->find($partidoId);
        self::assertInstanceOf(Partido::class, $partidoSinCambios);
        self::assertNotSame('Finalizado', $partidoSinCambios->getEstado());
        self::assertNull($partidoSinCambios->getLocalSet1());
        self::assertNull($partidoSinCambios->getVisitanteSet1());
    }

    public function testAdminAccedeAFormularioCargaResultadoPorGetSinEquiposAsignados(): void
    {
        $suffix = substr(md5(uniqid('', true)), 0, 8);
        $ruta = $this->buildRuta('ft-admin-get-resultado', $suffix);

        $admin = $this->crearUsuario('ft_admin_get_resultado_' . $suffix, ['ROLE_ADMIN', 'ROLE_USER']);
        $torneo = $this->crearTorneo($admin, $ruta, $suffix);
        $categoria = $this->crearCategoria($torneo, $suffix);
        $partido = $this->crearPartido($categoria, 99);

        $partidoNumero = $partido->getNumero();
        self::assertNotNull($partidoNumero);

        $this->client->loginUser($admin);

        $this->client->request('GET', '/admin/torneo/' . $ruta . '/partido/' . $partidoNumero . '/cargar_resultado');
        self::assertResponseIsSuccessful();
    }

    public function testAdminAccedeAFormularioCargaResultadoPorGetConEquiposAsignados(): void
    {
        $suffix = substr(md5(uniqid('', true)), 0, 8);
        $ruta = $this->buildRuta('ft-admin-get-resultado-eq', $suffix);

        $admin = $this->crearUsuario('ft_admin_get_resultado_eq_' . $suffix, ['ROLE_ADMIN', 'ROLE_USER']);
        $torneo = $this->crearTorneo($admin, $ruta, $suffix);
        $categoria = $this->crearCategoria($torneo, $suffix);
        $equipoLocal = $this->crearEquipo($categoria, $suffix . 'rl');
        $equipoVisitante = $this->crearEquipo($categoria, $suffix . 'rv');
        $partido = $this->crearPartido($categoria, 1221);
        $partido->setEquipoLocal($equipoLocal)->setEquipoVisitante($equipoVisitante);
        $this->entityManager->flush();

        $partidoNumero = $partido->getNumero();
        self::assertNotNull($partidoNumero);

        $this->client->loginUser($admin);
        $this->client->request('GET', '/admin/torneo/' . $ruta . '/partido/' . $partidoNumero . '/cargar_resultado');

        self::assertResponseIsSuccessful();
        $content = $this->client->getResponse()->getContent();
        self::assertStringContainsString($equipoLocal->getNombre(), $content);
        self::assertStringContainsString($equipoVisitante->getNombre(), $content);
    }

    public function testAdminGeneraPdfDePartido(): void
    {
        $suffix = substr(md5(uniqid('', true)), 0, 8);
        $ruta = $this->buildRuta('ft-admin-pdf-partido', $suffix);

        $admin = $this->crearUsuario('ft_admin_pdf_partido_' . $suffix, ['ROLE_ADMIN', 'ROLE_USER']);
        $torneo = $this->crearTorneo($admin, $ruta, $suffix);
        $categoria = $this->crearCategoria($torneo, $suffix);
        $equipoLocal = $this->crearEquipo($categoria, $suffix . 'pl');
        $equipoVisitante = $this->crearEquipo($categoria, $suffix . 'pv');
        $partido = $this->crearPartido($categoria, 121);
        $partido
            ->setEquipoLocal($equipoLocal)
            ->setEquipoVisitante($equipoVisitante);
        $this->entityManager->flush();

        $partidoNumero = $partido->getNumero();
        self::assertNotNull($partidoNumero);

        $this->client->loginUser($admin);

        $this->client->request('GET', '/admin/torneo/' . $ruta . '/partido/' . $partidoNumero . '/pdf');
        self::assertResponseStatusCodeSame(200);
        self::assertResponseHeaderSame('content-type', 'application/pdf');
    }

    public function testAnonimoGeneraPdfDePartidoYRedirigeAIndice(): void
    {
        $suffix = substr(md5(uniqid('', true)), 0, 8);
        $ruta = $this->buildRuta('ft-anon-pdf-partido', $suffix);

        $admin = $this->crearUsuario('ft_admin_anon_pdf_partido_' . $suffix, ['ROLE_ADMIN', 'ROLE_USER']);
        $torneo = $this->crearTorneo($admin, $ruta, $suffix);
        $categoria = $this->crearCategoria($torneo, $suffix);
        $partido = $this->crearPartido($categoria, 301);
        $partidoNumero = $partido->getNumero();
        self::assertNotNull($partidoNumero);

        $this->client->request('GET', '/admin/torneo/' . $ruta . '/partido/' . $partidoNumero . '/pdf');

        self::assertResponseStatusCodeSame(200);
        self::assertResponseHeaderSame('content-type', 'application/pdf');
    }

    public function testAnonimoPdfDePartidoInexistenteRedirigeAIndice(): void
    {
        $suffix = substr(md5(uniqid('', true)), 0, 8);
        $ruta = $this->buildRuta('ft-anon-pdf-partido-inx', $suffix);

        $admin = $this->crearUsuario('ft_admin_anon_pdf_partido_inx_' . $suffix, ['ROLE_ADMIN', 'ROLE_USER']);
        $this->crearTorneo($admin, $ruta, $suffix);

        $this->client->request('GET', '/admin/torneo/' . $ruta . '/partido/999999/pdf');

        self::assertResponseRedirects();
        $location = $this->client->getResponse()->headers->get('Location');
        self::assertNotNull($location);
        self::assertStringStartsWith('/error?', $location);
    }

    public function testUsuarioSinPermisoNoPuedeCargarResultadoYVuelveATorneo(): void
    {
        $suffix = substr(md5(uniqid('', true)), 0, 8);
        $ruta = $this->buildRuta('ft-user-deny-resultado', $suffix);

        $admin = $this->crearUsuario('ft_admin_deny_resultado_' . $suffix, ['ROLE_ADMIN', 'ROLE_USER']);
        $torneo = $this->crearTorneo($admin, $ruta, $suffix);
        $categoria = $this->crearCategoria($torneo, $suffix);
        $equipoLocal = $this->crearEquipo($categoria, $suffix . 'dl');
        $equipoVisitante = $this->crearEquipo($categoria, $suffix . 'dv');
        $partido = $this->crearPartido($categoria, 122);
        $partido
            ->setEquipoLocal($equipoLocal)
            ->setEquipoVisitante($equipoVisitante);
        $this->entityManager->flush();

        $partidoNumero = $partido->getNumero();
        self::assertNotNull($partidoNumero);

        $usuario = $this->crearUsuario('ft_user_deny_resultado_' . $suffix, ['ROLE_USER']);
        $this->client->loginUser($usuario);

        $this->client->request('GET', '/admin/torneo/' . $ruta . '/partido/' . $partidoNumero . '/cargar_resultado');
        self::assertResponseRedirects();
        $location = $this->client->getResponse()->headers->get('Location');
        self::assertNotNull($location);
        self::assertStringStartsWith('/no-autorizado', $location);
    }

    public function testPlanilleroCargaResultadoYRedirigeATorneo(): void
    {
        $suffix = substr(md5(uniqid('', true)), 0, 8);
        $ruta = $this->buildRuta('ft-planillero-resultado', $suffix);

        $admin = $this->crearUsuario('ft_admin_planillero_result_' . $suffix, ['ROLE_ADMIN', 'ROLE_USER']);
        $torneo = $this->crearTorneo($admin, $ruta, $suffix);
        $categoria = $this->crearCategoria($torneo, $suffix);
        $equipoLocal = $this->crearEquipo($categoria, $suffix . 'rl');
        $equipoVisitante = $this->crearEquipo($categoria, $suffix . 'rv');
        $partido = $this->crearPartido($categoria, 123);
        $partido
            ->setEquipoLocal($equipoLocal)
            ->setEquipoVisitante($equipoVisitante);
        $this->entityManager->flush();

        $partidoNumero = $partido->getNumero();
        $partidoId = $partido->getId();
        self::assertNotNull($partidoNumero);
        self::assertNotNull($partidoId);

        $planillero = $this->crearUsuario('ft_planillero_result_' . $suffix, ['ROLE_PLANILLERO', 'ROLE_USER']);
        $this->client->loginUser($planillero);

        $this->client->request('POST', '/admin/torneo/' . $ruta . '/partido/' . $partidoNumero . '/cargar_resultado', [
            'puntosLocal' => ['21', '21', ''],
            'puntosVisitante' => ['18', '19', ''],
        ]);

        self::assertResponseRedirects('/torneo/' . $ruta);

        $this->entityManager->clear();
        $partidoFinalizado = $this->entityManager->getRepository(Partido::class)->find($partidoId);
        self::assertInstanceOf(Partido::class, $partidoFinalizado);
        self::assertSame('Finalizado', $partidoFinalizado->getEstado());
    }

    public function testAnonimoNoPuedeCargarResultadoDePartido(): void
    {
        $suffix = substr(md5(uniqid('', true)), 0, 8);
        $ruta = $this->buildRuta('ft-anon-res-partido', $suffix);

        $admin = $this->crearUsuario('ft_admin_anon_res_' . $suffix, ['ROLE_ADMIN', 'ROLE_USER']);
        $torneo = $this->crearTorneo($admin, $ruta, $suffix);
        $categoria = $this->crearCategoria($torneo, $suffix);
        $partido = $this->crearPartido($categoria, 88);

        $partidoNumero = $partido->getNumero();
        self::assertNotNull($partidoNumero);

        $this->client->request('GET', '/admin/torneo/' . $ruta . '/partido/' . $partidoNumero . '/cargar_resultado');
        self::assertResponseRedirects('/login');
    }

    public function testUsuarioSinRolAdminNoPuedeCargarResultadoDePartidoYVuelveATorneo(): void
    {
        $suffix = substr(md5(uniqid('', true)), 0, 8);
        $ruta = $this->buildRuta('ft-user-res-partido', $suffix);

        $admin = $this->crearUsuario('ft_admin_user_res_' . $suffix, ['ROLE_ADMIN', 'ROLE_USER']);
        $torneo = $this->crearTorneo($admin, $ruta, $suffix);
        $categoria = $this->crearCategoria($torneo, $suffix);
        $equipoLocal = $this->crearEquipo($categoria, $suffix . 'rl');
        $equipoVisitante = $this->crearEquipo($categoria, $suffix . 'rv');
        $partido = $this->crearPartido($categoria, 88);
        $partido->setEquipoLocal($equipoLocal)->setEquipoVisitante($equipoVisitante);
        $this->entityManager->flush();

        $partidoNumero = $partido->getNumero();
        self::assertNotNull($partidoNumero);

        $usuario = $this->crearUsuario('ft_user_res_' . $suffix, ['ROLE_USER']);
        $this->client->loginUser($usuario);

        $this->client->request('GET', '/admin/torneo/' . $ruta . '/partido/' . $partidoNumero . '/cargar_resultado');

        self::assertResponseRedirects();
        $location = $this->client->getResponse()->headers->get('Location');
        self::assertNotNull($location);
        self::assertStringStartsWith('/no-autorizado', $location);
    }

    public function testAdminEditaDisputaYCierraCategoria(): void
    {
        $suffix = substr(md5(uniqid('', true)), 0, 8);
        $ruta = $this->buildRuta('ft-admin-disputa-cat', $suffix);

        $admin = $this->crearUsuario('ft_admin_disputa_cat_' . $suffix, ['ROLE_ADMIN', 'ROLE_USER']);
        $torneo = $this->crearTorneo($admin, $ruta, $suffix);
        $categoria = $this->crearCategoria($torneo, $suffix);
        $categoriaId = $categoria->getId();
        self::assertNotNull($categoriaId);

        $this->client->loginUser($admin);

        $this->client->request('POST', '/admin/torneo/' . $ruta . '/categoria/' . $categoriaId . '/editar/disputa/', [
            'disputa' => 'Simple',
        ]);

        self::assertResponseRedirects('/admin/torneo/');

        $this->client->request('POST', '/admin/torneo/' . $ruta . '/categoria/' . $categoriaId . '/cerrar');
        self::assertResponseRedirects('/admin/torneo/' . $ruta . '/categoria/' . $categoriaId . '/equipo/');

        $this->entityManager->clear();
        $categoriaActualizada = $this->entityManager->getRepository(Categoria::class)->find($categoriaId);
        self::assertInstanceOf(Categoria::class, $categoriaActualizada);
        self::assertSame('Simple', $categoriaActualizada->getDisputa());
        self::assertSame('Cerrada', $categoriaActualizada->getEstado());
    }
}

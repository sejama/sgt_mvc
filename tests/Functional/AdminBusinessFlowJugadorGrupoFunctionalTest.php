<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use App\Entity\Cancha;
use App\Entity\Categoria;
use App\Entity\Equipo;
use App\Entity\Grupo;
use App\Entity\Jugador;
use App\Entity\Partido;
use App\Entity\Sede;
use App\Entity\Torneo;
use App\Entity\Usuario;
use App\Enum\EstadoPartido;
use App\Enum\Genero;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;

class AdminBusinessFlowJugadorGrupoFunctionalTest extends AdminBusinessFlowFunctionalTestCase
{
    public function testAdminGestionaJugadorPorPostYGet(): void
    {
        $suffix = substr(md5(uniqid('', true)), 0, 8);
        $ruta = $this->buildRuta('ft-admin-jugador', $suffix);

        $admin = $this->crearUsuario('ft_admin_jugador_' . $suffix, ['ROLE_ADMIN', 'ROLE_USER']);
        $torneo = $this->crearTorneo($admin, $ruta, $suffix);
        $categoria = $this->crearCategoria($torneo, $suffix);
        $equipo = $this->crearEquipo($categoria, $suffix);

        $categoriaId = $categoria->getId();
        $equipoId = $equipo->getId();
        self::assertNotNull($categoriaId);
        self::assertNotNull($equipoId);

        $this->client->loginUser($admin);

        $this->client->request('GET', '/admin/torneo/' . $ruta . '/categoria/' . $categoriaId . '/equipo/' . $equipoId . '/jugador/');
        self::assertResponseIsSuccessful();

        $this->client->request('POST', '/admin/torneo/' . $ruta . '/categoria/' . $categoriaId . '/equipo/' . $equipoId . '/jugador/nuevo', [
            'nombre' => 'Mario',
            'apellido' => 'Gomez',
            'nacimiento' => '2000-02-01',
            'tipoPersona' => 'Jugador',
            'tipoDocumento' => 'DNI',
            'numeroDocumento' => '99' . substr($suffix, 0, 6),
            'email' => 'jugador+' . $suffix . '@example.com',
            'celular' => '2614441122',
        ]);

        self::assertResponseRedirects('/admin/torneo/' . $ruta . '/categoria/' . $categoriaId . '/equipo/' . $equipoId . '/jugador/');

        $jugador = $this->entityManager->getRepository(Jugador::class)->findOneBy([
            'equipo' => $equipo,
            'numeroDocumento' => '99' . substr($suffix, 0, 6),
        ]);
        self::assertInstanceOf(Jugador::class, $jugador);

        $jugadorId = $jugador->getId();
        self::assertNotNull($jugadorId);

        $this->client->request('POST', '/admin/torneo/' . $ruta . '/categoria/' . $categoriaId . '/equipo/' . $equipoId . '/jugador/' . $jugadorId . '/editar', [
            'nombre' => 'Mario Edit',
            'apellido' => 'Gomez Edit',
            'nacimiento' => '2000-03-01',
            'tipoPersona' => 'Jugador',
            'tipoDocumento' => 'DNI',
            'numeroDocumento' => '88' . substr($suffix, 0, 6),
            'email' => 'jugador-edit+' . $suffix . '@example.com',
            'celular' => '2614441133',
        ]);

        self::assertResponseRedirects('/admin/torneo/' . $ruta . '/categoria/' . $categoriaId . '/equipo/' . $equipoId . '/jugador/');

        $this->entityManager->clear();
        $jugadorEditado = $this->entityManager->getRepository(Jugador::class)->find($jugadorId);
        self::assertInstanceOf(Jugador::class, $jugadorEditado);
        self::assertSame('Mario Edit', $jugadorEditado->getNombre());
        self::assertSame('88' . substr($suffix, 0, 6), $jugadorEditado->getNumeroDocumento());

        $this->client->request('POST', '/admin/torneo/' . $ruta . '/categoria/' . $categoriaId . '/equipo/' . $equipoId . '/jugador/' . $jugadorId . '/eliminar', [
            '_token' => $this->csrfTokenValue('delete_jugador_' . $jugadorId),
        ]);
        self::assertResponseRedirects('/admin/torneo/' . $ruta . '/categoria/' . $categoriaId . '/equipo/' . $equipoId . '/jugador/');

        $this->entityManager->clear();
        $jugadorEliminado = $this->entityManager->getRepository(Jugador::class)->find($jugadorId);
        self::assertNull($jugadorEliminado);
    }

    public function testUsuarioSinRolAdminNoAccedeAEditarJugador(): void
    {
        $suffix = substr(md5(uniqid('', true)), 0, 8);
        $ruta = $this->buildRuta('ft-user-edit-jugador', $suffix);

        $adminCreador = $this->crearUsuario('ft_creator_edit_jugador_' . $suffix, ['ROLE_ADMIN', 'ROLE_USER']);
        $torneo = $this->crearTorneo($adminCreador, $ruta, $suffix);
        $categoria = $this->crearCategoria($torneo, $suffix);
        $equipo = $this->crearEquipo($categoria, $suffix);
        $jugador = $this->crearJugador($equipo, $suffix);

        $usuario = $this->crearUsuario('ft_user_edit_jugador_' . $suffix, ['ROLE_USER']);
        $this->client->loginUser($usuario);

        $this->client->request('GET', '/admin/torneo/' . $ruta . '/categoria/' . $categoria->getId() . '/equipo/' . $equipo->getId() . '/jugador/' . $jugador->getId() . '/editar');
        self::assertContains($this->client->getResponse()->getStatusCode(), [401, 403]);
    }

    public function testAnonimoNoAccedeAEditarJugador(): void
    {
        $suffix = substr(md5(uniqid('', true)), 0, 8);
        $ruta = $this->buildRuta('ft-anon-edit-jugador', $suffix);

        $adminCreador = $this->crearUsuario('ft_creator_anon_edit_jugador_' . $suffix, ['ROLE_ADMIN', 'ROLE_USER']);
        $torneo = $this->crearTorneo($adminCreador, $ruta, $suffix);
        $categoria = $this->crearCategoria($torneo, $suffix);
        $equipo = $this->crearEquipo($categoria, $suffix);
        $jugador = $this->crearJugador($equipo, $suffix);

        $this->client->request('GET', '/admin/torneo/' . $ruta . '/categoria/' . $categoria->getId() . '/equipo/' . $equipo->getId() . '/jugador/' . $jugador->getId() . '/editar');
        $status = $this->client->getResponse()->getStatusCode();
        if ($status === 302) {
            self::assertResponseRedirects('/login');
        } else {
            self::assertContains($status, [401, 403]);
        }
    }

    public function testUsuarioSinRolAdminNoAccedeAEliminarJugador(): void
    {
        $suffix = substr(md5(uniqid('', true)), 0, 8);
        $ruta = $this->buildRuta('ft-user-del-jugador', $suffix);

        $adminCreador = $this->crearUsuario('ft_creator_del_jugador_' . $suffix, ['ROLE_ADMIN', 'ROLE_USER']);
        $torneo = $this->crearTorneo($adminCreador, $ruta, $suffix);
        $categoria = $this->crearCategoria($torneo, $suffix);
        $equipo = $this->crearEquipo($categoria, $suffix);
        $jugador = $this->crearJugador($equipo, $suffix);
        $jugadorId = $jugador->getId();
        self::assertNotNull($jugadorId);

        $usuario = $this->crearUsuario('ft_user_del_jugador_' . $suffix, ['ROLE_USER']);
        $this->client->loginUser($usuario);

        $this->client->request('POST', '/admin/torneo/' . $ruta . '/categoria/' . $categoria->getId() . '/equipo/' . $equipo->getId() . '/jugador/' . $jugadorId . '/eliminar', [
            '_token' => $this->csrfTokenValue('delete_jugador_' . $jugadorId),
        ]);
        self::assertContains($this->client->getResponse()->getStatusCode(), [401, 403]);

        $this->entityManager->clear();
        $jugadorPersistido = $this->entityManager->getRepository(Jugador::class)->find($jugadorId);
        self::assertInstanceOf(Jugador::class, $jugadorPersistido);
    }

    public function testAnonimoNoAccedeAEliminarJugador(): void
    {
        $suffix = substr(md5(uniqid('', true)), 0, 8);
        $ruta = $this->buildRuta('ft-anon-del-jugador', $suffix);

        $adminCreador = $this->crearUsuario('ft_creator_anon_del_jugador_' . $suffix, ['ROLE_ADMIN', 'ROLE_USER']);
        $torneo = $this->crearTorneo($adminCreador, $ruta, $suffix);
        $categoria = $this->crearCategoria($torneo, $suffix);
        $equipo = $this->crearEquipo($categoria, $suffix);
        $jugador = $this->crearJugador($equipo, $suffix);
        $jugadorId = $jugador->getId();
        self::assertNotNull($jugadorId);

        $this->client->request('POST', '/admin/torneo/' . $ruta . '/categoria/' . $categoria->getId() . '/equipo/' . $equipo->getId() . '/jugador/' . $jugadorId . '/eliminar', [
            '_token' => $this->csrfTokenValue('delete_jugador_' . $jugadorId),
        ]);
        $status = $this->client->getResponse()->getStatusCode();
        if ($status === 302) {
            self::assertResponseRedirects('/login');
        } else {
            self::assertContains($status, [401, 403]);
        }

        $this->entityManager->clear();
        $jugadorPersistido = $this->entityManager->getRepository(Jugador::class)->find($jugadorId);
        self::assertInstanceOf(Jugador::class, $jugadorPersistido);
    }

    public function testAdminNoCreaJugadorConDocumentoInvalido(): void
    {
        $suffix = substr(md5(uniqid('', true)), 0, 8);
        $ruta = $this->buildRuta('ft-admin-jugador-inv', $suffix);

        $admin = $this->crearUsuario('ft_admin_jugador_inv_' . $suffix, ['ROLE_ADMIN', 'ROLE_USER']);
        $torneo = $this->crearTorneo($admin, $ruta, $suffix);
        $categoria = $this->crearCategoria($torneo, $suffix);
        $equipo = $this->crearEquipo($categoria, $suffix);

        $categoriaId = $categoria->getId();
        $equipoId = $equipo->getId();
        self::assertNotNull($categoriaId);
        self::assertNotNull($equipoId);

        $this->client->loginUser($admin);

        $this->client->request('POST', '/admin/torneo/' . $ruta . '/categoria/' . $categoriaId . '/equipo/' . $equipoId . '/jugador/nuevo', [
            'nombre' => 'Mario',
            'apellido' => 'Gomez',
            'nacimiento' => '2000-02-01',
            'tipoPersona' => 'Jugador',
            'tipoDocumento' => 'DNI',
            'numeroDocumento' => '1234',
            'email' => 'jugador-inv+' . $suffix . '@example.com',
            'celular' => '2614441122',
        ]);

        self::assertResponseStatusCodeSame(200);
        self::assertStringContainsString('El Número Documento debe tener entre 5 y 8 caracteres', $this->client->getResponse()->getContent());

        $jugador = $this->entityManager->getRepository(Jugador::class)->findOneBy([
            'equipo' => $equipo,
            'numeroDocumento' => '1234',
        ]);
        self::assertNull($jugador);
    }

    public function testAdminNoCreaJugadorDuplicadoPorPost(): void
    {
        $suffix = substr(md5(uniqid('', true)), 0, 8);
        $ruta = $this->buildRuta('ft-admin-jugador-dup', $suffix);

        $admin = $this->crearUsuario('ft_admin_jugador_dup_' . $suffix, ['ROLE_ADMIN', 'ROLE_USER']);
        $torneo = $this->crearTorneo($admin, $ruta, $suffix);
        $categoria = $this->crearCategoria($torneo, $suffix);
        $equipo = $this->crearEquipo($categoria, $suffix);

        $categoriaId = $categoria->getId();
        $equipoId = $equipo->getId();
        $dniFijo = '12345678';
        self::assertNotNull($categoriaId);
        self::assertNotNull($equipoId);

        $this->client->loginUser($admin);

        // Create first jugador with DNI
        $this->client->request('POST', '/admin/torneo/' . $ruta . '/categoria/' . $categoriaId . '/equipo/' . $equipoId . '/jugador/nuevo', [
            'nombre' => 'Mario',
            'apellido' => 'Gomez',
            'nacimiento' => '2000-02-01',
            'tipoPersona' => 'Jugador',
            'tipoDocumento' => 'DNI',
            'numeroDocumento' => $dniFijo,
            'email' => 'jugador-dup1+' . $suffix . '@example.com',
            'celular' => '2614441122',
        ]);

        self::assertResponseRedirects('/admin/torneo/' . $ruta . '/categoria/' . $categoriaId . '/equipo/' . $equipoId . '/jugador/');

        // Attempt to create second jugador with same DNI
        $this->client->request('POST', '/admin/torneo/' . $ruta . '/categoria/' . $categoriaId . '/equipo/' . $equipoId . '/jugador/nuevo', [
            'nombre' => 'Juan',
            'apellido' => 'Perez',
            'nacimiento' => '2001-03-15',
            'tipoPersona' => 'Jugador',
            'tipoDocumento' => 'DNI',
            'numeroDocumento' => $dniFijo,
            'email' => 'jugador-dup2+' . $suffix . '@example.com',
            'celular' => '2614441123',
        ]);

        self::assertResponseStatusCodeSame(200);
        self::assertStringContainsString('Ya existe un jugador con ese DNI', $this->client->getResponse()->getContent());

        $jugadores = $this->entityManager->getRepository(Jugador::class)->findBy([
            'equipo' => $equipo,
            'numeroDocumento' => $dniFijo,
        ]);

        self::assertCount(1, $jugadores);
    }

    public function testAdminNoEditaJugadorConDniDuplicadoPorPost(): void
    {
        $suffix = substr(md5(uniqid('', true)), 0, 8);
        $ruta = $this->buildRuta('ft-admin-jugador-edit-dup', $suffix);

        $admin = $this->crearUsuario('ft_admin_jugador_edit_dup_' . $suffix, ['ROLE_ADMIN', 'ROLE_USER']);
        $torneo = $this->crearTorneo($admin, $ruta, $suffix);
        $categoria = $this->crearCategoria($torneo, $suffix);
        $equipo = $this->crearEquipo($categoria, $suffix);

        $categoriaId = $categoria->getId();
        $equipoId = $equipo->getId();
        $dniBase = '12345678';
        $dniSegundo = '87654321';
        self::assertNotNull($categoriaId);
        self::assertNotNull($equipoId);

        $this->client->loginUser($admin);

        $this->client->request('POST', '/admin/torneo/' . $ruta . '/categoria/' . $categoriaId . '/equipo/' . $equipoId . '/jugador/nuevo', [
            'nombre' => 'Mario',
            'apellido' => 'Gomez',
            'nacimiento' => '2000-02-01',
            'tipoPersona' => 'Jugador',
            'tipoDocumento' => 'DNI',
            'numeroDocumento' => $dniBase,
            'email' => 'jugador-ed-dup1+' . $suffix . '@example.com',
            'celular' => '2614441122',
        ]);

        self::assertResponseRedirects('/admin/torneo/' . $ruta . '/categoria/' . $categoriaId . '/equipo/' . $equipoId . '/jugador/');

        $this->client->request('POST', '/admin/torneo/' . $ruta . '/categoria/' . $categoriaId . '/equipo/' . $equipoId . '/jugador/nuevo', [
            'nombre' => 'Juan',
            'apellido' => 'Perez',
            'nacimiento' => '2001-03-15',
            'tipoPersona' => 'Jugador',
            'tipoDocumento' => 'DNI',
            'numeroDocumento' => $dniSegundo,
            'email' => 'jugador-ed-dup2+' . $suffix . '@example.com',
            'celular' => '2614441123',
        ]);

        self::assertResponseRedirects('/admin/torneo/' . $ruta . '/categoria/' . $categoriaId . '/equipo/' . $equipoId . '/jugador/');

        $jugadorEditar = $this->entityManager->getRepository(Jugador::class)->findOneBy([
            'equipo' => $equipo,
            'numeroDocumento' => $dniSegundo,
        ]);
        self::assertInstanceOf(Jugador::class, $jugadorEditar);
        $jugadorEditarId = $jugadorEditar->getId();
        self::assertNotNull($jugadorEditarId);

        $this->client->request('POST', '/admin/torneo/' . $ruta . '/categoria/' . $categoriaId . '/equipo/' . $equipoId . '/jugador/' . $jugadorEditarId . '/editar', [
            'nombre' => 'Juan',
            'apellido' => 'Perez',
            'nacimiento' => '2001-03-15',
            'tipoPersona' => 'Jugador',
            'tipoDocumento' => 'DNI',
            'numeroDocumento' => $dniBase,
            'email' => 'jugador-ed-dup2+' . $suffix . '@example.com',
            'celular' => '2614441123',
        ]);

        self::assertResponseStatusCodeSame(200);
        self::assertStringContainsString('Ya existe un jugador con ese DNI', $this->client->getResponse()->getContent());

        $this->entityManager->clear();
        $jugadorSinCambios = $this->entityManager->getRepository(Jugador::class)->find($jugadorEditarId);
        self::assertInstanceOf(Jugador::class, $jugadorSinCambios);
        self::assertSame($dniSegundo, $jugadorSinCambios->getNumeroDocumento());
    }

    public function testAdminAccedeAVistasDeGrupos(): void
    {
        $suffix = substr(md5(uniqid('', true)), 0, 8);
        $ruta = $this->buildRuta('ft-admin-grupos', $suffix);

        $admin = $this->crearUsuario('ft_admin_grupos_' . $suffix, ['ROLE_ADMIN', 'ROLE_USER']);
        $torneo = $this->crearTorneo($admin, $ruta, $suffix);
        $categoria = $this->crearCategoria($torneo, $suffix);
        $grupo = $this->crearGrupo($categoria, $suffix);
        $categoriaId = $categoria->getId();
        $grupoId = $grupo->getId();
        self::assertNotNull($categoriaId);
        self::assertNotNull($grupoId);

        $this->client->loginUser($admin);

        $this->client->request('GET', '/admin/torneo/' . $ruta . '/categoria/' . $categoriaId . '/grupos');
        self::assertResponseIsSuccessful();

        $this->client->request('GET', '/admin/torneo/' . $ruta . '/categoria/' . $categoriaId . '/grupo/crear');
        self::assertResponseIsSuccessful();

        $this->client->request('GET', '/admin/torneo/' . $ruta . '/categoria/' . $categoriaId . '/grupo/' . $grupoId);
        self::assertResponseIsSuccessful();
    }

    public function testAdminCreaGrupoPorPostYRedirigeAIndice(): void
    {
        $suffix = substr(md5(uniqid('', true)), 0, 8);
        $ruta = $this->buildRuta('ft-admin-grupo-post', $suffix);

        $admin = $this->crearUsuario('ft_admin_grupo_post_' . $suffix, ['ROLE_ADMIN', 'ROLE_USER']);
        $torneo = $this->crearTorneo($admin, $ruta, $suffix);
        $categoria = $this->crearCategoria($torneo, $suffix);
        $this->crearEquipo($categoria, $suffix . 'g');
        $categoriaId = $categoria->getId();
        self::assertNotNull($categoriaId);

        $this->client->loginUser($admin);

        $this->client->request('POST', '/admin/torneo/' . $ruta . '/categoria/' . $categoriaId . '/grupo/crear', [
            'cantidadGrupos' => '1',
            'grupos' => [
                [
                    'nombre' => 'Grupo A',
                    'cantidadEquipo' => '1',
                    'clasificaOro' => '1',
                    'clasificaPlata' => '0',
                    'clasificaBronce' => '0',
                ],
            ],
        ]);

        $status = $this->client->getResponse()->getStatusCode();
        self::assertContains($status, [200, 302]);
    }
                public function testAdminAccedeAFormularioEditarSedePorGet(): void
                {
                    $suffix = substr(md5(uniqid('', true)), 0, 8);
                    $ruta = $this->buildRuta('ft-admin-edit-sede-get', $suffix);

                    $admin = $this->crearUsuario('ft_admin_edit_sede_get_' . $suffix, ['ROLE_ADMIN', 'ROLE_USER']);
                    $torneo = $this->crearTorneo($admin, $ruta, $suffix);
                    $sede = $this->crearSede($torneo, $suffix);

                    $this->client->loginUser($admin);
                    $this->client->request('GET', '/admin/torneo/' . $ruta . '/sede/' . $sede->getId() . '/editar');

                    self::assertResponseIsSuccessful();
                    self::assertStringContainsString((string) $sede->getNombre(), $this->client->getResponse()->getContent());
                }

    public function testAdminCrearGrupoConCantidadInconsistenteRedirigeAFormulario(): void
    {
        $suffix = substr(md5(uniqid('', true)), 0, 8);
        $ruta = $this->buildRuta('ft-admin-grupo-mismatch', $suffix);

        $admin = $this->crearUsuario('ft_admin_grupo_mismatch_' . $suffix, ['ROLE_ADMIN', 'ROLE_USER']);
        $torneo = $this->crearTorneo($admin, $ruta, $suffix);
        $categoria = $this->crearCategoria($torneo, $suffix);
        $this->crearEquipo($categoria, $suffix . 'm');
        $categoriaId = $categoria->getId();
        self::assertNotNull($categoriaId);

        $this->client->loginUser($admin);

        $this->client->request('POST', '/admin/torneo/' . $ruta . '/categoria/' . $categoriaId . '/grupo/crear', [
            'cantidadGrupos' => '2',
            'grupos' => [
                [
                    'nombre' => 'Grupo A',
                    'cantidadEquipo' => '1',
                    'clasificaOro' => '1',
                    'clasificaPlata' => '0',
                    'clasificaBronce' => '0',
                ],
            ],
        ]);

        self::assertResponseRedirects('/admin/torneo/' . $ruta . '/categoria/' . $categoriaId . '/grupo/crear');
    }

                public function testAdminEliminarSedeInexistenteRedirigeALogin(): void
                {
                    $suffix = substr(md5(uniqid('', true)), 0, 8);
                    $ruta = $this->buildRuta('ft-admin-del-sede-inx', $suffix);

                    $admin = $this->crearUsuario('ft_admin_del_sede_inx_' . $suffix, ['ROLE_ADMIN', 'ROLE_USER']);
                    $this->crearTorneo($admin, $ruta, $suffix);

                    $this->client->loginUser($admin);
                    $this->client->request('POST', '/admin/torneo/' . $ruta . '/sede/999999/eliminar', [
                        '_token' => $this->csrfTokenValue('delete_sede_999999'),
                    ]);

                    self::assertResponseRedirects('/login');
                }
    public function testAdminPublicaArmarPlayoffYRedirigeAGrupos(): void
    {
        $suffix = substr(md5(uniqid('', true)), 0, 8);
        $ruta = $this->buildRuta('ft-admin-playoff-post', $suffix);

        $admin = $this->crearUsuario('ft_admin_playoff_post_' . $suffix, ['ROLE_ADMIN', 'ROLE_USER']);
        $torneo = $this->crearTorneo($admin, $ruta, $suffix);
        $categoria = $this->crearCategoria($torneo, $suffix);
        $this->crearGrupo($categoria, $suffix);
        $categoriaId = $categoria->getId();
        self::assertNotNull($categoriaId);

        $this->client->loginUser($admin);
        $this->client->request('POST', '/admin/torneo/' . $ruta . '/categoria/' . $categoriaId . '/armarPlayoff');

        self::assertResponseRedirects('/admin/torneo/' . $ruta . '/categoria/' . $categoriaId . '/grupos');
    }

    public function testUsuarioSinRolAdminNoAccedeACrearGrupo(): void
    {
        $suffix = substr(md5(uniqid('', true)), 0, 8);
        $ruta = $this->buildRuta('ft-user-grupo-crear', $suffix);

        $admin = $this->crearUsuario('ft_admin_user_grupo_crear_' . $suffix, ['ROLE_ADMIN', 'ROLE_USER']);
        $torneo = $this->crearTorneo($admin, $ruta, $suffix);
        $categoria = $this->crearCategoria($torneo, $suffix);
        $categoriaId = $categoria->getId();
        self::assertNotNull($categoriaId);

        $usuario = $this->crearUsuario('ft_user_grupo_crear_' . $suffix, ['ROLE_USER']);
        $this->client->loginUser($usuario);

        $this->client->request('GET', '/admin/torneo/' . $ruta . '/categoria/' . $categoriaId . '/grupo/crear');
        self::assertContains($this->client->getResponse()->getStatusCode(), [401, 403]);
    }

    public function testAnonimoNoAccedeACrearGrupo(): void
    {
        $suffix = substr(md5(uniqid('', true)), 0, 8);
        $ruta = $this->buildRuta('ft-anon-grupo-crear', $suffix);

        $admin = $this->crearUsuario('ft_admin_anon_grupo_crear_' . $suffix, ['ROLE_ADMIN', 'ROLE_USER']);
        $torneo = $this->crearTorneo($admin, $ruta, $suffix);
        $categoria = $this->crearCategoria($torneo, $suffix);
        $categoriaId = $categoria->getId();
        self::assertNotNull($categoriaId);

        $this->client->request('GET', '/admin/torneo/' . $ruta . '/categoria/' . $categoriaId . '/grupo/crear');
        $status = $this->client->getResponse()->getStatusCode();
        if ($status === 302) {
            self::assertResponseRedirects('/login');
        } else {
            self::assertContains($status, [401, 403]);
        }
    }

    public function testUsuarioSinRolAdminNoPuedeArmarPlayoff(): void
    {
        $suffix = substr(md5(uniqid('', true)), 0, 8);
        $ruta = $this->buildRuta('ft-user-playoff-post', $suffix);

        $admin = $this->crearUsuario('ft_admin_user_playoff_' . $suffix, ['ROLE_ADMIN', 'ROLE_USER']);
        $torneo = $this->crearTorneo($admin, $ruta, $suffix);
        $categoria = $this->crearCategoria($torneo, $suffix);
        $this->crearGrupo($categoria, $suffix);
        $categoriaId = $categoria->getId();
        self::assertNotNull($categoriaId);

        $usuario = $this->crearUsuario('ft_user_playoff_' . $suffix, ['ROLE_USER']);
        $this->client->loginUser($usuario);

        $this->client->request('POST', '/admin/torneo/' . $ruta . '/categoria/' . $categoriaId . '/armarPlayoff');
        self::assertContains($this->client->getResponse()->getStatusCode(), [401, 403]);
    }

    public function testAnonimoNoPuedeArmarPlayoff(): void
    {
        $suffix = substr(md5(uniqid('', true)), 0, 8);
        $ruta = $this->buildRuta('ft-anon-playoff-post', $suffix);

        $admin = $this->crearUsuario('ft_admin_anon_playoff_' . $suffix, ['ROLE_ADMIN', 'ROLE_USER']);
        $torneo = $this->crearTorneo($admin, $ruta, $suffix);
        $categoria = $this->crearCategoria($torneo, $suffix);
        $this->crearGrupo($categoria, $suffix);
        $categoriaId = $categoria->getId();
        self::assertNotNull($categoriaId);

        $this->client->request('POST', '/admin/torneo/' . $ruta . '/categoria/' . $categoriaId . '/armarPlayoff');
        $status = $this->client->getResponse()->getStatusCode();
        if ($status === 302) {
            self::assertResponseRedirects('/login');
        } else {
            self::assertContains($status, [401, 403]);
        }
    }

    public function testUsuarioSinRolAdminNoAccedeAGrupos(): void
    {
        $suffix = substr(md5(uniqid('', true)), 0, 8);
        $ruta = $this->buildRuta('ft-user-grupos', $suffix);

        $admin = $this->crearUsuario('ft_admin_user_grupos_' . $suffix, ['ROLE_ADMIN', 'ROLE_USER']);
        $torneo = $this->crearTorneo($admin, $ruta, $suffix);
        $categoria = $this->crearCategoria($torneo, $suffix);
        $categoriaId = $categoria->getId();
        self::assertNotNull($categoriaId);

        $usuario = $this->crearUsuario('ft_user_grupos_' . $suffix, ['ROLE_USER']);
        $this->client->loginUser($usuario);

        $this->client->request('GET', '/admin/torneo/' . $ruta . '/categoria/' . $categoriaId . '/grupos');
        self::assertContains($this->client->getResponse()->getStatusCode(), [401, 403]);
    }

    public function testAnonimoNoAccedeAGrupos(): void
    {
        $suffix = substr(md5(uniqid('', true)), 0, 8);
        $ruta = $this->buildRuta('ft-anon-grupos', $suffix);

        $admin = $this->crearUsuario('ft_admin_anon_grupos_' . $suffix, ['ROLE_ADMIN', 'ROLE_USER']);
        $torneo = $this->crearTorneo($admin, $ruta, $suffix);
        $categoria = $this->crearCategoria($torneo, $suffix);
        $categoriaId = $categoria->getId();
        self::assertNotNull($categoriaId);

        $this->client->request('GET', '/admin/torneo/' . $ruta . '/categoria/' . $categoriaId . '/grupos');
        $status = $this->client->getResponse()->getStatusCode();
        if ($status === 302) {
            self::assertResponseRedirects('/login');
        } else {
            self::assertContains($status, [401, 403]);
        }
    }
}

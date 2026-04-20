<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use App\Entity\Categoria;
use App\Entity\Sede;
use App\Entity\Torneo;
use App\Entity\Usuario;

class AdminBusinessFlowTorneoUsuarioFunctionalTest extends AdminBusinessFlowFunctionalTestCase
{
    public function testAdminAccedeAListaDeTorneos(): void
    {
        $admin = $this->crearUsuario('admin_torneo_list', ['ROLE_ADMIN', 'ROLE_USER']);

        $this->client->loginUser($admin);
        $this->client->request('GET', '/admin/torneo/');

        self::assertResponseIsSuccessful();
        self::assertStringContainsString('Torneos', $this->client->getResponse()->getContent());
    }

    public function testNoAdminNoPuedeAccederANuevoTorneoYRedirige(): void
    {
        $usuario = $this->crearUsuario('no_admin_torneos', ['ROLE_USER']);

        $this->client->loginUser($usuario);
        $this->client->request('GET', '/admin/torneo/nuevo');

        self::assertResponseStatusCodeSame(403);
    }

    public function testAdminAccedeAFormularioCreacionTorneosPorGet(): void
    {
        $suffix = substr(md5(uniqid('', true)), 0, 8);
        $admin = $this->crearUsuario('admin_torneo_crear_get_' . $suffix, ['ROLE_ADMIN', 'ROLE_USER']);

        $this->client->loginUser($admin);
        $this->client->request('GET', '/admin/torneo/nuevo');

        self::assertResponseIsSuccessful();
        self::assertStringContainsString('Nombre', $this->client->getResponse()->getContent());
    }

    public function testAdminCreaTorneoNuevo(): void
    {
        $suffix = substr(md5(uniqid('', true)), 0, 8);
        $ruta = $this->buildRuta('ft-admin-create-torneo', $suffix);
        $admin = $this->crearUsuario('admin_torneo_crear_' . $suffix, ['ROLE_ADMIN', 'ROLE_USER']);

        $this->client->loginUser($admin);
        
        $fechaInicioTorneo = '2026-02-01 10:00';
        $fechaFinTorneo = '2026-02-20 18:00';
        $fechaInicioInscripcion = '2026-01-01 10:00';
        $fechaFinInscripcion = '2026-01-10 18:00';
        
        $this->client->request('POST', '/admin/torneo/nuevo', [
            'nombre' => 'FT Torneo Nuevo ' . $suffix,
            'ruta' => $ruta,
            'descripcion' => 'Torneo de prueba funcional',
            'fechaInicioTorneo' => $fechaInicioTorneo,
            'fechaFinTorneo' => $fechaFinTorneo,
            'fechaInicioInscripcion' => $fechaInicioInscripcion,
            'fechaFinInscripcion' => $fechaFinInscripcion,
        ]);

        self::assertResponseRedirects('/admin/torneo/');

        $torneoCreado = $this->entityManager->getRepository(Torneo::class)->findOneBy(['ruta' => $ruta]);
        self::assertInstanceOf(Torneo::class, $torneoCreado);
        self::assertSame('FT Torneo Nuevo ' . $suffix, $torneoCreado->getNombre());
    }

    public function testAdminNoCreaTorneoConRutaDuplicadaYMuestraError(): void
    {
        $suffix = substr(md5(uniqid('', true)), 0, 8);
        $ruta = $this->buildRuta('ft-admin-create-torneo-dup', $suffix);
        $admin = $this->crearUsuario('admin_torneo_dup_' . $suffix, ['ROLE_ADMIN', 'ROLE_USER']);

        $this->crearTorneo($admin, $ruta, $suffix);

        $this->client->loginUser($admin);
        $this->client->request('POST', '/admin/torneo/nuevo', [
            'nombre' => 'FT Torneo Nuevo Duplicado ' . $suffix,
            'ruta' => $ruta,
            'descripcion' => 'Torneo duplicado funcional',
            'fechaInicioTorneo' => '2026-02-01 10:00',
            'fechaFinTorneo' => '2026-02-20 18:00',
            'fechaInicioInscripcion' => '2026-01-01 10:00',
            'fechaFinInscripcion' => '2026-01-10 18:00',
        ]);

        self::assertResponseStatusCodeSame(200);
        self::assertStringContainsString('La ruta ya se encuentra registrada', $this->client->getResponse()->getContent());

        $torneos = $this->entityManager->getRepository(Torneo::class)->findBy(['ruta' => $ruta]);
        self::assertCount(1, $torneos);
    }

    public function testAdminAccedeAFormularioEditarTorneoPorGet(): void
    {
        $suffix = substr(md5(uniqid('', true)), 0, 8);
        $ruta = $this->buildRuta('ft-admin-edit-torneo', $suffix);
        $admin = $this->crearUsuario('admin_torneo_editar_get_' . $suffix, ['ROLE_ADMIN', 'ROLE_USER']);
        $torneo = $this->crearTorneo($admin, $ruta, $suffix);

        $this->client->loginUser($admin);
        $this->client->request('GET', '/admin/torneo/' . $ruta . '/editar');

        self::assertResponseIsSuccessful();
        self::assertStringContainsString($torneo->getNombre(), $this->client->getResponse()->getContent());
    }

    public function testAdminEditaTorneoExistenteYRedirige(): void
    {
        $suffix = substr(md5(uniqid('', true)), 0, 8);
        $ruta = $this->buildRuta('ft-admin-edit-torneo-post', $suffix);
        $admin = $this->crearUsuario('admin_torneo_editar_post_' . $suffix, ['ROLE_ADMIN', 'ROLE_USER']);
        $torneo = $this->crearTorneo($admin, $ruta, $suffix);
        $torneoId = $torneo->getId();
        self::assertNotNull($torneoId);

        $this->client->loginUser($admin);
        $this->client->request('POST', '/admin/torneo/' . $ruta . '/editar', [
            'nombre' => 'Torneo Editado ' . $suffix,
            'ruta' => $ruta,
            'descripcion' => 'Descripción actualizada',
            'fechaInicioTorneo' => '2026-02-01',
            'horaInicioTorneo' => '10:00',
            'fechaFinTorneo' => '2026-02-20',
            'horaFinTorneo' => '18:00',
            'fechaInicioInscripcion' => '2026-01-01',
            'horaInicioInscripcion' => '10:00',
            'fechaFinInscripcion' => '2026-01-10',
            'horaFinInscripcion' => '18:00',
        ]);

        self::assertResponseRedirects('/admin/torneo/');

        $this->entityManager->clear();
        $torneoActualizado = $this->entityManager->getRepository(Torneo::class)->find($torneoId);
        self::assertInstanceOf(Torneo::class, $torneoActualizado);
        self::assertSame('Torneo Editado ' . $suffix, $torneoActualizado->getNombre());
    }

    public function testAdminNoEditaTorneoConRutaDuplicadaYMuestraError(): void
    {
        $suffix = substr(md5(uniqid('', true)), 0, 8);
        $rutaOriginal = 'it-edit-a-' . $suffix;
        $rutaDestino = 'it-edit-b-' . $suffix;
        $admin = $this->crearUsuario('admin_torneo_editar_dup_' . $suffix, ['ROLE_ADMIN', 'ROLE_USER']);

        $torneoOriginal = $this->crearTorneo($admin, $rutaOriginal, 'orig-' . $suffix);
        $this->crearTorneo($admin, $rutaDestino, 'dup-' . $suffix);

        $torneoOriginalId = $torneoOriginal->getId();
        self::assertNotNull($torneoOriginalId);

        $this->client->loginUser($admin);
        $this->client->request('POST', '/admin/torneo/' . $rutaOriginal . '/editar', [
            'nombre' => 'Torneo Editado Duplicado ' . $suffix,
            'ruta' => $rutaDestino,
            'descripcion' => 'Descripción actualizada funcional',
            'fechaInicioTorneo' => '2026-02-01',
            'horaInicioTorneo' => '10:00',
            'fechaFinTorneo' => '2026-02-20',
            'horaFinTorneo' => '18:00',
            'fechaInicioInscripcion' => '2026-01-01',
            'horaInicioInscripcion' => '10:00',
            'fechaFinInscripcion' => '2026-01-10',
            'horaFinInscripcion' => '18:00',
        ]);

        self::assertResponseStatusCodeSame(200);
        self::assertStringContainsString('La ruta ya se encuentra registrada', $this->client->getResponse()->getContent());

        $this->entityManager->clear();
        $torneoSinCambios = $this->entityManager->getRepository(Torneo::class)->find($torneoOriginalId);
        self::assertInstanceOf(Torneo::class, $torneoSinCambios);
        self::assertSame($rutaOriginal, $torneoSinCambios->getRuta());
    }

    public function testAdminAccedeAFormularioEditarReglamentoPorGet(): void
    {
        $suffix = substr(md5(uniqid('', true)), 0, 8);
        $ruta = $this->buildRuta('ft-admin-regl-get', $suffix);
        $admin = $this->crearUsuario('admin_torneo_regl_get_' . $suffix, ['ROLE_ADMIN', 'ROLE_USER']);
        $this->crearTorneo($admin, $ruta, $suffix);

        $this->client->loginUser($admin);
        $this->client->request('GET', '/admin/torneo/' . $ruta . '/editar/reglamento');

        self::assertResponseIsSuccessful();
        self::assertStringContainsString('Reglamento', $this->client->getResponse()->getContent());
    }

    public function testAdminEditaReglamentoYRedirige(): void
    {
        $suffix = substr(md5(uniqid('', true)), 0, 8);
        $ruta = $this->buildRuta('ft-admin-regl-post', $suffix);
        $admin = $this->crearUsuario('admin_torneo_regl_post_' . $suffix, ['ROLE_ADMIN', 'ROLE_USER']);
        $torneo = $this->crearTorneo($admin, $ruta, $suffix);
        $torneoId = $torneo->getId();
        self::assertNotNull($torneoId);

        $this->client->loginUser($admin);
        $this->client->request('POST', '/admin/torneo/' . $ruta . '/editar/reglamento', [
            'reglamento' => '<p>Reglamento funcional ' . $suffix . '</p>',
        ]);

        self::assertResponseRedirects('/admin/torneo/');

        $this->entityManager->clear();
        $torneoActualizado = $this->entityManager->getRepository(Torneo::class)->find($torneoId);
        self::assertInstanceOf(Torneo::class, $torneoActualizado);
        self::assertStringContainsString('Reglamento funcional', (string) $torneoActualizado->getReglamento());
    }

    public function testAdminNoEditaReglamentoVacioPorPost(): void
    {
        $suffix = substr(md5(uniqid('', true)), 0, 8);
        $ruta = $this->buildRuta('ft-admin-regl-vacio', $suffix);
        $admin = $this->crearUsuario('admin_torneo_regl_vacio_' . $suffix, ['ROLE_ADMIN', 'ROLE_USER']);
        $torneo = $this->crearTorneo($admin, $ruta, $suffix);
        $torneoId = $torneo->getId();
        self::assertNotNull($torneoId);
        
        // Set initial reglamento
        $torneoBaseReglamento = '<p>Reglamento base ' . $suffix . '</p>';
        $torneo->setReglamento($torneoBaseReglamento);
        $this->entityManager->flush();

        $this->client->loginUser($admin);
        
        // Attempt to edit with empty reglamento
        $this->client->request('POST', '/admin/torneo/' . $ruta . '/editar/reglamento', [
            'reglamento' => '',
        ]);

        self::assertResponseStatusCodeSame(200);
        self::assertStringContainsString('El Reglamento no puede estar vacío', $this->client->getResponse()->getContent());

        $this->entityManager->clear();
        $torneoSinCambios = $this->entityManager->getRepository(Torneo::class)->find($torneoId);
        self::assertInstanceOf(Torneo::class, $torneoSinCambios);
        self::assertSame($torneoBaseReglamento, $torneoSinCambios->getReglamento());
    }

    public function testAdminNoEditaReglamentoMuyLargoPorPost(): void
    {
        $suffix = substr(md5(uniqid('', true)), 0, 8);
        $ruta = $this->buildRuta('ft-admin-regl-largo', $suffix);
        $admin = $this->crearUsuario('admin_torneo_regl_largo_' . $suffix, ['ROLE_ADMIN', 'ROLE_USER']);
        $torneo = $this->crearTorneo($admin, $ruta, $suffix);
        $torneoId = $torneo->getId();
        self::assertNotNull($torneoId);
        
        // Set initial reglamento
        $torneoBaseReglamento = '<p>Reglamento base ' . $suffix . '</p>';
        $torneo->setReglamento($torneoBaseReglamento);
        $this->entityManager->flush();

        $this->client->loginUser($admin);
        
        // Attempt to edit with very long reglamento (>5000 chars)
        $reglamentoLargo = '<p>' . str_repeat('A', 5100) . '</p>';
        $this->client->request('POST', '/admin/torneo/' . $ruta . '/editar/reglamento', [
            'reglamento' => $reglamentoLargo,
        ]);

        self::assertResponseStatusCodeSame(200);
        self::assertStringContainsString('El Reglamento no puede exceder 5000 caracteres', $this->client->getResponse()->getContent());

        $this->entityManager->clear();
        $torneoSinCambios = $this->entityManager->getRepository(Torneo::class)->find($torneoId);
        self::assertInstanceOf(Torneo::class, $torneoSinCambios);
        self::assertSame($torneoBaseReglamento, $torneoSinCambios->getReglamento());
    }

    public function testUsuarioSinRolAdminNoAccedeAEditarTorneo(): void
    {
        $suffix = substr(md5(uniqid('', true)), 0, 8);
        $ruta = $this->buildRuta('ft-user-edit-torneo', $suffix);

        $adminCreador = $this->crearUsuario('ft_creator_edit_torneo_' . $suffix, ['ROLE_ADMIN', 'ROLE_USER']);
        $torneo = $this->crearTorneo($adminCreador, $ruta, $suffix);

        $usuario = $this->crearUsuario('ft_user_edit_torneo_' . $suffix, ['ROLE_USER']);
        $this->client->loginUser($usuario);

        $this->client->request('GET', '/admin/torneo/' . $ruta . '/editar');
        $status = $this->client->getResponse()->getStatusCode();
        if ($status === 302) {
            self::assertResponseRedirects('/login');
        } else {
            self::assertContains($status, [401, 403]);
        }
    }

    public function testAnonimoNoAccedeAEditarTorneo(): void
    {
        $suffix = substr(md5(uniqid('', true)), 0, 8);
        $ruta = $this->buildRuta('ft-anon-edit-torneo', $suffix);

        $adminCreador = $this->crearUsuario('ft_creator_anon_edit_torneo_' . $suffix, ['ROLE_ADMIN', 'ROLE_USER']);
        $torneo = $this->crearTorneo($adminCreador, $ruta, $suffix);

        $this->client->request('GET', '/admin/torneo/' . $ruta . '/editar');
        $status = $this->client->getResponse()->getStatusCode();
        if ($status === 302) {
            self::assertResponseRedirects('/login');
        } else {
            self::assertContains($status, [401, 403]);
        }
    }

    public function testAdminNoCreadorNoAccedeAEditarTorneoYRedirigeALogin(): void
    {
        $suffix = substr(md5(uniqid('', true)), 0, 8);
        $ruta = $this->buildRuta('ft-admin-nocreador-edit-torneo', $suffix);

        $adminCreador = $this->crearUsuario('ft_admin_creador_edit_torneo_' . $suffix, ['ROLE_ADMIN', 'ROLE_USER']);
        $this->crearTorneo($adminCreador, $ruta, $suffix);

        $adminNoCreador = $this->crearUsuario('ft_admin_nocreador_edit_torneo_' . $suffix, ['ROLE_ADMIN', 'ROLE_USER']);
        $this->client->loginUser($adminNoCreador);

        $this->client->request('GET', '/admin/torneo/' . $ruta . '/editar');

        self::assertResponseRedirects('/login');
    }

    public function testUsuarioSinRolAdminNoAccedeAEditarReglamento(): void
    {
        $suffix = substr(md5(uniqid('', true)), 0, 8);
        $ruta = $this->buildRuta('ft-user-edit-regl', $suffix);

        $adminCreador = $this->crearUsuario('ft_creator_edit_regl_' . $suffix, ['ROLE_ADMIN', 'ROLE_USER']);
        $torneo = $this->crearTorneo($adminCreador, $ruta, $suffix);

        $usuario = $this->crearUsuario('ft_user_edit_regl_' . $suffix, ['ROLE_USER']);
        $this->client->loginUser($usuario);

        $this->client->request('GET', '/admin/torneo/' . $ruta . '/editar/reglamento');
        $status = $this->client->getResponse()->getStatusCode();
        if ($status === 302) {
            self::assertResponseRedirects('/login');
        } else {
            self::assertContains($status, [401, 403]);
        }
    }

    public function testAnonimoNoAccedeAEditarReglamento(): void
    {
        $suffix = substr(md5(uniqid('', true)), 0, 8);
        $ruta = $this->buildRuta('ft-anon-edit-regl', $suffix);

        $adminCreador = $this->crearUsuario('ft_creator_anon_edit_regl_' . $suffix, ['ROLE_ADMIN', 'ROLE_USER']);
        $torneo = $this->crearTorneo($adminCreador, $ruta, $suffix);

        $this->client->request('GET', '/admin/torneo/' . $ruta . '/editar/reglamento');
        $status = $this->client->getResponse()->getStatusCode();
        if ($status === 302) {
            self::assertResponseRedirects('/login');
        } else {
            self::assertContains($status, [401, 403]);
        }
    }

    public function testAdminNoCreadorNoAccedeAEditarReglamentoYRedirigeALogin(): void
    {
        $suffix = substr(md5(uniqid('', true)), 0, 8);
        $ruta = $this->buildRuta('ft-admin-nocreador-edit-regl', $suffix);

        $adminCreador = $this->crearUsuario('ft_admin_creador_edit_regl_' . $suffix, ['ROLE_ADMIN', 'ROLE_USER']);
        $this->crearTorneo($adminCreador, $ruta, $suffix);

        $adminNoCreador = $this->crearUsuario('ft_admin_nocreador_edit_regl_' . $suffix, ['ROLE_ADMIN', 'ROLE_USER']);
        $this->client->loginUser($adminNoCreador);

        $this->client->request('GET', '/admin/torneo/' . $ruta . '/editar/reglamento');

        self::assertResponseRedirects('/login');
    }

    public function testAdminEliminaTorneoYRedirigeAIndex(): void
    {
        $suffix = substr(md5(uniqid('', true)), 0, 8);
        $ruta = $this->buildRuta('ft-admin-del-torneo', $suffix);
        $admin = $this->crearUsuario('admin_torneo_del_' . $suffix, ['ROLE_ADMIN', 'ROLE_USER']);
        $this->crearTorneo($admin, $ruta, $suffix);

        $this->client->loginUser($admin);
        $this->client->request('POST', '/admin/torneo/' . $ruta . '/eliminar', [
            '_token' => $this->csrfTokenValue('delete_torneo_' . $ruta),
        ]);

        self::assertResponseRedirects('/admin/torneo/');

        $this->entityManager->clear();
        $torneoEliminado = $this->entityManager->getRepository(Torneo::class)->findOneBy(['ruta' => $ruta]);
        self::assertNull($torneoEliminado);
    }

    public function testAdminCreaTorneoConCategoriaYSedeEnMismoPost(): void
    {
        $suffix = substr(md5(uniqid('', true)), 0, 8);
        $ruta = $this->buildRuta('ft-admin-create-torneo-rel', $suffix);
        $admin = $this->crearUsuario('admin_torneo_crear_rel_' . $suffix, ['ROLE_ADMIN', 'ROLE_USER']);

        $this->client->loginUser($admin);

        $this->client->request('POST', '/admin/torneo/nuevo', [
            'nombre' => 'FT Torneo Rel ' . $suffix,
            'ruta' => $ruta,
            'descripcion' => 'Torneo con relaciones en post',
            'fechaInicioTorneo' => '2026-03-01T10:00',
            'fechaFinTorneo' => '2026-03-15T18:00',
            'fechaInicioInscripcion' => '2026-02-01T10:00',
            'fechaFinInscripcion' => '2026-02-20T18:00',
            'categorias' => [
                [
                    'generoId' => 'Masculino',
                    'categoriaNombre' => 'Cat Rel ' . $suffix,
                    'categoriaNombreCorto' => 'CR' . strtoupper(substr($suffix, 0, 4)),
                ],
            ],
            'sedes' => [
                [
                    'sedeNombre' => 'Sede Rel ' . $suffix,
                    'sedeDireccion' => 'Direccion Rel 123',
                ],
            ],
        ]);

        self::assertResponseRedirects('/admin/torneo/');

        $this->entityManager->clear();
        $torneoCreado = $this->entityManager->getRepository(Torneo::class)->findOneBy(['ruta' => $ruta]);
        self::assertInstanceOf(Torneo::class, $torneoCreado);

        $categoriaCreada = $this->entityManager->getRepository(Categoria::class)->findOneBy([
            'torneo' => $torneoCreado,
            'nombre' => 'Cat Rel ' . $suffix,
        ]);
        self::assertInstanceOf(Categoria::class, $categoriaCreada);

        $sedeCreada = $this->entityManager->getRepository(Sede::class)->findOneBy([
            'torneo' => $torneoCreado,
            'nombre' => 'Sede Rel ' . $suffix,
        ]);
        self::assertInstanceOf(Sede::class, $sedeCreada);
    }

    public function testAdminEliminarTorneoInexistenteRedirigeALogin(): void
    {
        $suffix = substr(md5(uniqid('', true)), 0, 8);
        $ruta = $this->buildRuta('ft-admin-del-torneo-inx', $suffix);

        $admin = $this->crearUsuario('admin_torneo_del_inx_' . $suffix, ['ROLE_ADMIN', 'ROLE_USER']);
        $this->client->loginUser($admin);

        $this->client->request('POST', '/admin/torneo/' . $ruta . '/eliminar', [
            '_token' => $this->csrfTokenValue('delete_torneo_' . $ruta),
        ]);

        self::assertResponseRedirects('/login');
    }

    public function testAdminAccedeAFormularioCrearUsuarioPorGet(): void
    {
        $suffix = substr(md5(uniqid('', true)), 0, 8);
        $admin = $this->crearUsuario('admin_usuario_get_' . $suffix, ['ROLE_ADMIN', 'ROLE_USER']);

        $this->client->loginUser($admin);
        $this->client->request('GET', '/admin/usuario/nuevo');

        self::assertResponseIsSuccessful();
    }

    public function testAdminAccedeAIndiceUsuariosYVeUsuarioCreado(): void
    {
        $suffix = substr(md5(uniqid('', true)), 0, 8);
        $admin = $this->crearUsuario('admin_usuario_index_ok_' . $suffix, ['ROLE_ADMIN', 'ROLE_USER']);
        $usuario = $this->crearUsuario('usuario_index_ok_' . $suffix, ['ROLE_USER']);

        $this->client->loginUser($admin);
        $this->client->request('GET', '/admin/usuario/');

        self::assertResponseIsSuccessful();
        self::assertStringContainsString($usuario->getUsername(), $this->client->getResponse()->getContent());
    }

    public function testAdminCreaUsuarioYRedirigeAIndice(): void
    {
        $suffix = substr(md5(uniqid('', true)), 0, 8);
        $admin = $this->crearUsuario('admin_usuario_create_' . $suffix, ['ROLE_ADMIN', 'ROLE_USER']);
        $nuevoUsername = 'usuario_nuevo_' . $suffix;

        $this->client->loginUser($admin);
        $this->client->request('POST', '/admin/usuario/nuevo', [
            'username' => $nuevoUsername,
            'password' => 'NuevaPass123!',
            'nombre' => 'Nombre ' . $suffix,
            'apellido' => 'Apellido ' . $suffix,
            'email' => 'nuevo_' . $suffix . '@example.com',
            'roles' => ['ROLE_ADMIN'],
        ]);

        self::assertResponseRedirects('/admin/usuario/');

        $this->entityManager->clear();
        $usuarioCreado = $this->entityManager->getRepository(Usuario::class)->findOneBy(['username' => $nuevoUsername]);
        self::assertInstanceOf(Usuario::class, $usuarioCreado);
        self::assertContains('ROLE_USER', $usuarioCreado->getRoles());
        self::assertContains('ROLE_ADMIN', $usuarioCreado->getRoles());
    }

    public function testAdminNoCreaUsuarioConCamposObligatoriosIncompletos(): void
    {
        $suffix = substr(md5(uniqid('', true)), 0, 8);
        $admin = $this->crearUsuario('admin_usuario_invalido_' . $suffix, ['ROLE_ADMIN', 'ROLE_USER']);

        $this->client->loginUser($admin);
        $this->client->request('POST', '/admin/usuario/nuevo', [
            'username' => 'usuario_invalido_' . $suffix,
            'password' => 'NuevaPass123!',
            'nombre' => '',
            'apellido' => 'Apellido ' . $suffix,
            'email' => 'invalido_' . $suffix . '@example.com',
            'roles' => ['ROLE_ADMIN'],
        ]);

        self::assertResponseStatusCodeSame(200);
        self::assertStringContainsString('Todos los campos son obligatorios.', $this->client->getResponse()->getContent());

        $usuarioCreado = $this->entityManager->getRepository(Usuario::class)->findOneBy([
            'username' => 'usuario_invalido_' . $suffix,
        ]);
        self::assertNull($usuarioCreado);
    }

    public function testAdminNoCreaUsuarioDuplicadoYMuestraError(): void
    {
        $suffix = substr(md5(uniqid('', true)), 0, 8);
        $admin = $this->crearUsuario('admin_usuario_dup_' . $suffix, ['ROLE_ADMIN', 'ROLE_USER']);
        $usernameDuplicado = 'usuario_dup_' . $suffix;

        $this->crearUsuario($usernameDuplicado, ['ROLE_USER']);

        $this->client->loginUser($admin);
        $this->client->request('POST', '/admin/usuario/nuevo', [
            'username' => $usernameDuplicado,
            'password' => 'NuevaPass123!',
            'nombre' => 'Nombre ' . $suffix,
            'apellido' => 'Apellido ' . $suffix,
            'email' => 'duplicado_' . $suffix . '@example.com',
            'roles' => ['ROLE_ADMIN'],
        ]);

        self::assertResponseStatusCodeSame(200);
        self::assertStringContainsString('El nombre de usuario ya se encuentra registrado', $this->client->getResponse()->getContent());

        $usuarios = $this->entityManager->getRepository(Usuario::class)->findBy([
            'username' => $usernameDuplicado,
        ]);
        self::assertCount(1, $usuarios);
    }

    public function testAdminNoCreaUsuarioDuplicadoEmailYMuestraError(): void
    {
        $suffix = substr(md5(uniqid('', true)), 0, 8);
        $admin = $this->crearUsuario('admin_usuario_dup_email_' . $suffix, ['ROLE_ADMIN', 'ROLE_USER']);
        
        // Create first user - email will be username@example.com
        $usuarioBase = $this->crearUsuario('usuario_base_email_' . $suffix, ['ROLE_USER']);
        $emailDuplicado = $usuarioBase->getEmail();

        $this->client->loginUser($admin);
        $this->client->request('POST', '/admin/usuario/nuevo', [
            'username' => 'usuario_nuevo_' . $suffix,
            'password' => 'NuevaPass123!',
            'nombre' => 'Nombre ' . $suffix,
            'apellido' => 'Apellido ' . $suffix,
            'email' => $emailDuplicado,
            'roles' => ['ROLE_ADMIN'],
        ]);

        self::assertResponseStatusCodeSame(200);
        self::assertStringContainsString('El email ya se encuentra registrado', $this->client->getResponse()->getContent());

        $usuarios = $this->entityManager->getRepository(Usuario::class)->findBy([
            'email' => $emailDuplicado,
        ]);
        self::assertCount(1, $usuarios);
    }

    public function testAdminNoEditaUsuarioDuplicadoUsernameYMuestraError(): void
    {
        $suffix = substr(md5(uniqid('', true)), 0, 8);
        $admin = $this->crearUsuario('admin_usuario_edit_dup_user_' . $suffix, ['ROLE_ADMIN', 'ROLE_USER']);
        $usuarioBase = $this->crearUsuario('usuario_base_user_' . $suffix, ['ROLE_USER']);
        $usuarioObjetivo = $this->crearUsuario('usuario_objetivo_user_' . $suffix, ['ROLE_USER']);
        $usuarioObjetivoId = $usuarioObjetivo->getId();
        self::assertNotNull($usuarioObjetivoId);

        $this->client->loginUser($admin);
        $this->client->request('POST', '/admin/usuario/editar/' . $usuarioObjetivoId, [
            'nombre' => 'Nombre Editado',
            'apellido' => 'Apellido Editado',
            'email' => 'usuario_objetivo_upd_' . $suffix . '@example.com',
            'username' => 'usuario_base_user_' . $suffix,
            'roles' => ['ROLE_USER'],
        ]);

        self::assertResponseStatusCodeSame(200);
        self::assertStringContainsString('El nombre de usuario ya se encuentra registrado', $this->client->getResponse()->getContent());

        $this->entityManager->clear();
        $usuarioSinCambios = $this->entityManager->getRepository(Usuario::class)->find($usuarioObjetivoId);
        self::assertInstanceOf(Usuario::class, $usuarioSinCambios);
        self::assertSame('usuario_objetivo_user_' . $suffix, $usuarioSinCambios->getUsername());
    }

    public function testAdminNoEditaUsuarioDuplicadoEmailYMuestraError(): void
    {
        $suffix = substr(md5(uniqid('', true)), 0, 8);
        $admin = $this->crearUsuario('admin_usuario_edit_dup_email_' . $suffix, ['ROLE_ADMIN', 'ROLE_USER']);
        $usuarioBase = $this->crearUsuario('usuario_base_email_' . $suffix, ['ROLE_USER']);
        $usuarioObjetivo = $this->crearUsuario('usuario_objetivo_email_' . $suffix, ['ROLE_USER']);
        $usuarioObjetivoId = $usuarioObjetivo->getId();
        self::assertNotNull($usuarioObjetivoId);
        
        $emailBase = $usuarioBase->getEmail();

        $this->client->loginUser($admin);
        $this->client->request('POST', '/admin/usuario/editar/' . $usuarioObjetivoId, [
            'nombre' => 'Nombre Editado',
            'apellido' => 'Apellido Editado',
            'email' => $emailBase,
            'username' => 'usuario_objetivo_email_' . $suffix,
            'roles' => ['ROLE_USER'],
        ]);

        self::assertResponseStatusCodeSame(200);
        self::assertStringContainsString('El email ya se encuentra registrado', $this->client->getResponse()->getContent());

        $this->entityManager->clear();
        $usuarioSinCambios = $this->entityManager->getRepository(Usuario::class)->find($usuarioObjetivoId);
        self::assertInstanceOf(Usuario::class, $usuarioSinCambios);
        self::assertSame('usuario_objetivo_email_' . $suffix, $usuarioSinCambios->getUsername());
    }

    public function testAdminEditaUsuarioYActualizaDatos(): void
    {
        $suffix = substr(md5(uniqid('', true)), 0, 8);
        $admin = $this->crearUsuario('admin_usuario_edit_' . $suffix, ['ROLE_ADMIN', 'ROLE_USER']);
        $usuarioObjetivo = $this->crearUsuario('usuario_obj_' . $suffix, ['ROLE_USER']);
        $usuarioObjetivoId = $usuarioObjetivo->getId();
        self::assertNotNull($usuarioObjetivoId);

        $this->client->loginUser($admin);
        $this->client->request('POST', '/admin/usuario/editar/' . $usuarioObjetivoId, [
            'nombre' => 'Nuevo Nombre',
            'apellido' => 'Nuevo Apellido',
            'email' => 'usuario_obj_' . $suffix . '@example.com',
            'username' => 'usuario_obj_' . $suffix,
            'roles' => ['ROLE_ADMIN'],
        ]);

        self::assertResponseRedirects('/admin/usuario/');

        $this->entityManager->clear();
        $usuarioEditado = $this->entityManager->getRepository(Usuario::class)->find($usuarioObjetivoId);
        self::assertInstanceOf(Usuario::class, $usuarioEditado);
        self::assertSame('Nuevo Nombre', $usuarioEditado->getNombre());
        self::assertSame('Nuevo Apellido', $usuarioEditado->getApellido());
        self::assertContains('ROLE_ADMIN', $usuarioEditado->getRoles());
    }

    public function testAdminAccedeAFormularioEditarUsuarioPorGet(): void
    {
        $suffix = substr(md5(uniqid('', true)), 0, 8);
        $admin = $this->crearUsuario('admin_usuario_edit_get_' . $suffix, ['ROLE_ADMIN', 'ROLE_USER']);
        $usuarioObjetivo = $this->crearUsuario('usuario_obj_edit_get_' . $suffix, ['ROLE_USER']);
        $usuarioObjetivoId = $usuarioObjetivo->getId();
        self::assertNotNull($usuarioObjetivoId);

        $this->client->loginUser($admin);
        $this->client->request('GET', '/admin/usuario/editar/' . $usuarioObjetivoId);

        self::assertResponseIsSuccessful();
        self::assertStringContainsString($usuarioObjetivo->getUsername(), $this->client->getResponse()->getContent());
    }

    public function testAdminEliminaUsuarioYRedirigeAIndice(): void
    {
        $suffix = substr(md5(uniqid('', true)), 0, 8);
        $admin = $this->crearUsuario('admin_usuario_del_' . $suffix, ['ROLE_ADMIN', 'ROLE_USER']);
        $usuarioObjetivo = $this->crearUsuario('usuario_del_' . $suffix, ['ROLE_USER']);
        $usuarioObjetivoId = $usuarioObjetivo->getId();
        self::assertNotNull($usuarioObjetivoId);

        $this->client->loginUser($admin);
            $this->client->request('POST', '/admin/usuario/eliminar/' . $usuarioObjetivoId, [
                '_token' => $this->csrfTokenValue('delete_usuario_' . $usuarioObjetivoId),
            ]);

        self::assertResponseRedirects('/admin/usuario/');

        $this->entityManager->clear();
        $usuarioEliminado = $this->entityManager->getRepository(Usuario::class)->find($usuarioObjetivoId);
        self::assertNull($usuarioEliminado);
    }

    public function testUsuarioSinRolAdminNoAccedeAIndiceUsuarios(): void
    {
        $suffix = substr(md5(uniqid('', true)), 0, 8);
        $admin = $this->crearUsuario('admin_usuario_index_deny_' . $suffix, ['ROLE_ADMIN', 'ROLE_USER']);
        $this->crearUsuario('usuario_index_deny_' . $suffix, ['ROLE_USER']);

        $usuario = $this->crearUsuario('usuario_index_deny_login_' . $suffix, ['ROLE_USER']);
        $this->client->loginUser($usuario);

        $this->client->request('GET', '/admin/usuario/');
        $status = $this->client->getResponse()->getStatusCode();
        self::assertContains($status, [401, 403, 302]);
    }

    public function testAnonimoNoAccedeAIndiceUsuarios(): void
    {
        $this->client->request('GET', '/admin/usuario/');
        $status = $this->client->getResponse()->getStatusCode();
        self::assertContains($status, [401, 403, 302]);
    }

    public function testUsuarioSinRolAdminNoAccedeACrearUsuario(): void
    {
        $suffix = substr(md5(uniqid('', true)), 0, 8);

        $usuario = $this->crearUsuario('usuario_create_deny_' . $suffix, ['ROLE_USER']);
        $this->client->loginUser($usuario);

        $this->client->request('GET', '/admin/usuario/nuevo');
        self::assertResponseRedirects('/');
    }

    public function testAnonimoNoAccedeACrearUsuario(): void
    {
        $suffix = substr(md5(uniqid('', true)), 0, 8);
        $this->crearUsuario('usuario_create_seed_' . $suffix, ['ROLE_USER']);

        $this->client->request('GET', '/admin/usuario/nuevo');
        self::assertResponseRedirects('/login');
    }

    public function testUsuarioSinRolUserNoAccedeACambiarPassword(): void
    {
        $suffix = substr(md5(uniqid('', true)), 0, 8);
        $usuario = $this->crearUsuario('usuario_sin_role_user_' . $suffix, ['ROLE_ADMIN']);

        $this->client->loginUser($usuario);
        $this->client->request('GET', '/admin/usuario/cambiar_password');

        self::assertContains($this->client->getResponse()->getStatusCode(), [401, 403]);
    }

    public function testAnonimoNoAccedeACambiarPassword(): void
    {
        $this->client->request('GET', '/admin/usuario/cambiar_password');

        self::assertContains($this->client->getResponse()->getStatusCode(), [401, 403, 302]);
    }

    public function testUsuarioCambiaPasswordYRedirigeAMain(): void
    {
        $suffix = substr(md5(uniqid('', true)), 0, 8);
        $usuario = $this->crearUsuario('usuario_pass_' . $suffix, ['ROLE_USER']);
        $usuarioId = $usuario->getId();
        self::assertNotNull($usuarioId);
        $passwordAnterior = $usuario->getPassword();

        $this->client->loginUser($usuario);
        $this->client->request('POST', '/admin/usuario/cambiar_password', [
            'password' => 'NuevaPassSegura123!',
        ]);

        self::assertResponseRedirects('/');

        $this->entityManager->clear();
        $usuarioActualizado = $this->entityManager->getRepository(Usuario::class)->find($usuarioId);
        self::assertInstanceOf(Usuario::class, $usuarioActualizado);
        self::assertNotSame($passwordAnterior, $usuarioActualizado->getPassword());
    }

    public function testAnonimoAccedeAMainIndex(): void
    {
        $suffix = substr(md5(uniqid('', true)), 0, 8);
        $ruta = $this->buildRuta('ft-main-index', $suffix);
        $admin = $this->crearUsuario('admin_main_index_' . $suffix, ['ROLE_ADMIN', 'ROLE_USER']);
        $torneo = $this->crearTorneo($admin, $ruta, $suffix);

        $this->client->request('GET', '/');

        self::assertResponseIsSuccessful();
        self::assertStringContainsString($torneo->getNombre(), $this->client->getResponse()->getContent());
    }

    public function testUsuarioRecienCreadoVePantallaCambioPasswordEnMain(): void
    {
        $suffix = substr(md5(uniqid('', true)), 0, 8);
        $usuario = $this->crearUsuario('usuario_main_' . $suffix, ['ROLE_USER']);

        $this->client->loginUser($usuario);
        $this->client->request('GET', '/');

        self::assertResponseIsSuccessful();
        self::assertStringContainsString('password', strtolower($this->client->getResponse()->getContent()));
    }

    public function testAnonimoAccedeAVistaTorneoConFiltros(): void
    {
        $suffix = substr(md5(uniqid('', true)), 0, 8);
        $ruta = $this->buildRuta('ft-main-torneo', $suffix);
        $admin = $this->crearUsuario('admin_main_torneo_' . $suffix, ['ROLE_ADMIN', 'ROLE_USER']);
        $torneo = $this->crearTorneo($admin, $ruta, $suffix);
        $categoria = $this->crearCategoria($torneo, $suffix);
        $categoriaId = $categoria->getId();
        self::assertNotNull($categoriaId);

        $this->client->request('GET', '/torneo/' . $ruta . '?categoria=' . $categoriaId . '&equipo=999999');

        self::assertResponseIsSuccessful();
        self::assertStringContainsString($torneo->getNombre(), $this->client->getResponse()->getContent());
    }

    public function testAnonimoAccedeAVistaCategoriaDeTorneo(): void
    {
        $suffix = substr(md5(uniqid('', true)), 0, 8);
        $ruta = $this->buildRuta('ft-main-cat', $suffix);
        $admin = $this->crearUsuario('admin_main_cat_' . $suffix, ['ROLE_ADMIN', 'ROLE_USER']);
        $torneo = $this->crearTorneo($admin, $ruta, $suffix);
        $categoria = $this->crearCategoria($torneo, $suffix);
        $grupo = $this->crearGrupo($categoria, $suffix);
        $categoriaId = $categoria->getId();
        self::assertNotNull($categoriaId);

        $this->client->request('GET', '/torneo/' . $ruta . '/categoria/' . $categoriaId);

        self::assertResponseIsSuccessful();
        self::assertStringContainsString('Volver', $this->client->getResponse()->getContent());
    }

}

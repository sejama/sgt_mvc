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
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AdminBusinessFlowFunctionalTest extends WebTestCase
{
    private KernelBrowser $client;

    private EntityManagerInterface $entityManager;

    protected function setUp(): void
    {
        $this->client = static::createClient();

        /** @var EntityManagerInterface $entityManager */
        $entityManager = static::getContainer()->get('doctrine.orm.entity_manager');
        $this->entityManager = $entityManager;
        $this->entityManager->clear();
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

    public function testAdminAccedeAFormulariosDeGestionDeTorneo(): void
    {
        $suffix = substr(md5(uniqid('', true)), 0, 8);
        $ruta = $this->buildRuta('ft-admin-gestion', $suffix);

        $admin = $this->crearUsuario('ft_admin_' . $suffix, ['ROLE_ADMIN', 'ROLE_USER']);
        $this->crearTorneo($admin, $ruta, $suffix);

        $this->client->loginUser($admin);

        $this->client->request('GET', '/admin/torneo/' . $ruta . '/categoria/nuevo');
        $statusCategoria = $this->client->getResponse()->getStatusCode();
        self::assertNotContains($statusCategoria, [401, 403]);

        $this->client->request('GET', '/admin/torneo/' . $ruta . '/sede/nuevo');
        $statusSede = $this->client->getResponse()->getStatusCode();
        self::assertNotContains($statusSede, [401, 403]);
    }

    public function testUsuarioSinRolAdminNoAccedeAFormulariosDeGestionDeTorneo(): void
    {
        $suffix = substr(md5(uniqid('', true)), 0, 8);
        $ruta = $this->buildRuta('ft-user-gestion', $suffix);

        $adminCreador = $this->crearUsuario('ft_creator_' . $suffix, ['ROLE_ADMIN', 'ROLE_USER']);
        $this->crearTorneo($adminCreador, $ruta, $suffix);

        $usuario = $this->crearUsuario('ft_user_' . $suffix, ['ROLE_USER']);
        $this->client->loginUser($usuario);

        $this->client->request('GET', '/admin/torneo/' . $ruta . '/categoria/nuevo');
        $statusCategoria = $this->client->getResponse()->getStatusCode();
        if ($statusCategoria === 302) {
            self::assertResponseRedirects('/login');
        } else {
            self::assertContains($statusCategoria, [401, 403]);
        }

        $this->client->request('GET', '/admin/torneo/' . $ruta . '/sede/nuevo');
        $statusSede = $this->client->getResponse()->getStatusCode();
        if ($statusSede === 302) {
            self::assertResponseRedirects('/login');
        } else {
            self::assertContains($statusSede, [401, 403]);
        }
    }

    public function testAnonimoNoAccedeAFormulariosDeGestionDeTorneo(): void
    {
        $suffix = substr(md5(uniqid('', true)), 0, 8);
        $ruta = $this->buildRuta('ft-anon-gestion', $suffix);

        $adminCreador = $this->crearUsuario('ft_creator_anon_' . $suffix, ['ROLE_ADMIN', 'ROLE_USER']);
        $this->crearTorneo($adminCreador, $ruta, $suffix);

        $this->client->request('GET', '/admin/torneo/' . $ruta . '/categoria/nuevo');
        $statusCategoria = $this->client->getResponse()->getStatusCode();
        if ($statusCategoria === 302) {
            self::assertResponseRedirects('/login');
        } else {
            self::assertContains($statusCategoria, [401, 403]);
        }

        $this->client->request('GET', '/admin/torneo/' . $ruta . '/sede/nuevo');
        $statusSede = $this->client->getResponse()->getStatusCode();
        if ($statusSede === 302) {
            self::assertResponseRedirects('/login');
        } else {
            self::assertContains($statusSede, [401, 403]);
        }
    }

    public function testAdminAccedeAIndiceDePartidos(): void
    {
        $suffix = substr(md5(uniqid('', true)), 0, 8);
        $ruta = $this->buildRuta('ft-admin-partidos', $suffix);

        $admin = $this->crearUsuario('ft_admin_partidos_' . $suffix, ['ROLE_ADMIN', 'ROLE_USER']);
        $this->crearTorneo($admin, $ruta, $suffix);

        $this->client->loginUser($admin);
        $this->client->request('GET', '/admin/torneo/' . $ruta . '/partido');

        $status = $this->client->getResponse()->getStatusCode();
        self::assertNotContains($status, [401, 403]);
    }

    public function testUsuarioSinRolAdminNoAccedeAIndiceDePartidos(): void
    {
        $suffix = substr(md5(uniqid('', true)), 0, 8);
        $ruta = $this->buildRuta('ft-user-partidos', $suffix);

        $adminCreador = $this->crearUsuario('ft_creator_partidos_' . $suffix, ['ROLE_ADMIN', 'ROLE_USER']);
        $this->crearTorneo($adminCreador, $ruta, $suffix);

        $usuario = $this->crearUsuario('ft_user_partidos_' . $suffix, ['ROLE_USER']);
        $this->client->loginUser($usuario);

        $this->client->request('GET', '/admin/torneo/' . $ruta . '/partido');
        self::assertContains($this->client->getResponse()->getStatusCode(), [401, 403]);
    }

    public function testAnonimoNoAccedeAIndiceDePartidos(): void
    {
        $suffix = substr(md5(uniqid('', true)), 0, 8);
        $ruta = $this->buildRuta('ft-anon-partidos', $suffix);

        $adminCreador = $this->crearUsuario('ft_creator_anon_partidos_' . $suffix, ['ROLE_ADMIN', 'ROLE_USER']);
        $this->crearTorneo($adminCreador, $ruta, $suffix);

        $this->client->request('GET', '/admin/torneo/' . $ruta . '/partido');
        $status = $this->client->getResponse()->getStatusCode();
        if ($status === 302) {
            self::assertResponseRedirects('/login');
        } else {
            self::assertContains($status, [401, 403]);
        }
    }

    public function testAdminCreaCategoriaPorPostYSePersiste(): void
    {
        $suffix = substr(md5(uniqid('', true)), 0, 8);
        $ruta = $this->buildRuta('ft-admin-post-cat', $suffix);

        $admin = $this->crearUsuario('ft_admin_post_cat_' . $suffix, ['ROLE_ADMIN', 'ROLE_USER']);
        $torneo = $this->crearTorneo($admin, $ruta, $suffix);

        $this->client->loginUser($admin);

        $this->client->request('POST', '/admin/torneo/' . $ruta . '/categoria/nuevo', [
            'genero' => 'Masculino',
            'nombre' => 'FT Cat ' . $suffix,
            'nombreCorto' => 'FC' . strtoupper(substr($suffix, 0, 4)),
        ]);

        self::assertResponseRedirects('/admin/torneo/');

        $categoria = $this->entityManager->getRepository(Categoria::class)->findOneBy([
            'nombre' => 'FT Cat ' . $suffix,
            'torneo' => $torneo,
        ]);

        self::assertInstanceOf(Categoria::class, $categoria);
        self::assertSame($torneo->getId(), $categoria->getTorneo()?->getId());
    }

    public function testAdminNoCreaCategoriaConNombreInvalidoPorPost(): void
    {
        $suffix = substr(md5(uniqid('', true)), 0, 8);
        $ruta = $this->buildRuta('ft-admin-post-cat-inv', $suffix);

        $admin = $this->crearUsuario('ft_admin_post_cat_inv_' . $suffix, ['ROLE_ADMIN', 'ROLE_USER']);
        $torneo = $this->crearTorneo($admin, $ruta, $suffix);

        $this->client->loginUser($admin);

        $this->client->request('POST', '/admin/torneo/' . $ruta . '/categoria/nuevo', [
            'genero' => 'Masculino',
            'nombre' => 'FT',
            'nombreCorto' => 'FC' . strtoupper(substr($suffix, 0, 4)),
        ]);

        self::assertResponseStatusCodeSame(200);
        self::assertStringContainsString('El Nombre debe tener entre 3 y 128 caracteres', $this->client->getResponse()->getContent());

        $categorias = $this->entityManager->getRepository(Categoria::class)->findBy([
            'torneo' => $torneo,
            'nombre' => 'FT',
        ]);

        self::assertCount(0, $categorias);
    }

    public function testAdminCreaSedePorPostYSePersiste(): void
    {
        $suffix = substr(md5(uniqid('', true)), 0, 8);
        $ruta = $this->buildRuta('ft-admin-post-sede', $suffix);

        $admin = $this->crearUsuario('ft_admin_post_sede_' . $suffix, ['ROLE_ADMIN', 'ROLE_USER']);
        $torneo = $this->crearTorneo($admin, $ruta, $suffix);

        $this->client->loginUser($admin);

        $this->client->request('POST', '/admin/torneo/' . $ruta . '/sede/nuevo', [
            'sedeNombre' => 'FT Sede ' . $suffix,
            'sedeDireccion' => 'Calle Functional 123',
        ]);

        self::assertResponseRedirects('/admin/torneo/');

        $sede = $this->entityManager->getRepository(Sede::class)->findOneBy([
            'nombre' => 'FT Sede ' . $suffix,
            'torneo' => $torneo,
        ]);

        self::assertInstanceOf(Sede::class, $sede);
        self::assertSame($torneo->getId(), $sede->getTorneo()?->getId());
    }

    public function testAdminNoCreaSedeConDireccionInvalidaPorPost(): void
    {
        $suffix = substr(md5(uniqid('', true)), 0, 8);
        $ruta = $this->buildRuta('ft-admin-post-sede-inv', $suffix);

        $admin = $this->crearUsuario('ft_admin_post_sede_inv_' . $suffix, ['ROLE_ADMIN', 'ROLE_USER']);
        $torneo = $this->crearTorneo($admin, $ruta, $suffix);

        $this->client->loginUser($admin);

        $this->client->request('POST', '/admin/torneo/' . $ruta . '/sede/nuevo', [
            'sedeNombre' => 'FT Sede Inv ' . $suffix,
            'sedeDireccion' => 'Corta',
        ]);

        self::assertResponseStatusCodeSame(200);
        self::assertStringContainsString('El Dirección debe tener entre 8 y 128 caracteres', $this->client->getResponse()->getContent());

        $sedes = $this->entityManager->getRepository(Sede::class)->findBy([
            'torneo' => $torneo,
            'nombre' => 'FT Sede Inv ' . $suffix,
        ]);

        self::assertCount(0, $sedes);
    }

    public function testAdminNoCreaCategoriaDuplicadaPorPost(): void
    {
        $suffix = substr(md5(uniqid('', true)), 0, 8);
        $ruta = $this->buildRuta('ft-admin-post-cat-dup', $suffix);

        $admin = $this->crearUsuario('ft_admin_post_cat_dup_' . $suffix, ['ROLE_ADMIN', 'ROLE_USER']);
        $torneo = $this->crearTorneo($admin, $ruta, $suffix);
        $this->crearCategoria($torneo, $suffix);

        $this->client->loginUser($admin);

        $this->client->request('POST', '/admin/torneo/' . $ruta . '/categoria/nuevo', [
            'genero' => 'Masculino',
            'nombre' => 'FT Cat Base ' . $suffix,
            'nombreCorto' => 'FB' . strtoupper(substr($suffix, 0, 4)),
        ]);

        self::assertResponseStatusCodeSame(200);

        $categorias = $this->entityManager->getRepository(Categoria::class)->findBy([
            'torneo' => $torneo,
            'nombre' => 'FT Cat Base ' . $suffix,
        ]);

        self::assertCount(1, $categorias);
    }

    public function testAdminNoCreaSedeInvalidaPorPost(): void
    {
        $suffix = substr(md5(uniqid('', true)), 0, 8);
        $ruta = $this->buildRuta('ft-admin-post-sede-inv', $suffix);

        $admin = $this->crearUsuario('ft_admin_post_sede_inv_' . $suffix, ['ROLE_ADMIN', 'ROLE_USER']);
        $torneo = $this->crearTorneo($admin, $ruta, $suffix);

        $this->client->loginUser($admin);

        $this->client->request('POST', '/admin/torneo/' . $ruta . '/sede/nuevo', [
            'sedeNombre' => 'FT Sede Corta ' . $suffix,
            'sedeDireccion' => 'Corta',
        ]);

        self::assertResponseStatusCodeSame(200);

        $sede = $this->entityManager->getRepository(Sede::class)->findOneBy([
            'torneo' => $torneo,
            'nombre' => 'FT Sede Corta ' . $suffix,
        ]);

        self::assertNull($sede);
    }

    public function testAdminEditaCategoriaPorPostYSePersiste(): void
    {
        $suffix = substr(md5(uniqid('', true)), 0, 8);
        $ruta = $this->buildRuta('ft-admin-edit-cat', $suffix);

        $admin = $this->crearUsuario('ft_admin_edit_cat_' . $suffix, ['ROLE_ADMIN', 'ROLE_USER']);
        $torneo = $this->crearTorneo($admin, $ruta, $suffix);
        $categoria = $this->crearCategoria($torneo, $suffix);

        $this->client->loginUser($admin);

        $this->client->request('POST', '/admin/torneo/' . $ruta . '/categoria/' . $categoria->getId() . '/editar/', [
            'genero' => 'Masculino',
            'nombre' => 'FT Cat Edit ' . $suffix,
            'nombreCorto' => 'FE' . strtoupper(substr($suffix, 0, 4)),
        ]);

        self::assertResponseRedirects('/admin/torneo/');

        $this->entityManager->clear();
        $editada = $this->entityManager->getRepository(Categoria::class)->find($categoria->getId());

        self::assertInstanceOf(Categoria::class, $editada);
        self::assertSame('FT Cat Edit ' . $suffix, $editada->getNombre());
        self::assertSame('FE' . strtoupper(substr($suffix, 0, 4)), $editada->getNombreCorto());
        self::assertSame('Masculino', $editada->getGenero()?->value);
    }

    public function testAdminAccedeAFormularioEditarCategoriaPorGet(): void
    {
        $suffix = substr(md5(uniqid('', true)), 0, 8);
        $ruta = $this->buildRuta('ft-admin-get-edit-cat', $suffix);

        $admin = $this->crearUsuario('ft_admin_get_edit_cat_' . $suffix, ['ROLE_ADMIN', 'ROLE_USER']);
        $torneo = $this->crearTorneo($admin, $ruta, $suffix);
        $categoria = $this->crearCategoria($torneo, $suffix);
        $categoriaId = $categoria->getId();
        self::assertNotNull($categoriaId);

        $this->client->loginUser($admin);

        $this->client->request('GET', '/admin/torneo/' . $ruta . '/categoria/' . $categoriaId . '/editar/');
        self::assertResponseIsSuccessful();
    }

    public function testAdminAccedeAFormularioEditarDisputaPorGet(): void
    {
        $suffix = substr(md5(uniqid('', true)), 0, 8);
        $ruta = $this->buildRuta('ft-admin-get-disputa-cat', $suffix);

        $admin = $this->crearUsuario('ft_admin_get_disputa_cat_' . $suffix, ['ROLE_ADMIN', 'ROLE_USER']);
        $torneo = $this->crearTorneo($admin, $ruta, $suffix);
        $categoria = $this->crearCategoria($torneo, $suffix);
        $categoriaId = $categoria->getId();
        self::assertNotNull($categoriaId);

        $this->client->loginUser($admin);

        $this->client->request('GET', '/admin/torneo/' . $ruta . '/categoria/' . $categoriaId . '/editar/disputa/');
        self::assertResponseIsSuccessful();
    }

    public function testAdminEditaSedePorPostYSePersiste(): void
    {
        $suffix = substr(md5(uniqid('', true)), 0, 8);
        $ruta = $this->buildRuta('ft-admin-edit-sede', $suffix);

        $admin = $this->crearUsuario('ft_admin_edit_sede_' . $suffix, ['ROLE_ADMIN', 'ROLE_USER']);
        $torneo = $this->crearTorneo($admin, $ruta, $suffix);
        $sede = $this->crearSede($torneo, $suffix);

        $this->client->loginUser($admin);

        $this->client->request('POST', '/admin/torneo/' . $ruta . '/sede/' . $sede->getId() . '/editar', [
            'sedeNombre' => 'FT Sede Edit ' . $suffix,
            'sedeDireccion' => 'Calle Editada 999',
        ]);

        self::assertResponseRedirects('/admin/torneo/');

        $this->entityManager->clear();
        $editada = $this->entityManager->getRepository(Sede::class)->find($sede->getId());

        self::assertInstanceOf(Sede::class, $editada);
        self::assertSame('FT Sede Edit ' . $suffix, $editada->getNombre());
        self::assertSame('Calle Editada 999', $editada->getDomicilio());
    }

    public function testAdminEliminaCategoriaPorGetYSeBorra(): void
    {
        $suffix = substr(md5(uniqid('', true)), 0, 8);
        $ruta = $this->buildRuta('ft-admin-del-cat', $suffix);

        $admin = $this->crearUsuario('ft_admin_del_cat_' . $suffix, ['ROLE_ADMIN', 'ROLE_USER']);
        $torneo = $this->crearTorneo($admin, $ruta, $suffix);
        $categoria = $this->crearCategoria($torneo, $suffix);
        $categoriaId = $categoria->getId();
        self::assertNotNull($categoriaId);

        $this->client->loginUser($admin);

        $this->client->request('GET', '/admin/torneo/' . $ruta . '/categoria/' . $categoriaId . '/eliminar');

        self::assertResponseRedirects('/admin/torneo/');

        $this->entityManager->clear();
        $eliminada = $this->entityManager->getRepository(Categoria::class)->find($categoriaId);
        self::assertNull($eliminada);
    }

    public function testAdminEliminarCategoriaInexistenteRedirigeALogin(): void
    {
        $suffix = substr(md5(uniqid('', true)), 0, 8);
        $ruta = $this->buildRuta('ft-admin-del-cat-inx', $suffix);

        $admin = $this->crearUsuario('ft_admin_del_cat_inx_' . $suffix, ['ROLE_ADMIN', 'ROLE_USER']);
        $this->crearTorneo($admin, $ruta, $suffix);

        $this->client->loginUser($admin);

        $this->client->request('GET', '/admin/torneo/' . $ruta . '/categoria/999999/eliminar');
        self::assertResponseRedirects('/login');
    }

    public function testAdminCerrarCategoriaInexistenteRedirigeALogin(): void
    {
        $suffix = substr(md5(uniqid('', true)), 0, 8);
        $ruta = $this->buildRuta('ft-admin-close-cat-inx', $suffix);

        $admin = $this->crearUsuario('ft_admin_close_cat_inx_' . $suffix, ['ROLE_ADMIN', 'ROLE_USER']);
        $this->crearTorneo($admin, $ruta, $suffix);

        $this->client->loginUser($admin);

        $this->client->request('POST', '/admin/torneo/' . $ruta . '/categoria/999999/cerrar');
        self::assertResponseRedirects('/login');
    }

    public function testAdminEliminaSedePorGetYSeBorra(): void
    {
        $suffix = substr(md5(uniqid('', true)), 0, 8);
        $ruta = $this->buildRuta('ft-admin-del-sede', $suffix);

        $admin = $this->crearUsuario('ft_admin_del_sede_' . $suffix, ['ROLE_ADMIN', 'ROLE_USER']);
        $torneo = $this->crearTorneo($admin, $ruta, $suffix);
        $sede = $this->crearSede($torneo, $suffix);
        $sedeId = $sede->getId();
        self::assertNotNull($sedeId);

        $this->client->loginUser($admin);

        $this->client->request('GET', '/admin/torneo/' . $ruta . '/sede/' . $sedeId . '/eliminar');

        self::assertResponseRedirects('/admin/torneo/');

        $this->entityManager->clear();
        $eliminada = $this->entityManager->getRepository(Sede::class)->find($sedeId);
        self::assertNull($eliminada);
    }

    public function testAdminCreaEquipoPorPostYSePersiste(): void
    {
        $suffix = substr(md5(uniqid('', true)), 0, 8);
        $ruta = $this->buildRuta('ft-admin-post-equipo', $suffix);

        $admin = $this->crearUsuario('ft_admin_post_equipo_' . $suffix, ['ROLE_ADMIN', 'ROLE_USER']);
        $torneo = $this->crearTorneo($admin, $ruta, $suffix);
        $categoria = $this->crearCategoria($torneo, $suffix);
        $categoriaId = $categoria->getId();
        self::assertNotNull($categoriaId);

        $this->client->loginUser($admin);

        $this->client->request('POST', '/admin/torneo/' . $ruta . '/categoria/' . $categoriaId . '/equipo/nuevo', [
            'nombre' => 'FT Equipo ' . $suffix,
            'nombreCorto' => 'EQ' . strtoupper(substr($suffix, 0, 4)),
            'pais' => 'Argentina',
            'provincia' => 'Mendoza',
            'localidad' => 'Capital',
            'delegado' => [[
                'nombre' => 'Juan',
                'apellido' => 'Perez',
                'tipoDocumento' => 'DNI',
                'numeroDocumento' => '12345678',
                'email' => 'delegado+' . $suffix . '@example.com',
                'celular' => '2615551234',
            ]],
        ]);

        self::assertResponseRedirects('/admin/torneo/' . $ruta . '/categoria/' . $categoriaId . '/equipo/');

        $equipo = $this->entityManager->getRepository(Equipo::class)->findOneBy([
            'nombre' => 'FT Equipo ' . $suffix,
            'categoria' => $categoria,
        ]);

        self::assertInstanceOf(Equipo::class, $equipo);
        self::assertSame($categoriaId, $equipo->getCategoria()?->getId());
    }

    public function testAdminNoCreaEquipoInvalidoPorPost(): void
    {
        $suffix = substr(md5(uniqid('', true)), 0, 8);
        $ruta = $this->buildRuta('ft-admin-post-equipo-inv', $suffix);

        $admin = $this->crearUsuario('ft_admin_post_equipo_inv_' . $suffix, ['ROLE_ADMIN', 'ROLE_USER']);
        $torneo = $this->crearTorneo($admin, $ruta, $suffix);
        $categoria = $this->crearCategoria($torneo, $suffix);
        $categoriaId = $categoria->getId();
        self::assertNotNull($categoriaId);

        $this->client->loginUser($admin);

        $this->client->request('POST', '/admin/torneo/' . $ruta . '/categoria/' . $categoriaId . '/equipo/nuevo', [
            'nombre' => 'FT Equipo Inv ' . $suffix,
            'nombreCorto' => 'X',
            'pais' => 'Argentina',
            'provincia' => 'Mendoza',
            'localidad' => 'Capital',
            'delegado' => [[
                'nombre' => 'Juan',
                'apellido' => 'Perez',
                'tipoDocumento' => 'DNI',
                'numeroDocumento' => '12345678',
                'email' => 'delegado-inv+' . $suffix . '@example.com',
                'celular' => '2615551234',
            ]],
        ]);

        self::assertResponseStatusCodeSame(200);

        $equipo = $this->entityManager->getRepository(Equipo::class)->findOneBy([
            'nombre' => 'FT Equipo Inv ' . $suffix,
            'categoria' => $categoria,
        ]);

        self::assertNull($equipo);
    }

    public function testAdminEditaEquipoPorPostYSePersiste(): void
    {
        $suffix = substr(md5(uniqid('', true)), 0, 8);
        $ruta = $this->buildRuta('ft-admin-edit-equipo', $suffix);

        $admin = $this->crearUsuario('ft_admin_edit_equipo_' . $suffix, ['ROLE_ADMIN', 'ROLE_USER']);
        $torneo = $this->crearTorneo($admin, $ruta, $suffix);
        $categoria = $this->crearCategoria($torneo, $suffix);
        $categoriaId = $categoria->getId();
        self::assertNotNull($categoriaId);
        $equipo = $this->crearEquipo($categoria, $suffix);
        $equipoId = $equipo->getId();
        self::assertNotNull($equipoId);

        $this->client->loginUser($admin);

        $this->client->request('POST', '/admin/torneo/' . $ruta . '/categoria/' . $categoriaId . '/equipo/' . $equipoId . '/editar', [
            'nombre' => 'FT Equipo Edit ' . $suffix,
            'nombreCorto' => 'EE' . strtoupper(substr($suffix, 0, 4)),
            'pais' => 'Argentina',
            'provincia' => 'Cordoba',
            'localidad' => 'Centro',
        ]);

        self::assertResponseRedirects('/admin/torneo/' . $ruta . '/categoria/' . $categoriaId . '/equipo/');

        $this->entityManager->clear();
        $editado = $this->entityManager->getRepository(Equipo::class)->find($equipoId);

        self::assertInstanceOf(Equipo::class, $editado);
        self::assertSame('FT Equipo Edit ' . $suffix, $editado->getNombre());
        self::assertSame('EE' . strtoupper(substr($suffix, 0, 4)), $editado->getNombreCorto());
    }

    public function testAdminEliminaEquipoPorGetYSeBorra(): void
    {
        $suffix = substr(md5(uniqid('', true)), 0, 8);
        $ruta = $this->buildRuta('ft-admin-del-equipo', $suffix);

        $admin = $this->crearUsuario('ft_admin_del_equipo_' . $suffix, ['ROLE_ADMIN', 'ROLE_USER']);
        $torneo = $this->crearTorneo($admin, $ruta, $suffix);
        $categoria = $this->crearCategoria($torneo, $suffix);
        $categoriaId = $categoria->getId();
        self::assertNotNull($categoriaId);
        $equipo = $this->crearEquipo($categoria, $suffix);
        $equipoId = $equipo->getId();
        self::assertNotNull($equipoId);

        $this->client->loginUser($admin);

        $this->client->request('GET', '/admin/torneo/' . $ruta . '/categoria/' . $categoriaId . '/equipo/' . $equipoId . '/eliminar');

        self::assertResponseRedirects('/admin/torneo/' . $ruta . '/categoria/' . $categoriaId . '/equipo/');

        $this->entityManager->clear();
        $eliminado = $this->entityManager->getRepository(Equipo::class)->find($equipoId);
        self::assertNull($eliminado);
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

        $this->client->request('GET', '/admin/torneo/' . $ruta . '/sede/' . $sedeId . '/cancha/' . $canchaId . '/eliminar');
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

        $this->client->request('GET', '/admin/torneo/' . $ruta . '/categoria/' . $categoriaId . '/equipo/' . $equipoId . '/jugador/' . $jugadorId . '/eliminar');
        self::assertResponseRedirects('/admin/torneo/' . $ruta . '/categoria/' . $categoriaId . '/equipo/' . $equipoId . '/jugador/');

        $this->entityManager->clear();
        $jugadorEliminado = $this->entityManager->getRepository(Jugador::class)->find($jugadorId);
        self::assertNull($jugadorEliminado);
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
        self::assertResponseRedirects('/admin/torneo/' . $ruta . '/partido');
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
        self::assertResponseRedirects('/torneo/' . $ruta);
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

        self::assertResponseStatusCodeSame(401);
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

    public function testAdminEliminaTorneoYRedirigeAIndex(): void
    {
        $suffix = substr(md5(uniqid('', true)), 0, 8);
        $ruta = $this->buildRuta('ft-admin-del-torneo', $suffix);
        $admin = $this->crearUsuario('admin_torneo_del_' . $suffix, ['ROLE_ADMIN', 'ROLE_USER']);
        $this->crearTorneo($admin, $ruta, $suffix);

        $this->client->loginUser($admin);
        $this->client->request('GET', '/admin/torneo/' . $ruta . '/eliminar');

        self::assertResponseRedirects('/admin/torneo/');

        $this->entityManager->clear();
        $torneoEliminado = $this->entityManager->getRepository(Torneo::class)->findOneBy(['ruta' => $ruta]);
        self::assertNull($torneoEliminado);
    }

    public function testAdminAccedeAFormularioCrearUsuarioPorGet(): void
    {
        $suffix = substr(md5(uniqid('', true)), 0, 8);
        $admin = $this->crearUsuario('admin_usuario_get_' . $suffix, ['ROLE_ADMIN', 'ROLE_USER']);

        $this->client->loginUser($admin);
        $this->client->request('GET', '/admin/usuario/nuevo');

        self::assertResponseIsSuccessful();
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

    public function testAdminEliminaUsuarioYRedirigeAIndice(): void
    {
        $suffix = substr(md5(uniqid('', true)), 0, 8);
        $admin = $this->crearUsuario('admin_usuario_del_' . $suffix, ['ROLE_ADMIN', 'ROLE_USER']);
        $usuarioObjetivo = $this->crearUsuario('usuario_del_' . $suffix, ['ROLE_USER']);
        $usuarioObjetivoId = $usuarioObjetivo->getId();
        self::assertNotNull($usuarioObjetivoId);

        $this->client->loginUser($admin);
        $this->client->request('GET', '/admin/usuario/eliminar/' . $usuarioObjetivoId);

        self::assertResponseRedirects('/admin/usuario/');

        $this->entityManager->clear();
        $usuarioEliminado = $this->entityManager->getRepository(Usuario::class)->find($usuarioObjetivoId);
        self::assertNull($usuarioEliminado);
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
        $this->client->request('GET', '/');

        self::assertResponseIsSuccessful();
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
    }

    public function testAnonimoAccedeAVistaCategoriaDeTorneo(): void
    {
        $suffix = substr(md5(uniqid('', true)), 0, 8);
        $ruta = $this->buildRuta('ft-main-cat', $suffix);
        $admin = $this->crearUsuario('admin_main_cat_' . $suffix, ['ROLE_ADMIN', 'ROLE_USER']);
        $torneo = $this->crearTorneo($admin, $ruta, $suffix);
        $categoria = $this->crearCategoria($torneo, $suffix);
        $this->crearGrupo($categoria, $suffix);
        $categoriaId = $categoria->getId();
        self::assertNotNull($categoriaId);

        $this->client->request('GET', '/torneo/' . $ruta . '/categoria/' . $categoriaId);

        self::assertResponseIsSuccessful();
    }

    /**
     * @param string[] $roles
     */
    private function crearUsuario(string $username, array $roles): Usuario
    {
        $usuario = (new Usuario())
            ->setUsername($username)
            ->setEmail($username . '@example.com')
            ->setPassword('test-password-hash')
            ->setRoles($roles);

        $this->entityManager->persist($usuario);
        $this->entityManager->flush();

        $usuarioId = $usuario->getId();
        self::assertNotNull($usuarioId);

        /** @var Usuario|null $usuarioGestionado */
        $usuarioGestionado = $this->entityManager->getRepository(Usuario::class)->find($usuarioId);
        self::assertInstanceOf(Usuario::class, $usuarioGestionado);

        return $usuarioGestionado;
    }

    private function crearTorneo(Usuario $creador, string $ruta, string $suffix): Torneo
    {
        $creadorId = $creador->getId();
        self::assertNotNull($creadorId);

        /** @var Usuario|null $creadorGestionado */
        $creadorGestionado = $this->entityManager->getRepository(Usuario::class)->find($creadorId);
        self::assertInstanceOf(Usuario::class, $creadorGestionado);

        $torneo = (new Torneo())
            ->setNombre('FT Torneo ' . $suffix)
            ->setRuta($ruta)
            ->setDescripcion('Torneo funcional para rutas admin')
            ->setFechaInicioInscripcion(new \DateTimeImmutable('2026-01-01 10:00:00'))
            ->setFechaFinInscripcion(new \DateTimeImmutable('2026-01-10 10:00:00'))
            ->setFechaInicioTorneo(new \DateTimeImmutable('2026-02-01 10:00:00'))
            ->setFechaFinTorneo(new \DateTimeImmutable('2026-02-20 10:00:00'))
            ->setEstado('activo')
            ->setCreador($creadorGestionado);

        $this->entityManager->persist($torneo);
        $this->entityManager->flush();

        return $torneo;
    }

    private function crearCategoria(Torneo $torneo, string $suffix): Categoria
    {
        $torneoId = $torneo->getId();
        self::assertNotNull($torneoId);

        /** @var Torneo|null $torneoGestionado */
        $torneoGestionado = $this->entityManager->getRepository(Torneo::class)->find($torneoId);
        self::assertInstanceOf(Torneo::class, $torneoGestionado);

        $categoria = (new Categoria())
            ->setNombre('FT Cat Base ' . $suffix)
            ->setNombreCorto('FB' . strtoupper(substr($suffix, 0, 4)))
            ->setGenero(Genero::MASCULINO)
            ->setEstado('borrador')
            ->setTorneo($torneoGestionado);

        $this->entityManager->persist($categoria);
        $this->entityManager->flush();

        return $categoria;
    }

    private function crearSede(Torneo $torneo, string $suffix): Sede
    {
        $torneoId = $torneo->getId();
        self::assertNotNull($torneoId);

        /** @var Torneo|null $torneoGestionado */
        $torneoGestionado = $this->entityManager->getRepository(Torneo::class)->find($torneoId);
        self::assertInstanceOf(Torneo::class, $torneoGestionado);

        $sede = (new Sede())
            ->setNombre('FT Sede Base ' . $suffix)
            ->setDomicilio('Calle Base 123')
            ->setTorneo($torneoGestionado);

        $this->entityManager->persist($sede);
        $this->entityManager->flush();

        return $sede;
    }

    private function crearCancha(Torneo $torneo, string $suffix): Cancha
    {
        $sede = $this->crearSede($torneo, $suffix . 'c');
        $sedeId = $sede->getId();
        self::assertNotNull($sedeId);

        /** @var Sede|null $sedeGestionada */
        $sedeGestionada = $this->entityManager->getRepository(Sede::class)->find($sedeId);
        self::assertInstanceOf(Sede::class, $sedeGestionada);

        $cancha = (new Cancha())
            ->setNombre('FT Cancha ' . $suffix)
            ->setDescripcion('Cancha funcional')
            ->setSede($sedeGestionada);

        $this->entityManager->persist($cancha);
        $this->entityManager->flush();

        return $cancha;
    }

    private function crearEquipo(Categoria $categoria, string $suffix): Equipo
    {
        $categoriaId = $categoria->getId();
        self::assertNotNull($categoriaId);

        /** @var Categoria|null $categoriaGestionada */
        $categoriaGestionada = $this->entityManager->getRepository(Categoria::class)->find($categoriaId);
        self::assertInstanceOf(Categoria::class, $categoriaGestionada);

        $equipo = (new Equipo())
            ->setNombre('FT Equipo Base ' . $suffix)
            ->setNombreCorto('EB' . strtoupper(substr($suffix, 0, 4)))
            ->setPais('Argentina')
            ->setProvincia('Mendoza')
            ->setLocalidad('Capital')
            ->setEstado('borrador')
            ->setNumero(1)
            ->setCategoria($categoriaGestionada);

        $this->entityManager->persist($equipo);
        $this->entityManager->flush();

        return $equipo;
    }

    private function crearGrupo(Categoria $categoria, string $suffix): Grupo
    {
        $categoriaId = $categoria->getId();
        self::assertNotNull($categoriaId);

        /** @var Categoria|null $categoriaGestionada */
        $categoriaGestionada = $this->entityManager->getRepository(Categoria::class)->find($categoriaId);
        self::assertInstanceOf(Categoria::class, $categoriaGestionada);

        $grupo = (new Grupo())
            ->setNombre('Grupo ' . strtoupper(substr($suffix, 0, 3)))
            ->setClasificaOro(1)
            ->setClasificaPlata(null)
            ->setClasificaBronce(null)
            ->setEstado('borrador')
            ->setCategoria($categoriaGestionada);

        $this->entityManager->persist($grupo);
        $this->entityManager->flush();

        return $grupo;
    }

    private function crearPartido(
        Categoria $categoria,
        int $numero,
        ?Cancha $cancha = null,
        ?\DateTimeImmutable $horario = null
    ): Partido {
        $categoriaId = $categoria->getId();
        self::assertNotNull($categoriaId);

        /** @var Categoria|null $categoriaGestionada */
        $categoriaGestionada = $this->entityManager->getRepository(Categoria::class)->find($categoriaId);
        self::assertInstanceOf(Categoria::class, $categoriaGestionada);

        $canchaGestionada = null;
        if ($cancha !== null) {
            $canchaId = $cancha->getId();
            self::assertNotNull($canchaId);

            /** @var Cancha|null $canchaGestionada */
            $canchaGestionada = $this->entityManager->getRepository(Cancha::class)->find($canchaId);
            self::assertInstanceOf(Cancha::class, $canchaGestionada);
        }

        $partido = (new Partido())
            ->setCategoria($categoriaGestionada)
            ->setGrupo(null)
            ->setCancha($canchaGestionada)
            ->setHorario($horario)
            ->setEquipoLocal(null)
            ->setEquipoVisitante(null)
            ->setEstado($horario === null ? 'Borrador' : 'Programado')
            ->setTipo('Clasificatorio')
            ->setNumero($numero);

        $this->entityManager->persist($partido);
        $this->entityManager->flush();

        return $partido;
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
}

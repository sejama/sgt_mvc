<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use App\Entity\Categoria;
use App\Entity\Sede;
use App\Entity\Torneo;
use App\Entity\Usuario;
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
        self::assertContains($this->client->getResponse()->getStatusCode(), [401, 403]);

        $this->client->request('GET', '/admin/torneo/' . $ruta . '/sede/nuevo');
        self::assertContains($this->client->getResponse()->getStatusCode(), [401, 403]);
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

        return $usuario;
    }

    private function crearTorneo(Usuario $creador, string $ruta, string $suffix): Torneo
    {
        $torneo = (new Torneo())
            ->setNombre('FT Torneo ' . $suffix)
            ->setRuta($ruta)
            ->setDescripcion('Torneo funcional para rutas admin')
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

    private function crearCategoria(Torneo $torneo, string $suffix): Categoria
    {
        $categoria = (new Categoria())
            ->setNombre('FT Cat Base ' . $suffix)
            ->setNombreCorto('FB' . strtoupper(substr($suffix, 0, 4)))
            ->setGenero(Genero::MASCULINO)
            ->setEstado('borrador')
            ->setTorneo($torneo);

        $this->entityManager->persist($categoria);
        $this->entityManager->flush();

        return $categoria;
    }

    private function crearSede(Torneo $torneo, string $suffix): Sede
    {
        $sede = (new Sede())
            ->setNombre('FT Sede Base ' . $suffix)
            ->setDomicilio('Calle Base 123')
            ->setTorneo($torneo);

        $this->entityManager->persist($sede);
        $this->entityManager->flush();

        return $sede;
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

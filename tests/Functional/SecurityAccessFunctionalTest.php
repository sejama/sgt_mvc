<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use App\Entity\Usuario;
use App\Repository\UsuarioRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SecurityAccessFunctionalTest extends WebTestCase
{
    private KernelBrowser $client;

    private EntityManagerInterface $entityManager;

    private UsuarioRepository $usuarioRepository;

    /** @var string[] */
    private array $usernamesToCleanup = [];

    protected function setUp(): void
    {
        $this->client = static::createClient();

        /** @var EntityManagerInterface $entityManager */
        $entityManager = static::getContainer()->get('doctrine.orm.entity_manager');
        $this->entityManager = $entityManager;

        /** @var UsuarioRepository $usuarioRepository */
        $usuarioRepository = $this->entityManager->getRepository(Usuario::class);
        $this->usuarioRepository = $usuarioRepository;
    }

    protected function tearDown(): void
    {
        foreach (array_unique($this->usernamesToCleanup) as $username) {
            $usuario = $this->usuarioRepository->findOneBy(['username' => $username]);

            if ($usuario instanceof Usuario) {
                $this->entityManager->remove($usuario);
            }
        }

        $this->entityManager->flush();
        $this->entityManager->clear();

        parent::tearDown();
    }

    public function testAnonimoNoPuedeAccederAAdminTorneo(): void
    {
        $this->client->request('GET', '/admin/torneo/');

        $response = $this->client->getResponse();
        $statusCode = $response->getStatusCode();

        if ($statusCode === 302) {
            self::assertResponseRedirects('/login');
            return;
        }

        self::assertContains($statusCode, [401, 403]);
    }

    public function testUsuarioSinRolAdminRecibe401EnAdminTorneo(): void
    {
        $usuario = $this->crearUsuario(['ROLE_USER']);
        $this->client->loginUser($usuario);

        $this->client->request('GET', '/admin/torneo/');

        self::assertResponseStatusCodeSame(401);
    }

    public function testUsuarioAdminAccedeAAdminUsuarioIndex(): void
    {
        $admin = $this->crearUsuario(['ROLE_ADMIN', 'ROLE_USER']);
        $this->client->loginUser($admin);

        $this->client->request('GET', '/admin/usuario/');

        self::assertResponseIsSuccessful();
    }

    public function testUsuarioSinRolAdminNoAccedeAAdminUsuarioIndex(): void
    {
        $usuario = $this->crearUsuario(['ROLE_USER']);
        $this->client->loginUser($usuario);

        $this->client->request('GET', '/admin/usuario/');

        self::assertResponseStatusCodeSame(403);
    }

    public function testAnonimoNoAccedeACambiarPassword(): void
    {
        $this->client->request('GET', '/admin/usuario/cambiar_password');

        self::assertResponseRedirects('/login');
    }

    public function testUsuarioAutenticadoAccedeACambiarPassword(): void
    {
        $usuario = $this->crearUsuario(['ROLE_USER']);
        $this->client->loginUser($usuario);

        $this->client->request('GET', '/admin/usuario/cambiar_password');

        self::assertResponseIsSuccessful();
    }

    /**
     * @dataProvider provideAdminRoutes
     */
    public function testAnonimoNoAccedeARutasAdmin(string $route): void
    {
        $this->client->request('GET', $route);

        $statusCode = $this->client->getResponse()->getStatusCode();

        if ($statusCode === 302) {
            self::assertResponseRedirects('/login');
            return;
        }

        self::assertContains($statusCode, [401, 403]);
    }

    /**
     * @dataProvider provideAdminRoutes
     */
    public function testUsuarioConRoleUserNoAccedeARutasAdmin(string $route): void
    {
        $usuario = $this->crearUsuario(['ROLE_USER']);
        $this->client->loginUser($usuario);

        $this->client->request('GET', $route);

        $statusCode = $this->client->getResponse()->getStatusCode();
        self::assertContains($statusCode, [401, 403]);
    }

    public static function provideAdminRoutes(): array
    {
        return [
            ['/admin/torneo/'],
            ['/admin/usuario/'],
            ['/admin/torneo/ruta-fake/categoria/nuevo'],
            ['/admin/torneo/ruta-fake/sede/nuevo'],
            ['/admin/torneo/ruta-fake/categoria/999/grupos'],
            ['/admin/torneo/ruta-fake/categoria/999/equipo/'],
        ];
    }

    /**
     * @dataProvider provideAdminRoutesForAdmin
     */
    public function testUsuarioAdminNoRecibe401Ni403EnRutasBaseAdmin(string $route): void
    {
        $admin = $this->crearUsuario(['ROLE_ADMIN', 'ROLE_USER']);
        $this->client->loginUser($admin);

        $this->client->request('GET', $route);

        $statusCode = $this->client->getResponse()->getStatusCode();
        self::assertNotContains($statusCode, [401, 403]);
    }

    public static function provideAdminRoutesForAdmin(): array
    {
        return [
            ['/admin/torneo/'],
            ['/admin/usuario/'],
            ['/admin/usuario/cambiar_password'],
        ];
    }

    /**
     * @param string[] $roles
     */
    private function crearUsuario(array $roles): Usuario
    {
        $username = 'ft_security_' . uniqid('', true);
        $this->usernamesToCleanup[] = $username;

        $usuario = (new Usuario())
            ->setUsername($username)
            ->setEmail($username . '@example.com')
            ->setRoles($roles)
            ->setPassword('test-password-hash');

        $this->entityManager->persist($usuario);
        $this->entityManager->flush();

        return $usuario;
    }
}

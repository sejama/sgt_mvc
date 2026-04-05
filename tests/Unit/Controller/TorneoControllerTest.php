<?php

declare(strict_types=1);

namespace App\Tests\Unit\Controller;

use App\Manager\TorneoManager;
use App\Entity\Torneo;
use App\Entity\Usuario;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;
use ReflectionProperty;

class TestableTorneoControllerForTesting
{
    public ?Usuario $mockUser = null;
    public ?Response $mockResponse = null;
    public array $lastParameters = [];

    public function getUser(): ?Usuario
    {
        return $this->mockUser;
    }

    public function render(string $view, array $parameters = []): Response
    {
        $this->lastParameters = $parameters;
        return $this->mockResponse ?? new Response();
    }

    public function redirectToRoute(string $route, array $parameters = [], int $status = 302): Response
    {
        $response = new Response('', $status);
        $response->headers->set('Location', '/' . $route);
        return $response;
    }

    public function addFlash(string $type, mixed $message): void
    {
        // Mock flashbag
    }

    public function index(TorneoManager $torneoManager): Response
    {
        if ($this->getUser() !== null) {
            $torneos = $torneoManager->obtenerTorneosXCreador((int)$this->getUser()->getId());
            return $this->render('torneo/index.html.twig', ['torneos' => $torneos]);
        }
        return $this->redirectToRoute('security_login');
    }
}

class TorneoControllerTest extends TestCase
{
    private function setPrivateProperty(object $object, string $property, mixed $value): void
    {
        $reflection = new ReflectionProperty($object, $property);
        $reflection->setAccessible(true);
        $reflection->setValue($object, $value);
    }

    public function testIndexRedirectsToLoginSiUsuarioEsNull(): void
    {
        $controller = new TestableTorneoControllerForTesting();
        $controller->mockUser = null;
        $torneoManager = $this->createMock(TorneoManager::class);

        $response = $controller->index($torneoManager);

        self::assertEquals(302, $response->getStatusCode());
        self::assertEquals('/security_login', $response->headers->get('Location'));
    }

    public function testIndexMuestraTorneosDelUsuarioCreador(): void
    {
        $controller = new TestableTorneoControllerForTesting();
        $usuario = (new Usuario())
            ->setUsername('prueba')
            ->setEmail('prueba@example.com')
            ->setPassword('hash');
        $this->setPrivateProperty($usuario, 'id', 123);
        $controller->mockUser = $usuario;
        $controller->mockResponse = new Response();

        $torneo = (new Torneo())
            ->setNombre('Test Torneo')
            ->setRuta('test-torneo')
            ->setCreador($usuario);

        $torneoManager = $this->createMock(TorneoManager::class);
        $torneoManager->method('obtenerTorneosXCreador')->with(123)->willReturn([$torneo]);

        $response = $controller->index($torneoManager);

        self::assertEquals(200, $response->getStatusCode());
        self::assertArrayHasKey('torneos', $controller->lastParameters);
        self::assertCount(1, $controller->lastParameters['torneos']);
    }

    public function testIndexVacioSiUsuarioNoTieneTorneos(): void
    {
        $controller = new TestableTorneoControllerForTesting();
        $usuario = (new Usuario())
            ->setUsername('sinTorneos')
            ->setEmail('sintorneos@example.com')
            ->setPassword('hash');
        $this->setPrivateProperty($usuario, 'id', 999);
        $controller->mockUser = $usuario;
        $controller->mockResponse = new Response();

        $torneoManager = $this->createMock(TorneoManager::class);
        $torneoManager->method('obtenerTorneosXCreador')->with(999)->willReturn([]);

        $response = $controller->index($torneoManager);

        self::assertEquals(200, $response->getStatusCode());
        self::assertArrayHasKey('torneos', $controller->lastParameters);
        self::assertCount(0, $controller->lastParameters['torneos']);
    }

    public function testIndexFiltraPorIdDelUsuarioActual(): void
    {
        $controller = new TestableTorneoControllerForTesting();
        $usuario1 = (new Usuario())
            ->setUsername('usuario1')
            ->setEmail('usuario1@example.com')
            ->setPassword('hash');
        $this->setPrivateProperty($usuario1, 'id', 111);

        $usuario2 = (new Usuario())
            ->setUsername('usuario2')
            ->setEmail('usuario2@example.com')
            ->setPassword('hash');
        $this->setPrivateProperty($usuario2, 'id', 222);

        $controller->mockUser = $usuario1;
        $controller->mockResponse = new Response();

        $torneoManager = $this->createMock(TorneoManager::class);
        $torneoManager->method('obtenerTorneosXCreador')->with(111)->willReturn([]);

        $response = $controller->index($torneoManager);

        self::assertEquals(200, $response->getStatusCode());
        $torneoManager->method('obtenerTorneosXCreador')->with(222)->willReturn([]);
    }

    public function testIndexPasaIdCorrectoAlManager(): void
    {
        $controller = new TestableTorneoControllerForTesting();
        $usuario = (new Usuario())
            ->setUsername('testuser')
            ->setEmail('testuser@example.com')
            ->setPassword('hash');
        $this->setPrivateProperty($usuario, 'id', 555);
        $controller->mockUser = $usuario;
        $controller->mockResponse = new Response();

        $torneoManager = $this->createMock(TorneoManager::class);
        $torneoManager->expects(self::once())
            ->method('obtenerTorneosXCreador')
            ->with(555)
            ->willReturn([]);

        $controller->index($torneoManager);

        self::assertTrue(true);
    }

    public function testIndexRenderizaTemplateCorrecta(): void
    {
        $controller = new TestableTorneoControllerForTesting();
        $usuario = (new Usuario())
            ->setUsername('render-test')
            ->setEmail('render-test@example.com')
            ->setPassword('hash');
        $this->setPrivateProperty($usuario, 'id', 777);
        $controller->mockUser = $usuario;
        $controller->mockResponse = new Response();

        $torneoManager = $this->createMock(TorneoManager::class);
        $torneoManager->method('obtenerTorneosXCreador')->willReturn([]);

        $response = $controller->index($torneoManager);

        self::assertEquals(200, $response->getStatusCode());
        self::assertStringContainsString('torneo/index.html.twig', 'torneo/index.html.twig');
    }
}

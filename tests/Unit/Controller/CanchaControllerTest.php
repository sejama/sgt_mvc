<?php

declare(strict_types=1);

namespace App\Tests\Unit\Controller;

use App\Controller\CanchaController;
use App\Entity\Cancha;
use App\Entity\Sede;
use App\Entity\Usuario;
use App\Exception\AppException;
use App\Manager\CanchaManager;
use App\Manager\SedeManager;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;

class CanchaControllerTest extends TestCase
{
    public function testIndexCanchaRendersMuestaSede(): void
    {
        $controller = new TestableCanchaController();
        $controller->testUser = (new Usuario())
            ->setUsername('admin')
            ->setPassword('hash')
            ->setRoles(['ROLE_ADMIN', 'ROLE_USER']);

        $sedeManager = $this->createMock(SedeManager::class);
        $sede = (new Sede())
            ->setNombre('Sede Test')
            ->setDomicilio('Calle Test 123');
        $sedeManager->expects($this->once())
            ->method('obtenerSede')
            ->with(1)
            ->willReturn($sede);

        $response = $controller->indexCancha('ruta-test', 1, $sedeManager);

        self::assertInstanceOf(Response::class, $response);
        self::assertSame('cancha/index.html.twig', $controller->lastTemplate);
        self::assertSame('ruta-test', $controller->lastParameters['ruta']);
        self::assertSame($sede, $controller->lastParameters['sede']);
    }

    public function testCrearCanchaPorPostYGuarda(): void
    {
        $controller = new TestableCanchaController();
        $controller->testUser = (new Usuario())
            ->setUsername('admin')
            ->setPassword('hash')
            ->setRoles(['ROLE_ADMIN', 'ROLE_USER']);

        $request = Request::create('/admin/torneo/ruta-test/sede/1/cancha/nuevo', 'POST', [
            'nombreCancha' => 'Cancha Nueva',
            'descripcionCancha' => 'Descripción test',
        ]);

        $sedeManager = $this->createMock(SedeManager::class);
        $sede = $this->createMock(Sede::class);
        $sede->method('getId')->willReturn(1);
        $sedeManager->method('obtenerSede')->willReturn($sede);

        $canchaManager = $this->createMock(CanchaManager::class);
        $canchaManager->expects($this->once())
            ->method('crearCancha')
            ->with($sede, 'Cancha Nueva', 'Descripción test');

        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects($this->once())->method('info');

        $response = $controller->crearCancha(
            'ruta-test',
            1,
            $request,
            $sedeManager,
            $canchaManager,
            $logger
        );

        self::assertInstanceOf(RedirectResponse::class, $response);
        self::assertSame(['success', 'Cancha creada con éxito.'], $controller->lastFlash);
    }

    public function testCrearCanchaManejaAppException(): void
    {
        $controller = new TestableCanchaController();
        $controller->testUser = (new Usuario())
            ->setUsername('admin')
            ->setPassword('hash')
            ->setRoles(['ROLE_ADMIN', 'ROLE_USER']);

        $request = Request::create('/admin/torneo/ruta-test/sede/1/cancha/nuevo', 'POST', [
            'nombreCancha' => 'Cancha',
            'descripcionCancha' => 'Desc',
        ]);

        $sedeManager = $this->createMock(SedeManager::class);
        $sede = new Sede();
        $sedeManager->method('obtenerSede')->willReturn($sede);

        $canchaManager = $this->createMock(CanchaManager::class);
        $canchaManager->method('crearCancha')
            ->willThrowException(new AppException('Error de negocio'));

        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects($this->once())->method('error');

        $response = $controller->crearCancha(
            'ruta-test',
            1,
            $request,
            $sedeManager,
            $canchaManager,
            $logger
        );

        self::assertInstanceOf(Response::class, $response);
        self::assertSame('cancha/nuevo.html.twig', $controller->lastTemplate);
        self::assertSame(['error', 'Error de negocio'], $controller->lastFlash);
    }

    public function testEditarCanchaPorPostYActualiza(): void
    {
        $controller = new TestableCanchaController();
        $controller->testUser = (new Usuario())
            ->setUsername('admin')
            ->setPassword('hash')
            ->setRoles(['ROLE_ADMIN', 'ROLE_USER']);

        $request = Request::create('/admin/torneo/ruta-test/sede/1/cancha/99/editar', 'POST', [
            'nombreCancha' => 'Cancha Editada',
            'descripcionCancha' => 'Nueva descripción',
        ]);

        $sedeManager = $this->createMock(SedeManager::class);
        $sede = $this->createMock(Sede::class);
        $sede->method('getId')->willReturn(1);
        $sedeManager->method('obtenerSede')->willReturn($sede);

        $cancha = (new Cancha())->setNombre('Cancha Vieja');
        $canchaManager = $this->createMock(CanchaManager::class);
        $canchaManager->method('obtenerCancha')->willReturn($cancha);
        $canchaManager->expects($this->once())
            ->method('editarCancha')
            ->with($cancha, 'Cancha Editada', 'Nueva descripción');

        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects($this->once())->method('info');

        $response = $controller->editarCancha(
            'ruta-test',
            1,
            99,
            $request,
            $sedeManager,
            $canchaManager,
            $logger
        );

        self::assertInstanceOf(RedirectResponse::class, $response);
        self::assertSame(['success', 'Cancha editada con éxito.'], $controller->lastFlash);
    }

    public function testEliminarCanchaPorGetYBorra(): void
    {
        $controller = new TestableCanchaController();
        $controller->testUser = (new Usuario())
            ->setUsername('admin')
            ->setPassword('hash')
            ->setRoles(['ROLE_ADMIN', 'ROLE_USER']);

        $request = Request::create('/admin/torneo/ruta-test/sede/1/cancha/99/eliminar', 'GET');

        $sedeManager = $this->createMock(SedeManager::class);
        $sede = $this->createMock(Sede::class);
        $sede->method('getId')->willReturn(1);
        $sedeManager->method('obtenerSede')->willReturn($sede);

        $cancha = (new Cancha())->setNombre('Cancha a Borrar');
        $canchaManager = $this->createMock(CanchaManager::class);
        $canchaManager->method('obtenerCancha')->willReturn($cancha);
        $canchaManager->expects($this->once())->method('eliminarCancha')->with($cancha);

        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects($this->once())->method('info');

        $response = $controller->eliminarCancha(
            'ruta-test',
            1,
            99,
            $request,
            $sedeManager,
            $canchaManager,
            $logger
        );

        self::assertInstanceOf(RedirectResponse::class, $response);
        self::assertSame(['success', 'Cancha eliminada con éxito.'], $controller->lastFlash);
    }

    public function testCrearCanchaManejaExcepcionGenericaYMuestraError(): void
    {
        $controller = new TestableCanchaController();
        $controller->testUser = (new Usuario())
            ->setUsername('admin')
            ->setPassword('hash')
            ->setRoles(['ROLE_ADMIN', 'ROLE_USER']);

        $request = Request::create('/admin/torneo/ruta-test/sede/1/cancha/nuevo', 'POST', [
            'nombreCancha' => 'Cancha',
            'descripcionCancha' => 'Desc',
        ]);

        $sedeManager = $this->createMock(SedeManager::class);
        $sede = new Sede();
        $sedeManager->method('obtenerSede')->willReturn($sede);

        $canchaManager = $this->createMock(CanchaManager::class);
        $canchaManager->method('crearCancha')
            ->willThrowException(new \RuntimeException('Error inesperado'));

        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects($this->atLeastOnce())->method('error');

        $response = $controller->crearCancha(
            'ruta-test',
            1,
            $request,
            $sedeManager,
            $canchaManager,
            $logger
        );

        self::assertInstanceOf(Response::class, $response);
        self::assertSame('cancha/nuevo.html.twig', $controller->lastTemplate);
        self::assertSame(['error', 'Ha ocurrido un error inesperado. Por favor, intente nuevamente.'], $controller->lastFlash);
    }
}

class TestableCanchaController extends CanchaController
{
    public ?UserInterface $testUser = null;
    public ?string $lastTemplate = null;
    public array $lastParameters = [];
    public array $lastFlash = [];

    public function getUser(): ?UserInterface
    {
        return $this->testUser;
    }

    protected function render(string $view, array $parameters = [], Response $response = null): Response
    {
        $this->lastTemplate = $view;
        $this->lastParameters = $parameters;
        return $response ?? new Response('ok');
    }

    public function redirectToRoute(string $route, array $parameters = [], int $status = 302): RedirectResponse
    {
        return new RedirectResponse('/' . $route, $status);
    }

    public function addFlash(string $type, mixed $message): void
    {
        $this->lastFlash = [$type, (string) $message];
    }
}

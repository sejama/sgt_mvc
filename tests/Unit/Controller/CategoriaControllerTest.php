<?php

declare(strict_types=1);

namespace App\Tests\Unit\Controller;

use App\Controller\CategoriaController;
use App\Entity\Categoria;
use App\Entity\Torneo;
use App\Entity\Usuario;
use App\Manager\CategoriaManager;
use App\Manager\TorneoManager;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\User\UserInterface;

class CategoriaControllerTest extends TestCase
{
    public function testCrearCategoriaSinUsuarioRedirigeALogin(): void
    {
        $controller = new TestableCategoriaController();
        $controller->testUser = null;

        $torneo = new Torneo();
        $request = Request::create('/admin/torneo/ruta-test/categoria/nuevo', 'GET');

        $torneoManager = $this->createMock(TorneoManager::class);
        $torneoManager->method('obtenerTorneo')->willReturn($torneo);

        $categoriaManager = $this->createMock(CategoriaManager::class);
        $entityManager = $this->createMock(\Doctrine\ORM\EntityManagerInterface::class);
        $logger = $this->createMock(LoggerInterface::class);

        $response = $controller->crearCategoria('ruta-test', $torneoManager, $categoriaManager, $entityManager, $request, $logger);

        self::assertInstanceOf(RedirectResponse::class, $response);
        self::assertSame('/security_login', $response->getTargetUrl());
    }

    public function testCrearCategoriaPorPostRegistraYRedirige(): void
    {
        $controller = new TestableCategoriaController();
        $controller->testUser = (new Usuario())
            ->setUsername('admin')
            ->setPassword('hash')
            ->setRoles(['ROLE_ADMIN', 'ROLE_USER']);

        $torneo = new Torneo();
        $request = Request::create('/admin/torneo/ruta-test/categoria/nuevo', 'POST', [
            'genero' => 'Masculino',
            'nombre' => 'Categoria Nueva',
            'nombreCorto' => 'CATN',
        ]);

        $torneoManager = $this->createMock(TorneoManager::class);
        $torneoManager->expects(self::once())
            ->method('obtenerTorneo')
            ->with('ruta-test')
            ->willReturn($torneo);

        $categoriaManager = $this->createMock(CategoriaManager::class);
        $categoriaManager->expects(self::once())
            ->method('crearCategoria')
            ->with($torneo, 'Masculino', 'Categoria Nueva', 'CATN');

        $entityManager = $this->createMock(\Doctrine\ORM\EntityManagerInterface::class);
        $entityManager->expects(self::once())->method('flush');

        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects(self::once())->method('info');

        $response = $controller->crearCategoria('ruta-test', $torneoManager, $categoriaManager, $entityManager, $request, $logger);

        self::assertInstanceOf(RedirectResponse::class, $response);
        self::assertSame('/admin_torneo_index', $response->getTargetUrl());
        self::assertSame(['success', 'Categoría creada con éxito.'], $controller->lastFlash);
    }

    public function testCrearCategoriaManejaExcepcionGenericaYRenderizaFormulario(): void
    {
        $controller = new TestableCategoriaController();
        $controller->testUser = (new Usuario())
            ->setUsername('admin')
            ->setPassword('hash')
            ->setRoles(['ROLE_ADMIN', 'ROLE_USER']);

        $torneo = new Torneo();
        $request = Request::create('/admin/torneo/ruta-test/categoria/nuevo', 'POST', [
            'genero' => 'Masculino',
            'nombre' => 'Categoria Nueva',
            'nombreCorto' => 'CATN',
        ]);

        $torneoManager = $this->createMock(TorneoManager::class);
        $torneoManager->expects(self::once())
            ->method('obtenerTorneo')
            ->with('ruta-test')
            ->willReturn($torneo);

        $categoriaManager = $this->createMock(CategoriaManager::class);
        $categoriaManager->expects(self::once())
            ->method('crearCategoria')
            ->willThrowException(new \RuntimeException('boom-crear-categoria'));

        $entityManager = $this->createMock(\Doctrine\ORM\EntityManagerInterface::class);
        $entityManager->expects(self::never())->method('flush');

        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects(self::once())->method('error')->with('boom-crear-categoria');

        $response = $controller->crearCategoria('ruta-test', $torneoManager, $categoriaManager, $entityManager, $request, $logger);

        self::assertInstanceOf(Response::class, $response);
        self::assertSame('categoria/nuevo.html.twig', $controller->lastTemplate);
        self::assertSame(['error', 'Ha ocurrido un error inesperado. Por favor, intente nuevamente.'], $controller->lastFlash);
    }

    public function testEditarCategoriaPorPostActualizaYRedirige(): void
    {
        $controller = new TestableCategoriaController();
        $controller->testUser = (new Usuario())
            ->setUsername('admin')
            ->setPassword('hash')
            ->setRoles(['ROLE_ADMIN', 'ROLE_USER']);

        $torneo = new Torneo();
        $categoria = new Categoria();
        $request = Request::create('/admin/torneo/ruta-test/categoria/7/editar/', 'POST', [
            'genero' => 'Femenino',
            'nombre' => 'Categoria Editada',
            'nombreCorto' => 'CATE',
        ]);

        $torneoManager = $this->createMock(TorneoManager::class);
        $torneoManager->expects(self::once())
            ->method('obtenerTorneo')
            ->with('ruta-test')
            ->willReturn($torneo);

        $categoriaManager = $this->createMock(CategoriaManager::class);
        $categoriaManager->expects(self::once())
            ->method('obtenerCategoria')
            ->with(7)
            ->willReturn($categoria);
        $categoriaManager->expects(self::once())
            ->method('editarCategoria')
            ->with($categoria, 'Femenino', 'Categoria Editada', 'CATE');

        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects(self::once())->method('info');

        $response = $controller->editarCategoria('ruta-test', 7, $torneoManager, $categoriaManager, $request, $logger);

        self::assertInstanceOf(RedirectResponse::class, $response);
        self::assertSame('/admin_torneo_index', $response->getTargetUrl());
        self::assertSame(['success', 'Categoría editada con éxito.'], $controller->lastFlash);
    }

    public function testEditarCategoriaSinUsuarioRedirigeALogin(): void
    {
        $controller = new TestableCategoriaController();
        $controller->testUser = null;

        $request = Request::create('/admin/torneo/ruta-test/categoria/7/editar/', 'GET');

        $torneoManager = $this->createMock(TorneoManager::class);
        $torneoManager->method('obtenerTorneo')->willReturn(new Torneo());

        $categoriaManager = $this->createMock(CategoriaManager::class);
        $logger = $this->createMock(LoggerInterface::class);

        $response = $controller->editarCategoria('ruta-test', 7, $torneoManager, $categoriaManager, $request, $logger);

        self::assertInstanceOf(RedirectResponse::class, $response);
        self::assertSame('/security_login', $response->getTargetUrl());
    }

    public function testEditarCategoriaManejaExcepcionGenericaYRenderizaFormulario(): void
    {
        $controller = new TestableCategoriaController();
        $controller->testUser = (new Usuario())
            ->setUsername('admin')
            ->setPassword('hash')
            ->setRoles(['ROLE_ADMIN', 'ROLE_USER']);

        $torneo = new Torneo();
        $categoria = new Categoria();
        $request = Request::create('/admin/torneo/ruta-test/categoria/7/editar/', 'POST', [
            'genero' => 'Femenino',
            'nombre' => 'Categoria Editada',
            'nombreCorto' => 'CATE',
        ]);

        $torneoManager = $this->createMock(TorneoManager::class);
        $torneoManager->expects(self::once())
            ->method('obtenerTorneo')
            ->with('ruta-test')
            ->willReturn($torneo);

        $categoriaManager = $this->createMock(CategoriaManager::class);
        $categoriaManager->expects(self::once())
            ->method('obtenerCategoria')
            ->with(7)
            ->willReturn($categoria);
        $categoriaManager->expects(self::once())
            ->method('editarCategoria')
            ->willThrowException(new \RuntimeException('boom-editar-categoria'));

        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects(self::once())
            ->method('error')
            ->with('boom-editar-categoria');

        $response = $controller->editarCategoria('ruta-test', 7, $torneoManager, $categoriaManager, $request, $logger);

        self::assertInstanceOf(Response::class, $response);
        self::assertSame('categoria/editar.html.twig', $controller->lastTemplate);
        self::assertSame(['error', 'Ha ocurrido un error inesperado. Por favor, intente nuevamente.'], $controller->lastFlash);
    }

    public function testEditarDisputaManejaErrorInesperadoYRenderizaFormulario(): void
    {
        $controller = new TestableCategoriaController();
        $controller->testUser = (new Usuario())
            ->setUsername('admin')
            ->setPassword('hash')
            ->setRoles(['ROLE_ADMIN', 'ROLE_USER']);

        $torneo = new Torneo();
        $categoria = new Categoria();

        $torneoManager = $this->createMock(TorneoManager::class);
        $torneoManager->expects(self::once())
            ->method('obtenerTorneo')
            ->with('ruta-test')
            ->willReturn($torneo);

        $categoriaManager = $this->createMock(CategoriaManager::class);
        $categoriaManager->expects(self::once())
            ->method('obtenerCategoria')
            ->with(7)
            ->willReturn($categoria);
        $categoriaManager->expects(self::once())
            ->method('editarDisputa')
            ->willThrowException(new \RuntimeException('boom-disputa'));

        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects(self::once())
            ->method('error')
            ->with('boom-disputa');

        $request = Request::create('/admin/torneo/ruta-test/categoria/7/editar/disputa/', 'POST', [
            'disputa' => 'Simple',
        ]);

        $response = $controller->editarDisputa('ruta-test', 7, $torneoManager, $categoriaManager, $request, $logger);

        self::assertInstanceOf(Response::class, $response);
        self::assertSame('categoria/editar_disputa.html.twig', $controller->lastTemplate);
        self::assertSame(['error', 'Ha ocurrido un error inesperado. Por favor, intente nuevamente.'], $controller->lastFlash);
    }

    public function testCerrarCategoriaManejaErrorInesperadoYRedirigeALogin(): void
    {
        $controller = new TestableCategoriaController();
        $controller->testUser = (new Usuario())
            ->setUsername('admin')
            ->setPassword('hash')
            ->setRoles(['ROLE_ADMIN', 'ROLE_USER']);

        $categoriaManager = $this->createMock(CategoriaManager::class);
        $categoriaManager->expects(self::once())
            ->method('obtenerCategoria')
            ->with(9)
            ->willThrowException(new \RuntimeException('boom-cerrar'));

        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects(self::once())
            ->method('error')
            ->with('boom-cerrar');

        $response = $controller->cerrarCategoria('ruta-test', 9, $categoriaManager, $logger);

        self::assertInstanceOf(RedirectResponse::class, $response);
        self::assertSame('/security_login', $response->getTargetUrl());
        self::assertSame(['error', 'Ha ocurrido un error inesperado. Por favor, intente nuevamente.'], $controller->lastFlash);
    }

    public function testEliminarCategoriaConCsrfInvalidoLanzaExcepcion(): void
    {
        $controller = new TestableCategoriaControllerCsrfInvalido();
        $controller->testUser = (new Usuario())
            ->setUsername('admin')
            ->setPassword('hash')
            ->setRoles(['ROLE_ADMIN', 'ROLE_USER']);

        $request = Request::create('/admin/torneo/ruta-test/categoria/7/eliminar', 'POST', [
            '_token' => 'token-invalido',
        ]);

        $torneoManager = $this->createMock(TorneoManager::class);
        $torneoManager->method('obtenerTorneo')->willReturn(new Torneo());

        $categoriaManager = $this->createMock(CategoriaManager::class);
        $logger = $this->createMock(LoggerInterface::class);

        $this->expectException(AccessDeniedException::class);
        $this->expectExceptionMessage('Token CSRF inválido.');

        $controller->eliminarCategoria('ruta-test', 7, $request, $torneoManager, $categoriaManager, $logger);
    }

    public function testEliminarCategoriaExitosoRedirigeAIndex(): void
    {
        $controller = new TestableCategoriaController();
        $controller->testUser = (new Usuario())
            ->setUsername('admin')
            ->setPassword('hash')
            ->setRoles(['ROLE_ADMIN', 'ROLE_USER']);

        $request = Request::create('/admin/torneo/ruta-test/categoria/7/eliminar', 'POST', [
            '_token' => 'token-valido',
        ]);

        $torneoManager = $this->createMock(TorneoManager::class);
        $torneoManager->expects(self::once())
            ->method('obtenerTorneo')
            ->with('ruta-test')
            ->willReturn(new Torneo());

        $categoriaManager = $this->createMock(CategoriaManager::class);
        $categoriaManager->expects(self::once())
            ->method('eliminarCategoria')
            ->with(7);

        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects(self::once())->method('info');

        $response = $controller->eliminarCategoria('ruta-test', 7, $request, $torneoManager, $categoriaManager, $logger);

        self::assertInstanceOf(RedirectResponse::class, $response);
        self::assertSame('/admin_torneo_index', $response->getTargetUrl());
        self::assertSame(['success', 'Categoría eliminada con éxito.'], $controller->lastFlash);
    }

    public function testCerrarCategoriaExitosoRedirigeAEquipos(): void
    {
        $controller = new TestableCategoriaController();
        $controller->testUser = (new Usuario())
            ->setUsername('admin')
            ->setPassword('hash')
            ->setRoles(['ROLE_ADMIN', 'ROLE_USER']);

        $categoria = new Categoria();
        $categoria->setNombre('Categoria');

        $categoriaManager = $this->createMock(CategoriaManager::class);
        $categoriaManager->expects(self::once())
            ->method('obtenerCategoria')
            ->with(9)
            ->willReturn($categoria);
        $categoriaManager->expects(self::once())
            ->method('cerrarCategoria')
            ->with($categoria);

        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects(self::once())->method('info');

        $response = $controller->cerrarCategoria('ruta-test', 9, $categoriaManager, $logger);

        self::assertInstanceOf(RedirectResponse::class, $response);
        self::assertSame('/admin_equipo_index', $response->getTargetUrl());
        self::assertSame(['success', 'Categoría cerrada con éxito.'], $controller->lastFlash);
    }
}

class TestableCategoriaController extends CategoriaController
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

    protected function isCsrfTokenValid(string $id, ?string $token): bool
    {
        return true;
    }
}

class TestableCategoriaControllerCsrfInvalido extends TestableCategoriaController
{
    protected function isCsrfTokenValid(string $id, ?string $token): bool
    {
        return false;
    }
}

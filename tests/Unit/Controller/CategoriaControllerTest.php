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
use Symfony\Component\Security\Core\User\UserInterface;

class CategoriaControllerTest extends TestCase
{
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
}

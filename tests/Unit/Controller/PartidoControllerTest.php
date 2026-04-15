<?php

declare(strict_types=1);

namespace App\Tests\Unit\Controller;

use App\Controller\PartidoController;
use App\Entity\Partido;
use App\Entity\Usuario;
use App\Exception\AppException;
use App\Manager\PartidoManager;
use App\Utils\GenerarPdf;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\User\UserInterface;

class PartidoControllerTest extends TestCase
{
    public function testGetLogUserIdRetornaAnonCuandoNoHayUsuario(): void
    {
        $controller = new class () extends PartidoController {
            public function getUser(): ?UserInterface
            {
                return null;
            }
        };

        $method = new \ReflectionMethod(PartidoController::class, 'getLogUserId');
        $method->setAccessible(true);

        $result = $method->invoke($controller);

        self::assertSame('anon', $result);
    }

    public function testGetLogUserIdRetornaIdCuandoHayUsuario(): void
    {
        $usuario = $this->createMock(Usuario::class);
        $usuario->method('getId')->willReturn(42);

        $controller = new class ($usuario) extends PartidoController {
            public function __construct(private Usuario $usuario)
            {
            }

            public function getUser(): ?UserInterface
            {
                return $this->usuario;
            }
        };

        $method = new \ReflectionMethod(PartidoController::class, 'getLogUserId');
        $method->setAccessible(true);

        $result = $method->invoke($controller);

        self::assertSame('42', $result);
    }

    public function testGenerarPdfRetornaRespuestaPdf(): void
    {
        $controller = new TestablePartidoController();
        $controller->testUser = (new Usuario())
            ->setUsername('admin')
            ->setPassword('hash')
            ->setRoles(['ROLE_ADMIN', 'ROLE_USER']);
        $controller->projectDir = sys_get_temp_dir() . '/sgt-partido-' . uniqid('', true);

        $pdfDir = $controller->projectDir . '/public/assets/planillas/ruta-test/pdf';
        mkdir($pdfDir, 0777, true);
        file_put_contents($pdfDir . '/partido-7.pdf', 'pdf');

        $partido = $this->createMock(Partido::class);
        $partidoManager = $this->createMock(PartidoManager::class);
        $partidoManager->expects(self::once())
            ->method('obtenerPartido')
            ->with('ruta-test', 7)
            ->willReturn($partido);

        $generarPdf = $this->createMock(GenerarPdf::class);
        $generarPdf->expects(self::once())
            ->method('generarPdf')
            ->with($partido, 'ruta-test');

        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects(self::once())->method('info');

        $response = $controller->generarPDF('ruta-test', 7, $partidoManager, $generarPdf, $logger);

        self::assertInstanceOf(BinaryFileResponse::class, $response);
        self::assertSame('application/pdf', $response->headers->get('Content-Type'));
        self::assertStringContainsString('partido-7.pdf', (string) $response->headers->get('Content-Disposition'));
    }

    public function testGenerarPdfConAppExceptionRedirigeAError(): void
    {
        $controller = new TestablePartidoController();
        $controller->testUser = (new Usuario())
            ->setUsername('admin')
            ->setPassword('hash')
            ->setRoles(['ROLE_ADMIN', 'ROLE_USER']);

        $partidoManager = $this->createMock(PartidoManager::class);
        $partidoManager->expects(self::once())
            ->method('obtenerPartido')
            ->with('ruta-test', 7)
            ->willThrowException(new AppException('PDF inválido'));

        $generarPdf = $this->createMock(GenerarPdf::class);
        $generarPdf->expects(self::never())->method('generarPdf');

        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects(self::once())
            ->method('error')
            ->with(self::stringContains('PDF inválido'));

        $response = $controller->generarPDF('ruta-test', 7, $partidoManager, $generarPdf, $logger);

        self::assertInstanceOf(RedirectResponse::class, $response);
        self::assertSame('/app_error_page', $response->headers->get('Location'));
    }

    public function testCargarResultadoSinUsuarioRedirigeALogin(): void
    {
        $controller = new TestablePartidoController();
        $controller->testUser = null;

        $request = Request::create('/admin/torneo/ruta-test/partido/5/cargar_resultado', 'POST', [
            'puntosLocal' => ['1'],
            'puntosVisitante' => ['0'],
        ]);
        $request->setSession(new Session(new MockArraySessionStorage()));

        $partido = $this->createMock(Partido::class);
        $partidoManager = $this->createMock(PartidoManager::class);
        $partidoManager->expects(self::once())
            ->method('obtenerPartido')
            ->with('ruta-test', 5)
            ->willReturn($partido);

        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects(self::never())->method('error');

        $response = $controller->cargarResultado('ruta-test', 5, $request, $partidoManager, $logger);

        self::assertInstanceOf(RedirectResponse::class, $response);
        self::assertSame('/security_login', $response->headers->get('Location'));
        self::assertSame($request->getUri(), $request->getSession()->get('_security.main.target_path'));
    }

    public function testCargarResultadoConUsuarioRedirigeAForbidden(): void
    {
        $controller = new TestablePartidoController();
        $controller->testUser = (new Usuario())
            ->setUsername('admin')
            ->setPassword('hash')
            ->setRoles(['ROLE_ADMIN', 'ROLE_USER']);

        $request = Request::create('/admin/torneo/ruta-test/partido/5/cargar_resultado', 'POST', [
            'puntosLocal' => ['1'],
            'puntosVisitante' => ['0'],
        ]);
        $request->setSession(new Session(new MockArraySessionStorage()));

        $partido = $this->createMock(Partido::class);
        $partidoManager = $this->createMock(PartidoManager::class);
        $partidoManager->expects(self::once())
            ->method('obtenerPartido')
            ->with('ruta-test', 5)
            ->willReturn($partido);

        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects(self::once())
            ->method('error')
            ->with(self::stringContains('Acceso denegado'));

        $response = $controller->cargarResultado('ruta-test', 5, $request, $partidoManager, $logger);

        self::assertInstanceOf(RedirectResponse::class, $response);
        self::assertSame('/app_error_forbidden', $response->headers->get('Location'));
    }
}

class TestablePartidoController extends PartidoController
{
    public ?UserInterface $testUser = null;
    public string $projectDir = '';

    public function getUser(): ?UserInterface
    {
        return $this->testUser;
    }

    public function isGranted(mixed $attributes, mixed $subject = null): bool
    {
        return false;
    }

    public function denyAccessUnlessGranted(mixed $attributes, mixed $subject = null, string $message = 'Access Denied.', ?int $statusCode = 403): void
    {
        throw new AccessDeniedException($message);
    }

    protected function render(string $view, array $parameters = [], Response $response = null): Response
    {
        return $response ?? new Response('ok');
    }

    public function redirectToRoute(string $route, array $parameters = [], int $status = 302): RedirectResponse
    {
        $response = new RedirectResponse('/' . $route, $status);

        return $response;
    }

    public function addFlash(string $type, mixed $message): void
    {
    }

    public function getParameter(string $name): array|string|int|bool|float|null
    {
        if ($name === 'kernel.project_dir') {
            return $this->projectDir;
        }

        return parent::getParameter($name);
    }
}

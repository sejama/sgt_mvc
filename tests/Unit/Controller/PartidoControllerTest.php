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

    public function testIndexRenderizaDatosAgrupadosDelTorneo(): void
    {
        $controller = new TestablePartidoController();
        $controller->testUser = (new Usuario())
            ->setUsername('admin')
            ->setPassword('hash')
            ->setRoles(['ROLE_ADMIN', 'ROLE_USER']);

        $torneo = $this->createMock(\App\Entity\Torneo::class);

        $torneoManager = $this->createMock(\App\Manager\TorneoManager::class);
        $torneoManager->expects(self::once())
            ->method('obtenerTorneo')
            ->with('ruta-test')
            ->willReturn($torneo);

        $partidoManager = $this->createMock(PartidoManager::class);
        $partidoManager->expects(self::once())
            ->method('obtenerPartidosSinAsignarXTorneo')
            ->with('ruta-test')
            ->willReturn(['clasificatorios' => []]);
        $partidoManager->expects(self::once())
            ->method('obtenerPartidosProgramadosXTorneo')
            ->with('ruta-test')
            ->willReturn(['Sede A' => ['Cancha 1' => []]]);
        $partidoManager->expects(self::once())
            ->method('obtenerSedesyCanchasXTorneo')
            ->with('ruta-test')
            ->willReturn([
                ['sede' => 'Sede A', 'id' => 1, 'sedeId' => 10, 'cancha' => 'Cancha 1'],
                ['sede' => 'Sede A', 'id' => 2, 'sedeId' => 10, 'cancha' => 'Cancha 2'],
            ]);
        $partidoManager->expects(self::once())
            ->method('obtenerHorariosProgramadosXTorneo')
            ->with('ruta-test')
            ->willReturn(['porCancha' => []]);

        $response = $controller->index('ruta-test', $partidoManager, $torneoManager);

        self::assertInstanceOf(Response::class, $response);
        self::assertSame('partido/index.html.twig', $controller->lastTemplate);
        self::assertSame($torneo, $controller->lastParameters['torneo']);
        self::assertSame(['clasificatorios' => []], $controller->lastParameters['partidosSinAsignar']);
        self::assertArrayHasKey('Sede A', $controller->lastParameters['canchas']);
    }

    public function testCrearPartidoClasificatorioPorGetCalculaTiposYRenderiza(): void
    {
        $controller = new TestablePartidoController();
        $controller->testUser = (new Usuario())
            ->setUsername('admin')
            ->setPassword('hash')
            ->setRoles(['ROLE_ADMIN', 'ROLE_USER']);

        $request = Request::create('/admin/torneo/ruta-test/categoria/7/partido/crear', 'GET');

        $grupo = $this->createMock(\App\Entity\Grupo::class);
        $grupo->method('getClasificaOro')->willReturn(4);
        $grupo->method('getClasificaPlata')->willReturn(2);
        $grupo->method('getClasificaBronce')->willReturn(8);

        $categoria = $this->createMock(\App\Entity\Categoria::class);
        $categoria->method('getGrupos')->willReturn([$grupo]);

        $categoriaManager = $this->createMock(\App\Manager\CategoriaManager::class);
        $categoriaManager->expects(self::once())
            ->method('obtenerCategoria')
            ->with(7)
            ->willReturn($categoria);

        $torneo = $this->createMock(\App\Entity\Torneo::class);
        $torneoManager = $this->createMock(\App\Manager\TorneoManager::class);
        $torneoManager->expects(self::once())
            ->method('obtenerTorneo')
            ->with('ruta-test')
            ->willReturn($torneo);

        $equipoManager = $this->createMock(\App\Manager\EquipoManager::class);
        $partidoManager = $this->createMock(PartidoManager::class);
        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects(self::never())->method('info');

        $response = $controller->crearPartidoClasificatorio(
            'ruta-test',
            7,
            $categoriaManager,
            $torneoManager,
            $equipoManager,
            $partidoManager,
            $request,
            $logger
        );

        self::assertInstanceOf(Response::class, $response);
        self::assertSame('partido/crear.html.twig', $controller->lastTemplate);
        self::assertSame(['Semi Final Oro', 'Final Oro'], $controller->lastParameters['tipoOro']);
        self::assertSame(['Final Plata'], $controller->lastParameters['tipoPlata']);
        self::assertSame(['Cuartos de Final Bronce', 'Semi Final Bronce', 'Final Bronce'], $controller->lastParameters['tipoBronce']);
    }

    public function testGestionarPartidoPorPostCrearRedirige(): void
    {
        $controller = new TestablePartidoController();
        $controller->testUser = (new Usuario())
            ->setUsername('admin')
            ->setPassword('hash')
            ->setRoles(['ROLE_ADMIN', 'ROLE_USER']);

        $request = Request::create('/admin/torneo/ruta-test/partido/gestionar', 'POST', [
            'accion' => 'crear',
        ]);

        $partido = $this->createMock(\App\Entity\Partido::class);
        $partido->method('getId')->willReturn(123);

        $partidoManager = $this->createMock(PartidoManager::class);
        $partidoManager->expects(self::once())
            ->method('crearPartidoManual')
            ->with(
                'ruta-test',
                self::callback(static fn (array $data): bool => ($data['accion'] ?? '') === 'crear')
            )
            ->willReturn($partido);

        $torneoManager = $this->createMock(\App\Manager\TorneoManager::class);
        $torneoManager->expects(self::never())->method('obtenerTorneo');

        $categoriaManager = $this->createMock(\App\Manager\CategoriaManager::class);
        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects(self::once())->method('info');

        $response = $controller->gestionarPartido('ruta-test', $request, $torneoManager, $categoriaManager, $partidoManager, $logger);

        self::assertInstanceOf(RedirectResponse::class, $response);
        self::assertSame(['success', 'Partido creado correctamente.'], $controller->lastFlash);
        self::assertSame('/admin_partido_gestionar', $response->headers->get('Location'));
    }

    public function testGestionarPartidoPorPostEditarRedirige(): void
    {
        $controller = new TestablePartidoController();
        $controller->testUser = (new Usuario())
            ->setUsername('admin')
            ->setPassword('hash')
            ->setRoles(['ROLE_ADMIN', 'ROLE_USER']);

        $request = Request::create('/admin/torneo/ruta-test/partido/gestionar', 'POST', [
            'accion' => 'editar',
        ]);

        $partido = $this->createMock(\App\Entity\Partido::class);
        $partido->method('getId')->willReturn(124);

        $partidoManager = $this->createMock(PartidoManager::class);
        $partidoManager->expects(self::once())
            ->method('editarPartidoManual')
            ->with(
                'ruta-test',
                self::callback(static fn (array $data): bool => ($data['accion'] ?? '') === 'editar')
            )
            ->willReturn($partido);

        $torneoManager = $this->createMock(\App\Manager\TorneoManager::class);
        $categoriaManager = $this->createMock(\App\Manager\CategoriaManager::class);
        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects(self::once())->method('info');

        $response = $controller->gestionarPartido('ruta-test', $request, $torneoManager, $categoriaManager, $partidoManager, $logger);

        self::assertInstanceOf(RedirectResponse::class, $response);
        self::assertSame(['success', 'Partido editado correctamente.'], $controller->lastFlash);
        self::assertSame('/admin_partido_gestionar', $response->headers->get('Location'));
    }

    public function testEditarPartidoPorPostEditaYGeneraPdf(): void
    {
        $controller = new TestablePartidoController();
        $controller->testUser = (new Usuario())
            ->setUsername('admin')
            ->setPassword('hash')
            ->setRoles(['ROLE_ADMIN', 'ROLE_USER']);

        $request = Request::create('/admin/torneo/ruta-test/partido/editar', 'POST', [
            'var_partidoId' => '7',
            'var_cancha' => '2',
            'var_horario' => '2026-05-01 10:30',
        ]);

        $partido = $this->createMock(\App\Entity\Partido::class);

        $partidoManager = $this->createMock(PartidoManager::class);
        $partidoManager->expects(self::once())
            ->method('editarPartido')
            ->with('ruta-test', 7, 2, '2026-05-01 10:30');
        $partidoManager->expects(self::once())
            ->method('obtenerPartidoxId')
            ->with(7)
            ->willReturn($partido);

        $generarPdf = $this->createMock(GenerarPdf::class);
        $generarPdf->expects(self::once())
            ->method('generarPdf')
            ->with($partido, 'ruta-test');

        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects(self::once())->method('info');

        $response = $controller->editarPartido('ruta-test', $request, $partidoManager, $generarPdf, $logger);

        self::assertInstanceOf(RedirectResponse::class, $response);
        self::assertSame(['success', 'Partido editado correctamente.'], $controller->lastFlash);
        self::assertSame('/admin_partido_index', $response->headers->get('Location'));
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

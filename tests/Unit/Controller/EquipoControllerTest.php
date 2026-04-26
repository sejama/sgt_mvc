<?php

declare(strict_types=1);

namespace App\Tests\Unit\Controller;

use App\Controller\EquipoController;
use App\Entity\Categoria;
use App\Entity\Equipo;
use App\Entity\Torneo;
use App\Entity\Usuario;
use App\Exception\AppException;
use App\Manager\CategoriaManager;
use App\Manager\EquipoManager;
use App\Manager\JugadorManager;
use App\Manager\TorneoManager;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\User\UserInterface;

class EquipoControllerTest extends TestCase
{
    private function invokePrivateMethod(object $object, string $methodName, array $args = []): mixed
    {
        $method = new \ReflectionMethod($object, $methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $args);
    }

    public function testIndexEquipoRenderizaTorneoCategoriaYEquipos(): void
    {
        $controller = new TestableEquipoController();
        $controller->testUser = (new Usuario())
            ->setUsername('admin')
            ->setPassword('hash')
            ->setRoles(['ROLE_ADMIN', 'ROLE_USER']);

        $torneoManager = $this->createMock(TorneoManager::class);
        $torneo = $this->createMock(Torneo::class);
        $torneoManager->expects($this->once())
            ->method('obtenerTorneo')
            ->with('ruta-test')
            ->willReturn($torneo);

        $categoriaManager = $this->createMock(CategoriaManager::class);
        $categoria = $this->createMock(Categoria::class);
        $categoriaManager->expects($this->once())
            ->method('obtenerCategoria')
            ->with(7)
            ->willReturn($categoria);

        $equipoManager = $this->createMock(EquipoManager::class);
        $equipos = [$this->createMock(Equipo::class)];
        $equipoManager->expects($this->once())
            ->method('obtenerEquiposPorCategoria')
            ->with($categoria)
            ->willReturn($equipos);

        $response = $controller->indexEquipo('ruta-test', 7, $torneoManager, $equipoManager, $categoriaManager);

        self::assertInstanceOf(Response::class, $response);
        self::assertSame('equipo/index.html.twig', $controller->lastTemplate);
        self::assertSame($torneo, $controller->lastParameters['torneo']);
        self::assertSame($categoria, $controller->lastParameters['categoria']);
        self::assertSame($equipos, $controller->lastParameters['equipos']);
    }

    public function testCrearEquipoPorPostYGuarda(): void
    {
        $controller = new TestableEquipoController();
        $controller->testUser = (new Usuario())
            ->setUsername('admin')
            ->setPassword('hash')
            ->setRoles(['ROLE_ADMIN', 'ROLE_USER']);

        $request = Request::create('/admin/torneo/ruta-test/categoria/7/equipo/nuevo', 'POST', [
            'nombre' => 'Equipo Nuevo',
            'nombreCorto' => 'EQN',
            'pais' => 'Argentina',
            'provincia' => 'Cba',
            'localidad' => 'Centro',
            'delegado' => [[
                'nombre' => 'Juan',
                'apellido' => 'Perez',
                'tipoDocumento' => 'DNI',
                'numeroDocumento' => '12345678',
                'email' => 'juan@example.com',
                'celular' => '2615551234',
            ]],
        ]);

        $categoriaManager = $this->createMock(CategoriaManager::class);
        $categoria = $this->createMock(Categoria::class);
        $categoriaManager->method('obtenerCategoria')->willReturn($categoria);

        $equipo = $this->createMock(Equipo::class);
        $equipo->method('getId')->willReturn(99);

        $equipoManager = $this->createMock(EquipoManager::class);
        $equipoManager->expects($this->once())
            ->method('crearEquipo')
            ->with($categoria, 'Equipo Nuevo', 'EQN', 'Argentina', 'Cba', 'Centro')
            ->willReturn($equipo);

        $jugadorManager = $this->createMock(JugadorManager::class);
        $jugadorManager->expects($this->once())
            ->method('crearJugador')
            ->with(
                $equipo,
                'Juan',
                'Perez',
                'DNI',
                '12345678',
                null,
                'Entrenador',
                true,
                'juan@example.com',
                '2615551234'
            );

        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager->expects($this->exactly(2))->method('flush');

        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects($this->once())->method('info');

        $response = $controller->crearEquipo(
            'ruta-test',
            7,
            $request,
            $equipoManager,
            $jugadorManager,
            $categoriaManager,
            $entityManager,
            $logger
        );

        self::assertInstanceOf(RedirectResponse::class, $response);
        self::assertSame(['success', 'Equipo creado con éxito.'], $controller->lastFlash);
    }

    public function testCrearEquipoManejaAppException(): void
    {
        $controller = new TestableEquipoController();
        $controller->testUser = (new Usuario())
            ->setUsername('admin')
            ->setPassword('hash')
            ->setRoles(['ROLE_ADMIN', 'ROLE_USER']);

        $request = Request::create('/admin/torneo/ruta-test/categoria/7/equipo/nuevo', 'POST', [
            'nombre' => 'Equipo Nuevo',
            'nombreCorto' => 'EQN',
            'pais' => 'Argentina',
            'provincia' => 'Cba',
            'localidad' => 'Centro',
            'delegado' => [[
                'nombre' => 'Juan',
                'apellido' => 'Perez',
                'tipoDocumento' => 'DNI',
                'numeroDocumento' => '12345678',
                'email' => 'juan@example.com',
                'celular' => '2615551234',
            ]],
        ]);

        $categoriaManager = $this->createMock(CategoriaManager::class);
        $categoria = $this->createMock(Categoria::class);
        $categoriaManager->method('obtenerCategoria')->willReturn($categoria);

        $equipoManager = $this->createMock(EquipoManager::class);
        $equipoManager->method('crearEquipo')
            ->willThrowException(new AppException('Equipo duplicado'));

        $jugadorManager = $this->createMock(JugadorManager::class);
        $jugadorManager->expects($this->never())->method('crearJugador');

        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager->expects($this->never())->method('flush');

        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects($this->once())->method('error');

        $response = $controller->crearEquipo(
            'ruta-test',
            7,
            $request,
            $equipoManager,
            $jugadorManager,
            $categoriaManager,
            $entityManager,
            $logger
        );

        self::assertInstanceOf(Response::class, $response);
        self::assertSame('equipo/nuevo.html.twig', $controller->lastTemplate);
        self::assertSame(['error', 'Equipo duplicado'], $controller->lastFlash);
    }

    public function testCrearEquipoManejaExcepcionGenericaYMuestraError(): void
    {
        $controller = new TestableEquipoController();
        $controller->testUser = (new Usuario())
            ->setUsername('admin')
            ->setPassword('hash')
            ->setRoles(['ROLE_ADMIN', 'ROLE_USER']);

        $request = Request::create('/admin/torneo/ruta-test/categoria/7/equipo/nuevo', 'POST', [
            'nombre' => 'Equipo Nuevo',
            'nombreCorto' => 'EQN',
            'pais' => 'Argentina',
            'provincia' => 'Cba',
            'localidad' => 'Centro',
            'delegado' => [[
                'nombre' => 'Juan',
                'apellido' => 'Perez',
                'tipoDocumento' => 'DNI',
                'numeroDocumento' => '12345678',
                'email' => 'juan@example.com',
                'celular' => '2615551234',
            ]],
        ]);

        $categoriaManager = $this->createMock(CategoriaManager::class);
        $categoria = $this->createMock(Categoria::class);
        $categoriaManager->method('obtenerCategoria')->willReturn($categoria);

        $equipoManager = $this->createMock(EquipoManager::class);
        $equipoManager->method('crearEquipo')
            ->willThrowException(new \RuntimeException('Error inesperado'));

        $jugadorManager = $this->createMock(JugadorManager::class);
        $jugadorManager->expects($this->never())->method('crearJugador');

        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager->expects($this->never())->method('flush');

        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects($this->once())->method('error');

        $response = $controller->crearEquipo(
            'ruta-test',
            7,
            $request,
            $equipoManager,
            $jugadorManager,
            $categoriaManager,
            $entityManager,
            $logger
        );

        self::assertInstanceOf(Response::class, $response);
        self::assertSame('equipo/nuevo.html.twig', $controller->lastTemplate);
        self::assertSame(['error', 'Ha ocurrido un error inesperado. Por favor, intente nuevamente.'], $controller->lastFlash);
    }

    public function testCrearEquipoPorGetRenderizaFormularioConTiposDocumento(): void
    {
        $controller = new TestableEquipoController();
        $request = Request::create('/admin/torneo/ruta-test/categoria/7/equipo/nuevo', 'GET');

        $equipoManager = $this->createMock(EquipoManager::class);
        $jugadorManager = $this->createMock(JugadorManager::class);
        $categoriaManager = $this->createMock(CategoriaManager::class);
        $entityManager = $this->createMock(EntityManagerInterface::class);
        $logger = $this->createMock(LoggerInterface::class);

        $response = $controller->crearEquipo(
            'ruta-test',
            7,
            $request,
            $equipoManager,
            $jugadorManager,
            $categoriaManager,
            $entityManager,
            $logger
        );

        self::assertInstanceOf(Response::class, $response);
        self::assertSame('equipo/nuevo.html.twig', $controller->lastTemplate);
        self::assertSame('ruta-test', $controller->lastParameters['ruta']);
        self::assertSame(7, $controller->lastParameters['categoriaId']);
        self::assertNotEmpty($controller->lastParameters['tipoDocumentos']);
    }

    public function testEditarEquipoPorPostYActualiza(): void
    {
        $controller = new TestableEquipoController();
        $controller->testUser = (new Usuario())
            ->setUsername('admin')
            ->setPassword('hash')
            ->setRoles(['ROLE_ADMIN', 'ROLE_USER']);

        $request = Request::create('/admin/torneo/ruta-test/categoria/7/equipo/99/editar', 'POST', [
            'nombre' => 'Equipo Editado',
            'nombreCorto' => 'EQE',
            'pais' => 'Argentina',
            'provincia' => 'Cba',
            'localidad' => 'Centro',
        ]);

        $equipo = $this->createMock(Equipo::class);
        $equipo->method('getId')->willReturn(99);

        $equipoManager = $this->createMock(EquipoManager::class);
        $equipoManager->expects($this->once())
            ->method('obtenerEquipo')
            ->with(99)
            ->willReturn($equipo);
        $equipoManager->expects($this->once())
            ->method('editarEquipo')
            ->with($equipo, 'Equipo Editado', 'EQE', 'Argentina', 'Cba', 'Centro');

        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects($this->once())->method('info');

        $response = $controller->editarEquipo('ruta-test', 7, 99, $request, $equipoManager, $logger);

        self::assertInstanceOf(RedirectResponse::class, $response);
        self::assertSame(['success', 'Equipo editado con éxito.'], $controller->lastFlash);
    }

    public function testEditarEquipoManejaAppExceptionYMantieneFormulario(): void
    {
        $controller = new TestableEquipoController();
        $controller->testUser = (new Usuario())
            ->setUsername('admin')
            ->setPassword('hash')
            ->setRoles(['ROLE_ADMIN', 'ROLE_USER']);

        $request = Request::create('/admin/torneo/ruta-test/categoria/7/equipo/99/editar', 'POST', [
            'nombre' => 'Equipo Editado',
            'nombreCorto' => 'EQE',
            'pais' => 'Argentina',
            'provincia' => 'Cba',
            'localidad' => 'Centro',
        ]);

        $equipo = $this->createMock(Equipo::class);
        $equipo->method('getId')->willReturn(null);

        $equipoManager = $this->createMock(EquipoManager::class);
        $equipoManager->expects($this->once())
            ->method('obtenerEquipo')
            ->with(99)
            ->willReturn($equipo);

        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects($this->once())->method('error');

        $response = $controller->editarEquipo('ruta-test', 7, 99, $request, $equipoManager, $logger);

        self::assertInstanceOf(Response::class, $response);
        self::assertSame('equipo/editar.html.twig', $controller->lastTemplate);
        self::assertSame(['error', 'No fue posible identificar el equipo a editar.'], $controller->lastFlash);
    }

    public function testEditarEquipoPorGetRenderizaFormulario(): void
    {
        $controller = new TestableEquipoController();
        $request = Request::create('/admin/torneo/ruta-test/categoria/7/equipo/99/editar', 'GET');

        $equipo = $this->createMock(Equipo::class);
        $equipoManager = $this->createMock(EquipoManager::class);
        $equipoManager->expects($this->once())
            ->method('obtenerEquipo')
            ->with(99)
            ->willReturn($equipo);

        $logger = $this->createMock(LoggerInterface::class);

        $response = $controller->editarEquipo('ruta-test', 7, 99, $request, $equipoManager, $logger);

        self::assertInstanceOf(Response::class, $response);
        self::assertSame('equipo/editar.html.twig', $controller->lastTemplate);
        self::assertSame($equipo, $controller->lastParameters['equipo']);
    }

    public function testEliminarEquipoPorGetYRedirige(): void
    {
        $controller = new TestableEquipoController();
        $controller->testUser = (new Usuario())
            ->setUsername('admin')
            ->setPassword('hash')
            ->setRoles(['ROLE_ADMIN', 'ROLE_USER']);

        $equipo = $this->createMock(Equipo::class);
        $equipo->method('getId')->willReturn(99);

        $equipoManager = $this->createMock(EquipoManager::class);
        $equipoManager->expects($this->once())
            ->method('obtenerEquipo')
            ->with(99)
            ->willReturn($equipo);
        $equipoManager->expects($this->once())
            ->method('eliminarEquipo')
            ->with($equipo);

        $request = Request::create('/admin/torneo/ruta-test/categoria/7/equipo/99/eliminar', 'POST', [
            '_token' => 'test-token-delete_equipo_99',
        ]);

        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects($this->once())->method('info');

        $response = $controller->eliminarEquipo('ruta-test', 7, 99, $request, $equipoManager, $logger);

        self::assertInstanceOf(RedirectResponse::class, $response);
        self::assertSame(['success', 'Equipo eliminado con éxito.'], $controller->lastFlash);
    }

    public function testEliminarEquipoManejaExcepcionGenerica(): void
    {
        $controller = new TestableEquipoController();
        $controller->testUser = (new Usuario())
            ->setUsername('admin')
            ->setPassword('hash')
            ->setRoles(['ROLE_ADMIN', 'ROLE_USER']);

        $equipo = $this->createMock(Equipo::class);
        $equipo->method('getId')->willReturn(99);

        $equipoManager = $this->createMock(EquipoManager::class);
        $equipoManager->expects($this->once())
            ->method('obtenerEquipo')
            ->with(99)
            ->willReturn($equipo);
        $equipoManager->expects($this->once())
            ->method('eliminarEquipo')
            ->with($equipo)
            ->willThrowException(new \RuntimeException('boom-delete'));

        $request = Request::create('/admin/torneo/ruta-test/categoria/7/equipo/99/eliminar', 'POST', [
            '_token' => 'test-token-delete_equipo_99',
        ]);

        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects($this->once())->method('error');

        $response = $controller->eliminarEquipo('ruta-test', 7, 99, $request, $equipoManager, $logger);

        self::assertInstanceOf(RedirectResponse::class, $response);
        self::assertSame(['error', 'Ha ocurrido un error inesperado. Por favor, intente nuevamente.'], $controller->lastFlash);
    }

    public function testEliminarEquipoManejaAppException(): void
    {
        $controller = new TestableEquipoController();
        $controller->testUser = (new Usuario())
            ->setUsername('admin')
            ->setPassword('hash')
            ->setRoles(['ROLE_ADMIN', 'ROLE_USER']);

        $equipo = $this->createMock(Equipo::class);

        $equipoManager = $this->createMock(EquipoManager::class);
        $equipoManager->expects($this->once())
            ->method('obtenerEquipo')
            ->with(99)
            ->willReturn($equipo);
        $equipoManager->expects($this->once())
            ->method('eliminarEquipo')
            ->with($equipo)
            ->willThrowException(new AppException('No se puede eliminar equipo'));

        $request = Request::create('/admin/torneo/ruta-test/categoria/7/equipo/99/eliminar', 'POST', [
            '_token' => 'test-token-delete_equipo_99',
        ]);

        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects($this->once())->method('error');

        $response = $controller->eliminarEquipo('ruta-test', 7, 99, $request, $equipoManager, $logger);

        self::assertInstanceOf(RedirectResponse::class, $response);
        self::assertSame(['error', 'No se puede eliminar equipo'], $controller->lastFlash);
    }

    public function testCambiarEstadoEquipoPorGetYRedirige(): void
    {
        $controller = new TestableEquipoController();
        $controller->testUser = (new Usuario())
            ->setUsername('admin')
            ->setPassword('hash')
            ->setRoles(['ROLE_ADMIN', 'ROLE_USER']);

        $equipo = $this->createMock(Equipo::class);
        $equipo->method('getId')->willReturn(55);

        $equipoManager = $this->createMock(EquipoManager::class);
        $equipoManager->expects($this->once())
            ->method('obtenerEquipo')
            ->with(55)
            ->willReturn($equipo);
        $equipoManager->expects($this->once())
            ->method('bajarEquipo')
            ->with($equipo);

        $request = Request::create('/admin/torneo/ruta-test/categoria/7/equipo/55/bajar', 'POST', [
            '_token' => 'test-token-bajar_equipo_55',
        ]);

        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects($this->once())->method('info');

        $response = $controller->cambiarEstado('ruta-test', 7, 55, $request, $equipoManager, $logger);

        self::assertInstanceOf(RedirectResponse::class, $response);
        self::assertSame(['success', 'Equipo dado de baja con éxito.'], $controller->lastFlash);
    }

    public function testCambiarEstadoManejaAppException(): void
    {
        $controller = new TestableEquipoController();
        $controller->testUser = (new Usuario())
            ->setUsername('admin')
            ->setPassword('hash')
            ->setRoles(['ROLE_ADMIN', 'ROLE_USER']);

        $equipo = $this->createMock(Equipo::class);

        $equipoManager = $this->createMock(EquipoManager::class);
        $equipoManager->expects($this->once())
            ->method('obtenerEquipo')
            ->with(55)
            ->willReturn($equipo);
        $equipoManager->expects($this->once())
            ->method('bajarEquipo')
            ->with($equipo)
            ->willThrowException(new AppException('No se puede bajar equipo'));

        $request = Request::create('/admin/torneo/ruta-test/categoria/7/equipo/55/bajar', 'POST', [
            '_token' => 'test-token-bajar_equipo_55',
        ]);

        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects($this->once())->method('error');

        $response = $controller->cambiarEstado('ruta-test', 7, 55, $request, $equipoManager, $logger);

        self::assertInstanceOf(RedirectResponse::class, $response);
        self::assertSame(['error', 'No se puede bajar equipo'], $controller->lastFlash);
    }

    public function testCambiarEstadoManejaExcepcionGenerica(): void
    {
        $controller = new TestableEquipoController();
        $controller->testUser = (new Usuario())
            ->setUsername('admin')
            ->setPassword('hash')
            ->setRoles(['ROLE_ADMIN', 'ROLE_USER']);

        $equipo = $this->createMock(Equipo::class);

        $equipoManager = $this->createMock(EquipoManager::class);
        $equipoManager->expects($this->once())
            ->method('obtenerEquipo')
            ->with(55)
            ->willReturn($equipo);
        $equipoManager->expects($this->once())
            ->method('bajarEquipo')
            ->with($equipo)
            ->willThrowException(new \RuntimeException('boom-baja'));

        $request = Request::create('/admin/torneo/ruta-test/categoria/7/equipo/55/bajar', 'POST', [
            '_token' => 'test-token-bajar_equipo_55',
        ]);

        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects($this->once())->method('error');

        $response = $controller->cambiarEstado('ruta-test', 7, 55, $request, $equipoManager, $logger);

        self::assertInstanceOf(RedirectResponse::class, $response);
        self::assertSame(['error', 'Ha ocurrido un error inesperado. Por favor, intente nuevamente.'], $controller->lastFlash);
    }

    public function testEliminarEquipoConCsrfInvalidoLanzaExcepcion(): void
    {
        $controller = new TestableEquipoControllerCsrfInvalido();

        $equipoManager = $this->createMock(EquipoManager::class);
        $logger = $this->createMock(LoggerInterface::class);
        $request = Request::create('/admin/torneo/ruta-test/categoria/7/equipo/99/eliminar', 'POST', [
            '_token' => 'token-invalido',
        ]);

        $this->expectException(AccessDeniedException::class);
        $this->expectExceptionMessage('Token CSRF inválido.');

        $controller->eliminarEquipo('ruta-test', 7, 99, $request, $equipoManager, $logger);
    }

    public function testCambiarEstadoConCsrfInvalidoLanzaExcepcion(): void
    {
        $controller = new TestableEquipoControllerCsrfInvalido();

        $equipoManager = $this->createMock(EquipoManager::class);
        $logger = $this->createMock(LoggerInterface::class);
        $request = Request::create('/admin/torneo/ruta-test/categoria/7/equipo/55/bajar', 'POST', [
            '_token' => 'token-invalido',
        ]);

        $this->expectException(AccessDeniedException::class);
        $this->expectExceptionMessage('Token CSRF inválido.');

        $controller->cambiarEstado('ruta-test', 7, 55, $request, $equipoManager, $logger);
    }

    public function testGuardarLogoEquipoSinArchivoRetornaLogoActual(): void
    {
        $controller = new TestableEquipoController();

        $resultado = $this->invokePrivateMethod(
            $controller,
            'guardarLogoEquipo',
            [null, 99, 'ruta-test', 'uploads/logos/actual.png']
        );

        self::assertSame('uploads/logos/actual.png', $resultado);
    }

    public function testGuardarLogoEquipoConTamanoExcedidoLanzaError(): void
    {
        $controller = new TestableEquipoController();
        $archivo = $this->createMock(UploadedFile::class);
        $archivo->method('getSize')->willReturn(3 * 1024 * 1024);

        $this->expectException(AppException::class);
        $this->expectExceptionMessage('El logo no puede superar los 2 MB.');

        $this->invokePrivateMethod($controller, 'guardarLogoEquipo', [$archivo, 99, 'ruta-test', null]);
    }

    public function testObtenerMimeTypeLogoValidoNormalizaJpg(): void
    {
        $controller = new TestableEquipoController();
        $archivo = $this->createMock(UploadedFile::class);
        $archivo->method('getPathname')->willReturn('/tmp/archivo-no-existe');
        $archivo->method('getMimeType')->willReturn('image/jpg');

        $resultado = $this->invokePrivateMethod($controller, 'obtenerMimeTypeLogoValido', [$archivo]);

        self::assertSame('image/jpeg', $resultado);
    }

    public function testObtenerMimeTypeLogoValidoInvalidoLanzaError(): void
    {
        $controller = new TestableEquipoController();
        $archivo = $this->createMock(UploadedFile::class);
        $archivo->method('getPathname')->willReturn('/tmp/archivo-no-existe');
        $archivo->method('getMimeType')->willReturn('application/pdf');

        $this->expectException(AppException::class);
        $this->expectExceptionMessage('El logo debe ser una imagen PNG, JPG, WEBP o GIF válida.');

        $this->invokePrivateMethod($controller, 'obtenerMimeTypeLogoValido', [$archivo]);
    }

    public function testNormalizarMimeTypeLogoMapeaValoresEsperados(): void
    {
        $controller = new TestableEquipoController();

        self::assertSame('image/jpeg', $this->invokePrivateMethod($controller, 'normalizarMimeTypeLogo', ['image/pjpeg']));
        self::assertSame('image/png', $this->invokePrivateMethod($controller, 'normalizarMimeTypeLogo', ['image/x-png']));
        self::assertSame('image/webp', $this->invokePrivateMethod($controller, 'normalizarMimeTypeLogo', ['image/webp']));
        self::assertNull($this->invokePrivateMethod($controller, 'normalizarMimeTypeLogo', ['application/pdf']));
    }

    public function testObtenerExtensionLogoPorMimeTypeCubreRamas(): void
    {
        $controller = new TestableEquipoController();

        self::assertSame('jpg', $this->invokePrivateMethod($controller, 'obtenerExtensionLogoPorMimeType', ['image/jpeg']));
        self::assertSame('png', $this->invokePrivateMethod($controller, 'obtenerExtensionLogoPorMimeType', ['image/png']));
        self::assertSame('webp', $this->invokePrivateMethod($controller, 'obtenerExtensionLogoPorMimeType', ['image/webp']));
        self::assertSame('gif', $this->invokePrivateMethod($controller, 'obtenerExtensionLogoPorMimeType', ['image/gif']));
        self::assertSame('png', $this->invokePrivateMethod($controller, 'obtenerExtensionLogoPorMimeType', ['otro']));
    }

    public function testNormalizarSegmentoRutaSanitizaYAplicaFallback(): void
    {
        $controller = new TestableEquipoController();

        self::assertSame('xvi-master_voley', $this->invokePrivateMethod($controller, 'normalizarSegmentoRuta', [' XVI Master_Voley ']));
        self::assertSame('torneo', $this->invokePrivateMethod($controller, 'normalizarSegmentoRuta', ['---___---']));
    }
}

class TestableEquipoController extends EquipoController
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

class TestableEquipoControllerCsrfInvalido extends TestableEquipoController
{
    protected function isCsrfTokenValid(string $id, ?string $token): bool
    {
        return false;
    }
}

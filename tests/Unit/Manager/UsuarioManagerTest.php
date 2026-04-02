<?php

declare(strict_types=1);

namespace App\Tests\Unit\Manager;

use App\Entity\Usuario;
use App\Exception\AppException;
use App\Manager\UsuarioManager;
use App\Manager\ValidadorManager;
use App\Repository\UsuarioRepository;
use PHPUnit\Framework\TestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UsuarioManagerTest extends TestCase
{
    public function testObtenerUsuariosRetornaArray(): void
    {
        $usuarioRepository = $this->createMock(UsuarioRepository::class);
        $validadorManager = $this->createMock(ValidadorManager::class);
        $passwordHasher = $this->createMock(UserPasswordHasherInterface::class);

        $usuarios = [new Usuario(), new Usuario()];
        $usuarioRepository->expects($this->once())
            ->method('findAll')
            ->willReturn($usuarios);

        $usuarioManager = new UsuarioManager($usuarioRepository, $validadorManager, $passwordHasher);

        self::assertSame($usuarios, $usuarioManager->obtenerUsuarios());
    }

    public function testBuscarUsuarioDelegaEnRepositorio(): void
    {
        $usuarioRepository = $this->createMock(UsuarioRepository::class);
        $validadorManager = $this->createMock(ValidadorManager::class);
        $passwordHasher = $this->createMock(UserPasswordHasherInterface::class);

        $usuario = new Usuario();
        $usuarioRepository->expects($this->once())
            ->method('find')
            ->with(10)
            ->willReturn($usuario);

        $usuarioManager = new UsuarioManager($usuarioRepository, $validadorManager, $passwordHasher);

        self::assertSame($usuario, $usuarioManager->buscarUsuario(10));
    }

    public function testRegistrarUsuarioFallaSiUsernameExiste(): void
    {
        $usuarioRepository = $this->createMock(UsuarioRepository::class);
        $validadorManager = $this->createMock(ValidadorManager::class);
        $passwordHasher = $this->createMock(UserPasswordHasherInterface::class);

        $validadorManager->expects($this->once())
            ->method('validarUsuario')
            ->with('user1', 'secret');

        $usuarioRepository->expects($this->once())
            ->method('findOneBy')
            ->with(['username' => 'user1'])
            ->willReturn(new Usuario());

        $usuarioRepository->expects($this->never())->method('guardar');

        $usuarioManager = new UsuarioManager($usuarioRepository, $validadorManager, $passwordHasher);

        $this->expectException(AppException::class);
        $this->expectExceptionMessage('El nombre de usuario ya se encuentra registrado');

        $usuarioManager->registrarUsuario('Nombre', 'Apellido', 'mail@test.com', 'user1', 'secret', ['ROLE_USER']);
    }

    public function testRegistrarUsuarioFallaSiEmailExiste(): void
    {
        $usuarioRepository = $this->createMock(UsuarioRepository::class);
        $validadorManager = $this->createMock(ValidadorManager::class);
        $passwordHasher = $this->createMock(UserPasswordHasherInterface::class);

        $validadorManager->expects($this->once())
            ->method('validarUsuario')
            ->with('user2', 'secret');

        $usuarioRepository->expects($this->exactly(2))
            ->method('findOneBy')
            ->willReturnOnConsecutiveCalls(null, new Usuario());

        $usuarioRepository->expects($this->never())->method('guardar');

        $usuarioManager = new UsuarioManager($usuarioRepository, $validadorManager, $passwordHasher);

        $this->expectException(AppException::class);
        $this->expectExceptionMessage('El email ya se encuentra registrado');

        $usuarioManager->registrarUsuario('Nombre', 'Apellido', 'mail@test.com', 'user2', 'secret', ['ROLE_USER']);
    }

    public function testRegistrarUsuarioOkGuardaUsuarioConPasswordHasheado(): void
    {
        $usuarioRepository = $this->createMock(UsuarioRepository::class);
        $validadorManager = $this->createMock(ValidadorManager::class);
        $passwordHasher = $this->createMock(UserPasswordHasherInterface::class);

        $validadorManager->expects($this->exactly(2))
            ->method('validarUsuario')
            ->with('user3', 'secret');

        $usuarioRepository->expects($this->exactly(2))
            ->method('findOneBy')
            ->willReturnOnConsecutiveCalls(null, null);

        $passwordHasher->expects($this->once())
            ->method('hashPassword')
            ->with(
                $this->isInstanceOf(Usuario::class),
                'secret'
            )
            ->willReturn('hashed-secret');

        $usuarioRepository->expects($this->once())
            ->method('guardar')
            ->with($this->callback(function (Usuario $usuario): bool {
                return $usuario->getNombre() === 'Nombre'
                    && $usuario->getApellido() === 'Apellido'
                    && $usuario->getEmail() === 'mail@test.com'
                    && $usuario->getUsername() === 'user3'
                    && $usuario->getPassword() === 'hashed-secret'
                    && $usuario->getRoles() === ['ROLE_ADMIN'];
            }));

        $usuarioManager = new UsuarioManager($usuarioRepository, $validadorManager, $passwordHasher);

        $usuarioManager->registrarUsuario('Nombre', 'Apellido', 'mail@test.com', 'user3', 'secret', ['ROLE_ADMIN']);

        $this->addToAssertionCount(1);
    }

    public function testEditarUsuarioFallaSiUsernamePerteneceAOtroUsuario(): void
    {
        $usuarioRepository = $this->createMock(UsuarioRepository::class);
        $validadorManager = $this->createMock(ValidadorManager::class);
        $passwordHasher = $this->createMock(UserPasswordHasherInterface::class);

        $usuarioActual = new Usuario();
        $usuarioExistente = new Usuario();

        $usuarioRepository->expects($this->once())
            ->method('findOneBy')
            ->with(['username' => 'nuevo-user'])
            ->willReturn($usuarioExistente);

        $usuarioRepository->expects($this->never())->method('guardar');

        $usuarioManager = new UsuarioManager($usuarioRepository, $validadorManager, $passwordHasher);

        $this->expectException(AppException::class);
        $this->expectExceptionMessage('El nombre de usuario ya se encuentra registrado');

        $usuarioManager->editarUsuario($usuarioActual, 'Nom', 'Ape', 'nuevo@mail.com', 'nuevo-user', ['ROLE_USER']);
    }

    public function testEditarUsuarioOkActualizaYGuarda(): void
    {
        $usuarioRepository = $this->createMock(UsuarioRepository::class);
        $validadorManager = $this->createMock(ValidadorManager::class);
        $passwordHasher = $this->createMock(UserPasswordHasherInterface::class);

        $usuario = (new Usuario())
            ->setNombre('Viejo')
            ->setApellido('Nombre')
            ->setEmail('viejo@mail.com')
            ->setUsername('user-old')
            ->setRoles(['ROLE_USER']);

        $usuarioRepository->expects($this->exactly(2))
            ->method('findOneBy')
            ->willReturnOnConsecutiveCalls($usuario, $usuario);

        $usuarioRepository->expects($this->once())
            ->method('guardar')
            ->with($usuario);

        $usuarioManager = new UsuarioManager($usuarioRepository, $validadorManager, $passwordHasher);

        $usuarioManager->editarUsuario($usuario, 'Nuevo', 'Apellido', 'nuevo@mail.com', 'nuevo-user', ['ROLE_ADMIN']);

        self::assertSame('Nuevo', $usuario->getNombre());
        self::assertSame('Apellido', $usuario->getApellido());
        self::assertSame('nuevo@mail.com', $usuario->getEmail());
        self::assertSame('nuevo-user', $usuario->getUsername());
        self::assertSame(['ROLE_ADMIN'], $usuario->getRoles());
    }

    public function testCambiarPasswordHasheaYGuarda(): void
    {
        $usuarioRepository = $this->createMock(UsuarioRepository::class);
        $validadorManager = $this->createMock(ValidadorManager::class);
        $passwordHasher = $this->createMock(UserPasswordHasherInterface::class);

        $usuario = new Usuario();

        $passwordHasher->expects($this->once())
            ->method('hashPassword')
            ->with($usuario, 'nueva-clave')
            ->willReturn('hash-nueva-clave');

        $usuarioRepository->expects($this->once())
            ->method('guardar')
            ->with($usuario);

        $usuarioManager = new UsuarioManager($usuarioRepository, $validadorManager, $passwordHasher);

        $usuarioManager->cambiarPassword($usuario, 'nueva-clave');

        self::assertSame('hash-nueva-clave', $usuario->getPassword());
    }

    public function testEliminarUsuarioDelegaEnRepositorio(): void
    {
        $usuarioRepository = $this->createMock(UsuarioRepository::class);
        $validadorManager = $this->createMock(ValidadorManager::class);
        $passwordHasher = $this->createMock(UserPasswordHasherInterface::class);

        $usuario = new Usuario();

        $usuarioRepository->expects($this->once())
            ->method('eliminar')
            ->with($usuario);

        $usuarioManager = new UsuarioManager($usuarioRepository, $validadorManager, $passwordHasher);

        $usuarioManager->eliminarUsuario($usuario);

        $this->addToAssertionCount(1);
    }
}

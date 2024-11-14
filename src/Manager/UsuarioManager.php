<?php

declare(strict_types=1);

namespace App\Manager;

use App\Entity\Usuario;
use App\Exception\AppException;
use App\Repository\UsuarioRepository;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UsuarioManager
{
    public function __construct(
        private UsuarioRepository $usuarioRepository,
        private ValidadorManager $validadorManager,
        private UserPasswordHasherInterface $userPasswordHasher
    ) {
    }

    public function obtenerUsuarios(): array
    {
        return $this->usuarioRepository->findAll();
    }

    public function buscarUsuario(int $id): ?Usuario
    {
        return $this->usuarioRepository->find($id);
    }

    public function registrarUsuario(
        string $nombre,
        string $apellido,
        string $email,
        string $username,
        string $password,
        $roles
    ): void {
        try {
            $this->validadorManager->validarUsuario($username, $password);

            if ($this->usuarioRepository->findOneBy(['username' => $username])) {
                throw new AppException('El nombre de usuario ya se encuentra registrado');
            }
            $this->validadorManager->validarUsuario($username, $password);
            $usuario = new Usuario();
            $usuario->setNombre($nombre);
            $usuario->setApellido($apellido);
            $usuario->setEmail($email);
            $usuario->setUsername($username);
            $usuario->setRoles($roles);
            $usuario->setPassword($this->userPasswordHasher->hashPassword($usuario, $password));

            $this->usuarioRepository->guardar($usuario);
        } catch (AppException $e) {
            throw $e;
        }
    }

    public function editarUsuario(
        Usuario $usuario,
        string $nombre,
        string $apellido,
        string $email,
        //string $username,
        //string $password,
        $roles
    ): void {
        try {
            $usuario->setNombre($nombre);
            $usuario->setApellido($apellido);
            $usuario->setEmail($email);
            //$usuario->setUsername($username);
            //$usuario->setPassword($this->userPasswordHasher->hashPassword($usuario, $password));
            $usuario->setRoles($roles);
            $this->usuarioRepository->guardar($usuario);
        } catch (AppException $e) {
            throw $e;
        }
    }

    public function cambiarPassword(Usuario $usuario, string $password): void
    {
        $usuario->setPassword($this->userPasswordHasher->hashPassword($usuario, $password));
        $this->usuarioRepository->guardar($usuario);
    }

    public function eliminarUsuario(Usuario $usuario): void
    {
        try {
            $this->usuarioRepository->eliminar($usuario);
        } catch (AppException $e) {
            throw $e;
        }
    }
}

<?php

declare(strict_types=1);

namespace App\Manager;

use App\Entity\User;
use App\Exception\AppException;
use App\Repository\UserRepository;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RegistrarManager
{

    public function __construct(
        private UserRepository $usuarioRepository,
        private ValidadorManager $validadorManager,
        private UserPasswordHasherInterface $userPasswordHasher
    ) {
    }

    public function obtenerUsuarios(): array
    {
        return $this->usuarioRepository->findAll();
    }

    public function buscarUsuario(int $id): ?User
    {
        return $this->usuarioRepository->find($id);
    }

    public function registrarUsuario(string $nombre, string $apellido, string $email, string $username, string $password, $roles): void
    {
        try{
            $this->validadorManager->validarUsuario($username, $password);
    
            if ($this->usuarioRepository->findOneBy(['username' => $username])) {
                throw new AppException('El nombre de usuario ya se encuentra registrado');
            }
            $usuario = new User();
            $usuario->setNombre($nombre);
            $usuario->setApellido($apellido);
            $usuario->setEmail($email);
            $usuario->setUsername($username);
            $usuario->setRoles($roles);
            $usuario->setPassword($this->userPasswordHasher->hashPassword($usuario, $password));
    
            $this->usuarioRepository->guardar($usuario);
        }catch(AppException $e){
            throw $e;
        }
    }

    public function editarUsuario(User $usuario, string $nombre, string $apellido, string $email, $roles): void
    {
        $usuario->setNombre($nombre);
        $usuario->setApellido($apellido);
        $usuario->setEmail($email);
        $usuario->setRoles($roles);
        $this->usuarioRepository->guardar($usuario);
    }

    public function cambiarPassword(User $usuario, string $password): void
    {
        $usuario->setPassword($this->userPasswordHasher->hashPassword($usuario, $password));
        $this->usuarioRepository->guardar($usuario);
    }

    public function eliminarUsuario(User $usuario): void
    {
        $this->usuarioRepository->eliminar($usuario);
    }
}
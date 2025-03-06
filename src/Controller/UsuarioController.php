<?php

namespace App\Controller;

use App\Enum\Rol;
use App\Exception\AppException;
use App\Manager\UsuarioManager;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Throwable;

#[Route('/admin/usuario')]
class UsuarioController extends AbstractController
{
    #[IsGranted('ROLE_ADMIN')]
    #[Route('/', name: 'app_usuario', methods: ['GET'])]
    public function usuarios(
        UsuarioManager $rm,
        LoggerInterface $logger
    ): Response {
        try {
            $usuarios = $rm->obtenerUsuarios();

            if ($this->getUser() !== null) {
                if (in_array('ROLE_ADMIN', $this->getUser()->getRoles())) {
                    return $this->render(
                        'usuario/index.html.twig',
                        [
                        'usuarios' => $usuarios,
                        ]
                    );
                } else {
                    $this->addFlash('error', "Debe tener el rol de administrador.");
                    return $this->redirectToRoute('app_main');
                }
            }
            return $this->redirectToRoute('app_login');
        } catch (Throwable $e) {
            $logger->error($e->getMessage());
            $this->addFlash('error', "Ha ocurrido un error inesperado. Por favor, intente nuevamente.");
            return $this->redirectToRoute('app_main');
        }
    }

    #[Route('/nuevo', name: 'app_usuario_nuevo', methods: ['GET', 'POST'])]
    public function registrar(
        Request $request,
        UsuarioManager $rm,
        LoggerInterface $logger
    ): Response {
        if ($request->isMethod('POST')) {
            try {
                // Handle the submission of the form
                $username = $request->request->get('username');
                $password = $request->request->get('password');
                $nombre = $request->request->get('nombre');
                $apellido = $request->request->get('apellido');
                $email = $request->request->get('email');

                if ($request->request->all('roles') === []) {
                    $roles[] = 'ROLE_ADMIN';
                } else {
                    foreach ($request->request->all('roles') as $rol) {
                        $roles[] = $rol;
                    }
                }

                $rm->registrarUsuario($nombre, $apellido, $email, $username, $password, $roles);
                $this->addFlash('success', 'Usuario registrado correctamente');
                return $this->redirectToRoute('app_usuario');
            } catch (AppException $ae) {
                $logger->error($ae->getMessage());
                $this->addFlash('error', $ae->getMessage());
            } catch (Throwable $e) {
                $logger->error($e->getMessage());
                $this->addFlash('error', "Ha ocurrido un error inesperado. Por favor, intente nuevamente.");
            }
        }

        foreach (Rol::cases() as $rol) {
            $roles[] = $rol->value;
        }
        $usuarios = $rm->obtenerUsuarios();
        if ($usuarios === []) {
            return $this->render(
                'usuario/registrar.html.twig',
                [
                'roles' => [],
                ]
            );
        } else {
            if ($this->getUser() !== null) {
                if (in_array('ROLE_ADMIN', $this->getUser()->getRoles())) {
                    return $this->render(
                        'usuario/registrar.html.twig',
                        [
                        'roles' => $roles,
                        ]
                    );
                } else {
                    $this->addFlash('error', "Debe tener el rol de administrador.");
                    return $this->redirectToRoute('app_main');
                }
            }
            return $this->redirectToRoute('app_login');
        }
    }

    #[Route('/cambiar_password', name: 'app_usuario_cambiar_password', methods: ['GET', 'POST'])]
    public function cambiarPassword(
        Request $request,
        UsuarioManager $rm,
        LoggerInterface $logger
    ): Response {
        if ($request->isMethod('POST')) {
            try {
                // Handle the submission of the form
                $password = $request->request->get('password');
                $rm->cambiarPassword($this->getUser(), $password);
                $this->addFlash('success', 'Contraseña cambiada correctamente');
                return $this->redirectToRoute('app_main');
            } catch (AppException $ae) {
                $logger->error($ae->getMessage());
                $this->addFlash('error', $ae->getMessage());
            } catch (Throwable $e) {
                $logger->error($e->getMessage());
                $this->addFlash('error', "Ha ocurrido un error inesperado. Por favor, intente nuevamente.");
            }
        }

        return $this->render('usuario/cambiar_password.html.twig');
    }

    #[Route('/editar/{id}', name: 'app_usuario_editar', methods: ['GET', 'POST'])]
    public function editar(Request $request, UsuarioManager $usuarioManager, LoggerInterface $logger, $id): Response
    {
        $usuario = $usuarioManager->buscarUsuario((int)$id);
        if ($request->isMethod('POST')) {
            try {
                // Procesar el formulario
                $nombre = $request->request->get('nombre');
                $apellido = $request->request->get('apellido');
                $email = $request->request->get('email');
                $username = $request->request->get('username');
                //$password = $request->request->get('password');

                foreach ($request->request->all('roles') as $rol) {
                    $roles[] = $rol;
                }
                $usuarioManager->editarUsuario(
                    $usuario,
                    $nombre,
                    $apellido,
                    $email,
                    $username,
                    //$password,
                    $roles
                );
                $this->addFlash('success', 'Usuario editado correctamente');
                return $this->redirectToRoute('app_usuario');
            } catch (AppException $ae) {
                $logger->error($ae->getMessage());
                $this->addFlash('error', $ae->getMessage());
            } catch (Throwable $e) {
                $logger->error($e->getMessage());
                $this->addFlash('error', "Ha ocurrido un error inesperado. Por favor, intente nuevamente.");
            }
        }
        foreach (Rol::cases() as $rol) {
            $roles[] = $rol->value;
        }
        return $this->render(
            'usuario/editar.html.twig',
            [
            'usuario' => $usuario,
            'roles' => $roles,
            ]
        );
    }

    #[Route('/eliminar/{id}', name: 'app_usuario_eliminar', methods: ['GET'])]
    public function eliminar(UsuarioManager $usuarioManager, LoggerInterface $logger, $id): Response
    {
        try {
            $usuario = $usuarioManager->buscarUsuario((int)$id);
            $usuarioManager->eliminarUsuario($usuario);
            $this->addFlash('success', 'Usuario eliminado correctamente');
            return $this->redirectToRoute('app_usuario');
        } catch (AppException $ae) {
            $logger->error($ae->getMessage());
            $this->addFlash('error', $ae->getMessage());
        } catch (Throwable $e) {
            $logger->error($e->getMessage());
            $this->addFlash('error', "Ha ocurrido un error inesperado. Por favor, intente nuevamente.");
        }
    }
}

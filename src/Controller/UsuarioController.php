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
    #[Route('/', name: 'admin_usuario_index', methods: ['GET'])]
    public function obtenerUsuarios(
        UsuarioManager $usuarioManager,
        LoggerInterface $logger
    ): Response {
        try {
            if ($this->getUser() !== null) {
                if (in_array('ROLE_ADMIN', $this->getUser()->getRoles())) {
                    $usuarios = $usuarioManager->obtenerUsuarios();
                    return $this->render(
                        'usuario/index.html.twig',
                        [
                        'usuarios' => $usuarios,
                        ]
                    );
                } else {
                    $this->addFlash('error', "Debe tener el rol de administrador.");
                    $logger->error('Acceso denegado al index de usuarios por el usuario: ' .  $this->getUser()->getId());
                    return $this->redirectToRoute('app_main');
                }
            }
            return $this->redirectToRoute('security_login');
        } catch (Throwable $e) {
            $logger->error($e->getMessage());
            $this->addFlash('error', "Ha ocurrido un error inesperado. Por favor, intente nuevamente.");
            return $this->redirectToRoute('app_main');
        }
    }

    #[Route('/nuevo', name: 'admin_usuario_crear', methods: ['GET', 'POST'])]
    public function crearUsuario(
        Request $request,
        UsuarioManager $usuarioManager,
        LoggerInterface $logger
    ): Response {
        $usuarios = $usuarioManager->obtenerUsuarios();
        $roles = [];
        // MODO INVITADO: No hay usuarios, se permite crear el primer admin
         if ($usuarios === []) {
            if ($request->isMethod('POST')) {
                try {
                    $username = $request->request->get('username');
                    $password = $request->request->get('password');
                    $nombre = $request->request->get('nombre');
                    $apellido = $request->request->get('apellido');
                    $email = $request->request->get('email');

                    $roles = ["ROLE_USER", Rol::ROLE_ADMIN->value];

                    $usuarioManager->registrarUsuario($nombre, $apellido, $email, $username, $password, $roles);
                    $this->addFlash('success', 'Primer usuario administrador creado correctamente');
                    $logger->info('Primer usuario administrador creado: ' . $username . ', por el usuario: ' .  $this->getUser()->getId());
                    return $this->redirectToRoute('security_login');
                } catch (AppException $ae) {
                    $logger->error($ae->getMessage());
                    $this->addFlash('error', $ae->getMessage());
                } catch (Throwable $e) {
                    $logger->error($e->getMessage());
                    $this->addFlash('error', "Ha ocurrido un error inesperado. Por favor, intente nuevamente.");
                }
            }
            return $this->render('usuario/registrar.html.twig', ['roles' => []]);
        }

         // MODO ADMIN: Solo un usuario autenticado con ROLE_ADMIN puede crear usuarios
        if ($this->getUser() !== null && in_array('ROLE_ADMIN', $this->getUser()->getRoles())) {
            foreach (Rol::cases() as $rol) {
                $roles[] = $rol->value;
            }

            if ($request->isMethod('POST')) {
                try {
                    $username = trim($request->request->get('username', ''));
                    $password = trim($request->request->get('password', ''));
                    $nombre = trim($request->request->get('nombre', ''));
                    $apellido = trim($request->request->get('apellido', ''));
                    $email = trim($request->request->get('email', ''));
                    $rolesSeleccionados = $request->request->all('roles') ?? [];

                    // Validación básica
                    if (!$username || !$password || !$nombre || !$apellido || !$email) {
                        $this->addFlash('error', 'Todos los campos son obligatorios.');
                        return $this->render('usuario/registrar.html.twig', ['roles' => $roles]);
                    }

                    $rolesAsignados = ["ROLE_USER"];
                    foreach ($rolesSeleccionados as $rol) {
                        if (in_array($rol, $roles)) {
                            $rolesAsignados[] = $rol;
                        }
                    }
                    if (empty($rolesAsignados)) {
                        $this->addFlash('error', 'Debe seleccionar al menos un rol.');
                        return $this->render('usuario/registrar.html.twig', ['roles' => $roles]);
                    }

                    $usuarioManager->registrarUsuario($nombre, $apellido, $email, $username, $password, $rolesAsignados);
                    $this->addFlash('success', 'Usuario registrado correctamente');
                    $logger->info('Usuario registrado: ' . $username . ', por el usuario: ' .  $this->getUser()->getId());
                    return $this->redirectToRoute('admin_usuario_index');
                } catch (AppException $ae) {
                    $logger->error($ae->getMessage());
                    $this->addFlash('error', $ae->getMessage());
                } catch (Throwable $e) {
                    $logger->error($e->getMessage());
                    $this->addFlash('error', "Ha ocurrido un error inesperado. Por favor, intente nuevamente.");
                }
            }
            return $this->render('usuario/registrar.html.twig', ['roles' => $roles]);
        }

        // Si no es admin, redirige
        $this->addFlash('error', "Debe tener el rol de administrador.");
        return $this->redirectToRoute($this->getUser() ? 'app_main' : 'security_login');
    }

    #[IsGranted('ROLE_USER')]
    #[Route('/cambiar_password', name: 'admin_usuario_cambiar_password', methods: ['GET', 'POST'])]
    public function cambiarPassword(
        Request $request,
        UsuarioManager $usuarioManager,
        LoggerInterface $logger
    ): Response {
        if ($request->isMethod('POST')) {
            try {
                // Handle the submission of the form
                $password = $request->request->get('password');
                $usuarioManager->cambiarPassword($this->getUser(), $password);
                $this->addFlash('success', 'Contraseña cambiada correctamente');
                $logger->info('Contraseña cambiada por el usuario: ' .  $this->getUser()->getId());
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

    #[Route('/editar/{id}', name: 'admin_usuario_editar', methods: ['GET', 'POST'])]
    public function editarUsuario(
        Request $request, 
        UsuarioManager $usuarioManager, 
        LoggerInterface $logger, $id): Response
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
                $roles = ["ROLE_USER"];
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
                $logger->info('Usuario editado: ' . $usuario->getId() . ', por el usuario: ' .  $this->getUser()->getId());
                return $this->redirectToRoute('admin_usuario_index');
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

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/eliminar/{id}', name: 'admin_usuario_eliminar', methods: ['GET'])]
    public function eliminarUsuario(UsuarioManager $usuarioManager, LoggerInterface $logger, $id): Response
    {
        try {
            $usuario = $usuarioManager->buscarUsuario((int)$id);
            $usuarioManager->eliminarUsuario($usuario);
            $this->addFlash('success', 'Usuario eliminado correctamente');
            $logger->info('Usuario eliminado: ' . $usuario->getId() . ', por el usuario: ' .  $this->getUser()->getId());
            return $this->redirectToRoute('admin_usuario_index');
        } catch (AppException $ae) {
            $logger->error($ae->getMessage());
            $this->addFlash('error', $ae->getMessage());
        } catch (Throwable $e) {
            $logger->error($e->getMessage());
            $this->addFlash('error', "Ha ocurrido un error inesperado. Por favor, intente nuevamente.");
        }
    }
}

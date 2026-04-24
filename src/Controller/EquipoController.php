<?php

namespace App\Controller;

use App\Enum\TipoDocumento;
use App\Exception\AppException;
use App\Entity\Usuario;
use App\Manager\CategoriaManager;
use App\Manager\EquipoManager;
use App\Manager\JugadorManager;
use App\Manager\TorneoManager;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin/torneo/{ruta}/categoria/{categoriaId}/equipo')]
#[IsGranted('ROLE_ADMIN')]
class EquipoController extends AbstractController
{
    private function getLogUserId(): string
    {
        $user = $this->getUser();

        if ($user instanceof Usuario) {
            $id = $user->getId();
            return $id !== null ? (string) $id : 'anon';
        }

        return 'anon';
    }

    #[Route('/', name: 'admin_equipo_index', methods: ['GET'])]
    public function indexEquipo(
        string $ruta,
        int $categoriaId,
        TorneoManager $torneoManager,
        EquipoManager $equipoManager,
        CategoriaManager $categoriaManager
    ): Response {
        $torneo = $torneoManager->obtenerTorneo($ruta);
        $categoria = $categoriaManager->obtenerCategoria($categoriaId);
        $equipos = $equipoManager->obtenerEquiposPorCategoria($categoria);
        return $this->render(
            'equipo/index.html.twig', [
            'torneo' => $torneo,
            'categoria' => $categoria,
            'equipos' => $equipos,
            ]
        );
    }

    #[Route('/nuevo', name: 'admin_equipo_crear', methods: ['GET', 'POST'])]
    public function crearEquipo(
        string $ruta,
        int $categoriaId,
        Request $request,
        EquipoManager $equipoManager,
        JugadorManager $jugadorManager,
        CategoriaManager $categoriaManager,
        EntityManagerInterface $entityManager,
        LoggerInterface $logger
    ): Response {
        if ($request->isMethod('POST')) {
            try {
                $nombre = $request->request->get('nombre');
                $nombreCorto = $request->request->get('nombreCorto');
                $pais = $request->request->get('pais') ?? null;
                $provincia = $request->request->get('provincia') ?? null;
                $localidad = $request->request->get('localidad') ?? null;
                $delegado = $request->request->all('delegado');
                
                $categoria = $categoriaManager->obtenerCategoria($categoriaId);
                
                $equipo = $equipoManager->crearEquipo($categoria, $nombre, $nombreCorto, $pais, $provincia, $localidad, null);
                $entityManager->flush();

                $equipoId = $equipo->getId();
                if ($equipoId === null) {
                    throw new AppException('No fue posible generar el identificador del equipo.');
                }

                $logoPath = $this->guardarLogoEquipo($request->files->get('logo'), $equipoId, $ruta, $equipo->getLogoPath());
                $equipo->setLogoPath($logoPath);
                
                $jugadorManager->crearJugador(
                    $equipo,
                    $delegado[0]['nombre'],
                    $delegado[0]['apellido'],
                    $delegado[0]['tipoDocumento'],
                    $delegado[0]['numeroDocumento'],
                    null,
                    'Entrenador',
                    true,
                    $delegado[0]['email'],
                    $delegado[0]['celular'],
                );

                $entityManager->flush();
                
                $this->addFlash('success', "Equipo creado con éxito.");
                $logger->info('Equipo creado: ' . $equipo->getId() . ', por el usuario: ' . $this->getLogUserId());
                return $this->redirectToRoute('admin_equipo_index', ['ruta' => $ruta, 'categoriaId' => $categoriaId]);
            } catch (AppException $ae) {
                $logger->error($ae->getMessage());
                $this->addFlash('error', $ae->getMessage());
            } catch (\Throwable $e) {
                $logger->error($e->getMessage());
                $this->addFlash('error', "Ha ocurrido un error inesperado. Por favor, intente nuevamente.");
            }
        }

        foreach (TipoDocumento::cases() as $tipoDocumento) {
            $tipoDocumentos[] = $tipoDocumento->value;
        }
        return $this->render(
            'equipo/nuevo.html.twig', [
            'ruta' => $ruta,
            'categoriaId' => $categoriaId,
            'tipoDocumentos' => $tipoDocumentos,
            ]
        );
    }

    #[Route('/{equipoId}/editar', name: 'admin_equipo_editar', methods: ['GET', 'POST'])]
    public function editarEquipo(
        string $ruta,
        int $categoriaId,
        int $equipoId,
        Request $request,
        EquipoManager $equipoManager,
        LoggerInterface $logger
    ): Response {
        $equipo = $equipoManager->obtenerEquipo($equipoId);
        if ($request->isMethod('POST')) {
            try {
                $nombre = $request->request->get('nombre');
                $nombreCorto = $request->request->get('nombreCorto');
                $pais = $request->request->get('pais') ?? null;
                $provincia = $request->request->get('provincia') ?? null;
                $localidad = $request->request->get('localidad') ?? null;
                $equipoId = $equipo->getId();
                if ($equipoId === null) {
                    throw new AppException('No fue posible identificar el equipo a editar.');
                }

                $logoPath = $this->guardarLogoEquipo($request->files->get('logo'), $equipoId, $ruta, $equipo->getLogoPath());
                $equipoManager->editarEquipo($equipo, $nombre, $nombreCorto, $pais, $provincia, $localidad, $logoPath);
                $this->addFlash('success', "Equipo editado con éxito.");
                $logger->info('Equipo editado: ' . $equipo->getId() . ', por el usuario: ' . $this->getLogUserId());
                return $this->redirectToRoute('admin_equipo_index', ['ruta' => $ruta, 'categoriaId' => $categoriaId]);
            } catch (AppException $ae) {
                $logger->error($ae->getMessage());
                $this->addFlash('error', $ae->getMessage());
            } catch (\Throwable $e) {
                $logger->error($e->getMessage());
                $this->addFlash('error', "Ha ocurrido un error inesperado. Por favor, intente nuevamente.");
            }
        }
        return $this->render(
            'equipo/editar.html.twig', [
            'ruta' => $ruta,
            'categoriaId' => $categoriaId,
            'equipo' => $equipo,
            ]
        );
    }

    #[Route('/{equipoId}/eliminar', name: 'admin_equipo_eliminar', methods: ['POST'])]
    public function eliminarEquipo(
        string $ruta,
        int $categoriaId,
        int $equipoId,
        Request $request,
        EquipoManager $equipoManager,
        LoggerInterface $logger
    ): Response {
        if (!$this->isCsrfTokenValid('delete_equipo_' . $equipoId, (string) $request->request->get('_token'))) {
            throw $this->createAccessDeniedException('Token CSRF inválido.');
        }

        try {
            $equipo = $equipoManager->obtenerEquipo($equipoId);
            $equipoManager->eliminarEquipo($equipo);
            $this->addFlash('success', "Equipo eliminado con éxito.");
            $logger->info('Equipo eliminado: ' . $equipo->getId() . ', por el usuario: ' . $this->getLogUserId());
        } catch (AppException $ae) {
            $logger->error($ae->getMessage());
            $this->addFlash('error', $ae->getMessage());
        } catch (\Throwable $e) {
            $logger->error($e->getMessage());
            $this->addFlash('error', "Ha ocurrido un error inesperado. Por favor, intente nuevamente.");
        }
        return $this->redirectToRoute('admin_equipo_index', ['ruta' => $ruta, 'categoriaId' => $categoriaId]);
    }

    #[Route('/{equipoId}/bajar', name: 'admin_equipo_bajar', methods: ['POST'])]
    public function cambiarEstado(
        string $ruta,
        int $categoriaId,
        int $equipoId,
        Request $request,
        EquipoManager $equipoManager,
        LoggerInterface $logger
    ): Response {
        if (!$this->isCsrfTokenValid('bajar_equipo_' . $equipoId, (string) $request->request->get('_token'))) {
            throw $this->createAccessDeniedException('Token CSRF inválido.');
        }

        try {
            $equipo = $equipoManager->obtenerEquipo($equipoId);
            $equipoManager->bajarEquipo($equipo);
            $this->addFlash('success', "Equipo dado de baja con éxito.");
            $logger->info('Equipo dado de baja: ' . $equipo->getId() . ', por el usuario: ' . $this->getLogUserId());
        } catch (AppException $ae) {
            $logger->error($ae->getMessage());
            $this->addFlash('error', $ae->getMessage());
        } catch (\Throwable $e) {
            $logger->error($e->getMessage());
            $this->addFlash('error', "Ha ocurrido un error inesperado. Por favor, intente nuevamente.");
        }
        return $this->redirectToRoute('admin_equipo_index', ['ruta' => $ruta, 'categoriaId' => $categoriaId]);
    }

    private function guardarLogoEquipo(mixed $archivo, int $equipoId, string $rutaTorneo, ?string $logoActual = null): ?string
    {
        if (!$archivo instanceof UploadedFile) {
            return $logoActual;
        }

        if ($archivo->getSize() !== null && $archivo->getSize() > 2 * 1024 * 1024) {
            throw new AppException('El logo no puede superar los 2 MB.');
        }

        $mimeType = $this->obtenerMimeTypeLogoValido($archivo);
        $extensionOriginal = $this->obtenerExtensionLogoPorMimeType($mimeType);
        $imagen = $this->normalizarImagenLogo($archivo, $mimeType);

        $filesystem = new Filesystem();
        $anioActual = (new \DateTimeImmutable('now'))->format('Y');
        $torneoSlug = $this->normalizarSegmentoRuta($rutaTorneo);
        $directorioDestino = $this->getParameter('kernel.project_dir') . '/public/uploads/logos/' . $anioActual . '/' . $torneoSlug;
        $filesystem->mkdir($directorioDestino);

        $nombreArchivo = $equipoId . '.png';
        $rutaDestinoCompleta = $directorioDestino . '/' . $nombreArchivo;

        if ($imagen instanceof \GdImage) {
            if (!imagepng($imagen, $rutaDestinoCompleta)) {
                imagedestroy($imagen);
                throw new AppException('No fue posible guardar el logo del equipo.');
            }

            imagedestroy($imagen);
        } else {
            $nombreArchivo = $equipoId . '.' . $extensionOriginal;
            $rutaDestinoCompleta = $directorioDestino . '/' . $nombreArchivo;
            $archivo->move($directorioDestino, $nombreArchivo);
        }

        if ($logoActual !== null) {
            $rutaAnterior = $this->getParameter('kernel.project_dir') . '/public/' . ltrim($logoActual, '/');
            if ($filesystem->exists($rutaAnterior) && $rutaAnterior !== $rutaDestinoCompleta) {
                $filesystem->remove($rutaAnterior);
            }
        }

        return 'uploads/logos/' . $anioActual . '/' . $torneoSlug . '/' . $nombreArchivo;
    }

    private function obtenerMimeTypeLogoValido(UploadedFile $archivo): string
    {
        $rutaArchivo = $archivo->getPathname();

        // Priorizamos deteccion por contenido real del archivo.
        if (function_exists('exif_imagetype')) {
            $tipoImagen = @exif_imagetype($rutaArchivo);
            if ($tipoImagen !== false) {
                return match ($tipoImagen) {
                    IMAGETYPE_JPEG => 'image/jpeg',
                    IMAGETYPE_PNG => 'image/png',
                    IMAGETYPE_WEBP => 'image/webp',
                    IMAGETYPE_GIF => 'image/gif',
                    default => throw new AppException('El logo debe ser una imagen PNG, JPG, WEBP o GIF válida.'),
                };
            }
        }

        $mimeTypeDetectado = null;
        $info = @getimagesize($rutaArchivo);
        if ($info !== false && isset($info['mime']) && is_string($info['mime'])) {
            $mimeTypeDetectado = $info['mime'];
        }

        if ($mimeTypeDetectado === null) {
            $mimeTypeDetectado = (string) ($archivo->getMimeType() ?? '');
        }

        $mimeType = $this->normalizarMimeTypeLogo($mimeTypeDetectado);
        if ($mimeType === null) {
            throw new AppException('El logo debe ser una imagen PNG, JPG, WEBP o GIF válida.');
        }

        return $mimeType;
    }

    private function normalizarMimeTypeLogo(string $mimeType): ?string
    {
        $mimeType = strtolower(trim($mimeType));

        return match ($mimeType) {
            'image/jpeg', 'image/jpg', 'image/pjpeg' => 'image/jpeg',
            'image/png', 'image/x-png' => 'image/png',
            'image/webp' => 'image/webp',
            'image/gif' => 'image/gif',
            default => null,
        };
    }

    private function obtenerExtensionLogoPorMimeType(string $mimeType): string
    {
        return match ($mimeType) {
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/webp' => 'webp',
            'image/gif' => 'gif',
            default => 'png',
        };
    }

    private function normalizarSegmentoRuta(string $valor): string
    {
        $valor = strtolower(trim($valor));
        $valor = preg_replace('/[^a-z0-9_-]+/i', '-', $valor) ?? $valor;
        $valor = trim($valor, '-_');

        return $valor !== '' ? $valor : 'torneo';
    }

    private function normalizarImagenLogo(UploadedFile $archivo, string $mimeType): ?\GdImage
    {
        $rutaArchivo = $archivo->getPathname();

        $origen = match ($mimeType) {
            'image/jpeg' => function_exists('imagecreatefromjpeg') ? @imagecreatefromjpeg($rutaArchivo) : false,
            'image/png' => function_exists('imagecreatefrompng') ? @imagecreatefrompng($rutaArchivo) : false,
            'image/webp' => function_exists('imagecreatefromwebp') ? @imagecreatefromwebp($rutaArchivo) : false,
            'image/gif' => function_exists('imagecreatefromgif') ? @imagecreatefromgif($rutaArchivo) : false,
            default => false,
        };

        if (!$origen) {
            // Fallback: si GD no decodifica esta imagen, se guarda el archivo original.
            return null;
        }

        $anchoOriginal = imagesx($origen);
        $altoOriginal = imagesy($origen);

        if ($anchoOriginal <= 0 || $altoOriginal <= 0) {
            imagedestroy($origen);
            throw new AppException('No fue posible procesar el logo.');
        }

        $maximo = 512;
        $escala = min($maximo / $anchoOriginal, $maximo / $altoOriginal, 1);
        $anchoNuevo = max(1, (int) round($anchoOriginal * $escala));
        $altoNuevo = max(1, (int) round($altoOriginal * $escala));

        $normalizada = imagecreatetruecolor($anchoNuevo, $altoNuevo);
        if (!$normalizada) {
            imagedestroy($origen);
            throw new AppException('No fue posible preparar el logo para guardar.');
        }

        imagealphablending($normalizada, false);
        imagesavealpha($normalizada, true);
        $transparente = imagecolorallocatealpha($normalizada, 0, 0, 0, 127);
        imagefilledrectangle($normalizada, 0, 0, $anchoNuevo, $altoNuevo, $transparente);
        imagecopyresampled($normalizada, $origen, 0, 0, 0, 0, $anchoNuevo, $altoNuevo, $anchoOriginal, $altoOriginal);
        imagedestroy($origen);

        return $normalizada;
    }
}

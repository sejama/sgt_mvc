<?php

declare(strict_types=1);

namespace App\Tests\Unit\Utils;

use App\Entity\Cancha;
use App\Entity\Categoria;
use App\Entity\Partido;
use App\Entity\PartidoConfig;
use App\Entity\Sede;
use App\Entity\Torneo;
use App\Entity\Usuario;
use App\Enum\Genero;
use App\Utils\GenerarPdf;
use PHPUnit\Framework\TestCase;

class GenerarPdfTest extends TestCase
{
    private string $projectRoot;

    private string $planillaImagePath;

    protected function setUp(): void
    {
        $this->projectRoot = dirname(__DIR__, 3);
        $this->planillaImagePath = $this->projectRoot . '/assets/img/planilla.png';

        if (!is_dir(dirname($this->planillaImagePath))) {
            mkdir(dirname($this->planillaImagePath), 0777, true);
        }

        $image = imagecreatetruecolor(1, 1);
        $backgroundColor = imagecolorallocate($image, 255, 255, 255);
        imagefill($image, 0, 0, $backgroundColor);
        imagepng($image, $this->planillaImagePath);
        imagedestroy($image);
    }

    protected function tearDown(): void
    {
        $this->removeDirectory($this->projectRoot . '/assets/planillas/test-generar-pdf-full');
        $this->removeDirectory($this->projectRoot . '/assets/planillas/test-generar-pdf-fallback');

        $tempPdf = $this->projectRoot . '/src/Utils/partido-101.pdf';
        if (file_exists($tempPdf)) {
            unlink($tempPdf);
        }

        if (file_exists($this->planillaImagePath)) {
            unlink($this->planillaImagePath);
        }

        $imgDir = dirname($this->planillaImagePath);
        if (is_dir($imgDir)) {
            @rmdir($imgDir);
        }
    }

    public function testGenerarPdfCreaArchivosConDatosCompletos(): void
    {
        $_SERVER['HTTP_HOST'] = 'localhost';
        unset($_SERVER['SERVER_NAME']);

        $partido = $this->crearPartidoCompleto(101);
        $ruta = 'test-generar-pdf-full';

        $generarPdf = new GenerarPdf();
        $generarPdf->generarPdf($partido, $ruta);

        $pdfPath = $this->projectRoot . '/assets/planillas/' . $ruta . '/pdf/partido-101.pdf';
        $qrPath = $this->projectRoot . '/assets/planillas/' . $ruta . '/qr/partido-101.png';

        self::assertFileExists($pdfPath);
        self::assertFileExists($qrPath);
        self::assertGreaterThan(0, filesize($pdfPath));
        self::assertGreaterThan(0, filesize($qrPath));
    }

    public function testGenerarPdfUsaDatosDeRespaldoSinCanchaNiEquipos(): void
    {
        unset($_SERVER['HTTP_HOST']);
        $_SERVER['SERVER_NAME'] = 'example.test';

        $partido = $this->crearPartidoSinCanchaNiEquipos(102);
        $ruta = 'test-generar-pdf-fallback';

        $generarPdf = new GenerarPdf();
        $generarPdf->generarPdf($partido, $ruta);

        $pdfPath = $this->projectRoot . '/assets/planillas/' . $ruta . '/pdf/partido-102.pdf';
        $qrPath = $this->projectRoot . '/assets/planillas/' . $ruta . '/qr/partido-102.png';

        self::assertFileExists($pdfPath);
        self::assertFileExists($qrPath);
        self::assertGreaterThan(0, filesize($pdfPath));
        self::assertGreaterThan(0, filesize($qrPath));
    }

    private function crearPartidoCompleto(int $numero): Partido
    {
        $torneo = (new Torneo())
            ->setNombre('Torneo de prueba PDF')
            ->setRuta('torneo-pdf-prueba')
            ->setDescripcion('Descripcion')
            ->setFechaInicioInscripcion(new \DateTimeImmutable('2026-01-01 00:00:00'))
            ->setFechaFinInscripcion(new \DateTimeImmutable('2026-01-02 00:00:00'))
            ->setFechaInicioTorneo(new \DateTimeImmutable('2026-01-03 00:00:00'))
            ->setFechaFinTorneo(new \DateTimeImmutable('2026-01-04 00:00:00'))
            ->setReglamento(null)
            ->setCreador((new Usuario())->setNombre('Creador')->setApellido('PDF')->setEmail('creador@example.com')->setUsername('creador_pdf')->setRoles(['ROLE_USER']))
            ->setEstado('Activo');

        $categoria = (new Categoria())
            ->setNombre('Categoria PDF')
            ->setNombreCorto('CPDF')
            ->setGenero(Genero::MASCULINO)
            ->setEstado('borrador')
            ->setTorneo($torneo);

        $sede = (new Sede())
            ->setNombre('Sede PDF')
            ->setDomicilio('Calle PDF 123')
            ->setTorneo($torneo);

        $cancha = (new Cancha())
            ->setNombre('Cancha PDF')
            ->setDescripcion('Descripcion cancha PDF')
            ->setSede($sede);

        $partido = (new Partido())
            ->setCategoria($categoria)
            ->setCancha($cancha)
            ->setHorario(new \DateTimeImmutable('2026-02-01 18:30:00'))
            ->setEstado('Programado')
            ->setTipo('Clasificatorio')
            ->setNumero($numero)
            ->setEquipoLocal((new \App\Entity\Equipo())->setNombre('Local PDF')->setNombreCorto('LPDF')->setPais('Argentina')->setProvincia('Mendoza')->setLocalidad('Capital')->setCategoria($categoria))
            ->setEquipoVisitante((new \App\Entity\Equipo())->setNombre('Visitante PDF')->setNombreCorto('VPDF')->setPais('Argentina')->setProvincia('Cordoba')->setLocalidad('Centro')->setCategoria($categoria));

        return $partido;
    }

    private function crearPartidoSinCanchaNiEquipos(int $numero): Partido
    {
        $torneo = (new Torneo())
            ->setNombre('Torneo fallback PDF')
            ->setRuta('torneo-pdf-fallback')
            ->setDescripcion('Descripcion')
            ->setFechaInicioInscripcion(new \DateTimeImmutable('2026-03-01 00:00:00'))
            ->setFechaFinInscripcion(new \DateTimeImmutable('2026-03-02 00:00:00'))
            ->setFechaInicioTorneo(new \DateTimeImmutable('2026-03-03 00:00:00'))
            ->setFechaFinTorneo(new \DateTimeImmutable('2026-03-04 00:00:00'))
            ->setReglamento(null)
            ->setCreador((new Usuario())->setNombre('Creador')->setApellido('Fallback')->setEmail('fallback@example.com')->setUsername('creador_fallback')->setRoles(['ROLE_USER']))
            ->setEstado('Activo');

        $categoria = (new Categoria())
            ->setNombre('Categoria Fallback')
            ->setNombreCorto('CFB')
            ->setGenero(Genero::MASCULINO)
            ->setEstado('borrador')
            ->setTorneo($torneo);

        $partidoConfig = (new PartidoConfig())
            ->setNombre('Partido Configurado PDF');

        $partido = (new Partido())
            ->setCategoria($categoria)
            ->setCancha(null)
            ->setHorario(null)
            ->setEstado('Borrador')
            ->setTipo('Eliminatorio')
            ->setNumero($numero)
            ->setPartidoConfig($partidoConfig);

        return $partido;
    }

    private function removeDirectory(string $directory): void
    {
        if (!is_dir($directory)) {
            return;
        }

        $items = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($directory, \FilesystemIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::CHILD_FIRST
        );

        foreach ($items as $item) {
            if ($item->isDir()) {
                @rmdir($item->getPathname());
            } else {
                @unlink($item->getPathname());
            }
        }

        @rmdir($directory);
    }
}

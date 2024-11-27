<?php

namespace App\Tests\Unit\Manager;

use App\Entity\Torneo;
use App\Entity\Usuario;
use App\Exception\AppException;
use App\Manager\ValidadorManager;
use PHPUnit\Framework\TestCase;

class ValidadorManagerTest extends TestCase
{
    public function testValidarTorneoOK(): void
    {
        $validadorManager = new ValidadorManager();
        $validadorManager->validarTorneo(
            'Torneo de prueba',
            'torneo-de-prueba',
            'Descripción del torneo de prueba',
            '2021-10-01 00:00',
            '2021-10-31 23:59',
            '2021-09-01 00:00',
            '2021-09-30 23:59',
            new Usuario()
        );

        $this->assertTrue(true);
    }

    public function testValidarTorneoNombreMenor3(): void
    {
        $validadorManager = new ValidadorManager();

        $this->expectException(AppException::class);
        $this->expectExceptionMessage('El Nombre debe tener entre 3 y 128 caracteres');
        $validadorManager->validarTorneo(
            '',
            'torneo-de-prueba',
            'Descripción del torneo de prueba',
            '2021-10-01 00:00',
            '2021-10-31 23:59',
            '2021-09-01 00:00',
            '2021-09-30 23:59',
            new Usuario()
        );
    }

    public function testValidarTorneoNombreMayor128(): void
    {
        $validadorManager = new ValidadorManager();

        $this->expectException(AppException::class);
        $this->expectExceptionMessage('El Nombre debe tener entre 3 y 128 caracteres');
        $validadorManager->validarTorneo(
            str_repeat('a', 129),
            'torneo-de-prueba',
            'Descripción del torneo de prueba',
            '2021-10-01 00:00',
            '2021-10-31 23:59',
            '2021-09-01 00:00',
            '2021-09-30 23:59',
            new Usuario()
        );
    }

    public function testValidarTorneoRutaMenor3(): void
    {
        $validadorManager = new ValidadorManager();

        $this->expectException(AppException::class);
        $this->expectExceptionMessage('El Nombre Corto debe tener entre 3 y 32 caracteres');
        $validadorManager->validarTorneo(
            'Torneo de prueba',
            '',
            'Descripción del torneo de prueba',
            '2021-10-01 00:00',
            '2021-10-31 23:59',
            '2021-09-01 00:00',
            '2021-09-30 23:59',
            new Usuario()
        );
    }

    public function testValidarTorneoRutaMayor32(): void
    {
        $validadorManager = new ValidadorManager();

        $this->expectException(AppException::class);
        $this->expectExceptionMessage('El Nombre Corto debe tener entre 3 y 32 caracteres');
        $validadorManager->validarTorneo(
            'Torneo de prueba',
            str_repeat('a', 129),
            'Descripción del torneo de prueba',
            '2021-10-01 00:00',
            '2021-10-31 23:59',
            '2021-09-01 00:00',
            '2021-09-30 23:59',
            new Usuario()
        );
    }

    public function testValidarTorneoRutaConEspacio(): void
    {
        $validadorManager = new ValidadorManager();

        $this->expectException(AppException::class);
        $this->expectExceptionMessage('La ruta no puede contener espacios');
        $validadorManager->validarTorneo(
            'Torneo de prueba',
            'torneo-de prueba',
            'Descripción del torneo de prueba',
            '2021-10-01 00:00',
            '2021-10-31 23:59',
            '2021-09-01 00:00',
            '2021-09-30 23:59',
            new Usuario()
        );
    }

    public function testValidarTorneoDescripcionMayor255(): void
    {
        $validadorManager = new ValidadorManager();

        $this->expectException(AppException::class);
        $this->expectExceptionMessage('El Descripción debe tener entre 0 y 255 caracteres');
        $validadorManager->validarTorneo(
            'Torneo de prueba',
            'torneo-de-prueba',
            str_repeat('a', 256),
            '2021-10-01 00:00',
            '2021-10-31 23:59',
            '2021-09-01 00:00',
            '2021-09-30 23:59',
            new Usuario()
        );
    }

    public function testValidarTorneoFechaInicioInvalida(): void
    {
        $validadorManager = new ValidadorManager();

        $this->expectException(AppException::class);
        $this->expectExceptionMessage('La fecha de Inicio del torneo no es válida');
        $validadorManager->validarTorneo(
            'Torneo de prueba',
            'torneo-de-prueba',
            'Descripción del torneo de prueba',
            '2021-10-01 00:00:00',
            '2021-10-31 23:59',
            '2021-09-01 00:00',
            '2021-09-30 23:59',
            new Usuario()
        );
    }

    public function testValidarTorneoFechaFinInvalida(): void
    {
        $validadorManager = new ValidadorManager();

        $this->expectException(AppException::class);
        $this->expectExceptionMessage('La fecha de Fin del torneo no es válida');
        $validadorManager->validarTorneo(
            'Torneo de prueba',
            'torneo-de-prueba',
            'Descripción del torneo de prueba',
            '2021-10-01 00:00',
            '2021-10-31 23:59:59',
            '2021-09-01 00:00',
            '2021-09-30 23:59',
            new Usuario()
        );
    }

    public function testValidarTorneoFechaInicioFinInvalida(): void
    {
        $validadorManager = new ValidadorManager();

        $this->expectException(AppException::class);
        $this->expectExceptionMessage('La fecha de Torneo no puede ser mayor a la fecha de fin');
        $validadorManager->validarTorneo(
            'Torneo de prueba',
            'torneo-de-prueba',
            'Descripción del torneo de prueba',
            '2021-10-31 23:59',
            '2021-10-01 00:00',
            '2021-09-01 00:00',
            '2021-09-30 23:59',
            new Usuario()
        );
    }

    public function testValidarTorneoFechaInscripcionInvalida(): void
    {
        $validadorManager = new ValidadorManager();

        $this->expectException(AppException::class);
        $this->expectExceptionMessage('La fecha de Inicio de inscripción no es válida');
        $validadorManager->validarTorneo(
            'Torneo de prueba',
            'torneo-de-prueba',
            'Descripción del torneo de prueba',
            '2021-10-01 00:00',
            '2021-10-31 23:59',
            '2021-09-01 00:00:00',
            '2021-09-30 23:59',
            new Usuario()
        );
    }

    public function testValidarTorneoFechaFinInscripcionInvalida(): void
    {
        $validadorManager = new ValidadorManager();

        $this->expectException(AppException::class);
        $this->expectExceptionMessage('La fecha de Fin de inscripción no es válida');
        $validadorManager->validarTorneo(
            'Torneo de prueba',
            'torneo-de-prueba',
            'Descripción del torneo de prueba',
            '2021-10-01 00:00',
            '2021-10-31 23:59',
            '2021-09-01 00:00',
            '2021-09-30 23:59:59',
            new Usuario()
        );
    }

    public function testValidarTorneoFechaInscripcionFinInscripcionInvalida(): void
    {
        $validadorManager = new ValidadorManager();

        $this->expectException(AppException::class);
        $this->expectExceptionMessage('La fecha de Inscripción no puede ser mayor a la fecha de fin');
        $validadorManager->validarTorneo(
            'Torneo de prueba',
            'torneo-de-prueba',
            'Descripción del torneo de prueba',
            '2021-10-01 00:00',
            '2021-10-31 23:59',
            '2021-09-30 23:59',
            '2021-09-01 00:00',
            new Usuario()
        );
    }

    public function testValidarTorneoFinInscripcionInicioTorneoInvalida(): void
    {
        $validadorManager = new ValidadorManager();

        $this->expectException(AppException::class);
        $this->expectExceptionMessage('La fecha de Inscripción y Torneo no puede ser mayor a la fecha de fin');
        $validadorManager->validarTorneo(
            'Torneo de prueba',
            'torneo-de-prueba',
            'Descripción del torneo de prueba',
            '2021-10-01 00:00',
            '2021-10-31 23:59',
            '2021-09-01 00:00',
            '2021-10-30 23:59',
            new Usuario()
        );
    }

    public function testValidarCategoriaOK(): void
    {
        $validadorManager = new ValidadorManager();
        $validadorManager->validarCategoria(
            new Torneo(),
            'Masculino',
            'Categoría de prueba',
            'cat-prueba'
        );

        $this->assertTrue(true);
    }

    public function testValidarCategoriaGeneroInvalido(): void
    {
        $validadorManager = new ValidadorManager();

        $this->expectException(AppException::class);
        $this->expectExceptionMessage('El género no es válido');
        $validadorManager->validarCategoria(
            new Torneo(),
            'Fem',
            'Categoría de prueba',
            'cat-prueba'
        );
    }

    public function testValidarCategoriaNombreMenor3(): void
    {
        $validadorManager = new ValidadorManager();

        $this->expectException(AppException::class);
        $this->expectExceptionMessage('El Nombre debe tener entre 3 y 128 caracteres');
        $validadorManager->validarCategoria(
            new Torneo(),
            'Masculino',
            '',
            'cat-prueba'
        );
    }

    public function testValidarCategoriaNombreMayor128(): void
    {
        $validadorManager = new ValidadorManager();

        $this->expectException(AppException::class);
        $this->expectExceptionMessage('El Nombre debe tener entre 3 y 128 caracteres');
        $validadorManager->validarCategoria(
            new Torneo(),
            'Masculino',
            str_repeat('a', 129),
            'cat-prueba'
        );
    }

    public function testValidarCategoriaNombreCortoMenor3(): void
    {
        $validadorManager = new ValidadorManager();

        $this->expectException(AppException::class);
        $this->expectExceptionMessage('El Nombre Corto debe tener entre 3 y 32 caracteres');
        $validadorManager->validarCategoria(
            new Torneo(),
            'Masculino',
            'Categoría de prueba',
            ''
        );
    }

    public function testValidarCategoriaNombreCortoMayor32(): void
    {
        $validadorManager = new ValidadorManager();

        $this->expectException(AppException::class);
        $this->expectExceptionMessage('El Nombre Corto debe tener entre 3 y 32 caracteres');
        $validadorManager->validarCategoria(
            new Torneo(),
            'Masculino',
            'Categoría de prueba',
            str_repeat('a', 33)
        );
    }

    public function testValidarSedeOK(): void
    {
        $validadorManager = new ValidadorManager();
        $validadorManager->validarSede(
            'Sede de prueba',
            'Dirección de prueba'
        );

        $this->assertTrue(true);
    }

    public function testValidarSedeNombreMenor3(): void
    {
        $validadorManager = new ValidadorManager();

        $this->expectException(AppException::class);
        $this->expectExceptionMessage('El Nombre debe tener entre 3 y 128 caracteres');
        $validadorManager->validarSede(
            '',
            'Dirección de prueba'
        );
    }

    public function testValidarSedeNombreMayor128(): void
    {
        $validadorManager = new ValidadorManager();

        $this->expectException(AppException::class);
        $this->expectExceptionMessage('El Nombre debe tener entre 3 y 128 caracteres');
        $validadorManager->validarSede(
            str_repeat('a', 129),
            'Dirección de prueba'
        );
    }

    public function testValidarSedeDireccionMenor8(): void
    {
        $validadorManager = new ValidadorManager();

        $this->expectException(AppException::class);
        $this->expectExceptionMessage('El Dirección debe tener entre 8 y 128 caracteres');
        $validadorManager->validarSede(
            'Sede de prueba',
            ''
        );
    }

    public function testValidarSedeDireccionMayor128(): void
    {
        $validadorManager = new ValidadorManager();

        $this->expectException(AppException::class);
        $this->expectExceptionMessage('El Dirección debe tener entre 8 y 128 caracteres');
        $validadorManager->validarSede(
            'Sede de prueba',
            str_repeat('a', 129)
        );
    }

    public function testValidarCanchaOK(): void
    {
        $validadorManager = new ValidadorManager();
        $validadorManager->validarCancha(
            'Cancha de prueba',
            'Descripción de la cancha de prueba'
        );

        $this->assertTrue(true);
    }

    public function testValidarCanchaNombreMenor1(): void
    {
        $validadorManager = new ValidadorManager();

        $this->expectException(AppException::class);
        $this->expectExceptionMessage('El Nombre debe tener entre 1 y 128 caracteres');
        $validadorManager->validarCancha(
            '',
            'Descripción de la cancha de prueba'
        );
    }

    public function testValidarCanchaNombreMayor128(): void
    {
        $validadorManager = new ValidadorManager();

        $this->expectException(AppException::class);
        $this->expectExceptionMessage('El Nombre debe tener entre 1 y 128 caracteres');
        $validadorManager->validarCancha(
            str_repeat('a', 129),
            'Descripción de la cancha de prueba'
        );
    }
}

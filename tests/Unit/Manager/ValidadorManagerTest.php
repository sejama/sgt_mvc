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

    public function testValidarCanchaDescripcionMayor255(): void
    {
        $validadorManager = new ValidadorManager();

        $this->expectException(AppException::class);
        $this->expectExceptionMessage('El Descripción debe tener entre 0 y 255 caracteres');
        $validadorManager->validarCancha(
            'Cancha de prueba',
            str_repeat('a', 256)
        );
    }

    public function testValidarEquipoOK(): void
    {
        $validadorManager = new ValidadorManager();
        $validadorManager->validarEquipo(
            'Equipo de prueba',
            'Eq. prueba',
            'Argentina',
            'Buenos Aires',
            'La Plata'
        );

        $this->assertTrue(true);
    }

    public function testValidarEquipoNombreMenor3(): void
    {
        $validadorManager = new ValidadorManager();

        $this->expectException(AppException::class);
        $this->expectExceptionMessage('El Nombre debe tener entre 3 y 128 caracteres');
        $validadorManager->validarEquipo(
            '',
            'Eq. prueba',
            'Argentina',
            'Buenos Aires',
            'La Plata'
        );
    }

    public function testValidarEquipoNombreMayor128(): void
    {
        $validadorManager = new ValidadorManager();

        $this->expectException(AppException::class);
        $this->expectExceptionMessage('El Nombre debe tener entre 3 y 128 caracteres');
        $validadorManager->validarEquipo(
            str_repeat('a', 129),
            'Eq. prueba',
            'Argentina',
            'Buenos Aires',
            'La Plata'
        );
    }

    public function testValidarEquipoNombreCortoMenor3(): void
    {
        $validadorManager = new ValidadorManager();

        $this->expectException(AppException::class);
        $this->expectExceptionMessage('El Nombre Corto debe tener entre 2 y 16 caracteres');
        $validadorManager->validarEquipo(
            'Equipo de prueba',
            '',
            'Argentina',
            'Buenos Aires',
            'La Plata'
        );
    }

    public function testValidarEquipoNombreCortoMayor16(): void
    {
        $validadorManager = new ValidadorManager();

        $this->expectException(AppException::class);
        $this->expectExceptionMessage('El Nombre Corto debe tener entre 2 y 16 caracteres');
        $validadorManager->validarEquipo(
            'Equipo de prueba',
            str_repeat('a', 17),
            'Argentina',
            'Buenos Aires',
            'La Plata'
        );
    }

    public function testValidarJugadorOK(): void
    {
        $validadorManager = new ValidadorManager();
        $validadorManager->validarJugador(
            'Nombre de prueba',
            'Apellido de prueba',
            'DNI',
            '12345678',
            '2000-01-01'
        );

        $this->assertTrue(true);
    }

    public function testValidarJugadorNombreMenor3(): void
    {
        $validadorManager = new ValidadorManager();

        $this->expectException(AppException::class);
        $this->expectExceptionMessage('El Nombre debe tener entre 3 y 128 caracteres');
        $validadorManager->validarJugador(
            '',
            'Apellido de prueba',
            'DNI',
            '12345678',
            '2000-01-01'
        );
    }

    public function testValidarJugadorNombreMayor128(): void
    {
        $validadorManager = new ValidadorManager();

        $this->expectException(AppException::class);
        $this->expectExceptionMessage('El Nombre debe tener entre 3 y 128 caracteres');
        $validadorManager->validarJugador(
            str_repeat('a', 129),
            'Apellido de prueba',
            'DNI',
            '12345678',
            '2000-01-01'
        );
    }

    public function testValidarJugadorApellidoMenor3(): void
    {
        $validadorManager = new ValidadorManager();

        $this->expectException(AppException::class);
        $this->expectExceptionMessage('El Apellido debe tener entre 3 y 128 caracteres');
        $validadorManager->validarJugador(
            'Nombre de prueba',
            '',
            'DNI',
            '12345678',
            '2000-01-01'
        );
    }

    public function testValidarJugadorApellidoMayor128(): void
    {
        $validadorManager = new ValidadorManager();

        $this->expectException(AppException::class);
        $this->expectExceptionMessage('El Apellido debe tener entre 3 y 128 caracteres');
        $validadorManager->validarJugador(
            'Nombre de prueba',
            str_repeat('a', 129),
            'DNI',
            '12345678',
            '2000-01-01'
        );
    }

    public function testValidarJugadorTipoDocumentoMenor1(): void
    {
        $validadorManager = new ValidadorManager();

        $this->expectException(AppException::class);
        $this->expectExceptionMessage('El Tipo Documento debe tener entre 1 y 8 caracteres');
        $validadorManager->validarJugador(
            'Nombre de prueba',
            'Apellido de prueba',
            '',
            '12345678',
            '2000-01-01'
        );
    }

    public function testValidarJugadorTipoDocumentoMayor8(): void
    {
        $validadorManager = new ValidadorManager();

        $this->expectException(AppException::class);
        $this->expectExceptionMessage('El Tipo Documento debe tener entre 1 y 8 caracteres');
        $validadorManager->validarJugador(
            'Nombre de prueba',
            'Apellido de prueba',
            str_repeat('a', 9),
            '12345678',
            '2000-01-01'
        );
    }

    public function testValidarJugadorNumeroDocumentoMenor5(): void
    {
        $validadorManager = new ValidadorManager();

        $this->expectException(AppException::class);
        $this->expectExceptionMessage('El Número Documento debe tener entre 5 y 8 caracteres');
        $validadorManager->validarJugador(
            'Nombre de prueba',
            'Apellido de prueba',
            'DNI',
            '',
            '2000-01-01'
        );
    }

    public function testValidarJugadorNumeroDocumentoMayor8(): void
    {
        $validadorManager = new ValidadorManager();

        $this->expectException(AppException::class);
        $this->expectExceptionMessage('El Número Documento debe tener entre 5 y 8 caracteres');
        $validadorManager->validarJugador(
            'Nombre de prueba',
            'Apellido de prueba',
            'DNI',
            str_repeat('1', 9),
            '2000-01-01'
        );
    }

    public function testValidarJugadorFechaNacimientoInvalida(): void
    {
        $validadorManager = new ValidadorManager();

        $this->expectException(AppException::class);
        $this->expectExceptionMessage('La fecha de Fecha de Nacimiento no es válida');
        $validadorManager->validarJugador(
            'Nombre de prueba',
            'Apellido de prueba',
            'DNI',
            '12345678',
            '2000-01-32a'
        );
    }
}

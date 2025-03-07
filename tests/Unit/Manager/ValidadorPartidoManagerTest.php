<?php

declare(strict_types=1);

namespace App\Tests\Manager;

use App\Exception\AppException;
use App\Manager\ValidadorPartidoManager;
use PHPUnit\Framework\TestCase;

class ValidadorPartidoManagerTest extends TestCase
{
    private ValidadorPartidoManager $validadorPartidoManager;

    protected function setUp(): void
    {
        $this->validadorPartidoManager = new ValidadorPartidoManager();
    }

    public function testValidarPlayOffNombreRequerido(): void
    {
        $this->expectException(AppException::class);
        $this->expectExceptionMessage('El nombre del partido es requerido');

        $partidosPlayOff = [
            'oro' => [
                'Cuartos de Final Oro' => [
                    [
                        'grupoEquipo1' => '1',
                        'posicionEquipo1' => '1',
                        'grupoEquipo2' => '2',
                        'posicionEquipo2' => '2',
                    ],
                ],
            ],
        ];

        $this->validadorPartidoManager->validarPlayOff($partidosPlayOff);
    }

    public function testValidarPlayOffEquiposRequeridos(): void
    {
        $this->expectException(AppException::class);
        $this->expectExceptionMessage('Los equipos y sus posiciones son requeridos');

        $partidosPlayOff = [
            'oro' => [
                'Cuartos de Final Oro' => [
                    [
                        'nombre' => 'Cuartos de Final Oro 1',
                        'grupoEquipo1' => '',
                        'posicionEquipo1' => '1',
                        'grupoEquipo2' => '2',
                        'posicionEquipo2' => '2',
                    ],
                ],
            ],
        ];

        $this->validadorPartidoManager->validarPlayOff($partidosPlayOff);
    }

    public function testValidarPlayOffPosicionesValidas(): void
    {
        $this->expectException(AppException::class);
        $this->expectExceptionMessage('Las posiciones de los equipos deben ser números válidos');

        $partidosPlayOff = [
            'oro' => [
                'Cuartos de Final Oro' => [
                    [
                        'nombre' => 'Cuartos de Final Oro 1',
                        'grupoEquipo1' => '1',
                        'posicionEquipo1' => 'uno',
                        'grupoEquipo2' => '2',
                        'posicionEquipo2' => '2',
                    ],
                ],
            ],
        ];

        $this->validadorPartidoManager->validarPlayOff($partidosPlayOff);
    }

    public function testValidarPlayOffEquiposGanadoresValidos(): void
    {
        $this->expectException(AppException::class);
        $this->expectExceptionMessage('Los equipos ganadores deben ser números válidos');

        $partidosPlayOff = [
            'oro' => [
                'Cuartos de Final Oro' => [
                    [
                        'nombre' => 'Cuartos de Final Oro 1',
                        'grupoEquipo1' => '1',
                        'posicionEquipo1' => '1',
                        'grupoEquipo2' => '2',
                        'posicionEquipo2' => '2',
                        'equipoGanador1' => 'uno',
                        'equipoGanador2' => '2',
                    ],
                ],
            ],
        ];

        $this->validadorPartidoManager->validarPlayOff($partidosPlayOff);
    }

    public function testValidarPlayOffEquiposMismoGrupoPosicion(): void
    {
        $this->expectException(AppException::class);
        $this->expectExceptionMessage('Los equipos no pueden ser del mismo grupo y posición');

        $partidosPlayOff = [
            'oro' => [
                'Cuartos de Final Oro' => [
                    [
                        'nombre' => 'Cuartos de Final Oro 1',
                        'grupoEquipo1' => '1',
                        'posicionEquipo1' => '1',
                        'grupoEquipo2' => '1',
                        'posicionEquipo2' => '1',
                    ],
                ],
            ],
        ];

        $this->validadorPartidoManager->validarPlayOff($partidosPlayOff);
    }

    public function testValidarPlayOffEquiposGanadoresMismo(): void
    {
        $this->expectException(AppException::class);
        $this->expectExceptionMessage('Los equipos ganadores no pueden ser el mismo');

        $partidosPlayOff = [
            'oro' => [
                'Cuartos de Final Oro' => [
                    [
                        'nombre' => 'Cuartos de Final Oro 1',
                        'grupoEquipo1' => '1',
                        'posicionEquipo1' => '1',
                        'grupoEquipo2' => '2',
                        'posicionEquipo2' => '2',
                        'equipoGanador1' => '1',
                        'equipoGanador2' => '1',
                    ],
                ],
            ],
        ];

        $this->validadorPartidoManager->validarPlayOff($partidosPlayOff);
    }

    public function testValidarPlayOffEquipoAsignadoEnOtroPartido(): void
    {
        $this->expectException(AppException::class);
        $this->expectExceptionMessage('El equipo 1-1 ya está asignado en otro partido');

        $partidosPlayOff = [
            'oro' => [
                'Cuartos de Final Oro' => [
                    [
                        'nombre' => 'Cuartos de Final Oro 1',
                        'grupoEquipo1' => '1',
                        'posicionEquipo1' => '1',
                        'grupoEquipo2' => '2',
                        'posicionEquipo2' => '2',
                    ],
                    [
                        'nombre' => 'Cuartos de Final Oro 2',
                        'grupoEquipo1' => '1',
                        'posicionEquipo1' => '1',
                        'grupoEquipo2' => '3',
                        'posicionEquipo2' => '2',
                    ],
                ],
            ],
        ];

        $this->validadorPartidoManager->validarPlayOff($partidosPlayOff);
    }
}
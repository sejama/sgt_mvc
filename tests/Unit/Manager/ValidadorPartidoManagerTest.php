<?php

declare(strict_types=1);

namespace App\Tests\Manager;

use App\Exception\AppException;
use App\Manager\ValidadorPartidoManager;
use phpDocumentor\Reflection\PseudoTypes\True_;
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
                'Semi Final Oro' => [
                    [
                        'nombre' => 'Semi Final Oro 1',
                        'equipoGanador1' => '1',
                        'equipoGanador2' => '2',
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

    public function testValidarPlayOffOK(): void
    {
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
                        'grupoEquipo1' => '2',
                        'posicionEquipo1' => '1',
                        'grupoEquipo2' => '1',
                        'posicionEquipo2' => '2',
                    ],
                    [
                        'nombre' => 'Cuartos de Final Oro 3',
                        'grupoEquipo1' => '3',
                        'posicionEquipo1' => '1',
                        'grupoEquipo2' => '4',
                        'posicionEquipo2' => '2',
                    ],
                    [
                        'nombre' => 'Cuartos de Final Oro 4',
                        'grupoEquipo1' => '4',
                        'posicionEquipo1' => '1',
                        'grupoEquipo2' => '3',
                        'posicionEquipo2' => '2',
                    ],
                ],
                'Semi Final Oro' => [
                    [
                        'nombre' => 'Semi Final Oro 1',
                        'equipoGanador1' => '0',
                        'equipoGanador2' => '2',
                    ],
                    [
                        'nombre' => 'Semi Final Oro 2',
                        'equipoGanador1' => '1',
                        'equipoGanador2' => '3',
                    ]
                ],
                'Final Oro' => [
                    [
                        'nombre' => 'Final Oro 1',
                        'equipoGanador1' => '4',
                        'equipoGanador2' => '5',
                    ],
                ],
            ],
            'plata' => [
                'Cuartos de Final Plata' => [
                    [
                        'nombre' => 'Cuartos de Final Plata 1',
                        'grupoEquipo1' => '1',
                        'posicionEquipo1' => '9',
                        'grupoEquipo2' => '2',
                        'posicionEquipo2' => '10',
                    ],
                    [
                        'nombre' => 'Cuartos de Final Plata 2',
                        'grupoEquipo1' => '2',
                        'posicionEquipo1' => '9',
                        'grupoEquipo2' => '1',
                        'posicionEquipo2' => '10',
                    ],
                    [
                        'nombre' => 'Cuartos de Final Plata 3',
                        'grupoEquipo1' => '3',
                        'posicionEquipo1' => '9',
                        'grupoEquipo2' => '4',
                        'posicionEquipo2' => '10',
                    ],
                    [
                        'nombre' => 'Cuartos de Final Plata 4',
                        'grupoEquipo1' => '4',
                        'posicionEquipo1' => '9',
                        'grupoEquipo2' => '3',
                        'posicionEquipo2' => '10',
                    ],
                ],
                'Semi Final Plata' => [
                    [
                        'nombre' => 'Semi Final Plata 1',
                        'equipoGanador1' => '0',
                        'equipoGanador2' => '2',
                    ],
                    [
                        'nombre' => 'Semi Final Plata 2',
                        'equipoGanador1' => '1',
                        'equipoGanador2' => '3',
                    ]
                ],
                'Final Plata' => [
                    [
                        'nombre' => 'Final Plata 1',
                        'equipoGanador1' => '4',
                        'equipoGanador2' => '5',
                    ],
                ],
            ],
        ]; 
        $this->validadorPartidoManager->validarPlayOff($partidosPlayOff);
        $this->assertTrue(true);
        
    }

}
<?php

namespace App\DataFixtures;

use App\Entity\Categoria;
use App\Entity\Equipo;
use App\Entity\Jugador;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class EquipoFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $catF35 = $this->getReference(CategoriaFixtures::CATF35_REFERENCE, Categoria::class);
        $catF40 = $this->getReference(CategoriaFixtures::CATF40_REFERENCE, Categoria::class);
        $catF45 = $this->getReference(CategoriaFixtures::CATF45_REFERENCE, Categoria::class);
        $catM42 = $this->getReference(CategoriaFixtures::CATM42_REFERENCE, Categoria::class);
        $catM50 = $this->getReference(CategoriaFixtures::CATM50_REFERENCE, Categoria::class);

        $equiposF35 = [
            [
                'nombre' => 'VILLA DORA',
                'nombreCorto' => 'VD',
                'pais' => 'Argentina',
                'provincia' => 'Santa Fe',
                'localidad' => 'Rosario',
            ],
            [
                'nombre' => 'CORRIENTES VOLEY',
                'nombreCorto' => 'CV',
                'pais' => 'Argentina',
                'provincia' => 'Corrientes',
                'localidad' => 'Corrientes',
            ],
            [
                'nombre' => 'TREDE BIRRA',
                'nombreCorto' => 'TB',
                'pais' => 'Argentina',
                'provincia' => 'Santa Fe',
                'localidad' => 'Rosario',
            ],
            [
                'nombre' => 'ALUMNI CASILDA',
                'nombreCorto' => 'AC',
                'pais' => 'Argentina',
                'provincia' => 'Santa Fe',
                'localidad' => 'Casilda',
            ],
            [
                'nombre' => 'EL QUILLA',
                'nombreCorto' => 'EQ',
                'pais' => 'Argentina',
                'provincia' => 'Santa Fe',
                'localidad' => 'Santa Fe',
            ],
            [
                'nombre' => 'MONSTARS',
                'nombreCorto' => 'MON',
                'pais' => 'Argentina',
                'provincia' => 'Santa Fe',
                'localidad' => 'Rosario',
            ],
            [
                'nombre' => 'CLUB JUNIN',
                'nombreCorto' => 'CJ',
                'pais' => 'Argentina',
                'provincia' => 'Buenos Aires',
                'localidad' => 'Junin',
            ],
            [
                'nombre' => 'REGATAS ROSARIO',
                'nombreCorto' => 'RR',
                'pais' => 'Argentina',
                'provincia' => 'Santa Fe',
                'localidad' => 'Rosario',
            ],
            [
                'nombre' => 'ALIANZA SANTO TOME',
                'nombreCorto' => 'AST',
                'pais' => 'Argentina',
                'provincia' => 'Santa Fe',
                'localidad' => 'Santo Tome',
            ],
            [
                'nombre' => 'NAUTICO AVELLANEDA',
                'nombreCorto' => 'NA',
                'pais' => 'Argentina',
                'provincia' => 'Santa Fe',
                'localidad' => 'Avellaneda',
            ],
            [
                'nombre' => 'MALUCA',
                'nombreCorto' => 'MAL',
                'pais' => 'Argentina',
                'provincia' => 'Santa Fe',
                'localidad' => 'Rosario',
            ],
            [
                'nombre' => 'LA GRULLAS',
                'nombreCorto' => 'LG',
                'pais' => 'Argentina',
                'provincia' => 'Santa Fe',
                'localidad' => 'Rosario',
            ],
            [
                'nombre' => 'INFINITO',
                'nombreCorto' => 'INF',
                'pais' => 'Argentina',
                'provincia' => 'Santa Fe',
                'localidad' => 'Rosario',
            ],
            [
                'nombre' => 'LA EMILIA',
                'nombreCorto' => 'LE',
                'pais' => 'Argentina',
                'provincia' => 'Santa Fe',
                'localidad' => 'Rosario',
            ],
            [
                'nombre' => 'SANTO TOME',
                'nombreCorto' => 'ST',
                'pais' => 'Argentina',
                'provincia' => 'Santa Fe',
                'localidad' => 'Santo Tome',
            ],
            [
                'nombre' => 'LAS CUERVAS',
                'nombreCorto' => 'LC',
                'pais' => 'Argentina',
                'provincia' => 'Santa Fe',
                'localidad' => 'Rosario',
            ]
        ];

        foreach ($equiposF35 as $equipoF35) {
            $equipo = new Equipo();
            $equipo->setCategoria($catF35);
            $equipo->setNombre($equipoF35['nombre']);
            $equipo->setNombreCorto($equipoF35['nombreCorto']);
            $equipo->setPais($equipoF35['pais']);
            $equipo->setProvincia($equipoF35['provincia']);
            $equipo->setLocalidad($equipoF35['localidad']);

            $manager->persist($equipo);

            $jugador = new Jugador();
            $jugador->setEquipo($equipo);
            $jugador->setNombre('Delegado');
            $jugador->setApellido('Delegado');
            $jugador->setTipoDocumento('DNI');
            $jugador->setNumeroDocumento('12345678');
            $jugador->setNacimiento(
                new \DateTimeImmutable('1980-01-01', new \DateTimeZone('America/Argentina/Buenos_Aires'))
            );
            $jugador->setResponsable(true);
            $jugador->setEmail('delegado@delegado.com');
            $jugador->setCelular('3411234567');
            $jugador->setTipo('Entrenador');

            $manager->persist($jugador);
        }

        $equiposF40 = [
            [
                'nombre' => 'TREDE BIRRA',
                'nombreCorto' => 'TB',
                'pais' => 'Argentina',
                'provincia' => 'Santa Fe',
                'localidad' => 'Rosario',
            ],
            [
                'nombre' => 'VAMOS EL APOYO',
                'nombreCorto' => 'VEA',
                'pais' => 'Argentina',
                'provincia' => 'Santa Fe',
                'localidad' => 'Rosario',
            ],
            [
                'nombre' => 'COSTA CANELONES',
                'nombreCorto' => 'CC',
                'pais' => 'Uruguay',
                'provincia' => 'Canelones',
                'localidad' => 'Canelones',
            ],
            [
                'nombre' => 'INTRUSAS',
                'nombreCorto' => 'INT',
                'pais' => 'Argentina',
                'provincia' => 'Santa Fe',
                'localidad' => 'Rosario',
            ],
            [
                'nombre' => 'DOS HACHES',
                'nombreCorto' => 'DH',
                'pais' => 'Argentina',
                'provincia' => 'Santa Fe',
                'localidad' => 'Rosario',
            ],
            [
                'nombre' => 'SOMOS LA 18',
                'nombreCorto' => 'S18',
                'pais' => 'Argentina',
                'provincia' => 'Santa Fe',
                'localidad' => 'Rosario',
            ],
            [
                'nombre' => 'CLUB FISHERTON',
                'nombreCorto' => 'CF',
                'pais' => 'Argentina',
                'provincia' => 'Santa Fe',
                'localidad' => 'Rosario',
            ],
            [
                'nombre' => 'COSTA MIX',
                'nombreCorto' => 'CM',
                'pais' => 'Argentina',
                'provincia' => 'Santa Fe',
                'localidad' => 'Rosario',
            ],
            [
                'nombre' => 'PASO REY',
                'nombreCorto' => 'PR',
                'pais' => 'Argentina',
                'provincia' => 'Santa Fe',
                'localidad' => 'Rosario',
            ],
            [
                'nombre' => 'DESTINO VOLEY',
                'nombreCorto' => 'DV',
                'pais' => 'Argentina',
                'provincia' => 'Santa Fe',
                'localidad' => 'Rosario',
            ],
            [
                'nombre' => 'CITADAS',
                'nombreCorto' => 'CIT',
                'pais' => 'Argentina',
                'provincia' => 'Santa Fe',
                'localidad' => 'Rosario',
            ],
            [
                'nombre' => 'MALUCA',
                'nombreCorto' => 'MAL',
                'pais' => 'Argentina',
                'provincia' => 'Santa Fe',
                'localidad' => 'Rosario',
            ],
        ];

        foreach ($equiposF40 as $equipoF40) {
            $equipo = new Equipo();
            $equipo->setCategoria($catF40);
            $equipo->setNombre($equipoF40['nombre']);
            $equipo->setNombreCorto($equipoF40['nombreCorto']);
            $equipo->setPais($equipoF40['pais']);
            $equipo->setProvincia($equipoF40['provincia']);
            $equipo->setLocalidad($equipoF40['localidad']);

            $manager->persist($equipo);

            $jugador = new Jugador();
            $jugador->setEquipo($equipo);
            $jugador->setNombre('Delegado');
            $jugador->setApellido('Delegado');
            $jugador->setTipoDocumento('DNI');
            $jugador->setNumeroDocumento('12345678');
            $jugador->setNacimiento(
                new \DateTimeImmutable('1980-01-01', new \DateTimeZone('America/Argentina/Buenos_Aires'))
            );
            $jugador->setResponsable(true);
            $jugador->setEmail('delegado@delegado.com');
            $jugador->setCelular('3411234567');
            $jugador->setTipo('Entrenador');

            $manager->persist($jugador);
        }

        $equiposF45 = [
            [
                'nombre' => 'EL REJUNTE',
                'nombreCorto' => 'S18',
                'pais' => 'Argentina',
                'provincia' => 'Santa Fe',
                'localidad' => 'Rosario',
            ],
            [
                'nombre' => 'CLUB ROSARIO',
                'nombreCorto' => 'CR',
                'pais' => 'Argentina',
                'provincia' => 'Santa Fe',
                'localidad' => 'Rosario',
            ],
            [
                'nombre' => 'GYE CONCEP URUGUAY',
                'nombreCorto' => 'GCU',
                'pais' => 'Argentina',
                'provincia' => 'Entre Rios',
                'localidad' => 'Concepcion del Uruguay',
            ],
            [
                'nombre' => 'MONSTARS',
                'nombreCorto' => 'MON',
                'pais' => 'Argentina',
                'provincia' => 'Santa Fe',
                'localidad' => 'Rosario',
            ],
            [
                'nombre' => 'VOLEY MONTE',
                'nombreCorto' => 'VM',
                'pais' => 'Argentina',
                'provincia' => 'Santa Fe',
                'localidad' => 'San Lorenzo',
            ],
            [
                'nombre' => 'BANCO SANTA FE',
                'nombreCorto' => 'BSF',
                'pais' => 'Argentina',
                'provincia' => 'Santa Fe',
                'localidad' => 'Santa Fe',
            ],
            [
                'nombre' => 'UNI',
                'nombreCorto' => 'UNI',
                'pais' => 'Argentina',
                'provincia' => 'Santa Fe',
                'localidad' => 'Rosario',
            ],
        ];

        foreach ($equiposF45 as $equipoF45) {
            $equipo = new Equipo();
            $equipo->setCategoria($catF45);
            $equipo->setNombre($equipoF45['nombre']);
            $equipo->setNombreCorto($equipoF45['nombreCorto']);
            $equipo->setPais($equipoF45['pais']);
            $equipo->setProvincia($equipoF45['provincia']);
            $equipo->setLocalidad($equipoF45['localidad']);

            $manager->persist($equipo);

            $jugador = new Jugador();
            $jugador->setEquipo($equipo);
            $jugador->setNombre('Delegado');
            $jugador->setApellido('Delegado');
            $jugador->setTipoDocumento('DNI');
            $jugador->setNumeroDocumento('12345678');
            $jugador->setNacimiento(
                new \DateTimeImmutable('1980-01-01', new \DateTimeZone('America/Argentina/Buenos_Aires'))
            );
            $jugador->setResponsable(true);
            $jugador->setEmail('delegado@delegado.com');
            $jugador->setCelular('3411234567');
            $jugador->setTipo('Entrenador');

            $manager->persist($jugador);
        }

        $equiposM42 = [
            [
                'nombre' => 'MAXI SANTA FE A',
                'nombreCorto' => 'MSFA',
                'pais' => 'Argentina',
                'provincia' => 'Santa Fe',
                'localidad' => 'Santa Fe',
            ],
            [
                'nombre' => 'PERO',
                'nombreCorto' => 'PERO',
                'pais' => 'Argentina',
                'provincia' => 'Santa Fe',
                'localidad' => 'Santa Fe',
            ],
            [
                'nombre' => 'RECREATIVO VERA',
                'nombreCorto' => 'REVE',
                'pais' => 'Argentina',
                'provincia' => 'Santa Fe',
                'localidad' => 'Vera',
            ],
            [
                'nombre' => 'BOSQUE URUGUAY',
                'nombreCorto' => 'BOSQUE',
                'pais' => 'Argentina',
                'provincia' => 'Santa Fe',
                'localidad' => 'Santa Fe',
            ],
            [
                'nombre' => 'ROSARIO VOLEY',
                'nombreCorto' => 'ROVA',
                'pais' => 'Argentina',
                'provincia' => 'Santa Fe',
                'localidad' => 'Rosario',
            ],
            [
                'nombre' => 'LA TRIBU',
                'nombreCorto' => 'LATR',
                'pais' => 'Argentina',
                'provincia' => 'Santa Fe',
                'localidad' => 'Santa Fe',
            ],
            [
                'nombre' => 'MAXI SANTA FE B',
                'nombreCorto' => 'MSFB',
                'pais' => 'Argentina',
                'provincia' => 'Santa Fe',
                'localidad' => 'Santa Fe',
            ],
            [
                'nombre' => 'BANCO PROVINCIA',
                'nombreCorto' => 'BAPR',
                'pais' => 'Argentina',
                'provincia' => 'Santa Fe',
                'localidad' => 'Santa Fe',
            ]
        ];

        foreach ($equiposM42 as $equipoM42) {
            $equipo = new Equipo();
            $equipo->setCategoria($catM42);
            $equipo->setNombre($equipoM42['nombre']);
            $equipo->setNombreCorto($equipoM42['nombreCorto']);
            $equipo->setPais($equipoM42['pais']);
            $equipo->setProvincia($equipoM42['provincia']);
            $equipo->setLocalidad($equipoM42['localidad']);

            $manager->persist($equipo);

            $jugador = new Jugador();
            $jugador->setEquipo($equipo);
            $jugador->setNombre('Delegado');
            $jugador->setApellido('Delegado');
            $jugador->setTipoDocumento('DNI');
            $jugador->setNumeroDocumento('12345678');
            $jugador->setNacimiento(
                new \DateTimeImmutable('1980-01-01', new \DateTimeZone('America/Argentina/Buenos_Aires'))
            );
            $jugador->setResponsable(true);
            $jugador->setEmail('delegado@delegado.com');
            $jugador->setCelular('3411234567');
            $jugador->setTipo('Entrenador');

            $manager->persist($jugador);
        }

        $equiposM50 = [
            [
                'nombre' => 'MAXI SANTA FE',
                'nombreCorto' => 'MSF',
                'pais' => 'Argentina',
                'provincia' => 'Santa Fe',
                'localidad' => 'Santa Fe',
            ],
            [
                'nombre' => 'CORCHA VOLEY',
                'nombreCorto' => 'CORCHA',
                'pais' => 'Argentina',
                'provincia' => 'Santa Fe',
                'localidad' => 'Rosario',
            ],
            [
                'nombre' => 'LOS PERKINS',
                'nombreCorto' => 'LP',
                'pais' => 'Argentina',
                'provincia' => 'Santa Fe',
                'localidad' => 'Rosario',
            ],
            [
                'nombre' => 'ABANDONADOS',
                'nombreCorto' => 'ABA',
                'pais' => 'Argentina',
                'provincia' => 'Santa Fe',
                'localidad' => 'Rosario',
            ],
            [
                'nombre' => 'ROSARIO VOLEY',
                'nombreCorto' => 'ROVA',
                'pais' => 'Argentina',
                'provincia' => 'Santa Fe',
                'localidad' => 'Rosario',
            ],
            [
                'nombre' => 'DEFENSORES MORENO',
                'nombreCorto' => 'DEFF',
                'pais' => 'Argentina',
                'provincia' => 'Buenos Aires',
                'localidad' => 'Moreno',
            ],
            [
                'nombre' => 'TUCUMAN DE GIMNASIA',
                'nombreCorto' => 'TUGI',
                'pais' => 'Argentina',
                'provincia' => 'Tucuman',
                'localidad' => 'San Miguel de Tucuman',
            ],
            [
                'nombre' => 'DEPORTE RIO  IV',
                'nombreCorto' => 'DRI4',
                'pais' => 'Argentina',
                'provincia' => 'Cordoba',
                'localidad' => 'Rio Cuarto',
            ],
        ];

        foreach ($equiposM50 as $equipoM50) {
            $equipo = new Equipo();
            $equipo->setCategoria($catM50);
            $equipo->setNombre($equipoM50['nombre']);
            $equipo->setNombreCorto($equipoM50['nombreCorto']);
            $equipo->setPais($equipoM50['pais']);
            $equipo->setProvincia($equipoM50['provincia']);
            $equipo->setLocalidad($equipoM50['localidad']);

            $manager->persist($equipo);

            $jugador = new Jugador();
            $jugador->setEquipo($equipo);
            $jugador->setNombre('Delegado');
            $jugador->setApellido('Delegado');
            $jugador->setTipoDocumento('DNI');
            $jugador->setNumeroDocumento('12345678');
            $jugador->setNacimiento(
                new \DateTimeImmutable('1980-01-01', new \DateTimeZone('America/Argentina/Buenos_Aires'))
            );
            $jugador->setResponsable(true);
            $jugador->setEmail('delegado@delegado.com');
            $jugador->setCelular('3411234567');
            $jugador->setTipo('Entrenador');

            $manager->persist($jugador);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            CategoriaFixtures::class,
        ];
    }
}

<?php

namespace App\DataFixtures;

use App\Entity\Categoria;
use App\Entity\Grupo;
use App\Manager\GrupoManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class GrupoFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct(
        private GrupoManager $grupoManager
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        //Femenino +35 - F35 - 16 Equipos 4 Grupos de 4
        $categoria = $this->getReference(CategoriaFixtures::CATF35_REFERENCE, Categoria::class)->getId();
        $grupos = [
            [
                'nombre' => 'A',
                'categoria' => $categoria,
                'cantidad' => 4,
                'clasificaOro' => 2,
                'clasificaPlata' => 2,
                'clasificaBronce' => null
            ],
            [
                'nombre' => 'B',
                'categoria' => $categoria,
                'cantidad' => 4,
                'clasificaOro' => 2,
                'clasificaPlata' => 2,
                'clasificaBronce' => null
            ],
            [
                'nombre' => 'C',
                'categoria' => $categoria,
                'cantidad' => 4,
                'clasificaOro' => 2,
                'clasificaPlata' => 2,
                'clasificaBronce' => null
            ],
            [
                'nombre' => 'D',
                'categoria' => $categoria,
                'cantidad' => 4,
                'clasificaOro' => 2,
                'clasificaPlata' => 2,
                'clasificaBronce' => null
            ]
        ];
        $this->grupoManager->crearGrupos($grupos);
        //$manager->flush();

        //Femenino +40 - F40 - 12 Equipos 3 Grupos de 4
        $categoriaID = $this->getReference(CategoriaFixtures::CATF40_REFERENCE, Categoria::class)->getId();
        $grupos = [
            [
                'nombre' => 'E',
                'categoria' => $categoriaID,
                'cantidad' => 4,
                'clasificaOro' => 2,
                'clasificaPlata' => 2,
                'clasificaBronce' => null
            ],
            [
                'nombre' => 'F',
                'categoria' => $categoriaID,
                'cantidad' => 4,
                'clasificaOro' => 2,
                'clasificaPlata' => 2,
                'clasificaBronce' => null
            ],
            [
                'nombre' => 'G',
                'categoria' => $categoriaID,
                'cantidad' => 4,
                'clasificaOro' => 2,
                'clasificaPlata' => 2,
                'clasificaBronce' => null
            ]
        ];
        $this->grupoManager->crearGrupos($grupos);
        //$manager->flush();

        //Femenino +45 - F45 - 7 Equipos 1 Grupo de 7
        $categoriaID = $this->getReference(CategoriaFixtures::CATF45_REFERENCE, Categoria::class)->getId();
        $grupos = [
            [
                'nombre' => 'Unica',
                'categoria' => $categoriaID,
                'cantidad' => 7,
                'clasificaOro' => 4,
                'clasificaPlata' => 2,
                'clasificaBronce' => null
            ]
        ];

        $this->grupoManager->crearGrupos($grupos);

        //Masculino +42 - M42 - 8 Equipos 2 Grupos de 4
        $categoriaID = $this->getReference(CategoriaFixtures::CATM42_REFERENCE, Categoria::class)->getId();
        $grupos = [
            [
                'nombre' => '1',
                'categoria' => $categoriaID,
                'cantidad' => 4,
                'clasificaOro' => 2,
                'clasificaPlata' => 2,
                'clasificaBronce' => null
            ],
            [
                'nombre' => '2',
                'categoria' => $categoriaID,
                'cantidad' => 4,
                'clasificaOro' => 2,
                'clasificaPlata' => 2,
                'clasificaBronce' => null
            ]
        ];
        $this->grupoManager->crearGrupos($grupos);
        //$manager->flush();

        //Masculino +50 - M50 - 8 Equipos 2 Grupos de 4
        $categoriaID = $this->getReference(CategoriaFixtures::CATM50_REFERENCE, Categoria::class)->getId();
        $grupos = [
            [
                'nombre' => '3',
                'categoria' => $categoriaID,
                'cantidad' => 4,
                'clasificaOro' => 2,
                'clasificaPlata' => 2,
                'clasificaBronce' => null
            ],
            [
                'nombre' => '4',
                'categoria' => $categoriaID,
                'cantidad' => 4,
                'clasificaOro' => 2,
                'clasificaPlata' => 2,
                'clasificaBronce' => null
            ]
        ];
        $this->grupoManager->crearGrupos($grupos);
        //$manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            CategoriaFixtures::class,
        ];
    }
}

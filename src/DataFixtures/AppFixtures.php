<?php

namespace App\DataFixtures;

use App\Entity\Equipo;
use App\Entity\Categoria;
use App\Entity\Jugador;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
    }
}

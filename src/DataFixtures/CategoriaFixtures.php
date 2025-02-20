<?php

namespace App\DataFixtures;

use App\Entity\Categoria;
use App\Entity\Torneo;
use App\Enum\Genero;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class CategoriaFixtures extends Fixture implements DependentFixtureInterface
{
    public const CATF35_REFERENCE = 'catF35';
    public const CATF40_REFERENCE = 'catF40';
    public const CATF45_REFERENCE = 'catF45';
    public const CATM42_REFERENCE = 'catM42';
    public const CATM50_REFERENCE = 'catM50';

    public function load(ObjectManager $manager): void
    {

        $torneo = $this->getReference(TorneoFixtures::TORNEO1_REFERENCE, Torneo::class);

        $catF35 = new Categoria();
        $catF35->setTorneo($torneo);
        $catF35->setGenero(Genero::FEMENINO);
        $catF35->setNombre('Femenino +35');
        $catF35->setNombreCorto('F35');

        $catF40 = new Categoria();
        $catF40->setTorneo($torneo);
        $catF40->setGenero(Genero::FEMENINO);
        $catF40->setNombre('Femenino +40');
        $catF40->setNombreCorto('F40');

        $catF45 = new Categoria();
        $catF45->setTorneo($torneo);
        $catF45->setGenero(Genero::FEMENINO);
        $catF45->setNombre('Femenino +45');
        $catF45->setNombreCorto('F45');

        $catM42 = new Categoria();
        $catM42->setTorneo($torneo);
        $catM42->setGenero(Genero::MASCULINO);
        $catM42->setNombre('Masculino +42');
        $catM42->setNombreCorto('M42');

        $catM50 = new Categoria();
        $catM50->setTorneo($torneo);
        $catM50->setGenero(Genero::MASCULINO);
        $catM50->setNombre('Masculino +50');
        $catM50->setNombreCorto('M50');

        $manager->persist($catF35);
        $manager->persist($catF40);
        $manager->persist($catF45);
        $manager->persist($catM42);
        $manager->persist($catM50);

        $manager->flush();

        // other fixtures can get this object using the CategoriaFixtures::CATF35_REFERENCE constant
        $this->addReference(self::CATF35_REFERENCE, $catF35);
        $this->addReference(self::CATF40_REFERENCE, $catF40);
        $this->addReference(self::CATF45_REFERENCE, $catF45);
        $this->addReference(self::CATM42_REFERENCE, $catM42);
        $this->addReference(self::CATM50_REFERENCE, $catM50);
    }

    public function getDependencies(): array
    {
        return [
            TorneoFixtures::class,
        ];
    }
}

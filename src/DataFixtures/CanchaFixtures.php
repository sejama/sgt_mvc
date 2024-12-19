<?php

namespace App\DataFixtures;

use App\Entity\Cancha;
use App\Entity\Sede;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class CanchaFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $sede1 = $this->getReference(SedeFixtures::SEDE1_REFERENCE, Sede::class);
        $sede2 = $this->getReference(SedeFixtures::SEDE2_REFERENCE, Sede::class);

        $cancha1 = new Cancha();
        $cancha1->setSede($sede1);
        $cancha1->setNombre('Cancha 1 - Arriba');
        $cancha1->setDescripcion('Cancha 1 - Arriba');

        $manager->persist($cancha1);

        $cancha2 = new Cancha();
        $cancha2->setSede($sede1);
        $cancha2->setNombre('Cancha 2 - Arriba');
        $cancha2->setDescripcion('Cancha 2 - Arriba');

        $manager->persist($cancha2);

        $cancha3 = new Cancha();
        $cancha3->setSede($sede1);
        $cancha3->setNombre('Cancha 3 - Abajo');
        $cancha3->setDescripcion('Cancha 3 - Abajo');

        $manager->persist($cancha3);

        $cancha4 = new Cancha();
        $cancha4->setSede($sede1);
        $cancha4->setNombre('Cancha 4 - Abajo');
        $cancha4->setDescripcion('Cancha 4 - Abajo');

        $manager->persist($cancha4);

        $cancha5 = new Cancha();
        $cancha5->setSede($sede2);
        $cancha5->setNombre('Cancha 1');
        $cancha5->setDescripcion('Cancha 1');

        $manager->persist($cancha5);

        $cancha6 = new Cancha();
        $cancha6->setSede($sede2);
        $cancha6->setNombre('Cancha 2');
        $cancha6->setDescripcion('Cancha 2');

        $manager->persist($cancha6);


        $manager->flush();
    }
    public function getDependencies()
    {
        return [
            SedeFixtures::class,
        ];
    }
}

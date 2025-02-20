<?php

namespace App\DataFixtures;

use App\Entity\Sede;
use App\Entity\Torneo;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class SedeFixtures extends Fixture implements DependentFixtureInterface
{
    public const SEDE1_REFERENCE = 'sede1';
    public const SEDE2_REFERENCE = 'sede2';

    public function load(ObjectManager $manager): void
    {
        $torneo = $this->getReference(TorneoFixtures::TORNEO1_REFERENCE, Torneo::class);

        $sede1 = new Sede();
        $sede1->setTorneo($torneo);
        $sede1->setNombre('Club Villa Dora');
        $sede1->setDomicilio('Ruperto Godoy 1231');

        $manager->persist($sede1);

        $sede2 = new Sede();
        $sede2->setTorneo($torneo);
        $sede2->setNombre('Club Regatas Santa Fe');
        $sede2->setDomicilio('Av. Leandro N. Alem 3288');

        $manager->persist($sede2);

        $manager->flush();

        // other fixtures can get this object using the SedeFixtures::SEDE1_REFERENCE constant
        $this->addReference(self::SEDE1_REFERENCE, $sede1);
        $this->addReference(self::SEDE2_REFERENCE, $sede2);
    }

    public function getDependencies(): array
    {
        return [
            TorneoFixtures::class,
        ];
    }
}

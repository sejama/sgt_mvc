<?php

namespace App\DataFixtures;

use App\Entity\Torneo;
use App\Entity\Usuario;
use App\Enum\EstadoTorneo;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class TorneoFixtures extends Fixture implements DependentFixtureInterface
{
    public const TORNEO1_REFERENCE = 'torneo';

    public function load(ObjectManager $manager): void
    {
        $torneo = new Torneo();
        // this reference returns the User object created in UserFixtures
        $torneo->setCreador(
            $this->getReference(UsuarioFixtures::ADMIN_USER_REFERENCE, Usuario::class)
        );
        $torneo->setNombre('XIV Torneo Sudamericano de Master Voley Santa Fe');
        $torneo->setDescripcion('XIV Torneo Sudamericano de Master Voley Santa Fe');
        $torneo->setRuta('xiv-sudamericano-master-voley-sf');
        $torneo->setFechaInicioInscripcion(
            new \DateTimeImmutable('2024-12-02 18:00:00', new \DateTimeZone('America/Argentina/Buenos_Aires'))
        );
        $torneo->setFechaFinInscripcion(
            new \DateTimeImmutable('2024-12-02 19:00:00', new \DateTimeZone('America/Argentina/Buenos_Aires'))
        );
        $torneo->setFechaInicioTorneo(
            new \DateTimeImmutable('2024-12-02 20:00:00', new \DateTimeZone('America/Argentina/Buenos_Aires'))
        );
        $torneo->setFechaFinTorneo(
            new \DateTimeImmutable('2024-12-02 21:00:00', new \DateTimeZone('America/Argentina/Buenos_Aires'))
        );
        $torneo->setEstado(EstadoTorneo::BORRADOR);

        $manager->persist($torneo);

        $manager->flush();

        // other fixtures can get this object using the UserFixtures::TORNEO_REFERENCE constant
        $this->addReference(self::TORNEO1_REFERENCE, $torneo);
    }

    public function getDependencies(): array
    {
        return [
            UsuarioFixtures::class,
        ];
    }
}

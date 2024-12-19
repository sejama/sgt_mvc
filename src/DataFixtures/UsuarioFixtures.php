<?php

namespace App\DataFixtures;

use App\Entity\Usuario;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class UsuarioFixtures extends Fixture
{
    public const ADMIN_USER_REFERENCE = 'admin-user';

    public function load(ObjectManager $manager): void
    {
        $usuario = new Usuario();
        $usuario->setNombre('Administrador1');
        $usuario->setApellido('Administrador1');
        $usuario->setUsername('admin1');
        $usuario->setPassword('$2y$13$muUZVnYBqED2n756PW3oeOolJD3OsNapCOLwe/QQOJEwP8fsI/UGK');
        $usuario->setEmail('admin1@correo.com');
        $usuario->setRoles(['ROLE_ADMIN']);

        $manager->persist($usuario);

        $manager->flush();

        // other fixtures can get this object using the UsuarioFixtures::ADMIN_USER_REFERENCE constant
        $this->addReference(self::ADMIN_USER_REFERENCE, $usuario);
    }
}

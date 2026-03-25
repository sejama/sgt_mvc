<?php

declare(strict_types=1);

namespace App\Tests\Integration\Repository;

use App\Entity\Usuario;
use App\Repository\UsuarioRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

class UsuarioRepositoryIntegrationTest extends KernelTestCase
{
    private EntityManagerInterface $entityManager;

    private UsuarioRepository $repository;

    /** @var string[] */
    private array $usernamesToCleanup = [];

    protected function setUp(): void
    {
        self::bootKernel();

        /** @var ManagerRegistry $registry */
        $registry = static::getContainer()->get(ManagerRegistry::class);

        $this->entityManager = $registry->getManager();

        $repository = $registry->getRepository(Usuario::class);
        self::assertInstanceOf(UsuarioRepository::class, $repository);
        $this->repository = $repository;
    }

    protected function tearDown(): void
    {
        foreach (array_unique($this->usernamesToCleanup) as $username) {
            $usuario = $this->repository->findOneBy(['username' => $username]);

            if ($usuario instanceof Usuario) {
                $this->repository->eliminar($usuario, true);
            }
        }

        $this->entityManager->clear();

        parent::tearDown();
    }

    public function testGuardarYEliminarUsuario(): void
    {
        $username = 'it_usuario_repo_' . uniqid('', true);
        $this->usernamesToCleanup[] = $username;

        $usuario = (new Usuario())
            ->setUsername($username)
            ->setEmail($username . '@example.com')
            ->setRoles(['ROLE_USER'])
            ->setPassword('hashed-password');

        $this->repository->guardar($usuario, true);

        $persistido = $this->repository->findOneBy(['username' => $username]);
        self::assertInstanceOf(Usuario::class, $persistido);
        self::assertSame($username, $persistido->getUsername());

        $this->repository->eliminar($persistido, true);

        $eliminado = $this->repository->findOneBy(['username' => $username]);
        self::assertNull($eliminado);
    }

    public function testUpgradePasswordActualizaPassword(): void
    {
        $username = 'it_upgrade_password_' . uniqid('', true);
        $this->usernamesToCleanup[] = $username;

        $usuario = (new Usuario())
            ->setUsername($username)
            ->setEmail($username . '@example.com')
            ->setRoles(['ROLE_USER'])
            ->setPassword('old-password');

        $this->repository->guardar($usuario, true);

        $this->repository->upgradePassword($usuario, 'new-hashed-password');

        $this->entityManager->clear();

        $actualizado = $this->repository->findOneBy(['username' => $username]);
        self::assertInstanceOf(Usuario::class, $actualizado);
        self::assertSame('new-hashed-password', $actualizado->getPassword());
    }

    public function testUpgradePasswordConUsuarioNoSoportadoLanzaExcepcion(): void
    {
        $usuarioNoSoportado = new class() implements PasswordAuthenticatedUserInterface {
            public function getPassword(): ?string
            {
                return 'hash';
            }
        };

        $this->expectException(UnsupportedUserException::class);
        $this->repository->upgradePassword($usuarioNoSoportado, 'new-hash');
    }
}

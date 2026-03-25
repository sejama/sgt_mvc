<?php

declare(strict_types=1);

namespace App\Tests\Integration\Repository;

use App\Entity\Torneo;
use App\Entity\Usuario;
use App\Repository\TorneoRepository;
use App\Repository\UsuarioRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class TorneoRepositoryIntegrationTest extends KernelTestCase
{
    private EntityManagerInterface $entityManager;

    private TorneoRepository $torneoRepository;

    private UsuarioRepository $usuarioRepository;

    /** @var string[] */
    private array $rutasTorneoToCleanup = [];

    /** @var string[] */
    private array $usernamesToCleanup = [];

    protected function setUp(): void
    {
        self::bootKernel();

        /** @var ManagerRegistry $registry */
        $registry = static::getContainer()->get(ManagerRegistry::class);

        $this->entityManager = $registry->getManager();

        $torneoRepository = $registry->getRepository(Torneo::class);
        self::assertInstanceOf(TorneoRepository::class, $torneoRepository);
        $this->torneoRepository = $torneoRepository;

        $usuarioRepository = $registry->getRepository(Usuario::class);
        self::assertInstanceOf(UsuarioRepository::class, $usuarioRepository);
        $this->usuarioRepository = $usuarioRepository;
    }

    protected function tearDown(): void
    {
        foreach (array_unique($this->rutasTorneoToCleanup) as $ruta) {
            $torneo = $this->torneoRepository->findOneBy(['ruta' => $ruta]);
            if ($torneo instanceof Torneo) {
                $this->torneoRepository->eliminar($torneo, true);
            }
        }

        foreach (array_unique($this->usernamesToCleanup) as $username) {
            $usuario = $this->usuarioRepository->findOneBy(['username' => $username]);
            if ($usuario instanceof Usuario) {
                $this->usuarioRepository->eliminar($usuario, true);
            }
        }

        $this->entityManager->clear();

        parent::tearDown();
    }

    public function testGuardarYEliminarTorneo(): void
    {
        $id = substr(md5(uniqid('', true)), 0, 8);
        $ruta = 'it' . $id;

        $this->rutasTorneoToCleanup[] = $ruta;

        $creador = $this->crearUsuario('it_torneo_creator_' . $id);

        $torneo = (new Torneo())
            ->setNombre('Torneo Integracion ' . $id)
            ->setRuta($ruta)
            ->setDescripcion('Torneo de prueba de integracion')
            ->setFechaInicioInscripcion(new \DateTimeImmutable('2026-01-01 10:00:00'))
            ->setFechaFinInscripcion(new \DateTimeImmutable('2026-01-15 10:00:00'))
            ->setFechaInicioTorneo(new \DateTimeImmutable('2026-02-01 10:00:00'))
            ->setFechaFinTorneo(new \DateTimeImmutable('2026-02-15 10:00:00'))
            ->setEstado('activo')
            ->setCreador($creador);

        $this->torneoRepository->guardar($torneo, true);

        $persistido = $this->torneoRepository->findOneBy(['ruta' => $ruta]);
        self::assertInstanceOf(Torneo::class, $persistido);
        self::assertSame($ruta, $persistido->getRuta());
        self::assertSame('activo', $persistido->getEstado());
        self::assertNotNull($persistido->getCreatedAt());
        self::assertNotNull($persistido->getUpdatedAt());
        self::assertSame($creador->getId(), $persistido->getCreador()?->getId());

        $this->torneoRepository->eliminar($persistido, true);

        $eliminado = $this->torneoRepository->findOneBy(['ruta' => $ruta]);
        self::assertNull($eliminado);
    }

    private function crearUsuario(string $username): Usuario
    {
        $this->usernamesToCleanup[] = $username;

        $usuario = (new Usuario())
            ->setUsername($username)
            ->setEmail($username . '@example.com')
            ->setRoles(['ROLE_ADMIN', 'ROLE_USER'])
            ->setPassword('hashed-password');

        $this->usuarioRepository->guardar($usuario, true);

        return $usuario;
    }
}

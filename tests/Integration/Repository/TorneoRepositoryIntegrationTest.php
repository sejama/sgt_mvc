<?php

declare(strict_types=1);

namespace App\Tests\Integration\Repository;

use App\Entity\Torneo;
use App\Entity\Usuario;
use App\Repository\TorneoRepository;
use App\Repository\UsuarioRepository;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class TorneoRepositoryIntegrationTest extends KernelTestCase
{
    private EntityManagerInterface $entityManager;

    private TorneoRepository $torneoRepository;

    private UsuarioRepository $usuarioRepository;

    private Connection $connection;

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

        $this->connection = $this->entityManager->getConnection();
    }

    protected function tearDown(): void
    {
        $this->purgeDatabase($this->connection);

        if ($this->entityManager->isOpen()) {
            $this->entityManager->clear();
        }

        parent::tearDown();
    }

    private function purgeDatabase(Connection $connection): void
    {
        $tables = [
            'partido_config',
            'partido',
            'jugador',
            'equipo',
            'grupo',
            'cancha',
            'sede',
            'categoria',
            'torneo',
            'usuario',
        ];

        $connection->executeStatement('SET FOREIGN_KEY_CHECKS=0');

        try {
            foreach ($tables as $table) {
                $connection->executeStatement(sprintf('TRUNCATE TABLE `%s`', $table));
            }
        } finally {
            $connection->executeStatement('SET FOREIGN_KEY_CHECKS=1');
        }
    }

    public function testGuardarYEliminarTorneo(): void
    {
        $id = substr(md5(uniqid('', true)), 0, 8);
        $ruta = 'it' . $id;

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

    public function testGuardarTorneoConRutaDuplicadaLanzaExcepcionDeUnicidad(): void
    {
        $id = substr(md5(uniqid('', true)), 0, 8);
        $ruta = 'it-ruta-' . $id;

        $creador = $this->crearUsuario('it_torneo_dup_ruta_' . $id);

        $torneoA = (new Torneo())
            ->setNombre('Torneo Dup Ruta A ' . $id)
            ->setRuta($ruta)
            ->setDescripcion('Primer torneo con ruta unica')
            ->setFechaInicioInscripcion(new \DateTimeImmutable('2026-01-01 10:00:00'))
            ->setFechaFinInscripcion(new \DateTimeImmutable('2026-01-15 10:00:00'))
            ->setFechaInicioTorneo(new \DateTimeImmutable('2026-02-01 10:00:00'))
            ->setFechaFinTorneo(new \DateTimeImmutable('2026-02-15 10:00:00'))
            ->setEstado('activo')
            ->setCreador($creador);

        $torneoB = (new Torneo())
            ->setNombre('Torneo Dup Ruta B ' . $id)
            ->setRuta($ruta)
            ->setDescripcion('Segundo torneo con misma ruta')
            ->setFechaInicioInscripcion(new \DateTimeImmutable('2026-03-01 10:00:00'))
            ->setFechaFinInscripcion(new \DateTimeImmutable('2026-03-15 10:00:00'))
            ->setFechaInicioTorneo(new \DateTimeImmutable('2026-04-01 10:00:00'))
            ->setFechaFinTorneo(new \DateTimeImmutable('2026-04-15 10:00:00'))
            ->setEstado('activo')
            ->setCreador($creador);

        $this->torneoRepository->guardar($torneoA, true);

        $this->expectException(UniqueConstraintViolationException::class);
        $this->torneoRepository->guardar($torneoB, true);
    }

    public function testGuardarTorneoConNombreDuplicadoLanzaExcepcionDeUnicidad(): void
    {
        $id = substr(md5(uniqid('', true)), 0, 8);
        $nombre = 'IT Torneo Nombre Duplicado ' . $id;
        $rutaA = 'it-nombre-a-' . $id;
        $rutaB = 'it-nombre-b-' . $id;

        $creador = $this->crearUsuario('it_torneo_dup_nombre_' . $id);

        $torneoA = (new Torneo())
            ->setNombre($nombre)
            ->setRuta($rutaA)
            ->setDescripcion('Primer torneo con nombre unico')
            ->setFechaInicioInscripcion(new \DateTimeImmutable('2026-01-01 10:00:00'))
            ->setFechaFinInscripcion(new \DateTimeImmutable('2026-01-15 10:00:00'))
            ->setFechaInicioTorneo(new \DateTimeImmutable('2026-02-01 10:00:00'))
            ->setFechaFinTorneo(new \DateTimeImmutable('2026-02-15 10:00:00'))
            ->setEstado('activo')
            ->setCreador($creador);

        $torneoB = (new Torneo())
            ->setNombre($nombre)
            ->setRuta($rutaB)
            ->setDescripcion('Segundo torneo con mismo nombre')
            ->setFechaInicioInscripcion(new \DateTimeImmutable('2026-03-01 10:00:00'))
            ->setFechaFinInscripcion(new \DateTimeImmutable('2026-03-15 10:00:00'))
            ->setFechaInicioTorneo(new \DateTimeImmutable('2026-04-01 10:00:00'))
            ->setFechaFinTorneo(new \DateTimeImmutable('2026-04-15 10:00:00'))
            ->setEstado('activo')
            ->setCreador($creador);

        $this->torneoRepository->guardar($torneoA, true);

        $this->expectException(UniqueConstraintViolationException::class);
        $this->torneoRepository->guardar($torneoB, true);
    }

    public function testEliminarUsuarioCreadorConTorneoAsociadoLanzaExcepcionDeFk(): void
    {
        $id = substr(md5(uniqid('', true)), 0, 8);
        $ruta = 'it-fk-' . $id;

        $creador = $this->crearUsuario('it_torneo_fk_user_' . $id);

        $torneo = (new Torneo())
            ->setNombre('Torneo FK ' . $id)
            ->setRuta($ruta)
            ->setDescripcion('Torneo para validar FK con creador')
            ->setFechaInicioInscripcion(new \DateTimeImmutable('2026-01-01 10:00:00'))
            ->setFechaFinInscripcion(new \DateTimeImmutable('2026-01-15 10:00:00'))
            ->setFechaInicioTorneo(new \DateTimeImmutable('2026-02-01 10:00:00'))
            ->setFechaFinTorneo(new \DateTimeImmutable('2026-02-15 10:00:00'))
            ->setEstado('activo')
            ->setCreador($creador);

        $this->torneoRepository->guardar($torneo, true);

        try {
            $this->usuarioRepository->eliminar($creador, true);
            self::fail('Se esperaba excepción de FK al eliminar usuario creador con torneo asociado.');
        } catch (ForeignKeyConstraintViolationException $e) {
            self::assertTrue(true);
        }

        $torneoPersistido = $this->torneoRepository->findOneBy(['ruta' => $ruta]);
        self::assertInstanceOf(Torneo::class, $torneoPersistido);
        self::assertSame($creador->getId(), $torneoPersistido->getCreador()?->getId());
    }

    private function crearUsuario(string $username): Usuario
    {
        $usuario = (new Usuario())
            ->setUsername($username)
            ->setEmail($username . '@example.com')
            ->setRoles(['ROLE_ADMIN', 'ROLE_USER'])
            ->setPassword('hashed-password');

        $this->usuarioRepository->guardar($usuario, true);

        return $usuario;
    }
}

<?php

declare(strict_types=1);

namespace App\Tests\Integration\Repository;

use App\Entity\Sede;
use App\Entity\Torneo;
use App\Entity\Usuario;
use App\Repository\SedeRepository;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class SedeRepositoryIntegrationTest extends KernelTestCase
{
    private EntityManagerInterface $entityManager;

    private SedeRepository $sedeRepository;

    protected function setUp(): void
    {
        self::bootKernel();

        /** @var ManagerRegistry $registry */
        $registry = static::getContainer()->get(ManagerRegistry::class);

        $this->entityManager = $registry->getManager();

        $sedeRepository = $registry->getRepository(Sede::class);
        self::assertInstanceOf(SedeRepository::class, $sedeRepository);
        $this->sedeRepository = $sedeRepository;
    }

    protected function tearDown(): void
    {
        $this->purgeDatabase($this->entityManager->getConnection());
        $this->entityManager->clear();

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

    public function testGuardarYEliminarSede(): void
    {
        $suffix = substr(md5(uniqid('', true)), 0, 8);
        $ruta = $this->buildRuta('it-sede', $suffix);

        $creador = $this->crearUsuario('it_sede_user_' . $suffix);
        $torneo = $this->crearTorneo($creador, $ruta, $suffix);

        $sede = (new Sede())
            ->setNombre('IT Sede ' . strtoupper(substr($suffix, 0, 6)))
            ->setDomicilio('Calle Test 123')
            ->setTorneo($torneo);

        $this->sedeRepository->guardar($sede, true);

        $sedeId = (int) $sede->getId();
        self::assertGreaterThan(0, $sedeId);

        $persistida = $this->entityManager->getRepository(Sede::class)->find($sedeId);
        self::assertInstanceOf(Sede::class, $persistida);
        self::assertSame($torneo->getId(), $persistida->getTorneo()?->getId());

        $this->sedeRepository->eliminar($persistida, true);
        $this->entityManager->clear();

        $eliminada = $this->entityManager->getRepository(Sede::class)->find($sedeId);
        self::assertNull($eliminada);
    }

    private function crearUsuario(string $username): Usuario
    {
        $usuario = (new Usuario())
            ->setUsername($username)
            ->setEmail($username . '@example.com')
            ->setPassword('hashed-password')
            ->setRoles(['ROLE_ADMIN', 'ROLE_USER']);

        $this->entityManager->persist($usuario);
        $this->entityManager->flush();

        return $usuario;
    }

    private function crearTorneo(Usuario $creador, string $ruta, string $suffix): Torneo
    {
        $torneo = (new Torneo())
            ->setNombre('IT Torneo Sede ' . $suffix)
            ->setRuta($ruta)
            ->setDescripcion('Torneo para integration test de SedeRepository')
            ->setFechaInicioInscripcion(new \DateTimeImmutable('2026-01-01 10:00:00'))
            ->setFechaFinInscripcion(new \DateTimeImmutable('2026-01-10 10:00:00'))
            ->setFechaInicioTorneo(new \DateTimeImmutable('2026-02-01 10:00:00'))
            ->setFechaFinTorneo(new \DateTimeImmutable('2026-02-20 10:00:00'))
            ->setEstado('activo')
            ->setCreador($creador);

        $this->entityManager->persist($torneo);
        $this->entityManager->flush();

        return $torneo;
    }

    private function buildRuta(string $prefix, string $suffix): string
    {
        $maxLength = 32;
        $normalizedPrefix = trim($prefix, '-');
        $availablePrefixLength = $maxLength - 1 - strlen($suffix);

        if ($availablePrefixLength < 1) {
            return 't-' . substr($suffix, 0, $maxLength - 2);
        }

        return substr($normalizedPrefix, 0, $availablePrefixLength) . '-' . $suffix;
    }
}

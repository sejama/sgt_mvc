<?php

declare(strict_types=1);

namespace App\Tests\Integration\Repository;

use App\Entity\Cancha;
use App\Entity\Torneo;
use App\Entity\Usuario;
use App\Repository\CanchaRepository;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class CanchaRepositoryIntegrationTest extends KernelTestCase
{
    private EntityManagerInterface $entityManager;

    private CanchaRepository $canchaRepository;

    protected function setUp(): void
    {
        self::bootKernel();

        /** @var ManagerRegistry $registry */
        $registry = static::getContainer()->get(ManagerRegistry::class);

        $this->entityManager = $registry->getManager();

        $canchaRepository = $registry->getRepository(Cancha::class);
        self::assertInstanceOf(CanchaRepository::class, $canchaRepository);
        $this->canchaRepository = $canchaRepository;
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

    public function testGuardarYEliminarCancha(): void
    {
        $suffix = substr(md5(uniqid('', true)), 0, 8);
        $ruta = $this->buildRuta('it-cancha', $suffix);

        $creador = $this->crearUsuario('it_cancha_user_' . $suffix);
        $torneo = $this->crearTorneo($creador, $ruta, $suffix);
        $sede = $this->crearSede($torneo, $suffix);

        $cancha = $this->crearCancha($sede, $suffix);
        $canchaId = (int) $cancha->getId();

        self::assertGreaterThan(0, $canchaId);

        $this->canchaRepository->eliminar($cancha, true);
        $this->entityManager->clear();

        $eliminada = $this->entityManager->getRepository(Cancha::class)->find($canchaId);
        self::assertNull($eliminada);
    }

    public function testBuscarSedesYCanchasByTorneoFiltraPorRuta(): void
    {
        $suffix = substr(md5(uniqid('', true)), 0, 8);
        $ruta = $this->buildRuta('it-cancha-ruta', $suffix);

        $creador = $this->crearUsuario('it_cancha_ruta_user_' . $suffix);
        $torneo = $this->crearTorneo($creador, $ruta, $suffix);
        $sede = $this->crearSede($torneo, $suffix);
        $cancha1 = $this->crearCancha($sede, $suffix . 'a');
        $cancha2 = $this->crearCancha($sede, $suffix . 'b');

        $suffixOtro = substr(md5(uniqid('', true)), 0, 8);
        $rutaOtro = $this->buildRuta('it-cancha-ruta-otro', $suffixOtro);
        $creadorOtro = $this->crearUsuario('it_cancha_ruta_otro_user_' . $suffixOtro);
        $torneoOtro = $this->crearTorneo($creadorOtro, $rutaOtro, $suffixOtro);
        $sedeOtra = $this->crearSede($torneoOtro, $suffixOtro);
        $canchaOtro = $this->crearCancha($sedeOtra, $suffixOtro);

        $resultados = $this->canchaRepository->buscarSedesYCanchasByTorneo($ruta);

        self::assertCount(2, $resultados);

        $ids = array_map(static fn (array $fila): int => (int) $fila['id'], $resultados);
        self::assertContains((int) $cancha1->getId(), $ids);
        self::assertContains((int) $cancha2->getId(), $ids);
        self::assertNotContains((int) $canchaOtro->getId(), $ids);

        foreach ($resultados as $fila) {
            self::assertArrayHasKey('sede', $fila);
            self::assertArrayHasKey('cancha', $fila);
            self::assertSame($sede->getNombre(), $fila['sede']);
        }
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
            ->setNombre('IT Torneo Cancha ' . $suffix)
            ->setRuta($ruta)
            ->setDescripcion('Torneo para integration test de CanchaRepository')
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

    private function crearSede(Torneo $torneo, string $suffix): \App\Entity\Sede
    {
        $sede = (new \App\Entity\Sede())
            ->setNombre('IT Sede Cancha ' . $suffix)
            ->setDomicilio('Calle Test 123')
            ->setTorneo($torneo);

        $this->entityManager->persist($sede);
        $this->entityManager->flush();

        return $sede;
    }

    private function crearCancha(\App\Entity\Sede $sede, string $suffix): Cancha
    {
        $cancha = (new Cancha())
            ->setNombre('IT Cancha ' . strtoupper(substr($suffix, 0, 6)))
            ->setDescripcion('Cancha de test')
            ->setSede($sede);

        $this->canchaRepository->guardar($cancha, true);

        return $cancha;
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

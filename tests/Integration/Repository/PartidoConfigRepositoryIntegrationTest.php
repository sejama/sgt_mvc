<?php

declare(strict_types=1);

namespace App\Tests\Integration\Repository;

use App\Entity\Categoria;
use App\Entity\Partido;
use App\Entity\PartidoConfig;
use App\Entity\Torneo;
use App\Entity\Usuario;
use App\Enum\Genero;
use App\Repository\PartidoConfigRepository;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class PartidoConfigRepositoryIntegrationTest extends KernelTestCase
{
    private EntityManagerInterface $entityManager;

    private PartidoConfigRepository $partidoConfigRepository;

    protected function setUp(): void
    {
        self::bootKernel();

        /** @var ManagerRegistry $registry */
        $registry = static::getContainer()->get(ManagerRegistry::class);

        $this->entityManager = $registry->getManager();

        $partidoConfigRepository = $registry->getRepository(PartidoConfig::class);
        self::assertInstanceOf(PartidoConfigRepository::class, $partidoConfigRepository);
        $this->partidoConfigRepository = $partidoConfigRepository;
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

    public function testObtenerPartidoConfigPorCategoriaYNombreYGuardar(): void
    {
        $suffix = substr(md5(uniqid('', true)), 0, 8);
        $ruta = $this->buildRuta('it-pconfig', $suffix);

        $creador = $this->crearUsuario('it_pconfig_user_' . $suffix);
        $torneo = $this->crearTorneo($creador, $ruta, $suffix);
        $categoria = $this->crearCategoria($torneo, $suffix);

        $partido = (new Partido())
            ->setCategoria($categoria)
            ->setNumero(1001)
            ->setTipo('playoff')
            ->setEstado('Pendiente');

        $this->entityManager->persist($partido);
        $this->entityManager->flush();

        $partidoConfig = (new PartidoConfig())
            ->setPartido($partido)
            ->setNombre('Llave 1');

        $this->partidoConfigRepository->guardar($partidoConfig, true);

        $encontrado = $this->partidoConfigRepository->obtenerPartidoConfig((int) $categoria->getId(), 'Llave 1');

        self::assertInstanceOf(PartidoConfig::class, $encontrado);
        self::assertSame('Llave 1', $encontrado->getNombre());
        self::assertSame((int) $partido->getId(), (int) $encontrado->getPartido()?->getId());
    }

    public function testObtenerPartidoConfigXGanadorPartidoRetornaConfigFinal(): void
    {
        $suffix = substr(md5(uniqid('', true)), 0, 8);
        $ruta = $this->buildRuta('it-pconfig-gan', $suffix);

        $creador = $this->crearUsuario('it_pconfig_gan_user_' . $suffix);
        $torneo = $this->crearTorneo($creador, $ruta, $suffix);
        $categoria = $this->crearCategoria($torneo, $suffix);

        $semi1 = (new Partido())
            ->setCategoria($categoria)
            ->setNumero(1101)
            ->setTipo('playoff')
            ->setEstado('Pendiente');

        $semi2 = (new Partido())
            ->setCategoria($categoria)
            ->setNumero(1102)
            ->setTipo('playoff')
            ->setEstado('Pendiente');

        $final = (new Partido())
            ->setCategoria($categoria)
            ->setNumero(1103)
            ->setTipo('playoff')
            ->setEstado('Pendiente');

        $this->entityManager->persist($semi1);
        $this->entityManager->persist($semi2);
        $this->entityManager->persist($final);
        $this->entityManager->flush();

        $configSemi1 = (new PartidoConfig())
            ->setPartido($semi1)
            ->setNombre('Semi 1');

        $configSemi2 = (new PartidoConfig())
            ->setPartido($semi2)
            ->setNombre('Semi 2');

        $configFinal = (new PartidoConfig())
            ->setPartido($final)
            ->setNombre('Final')
            ->setGanadorPartido1($semi1)
            ->setGanadorPartido2($semi2);

        $this->partidoConfigRepository->guardar($configSemi1, true);
        $this->partidoConfigRepository->guardar($configSemi2, true);
        $this->partidoConfigRepository->guardar($configFinal, true);

        $encontrado = $this->partidoConfigRepository->obtenerPartidoConfigXGanadorPartido($semi1);

        self::assertInstanceOf(PartidoConfig::class, $encontrado);
        self::assertSame('Final', $encontrado->getNombre());
        self::assertSame((int) $final->getId(), (int) $encontrado->getPartido()?->getId());
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
            ->setNombre('IT Torneo PConfig ' . $suffix)
            ->setRuta($ruta)
            ->setDescripcion('Torneo para integration test de PartidoConfigRepository')
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

    private function crearCategoria(Torneo $torneo, string $suffix): Categoria
    {
        $categoria = (new Categoria())
            ->setNombre('IT Categoria PConfig ' . $suffix)
            ->setNombreCorto('PC' . strtoupper(substr($suffix, 0, 4)))
            ->setGenero(Genero::MASCULINO)
            ->setEstado('activo')
            ->setTorneo($torneo);

        $this->entityManager->persist($categoria);
        $this->entityManager->flush();

        return $categoria;
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

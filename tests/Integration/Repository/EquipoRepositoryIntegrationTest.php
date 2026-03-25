<?php

declare(strict_types=1);

namespace App\Tests\Integration\Repository;

use App\Entity\Categoria;
use App\Entity\Equipo;
use App\Entity\Grupo;
use App\Entity\Torneo;
use App\Entity\Usuario;
use App\Enum\Genero;
use App\Repository\EquipoRepository;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class EquipoRepositoryIntegrationTest extends KernelTestCase
{
    private EntityManagerInterface $entityManager;

    private EquipoRepository $equipoRepository;

    protected function setUp(): void
    {
        self::bootKernel();

        /** @var ManagerRegistry $registry */
        $registry = static::getContainer()->get(ManagerRegistry::class);

        $this->entityManager = $registry->getManager();

        $equipoRepository = $registry->getRepository(Equipo::class);
        self::assertInstanceOf(EquipoRepository::class, $equipoRepository);
        $this->equipoRepository = $equipoRepository;
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

    public function testGuardarYEliminarEquipo(): void
    {
        $suffix = substr(md5(uniqid('', true)), 0, 8);
        $ruta = $this->buildRuta('it-equipo', $suffix);

        $creador = $this->crearUsuario('it_equipo_user_' . $suffix);
        $torneo = $this->crearTorneo($creador, $ruta, $suffix);
        $categoria = $this->crearCategoria($torneo, $suffix);
        $grupo = $this->crearGrupo($categoria, $suffix);

        $equipo = $this->crearEquipo($categoria, $grupo, 'Equipo IT', 1, $suffix);
        $equipoId = (int) $equipo->getId();

        self::assertGreaterThan(0, $equipoId);

        $this->equipoRepository->eliminar($equipo, true);
        $this->entityManager->clear();

        $eliminado = $this->entityManager->getRepository(Equipo::class)->find($equipoId);
        self::assertNull($eliminado);
    }

    public function testBuscarEquiposXTorneoFiltraPorRuta(): void
    {
        $suffix = substr(md5(uniqid('', true)), 0, 8);
        $ruta = $this->buildRuta('it-equipos-ruta', $suffix);

        $creador = $this->crearUsuario('it_equipos_user_' . $suffix);
        $torneo = $this->crearTorneo($creador, $ruta, $suffix);
        $categoria = $this->crearCategoria($torneo, $suffix);
        $grupo = $this->crearGrupo($categoria, $suffix);

        $equipo1 = $this->crearEquipo($categoria, $grupo, 'RutaLocal', 10, $suffix);
        $equipo2 = $this->crearEquipo($categoria, $grupo, 'RutaVisitante', 11, $suffix);

        $suffixOtro = substr(md5(uniqid('', true)), 0, 8);
        $rutaOtro = $this->buildRuta('it-equipos-ruta-otro', $suffixOtro);
        $creadorOtro = $this->crearUsuario('it_equipos_otro_user_' . $suffixOtro);
        $torneoOtro = $this->crearTorneo($creadorOtro, $rutaOtro, $suffixOtro);
        $categoriaOtra = $this->crearCategoria($torneoOtro, $suffixOtro);
        $grupoOtro = $this->crearGrupo($categoriaOtra, $suffixOtro);
        $equipoOtro = $this->crearEquipo($categoriaOtra, $grupoOtro, 'RutaOtro', 12, $suffixOtro);

        $resultados = $this->equipoRepository->buscarEquiposXTorneo($ruta);

        self::assertCount(2, $resultados);

        $ids = array_map(static fn (Equipo $equipo): int => (int) $equipo->getId(), $resultados);
        self::assertContains((int) $equipo1->getId(), $ids);
        self::assertContains((int) $equipo2->getId(), $ids);
        self::assertNotContains((int) $equipoOtro->getId(), $ids);
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
            ->setNombre('IT Torneo Equipo ' . $suffix)
            ->setRuta($ruta)
            ->setDescripcion('Torneo para integration test de EquipoRepository')
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
            ->setNombre('IT Categoria Equipo ' . $suffix)
            ->setNombreCorto('EQ' . strtoupper(substr($suffix, 0, 4)))
            ->setGenero(Genero::MASCULINO)
            ->setEstado('activo')
            ->setTorneo($torneo);

        $this->entityManager->persist($categoria);
        $this->entityManager->flush();

        return $categoria;
    }

    private function crearGrupo(Categoria $categoria, string $suffix): Grupo
    {
        $grupo = (new Grupo())
            ->setNombre('Grupo EQ ' . strtoupper(substr($suffix, 0, 4)))
            ->setClasificaOro(2)
            ->setClasificaPlata(0)
            ->setClasificaBronce(0)
            ->setEstado('activo')
            ->setCategoria($categoria);

        $this->entityManager->persist($grupo);
        $this->entityManager->flush();

        return $grupo;
    }

    private function crearEquipo(Categoria $categoria, Grupo $grupo, string $nombreBase, int $numero, string $suffix): Equipo
    {
        $equipo = (new Equipo())
            ->setNombre($nombreBase . ' ' . strtoupper(substr($suffix, 0, 4)))
            ->setNombreCorto(substr(strtoupper($nombreBase), 0, 3) . $numero)
            ->setCategoria($categoria)
            ->setGrupo($grupo)
            ->setEstado('activo')
            ->setNumero($numero);

        $this->equipoRepository->guardar($equipo, true);

        return $equipo;
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

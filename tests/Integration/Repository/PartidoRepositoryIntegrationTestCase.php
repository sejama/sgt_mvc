<?php

declare(strict_types=1);

namespace App\Tests\Integration\Repository;

use App\Entity\Cancha;
use App\Entity\Categoria;
use App\Entity\Equipo;
use App\Entity\Grupo;
use App\Entity\Partido;
use App\Entity\Sede;
use App\Entity\Torneo;
use App\Entity\Usuario;
use App\Enum\Genero;
use App\Repository\PartidoRepository;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

abstract class PartidoRepositoryIntegrationTestCase extends KernelTestCase
{
    protected EntityManagerInterface $entityManager;

    protected PartidoRepository $partidoRepository;

    protected function setUp(): void
    {
        self::bootKernel();

        /** @var ManagerRegistry $registry */
        $registry = static::getContainer()->get(ManagerRegistry::class);

        $this->entityManager = $registry->getManager();

        $partidoRepository = $registry->getRepository(Partido::class);
        self::assertInstanceOf(PartidoRepository::class, $partidoRepository);
        $this->partidoRepository = $partidoRepository;
        $this->entityManager->clear();
    }

    protected function tearDown(): void
    {
        $this->purgeDatabase($this->entityManager->getConnection());
        $this->entityManager->clear();

        parent::tearDown();
    }

    protected function purgeDatabase(Connection $connection): void
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
    protected function crearUsuario(string $username): Usuario
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

    protected function crearTorneo(Usuario $creador, string $ruta, string $suffix): Torneo
    {
        $creadorId = $creador->getId();
        self::assertNotNull($creadorId);

        /** @var Usuario|null $creadorGestionado */
        $creadorGestionado = $this->entityManager->getRepository(Usuario::class)->find($creadorId);
        self::assertInstanceOf(Usuario::class, $creadorGestionado);

        $torneo = (new Torneo())
            ->setNombre('IT Torneo Partido ' . $suffix)
            ->setRuta($ruta)
            ->setDescripcion('Torneo para integration test de PartidoRepository')
            ->setFechaInicioInscripcion(new \DateTimeImmutable('2026-01-01 10:00:00'))
            ->setFechaFinInscripcion(new \DateTimeImmutable('2026-01-10 10:00:00'))
            ->setFechaInicioTorneo(new \DateTimeImmutable('2026-02-01 10:00:00'))
            ->setFechaFinTorneo(new \DateTimeImmutable('2026-02-20 10:00:00'))
            ->setEstado('activo')
            ->setCreador($creadorGestionado);

        $this->entityManager->persist($torneo);
        $this->entityManager->flush();

        return $torneo;
    }

    protected function buildRuta(string $prefix, string $suffix): string
    {
        $maxLength = 32;
        $normalizedPrefix = trim($prefix, '-');
        $availablePrefixLength = $maxLength - 1 - strlen($suffix);

        if ($availablePrefixLength < 1) {
            return 't-' . substr($suffix, 0, $maxLength - 2);
        }

        return substr($normalizedPrefix, 0, $availablePrefixLength) . '-' . $suffix;
    }

    protected function crearCategoria(Torneo $torneo, string $suffix): Categoria
    {
        $torneoId = $torneo->getId();
        self::assertNotNull($torneoId);

        /** @var Torneo|null $torneoGestionado */
        $torneoGestionado = $this->entityManager->getRepository(Torneo::class)->find($torneoId);
        self::assertInstanceOf(Torneo::class, $torneoGestionado);

        $categoria = (new Categoria())
            ->setNombre('IT Categoria ' . $suffix)
            ->setNombreCorto('IT' . strtoupper(substr($suffix, 0, 4)))
            ->setGenero(Genero::MASCULINO)
            ->setEstado('activo')
            ->setTorneo($torneoGestionado);

        $this->entityManager->persist($categoria);
        $this->entityManager->flush();

        return $categoria;
    }

    protected function crearSede(Torneo $torneo, string $suffix): Sede
    {
        $torneoId = $torneo->getId();
        self::assertNotNull($torneoId);

        /** @var Torneo|null $torneoGestionado */
        $torneoGestionado = $this->entityManager->getRepository(Torneo::class)->find($torneoId);
        self::assertInstanceOf(Torneo::class, $torneoGestionado);

        $sede = (new Sede())
            ->setNombre('IT Sede ' . $suffix)
            ->setDomicilio('Calle Test 123')
            ->setTorneo($torneoGestionado);

        $this->entityManager->persist($sede);
        $this->entityManager->flush();

        return $sede;
    }

    protected function crearCancha(Sede $sede, string $suffix): Cancha
    {
        $sedeId = $sede->getId();
        self::assertNotNull($sedeId);

        /** @var Sede|null $sedeGestionada */
        $sedeGestionada = $this->entityManager->getRepository(Sede::class)->find($sedeId);
        self::assertInstanceOf(Sede::class, $sedeGestionada);

        $cancha = (new Cancha())
            ->setNombre('IT Cancha ' . $suffix)
            ->setDescripcion('Cancha de test')
            ->setSede($sedeGestionada);

        $this->entityManager->persist($cancha);
        $this->entityManager->flush();

        return $cancha;
    }

    protected function crearGrupo(Categoria $categoria, string $suffix): Grupo
    {
        $categoriaId = $categoria->getId();
        self::assertNotNull($categoriaId);

        /** @var Categoria|null $categoriaGestionada */
        $categoriaGestionada = $this->entityManager->getRepository(Categoria::class)->find($categoriaId);
        self::assertInstanceOf(Categoria::class, $categoriaGestionada);

        $grupo = (new Grupo())
            ->setNombre('Grupo ' . strtoupper(substr($suffix, 0, 4)))
            ->setClasificaOro(2)
            ->setClasificaPlata(0)
            ->setClasificaBronce(0)
            ->setEstado('activo')
            ->setCategoria($categoriaGestionada);

        $this->entityManager->persist($grupo);
        $this->entityManager->flush();

        return $grupo;
    }

    protected function crearEquipo(Categoria $categoria, Grupo $grupo, string $nombreBase, int $numero, string $suffix): Equipo
    {
        $categoriaId = $categoria->getId();
        $grupoId = $grupo->getId();
        self::assertNotNull($categoriaId);
        self::assertNotNull($grupoId);

        /** @var Categoria|null $categoriaGestionada */
        $categoriaGestionada = $this->entityManager->getRepository(Categoria::class)->find($categoriaId);
        /** @var Grupo|null $grupoGestionado */
        $grupoGestionado = $this->entityManager->getRepository(Grupo::class)->find($grupoId);
        self::assertInstanceOf(Categoria::class, $categoriaGestionada);
        self::assertInstanceOf(Grupo::class, $grupoGestionado);

        $equipo = (new Equipo())
            ->setNombre($nombreBase . ' ' . strtoupper(substr($suffix, 0, 4)))
            ->setNombreCorto(substr(strtoupper($nombreBase), 0, 3) . $numero)
            ->setCategoria($categoriaGestionada)
            ->setGrupo($grupoGestionado)
            ->setEstado('activo')
            ->setNumero($numero);

        $this->entityManager->persist($equipo);
        $this->entityManager->flush();

        return $equipo;
    }

    protected function crearPartidoConfigPlayoff(Partido $partido, Grupo $grupo1, Grupo $grupo2, string $nombre): void
    {
        $connection = $this->entityManager->getConnection();
        $columns = $connection->createSchemaManager()->listTableColumns('partido_config');

        $resolveColumn = static function (array $columnCandidates) use ($columns): ?string {
            foreach ($columnCandidates as $candidate) {
                if (isset($columns[$candidate])) {
                    return $candidate;
                }
            }

            return null;
        };

        $columnPartidoId = $resolveColumn(['partido_id']);
        $columnNombre = $resolveColumn(['nombre']);
        $columnGrupo1 = $resolveColumn(['grupo_equipo1_id']);
        $columnPos1 = $resolveColumn(['posicion_equipo1']);
        $columnGrupo2 = $resolveColumn(['grupo_equipo2_id']);
        $columnPos2 = $resolveColumn(['posicion_equipo2']);

        if (
            $columnPartidoId === null
            || $columnNombre === null
            || $columnGrupo1 === null
            || $columnPos1 === null
            || $columnGrupo2 === null
            || $columnPos2 === null
        ) {
            self::markTestSkipped('La tabla partido_config no tiene columnas mínimas esperadas para escenario PlayOff.');
        }

        $data = [
            $columnPartidoId => (int) $partido->getId(),
            $columnNombre => $nombre,
            $columnGrupo1 => (int) $grupo1->getId(),
            $columnPos1 => 1,
            $columnGrupo2 => (int) $grupo2->getId(),
            $columnPos2 => 2,
        ];

        $columnCreatedAt = $resolveColumn(['created_at']);
        $columnUpdatedAt = $resolveColumn(['updated_at']);
        $now = (new \DateTimeImmutable('now'))->format('Y-m-d H:i:s');

        if ($columnCreatedAt !== null) {
            $data[$columnCreatedAt] = $now;
        }

        if ($columnUpdatedAt !== null) {
            $data[$columnUpdatedAt] = $now;
        }

        $connection->insert('partido_config', $data);
    }

    protected function crearPartidoConfigNombreMinimo(Partido $partido, string $nombre): void
    {
        $connection = $this->entityManager->getConnection();
        $columns = $connection->createSchemaManager()->listTableColumns('partido_config');

        if (!isset($columns['partido_id']) || !isset($columns['nombre'])) {
            self::markTestSkipped('La tabla partido_config no tiene columnas mínimas (partido_id, nombre).');
        }

        $data = [
            'partido_id' => (int) $partido->getId(),
            'nombre' => $nombre,
        ];

        $now = (new \DateTimeImmutable('now'))->format('Y-m-d H:i:s');
        if (isset($columns['created_at'])) {
            $data['created_at'] = $now;
        }
        if (isset($columns['updated_at'])) {
            $data['updated_at'] = $now;
        }

        $connection->insert('partido_config', $data);
    }

    protected function crearPartidoConfigFinalConGanadores(Partido $partidoFinal, Partido $partido1, Partido $partido2, string $nombre): void
    {
        $connection = $this->entityManager->getConnection();
        $columns = $connection->createSchemaManager()->listTableColumns('partido_config');

        if (
            !isset($columns['partido_id'])
            || !isset($columns['nombre'])
            || !isset($columns['ganador_partido1_id'])
            || !isset($columns['ganador_partido2_id'])
        ) {
            self::markTestSkipped('La tabla partido_config no tiene columnas mínimas para finales con ganadores.');
        }

        $data = [
            'partido_id' => (int) $partidoFinal->getId(),
            'nombre' => $nombre,
            'ganador_partido1_id' => (int) $partido1->getId(),
            'ganador_partido2_id' => (int) $partido2->getId(),
        ];

        $now = (new \DateTimeImmutable('now'))->format('Y-m-d H:i:s');
        if (isset($columns['created_at'])) {
            $data['created_at'] = $now;
        }
        if (isset($columns['updated_at'])) {
            $data['updated_at'] = $now;
        }

        $connection->insert('partido_config', $data);
    }

    /**
     * @param string[] $requiredColumns
     */
    protected function hasPartidoConfigColumns(array $requiredColumns): bool
    {
        $columns = $this->entityManager->getConnection()->createSchemaManager()->listTableColumns('partido_config');

        foreach ($requiredColumns as $requiredColumn) {
            if (!isset($columns[$requiredColumn])) {
                return false;
            }
        }

        return true;
    }
}

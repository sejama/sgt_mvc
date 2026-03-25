<?php

declare(strict_types=1);

namespace App\Tests\Integration\Repository;

use App\Entity\Categoria;
use App\Entity\Equipo;
use App\Entity\Grupo;
use App\Entity\Jugador;
use App\Entity\Torneo;
use App\Entity\Usuario;
use App\Enum\Genero;
use App\Repository\JugadorRepository;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class JugadorRepositoryIntegrationTest extends KernelTestCase
{
    private EntityManagerInterface $entityManager;

    private JugadorRepository $jugadorRepository;

    protected function setUp(): void
    {
        self::bootKernel();

        /** @var ManagerRegistry $registry */
        $registry = static::getContainer()->get(ManagerRegistry::class);

        $this->entityManager = $registry->getManager();

        $jugadorRepository = $registry->getRepository(Jugador::class);
        self::assertInstanceOf(JugadorRepository::class, $jugadorRepository);
        $this->jugadorRepository = $jugadorRepository;
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

    public function testGuardarYEliminarJugador(): void
    {
        $suffix = substr(md5(uniqid('', true)), 0, 8);
        $ruta = $this->buildRuta('it-jugador', $suffix);

        $creador = $this->crearUsuario('it_jugador_user_' . $suffix);
        $torneo = $this->crearTorneo($creador, $ruta, $suffix);
        $categoria = $this->crearCategoria($torneo, $suffix);
        $grupo = $this->crearGrupo($categoria, $suffix);
        $equipo = $this->crearEquipo($categoria, $grupo, $suffix);

        $jugador = (new Jugador())
            ->setNombre('Nombre ' . $suffix)
            ->setApellido('Apellido ' . $suffix)
            ->setTipoDocumento('DNI')
            ->setNumeroDocumento('40' . substr($suffix, 0, 6))
            ->setNacimiento(new \DateTimeImmutable('2000-01-01'))
            ->setEquipo($equipo)
            ->setResponsable(true)
            ->setEmail('jugador.' . $suffix . '@example.com')
            ->setCelular('3415000000')
            ->setTipo('jugador');

        $this->jugadorRepository->guardar($jugador, true);

        $jugadorId = (int) $jugador->getId();
        self::assertGreaterThan(0, $jugadorId);

        $persistido = $this->entityManager->getRepository(Jugador::class)->find($jugadorId);
        self::assertInstanceOf(Jugador::class, $persistido);
        self::assertSame($equipo->getId(), $persistido->getEquipo()?->getId());
        self::assertTrue((bool) $persistido->isResponsable());

        $this->jugadorRepository->eliminar($persistido, true);
        $this->entityManager->clear();

        $eliminado = $this->entityManager->getRepository(Jugador::class)->find($jugadorId);
        self::assertNull($eliminado);
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
            ->setNombre('IT Torneo Jugador ' . $suffix)
            ->setRuta($ruta)
            ->setDescripcion('Torneo para integration test de JugadorRepository')
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
            ->setNombre('IT Categoria Jugador ' . $suffix)
            ->setNombreCorto('JG' . strtoupper(substr($suffix, 0, 4)))
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
            ->setNombre('Grupo JG ' . strtoupper(substr($suffix, 0, 4)))
            ->setClasificaOro(2)
            ->setClasificaPlata(0)
            ->setClasificaBronce(0)
            ->setEstado('activo')
            ->setCategoria($categoria);

        $this->entityManager->persist($grupo);
        $this->entityManager->flush();

        return $grupo;
    }

    private function crearEquipo(Categoria $categoria, Grupo $grupo, string $suffix): Equipo
    {
        $equipo = (new Equipo())
            ->setNombre('Equipo Jugador ' . strtoupper(substr($suffix, 0, 4)))
            ->setNombreCorto('EJ' . substr(strtoupper($suffix), 0, 2))
            ->setCategoria($categoria)
            ->setGrupo($grupo)
            ->setEstado('activo')
            ->setNumero(1);

        $this->entityManager->persist($equipo);
        $this->entityManager->flush();

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

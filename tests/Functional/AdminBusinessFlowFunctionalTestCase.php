<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use App\Entity\Cancha;
use App\Entity\Categoria;
use App\Entity\Equipo;
use App\Entity\Grupo;
use App\Entity\Jugador;
use App\Entity\Partido;
use App\Entity\Sede;
use App\Entity\Torneo;
use App\Entity\Usuario;
use App\Enum\EstadoPartido;
use App\Enum\Genero;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
abstract class AdminBusinessFlowFunctionalTestCase extends WebTestCase
{
    protected KernelBrowser $client;

    protected EntityManagerInterface $entityManager;

    protected function setUp(): void
    {
        $this->client = static::createClient();

        /** @var EntityManagerInterface $entityManager */
        $entityManager = static::getContainer()->get('doctrine.orm.entity_manager');
        $this->entityManager = $entityManager;
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
    protected function crearUsuario(string $username, array $roles): Usuario
    {
        $usuario = (new Usuario())
            ->setUsername($username)
            ->setEmail($username . '@example.com')
            ->setPassword('test-password-hash')
            ->setRoles($roles);

        $this->entityManager->persist($usuario);
        $this->entityManager->flush();

        $usuarioId = $usuario->getId();
        self::assertNotNull($usuarioId);

        /** @var Usuario|null $usuarioGestionado */
        $usuarioGestionado = $this->entityManager->getRepository(Usuario::class)->find($usuarioId);
        self::assertInstanceOf(Usuario::class, $usuarioGestionado);

        return $usuarioGestionado;
    }

    protected function crearTorneo(Usuario $creador, string $ruta, string $suffix): Torneo
    {
        $creadorId = $creador->getId();
        self::assertNotNull($creadorId);

        /** @var Usuario|null $creadorGestionado */
        $creadorGestionado = $this->entityManager->getRepository(Usuario::class)->find($creadorId);
        self::assertInstanceOf(Usuario::class, $creadorGestionado);

        $torneo = (new Torneo())
            ->setNombre('FT Torneo ' . $suffix)
            ->setRuta($ruta)
            ->setDescripcion('Torneo funcional para rutas admin')
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

    protected function crearCategoria(Torneo $torneo, string $suffix): Categoria
    {
        $torneoId = $torneo->getId();
        self::assertNotNull($torneoId);

        /** @var Torneo|null $torneoGestionado */
        $torneoGestionado = $this->entityManager->getRepository(Torneo::class)->find($torneoId);
        self::assertInstanceOf(Torneo::class, $torneoGestionado);

        $categoria = (new Categoria())
            ->setNombre('FT Cat Base ' . $suffix)
            ->setNombreCorto('FB' . strtoupper(substr($suffix, 0, 4)))
            ->setGenero(Genero::MASCULINO)
            ->setEstado('borrador')
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
            ->setNombre('FT Sede Base ' . $suffix)
            ->setDomicilio('Calle Base 123')
            ->setTorneo($torneoGestionado);

        $this->entityManager->persist($sede);
        $this->entityManager->flush();

        return $sede;
    }

    protected function crearCancha(Torneo $torneo, string $suffix): Cancha
    {
        $sede = $this->crearSede($torneo, $suffix . 'c');
        $sedeId = $sede->getId();
        self::assertNotNull($sedeId);

        /** @var Sede|null $sedeGestionada */
        $sedeGestionada = $this->entityManager->getRepository(Sede::class)->find($sedeId);
        self::assertInstanceOf(Sede::class, $sedeGestionada);

        $cancha = (new Cancha())
            ->setNombre('FT Cancha ' . $suffix)
            ->setDescripcion('Cancha funcional')
            ->setSede($sedeGestionada);

        $this->entityManager->persist($cancha);
        $this->entityManager->flush();

        return $cancha;
    }

    protected function crearEquipo(Categoria $categoria, string $suffix): Equipo
    {
        $categoriaId = $categoria->getId();
        self::assertNotNull($categoriaId);

        /** @var Categoria|null $categoriaGestionada */
        $categoriaGestionada = $this->entityManager->getRepository(Categoria::class)->find($categoriaId);
        self::assertInstanceOf(Categoria::class, $categoriaGestionada);

        $equipo = (new Equipo())
            ->setNombre('FT Equipo Base ' . $suffix)
            ->setNombreCorto('EB' . strtoupper(substr($suffix, 0, 4)))
            ->setPais('Argentina')
            ->setProvincia('Mendoza')
            ->setLocalidad('Capital')
            ->setEstado('borrador')
            ->setNumero(1)
            ->setCategoria($categoriaGestionada);

        $this->entityManager->persist($equipo);
        $this->entityManager->flush();

        return $equipo;
    }

    protected function crearJugador(Equipo $equipo, string $suffix): Jugador
    {
        $equipoId = $equipo->getId();
        self::assertNotNull($equipoId);

        /** @var Equipo|null $equipoGestionado */
        $equipoGestionado = $this->entityManager->getRepository(Equipo::class)->find($equipoId);
        self::assertInstanceOf(Equipo::class, $equipoGestionado);

        $jugador = (new Jugador())
            ->setEquipo($equipoGestionado)
            ->setNombre('FT Jugador Base ' . $suffix)
            ->setApellido('Test')
            ->setTipoDocumento('DNI')
            ->setNumeroDocumento('77' . substr($suffix, 0, 6))
            ->setNacimiento(new \DateTimeImmutable('2000-01-01'))
            ->setTipo('Jugador')
            ->setResponsable(false)
            ->setEmail('jugador-base+' . $suffix . '@example.com')
            ->setCelular('2614440000');

        $this->entityManager->persist($jugador);
        $this->entityManager->flush();

        return $jugador;
    }

    protected function crearGrupo(Categoria $categoria, string $suffix): Grupo
    {
        $categoriaId = $categoria->getId();
        self::assertNotNull($categoriaId);

        /** @var Categoria|null $categoriaGestionada */
        $categoriaGestionada = $this->entityManager->getRepository(Categoria::class)->find($categoriaId);
        self::assertInstanceOf(Categoria::class, $categoriaGestionada);

        $grupo = (new Grupo())
            ->setNombre('Grupo ' . strtoupper(substr($suffix, 0, 3)))
            ->setClasificaOro(1)
            ->setClasificaPlata(null)
            ->setClasificaBronce(null)
            ->setEstado('borrador')
            ->setCategoria($categoriaGestionada);

        $this->entityManager->persist($grupo);
        $this->entityManager->flush();

        return $grupo;
    }

    protected function crearPartido(
        Categoria $categoria,
        int $numero,
        ?Cancha $cancha = null,
        ?\DateTimeImmutable $horario = null
    ): Partido {
        $categoriaId = $categoria->getId();
        self::assertNotNull($categoriaId);

        /** @var Categoria|null $categoriaGestionada */
        $categoriaGestionada = $this->entityManager->getRepository(Categoria::class)->find($categoriaId);
        self::assertInstanceOf(Categoria::class, $categoriaGestionada);

        $canchaGestionada = null;
        if ($cancha !== null) {
            $canchaId = $cancha->getId();
            self::assertNotNull($canchaId);

            /** @var Cancha|null $canchaGestionada */
            $canchaGestionada = $this->entityManager->getRepository(Cancha::class)->find($canchaId);
            self::assertInstanceOf(Cancha::class, $canchaGestionada);
        }

        $partido = (new Partido())
            ->setCategoria($categoriaGestionada)
            ->setGrupo(null)
            ->setCancha($canchaGestionada)
            ->setHorario($horario)
            ->setEquipoLocal(null)
            ->setEquipoVisitante(null)
            ->setEstado($horario === null ? 'Borrador' : 'Programado')
            ->setTipo('Clasificatorio')
            ->setNumero($numero);

        $this->entityManager->persist($partido);
        $this->entityManager->flush();

        return $partido;
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
}

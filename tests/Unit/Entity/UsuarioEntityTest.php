<?php

declare(strict_types=1);

namespace App\Tests\Unit\Entity;

use App\Entity\Torneo;
use App\Entity\Usuario;
use PHPUnit\Framework\TestCase;

class UsuarioEntityTest extends TestCase
{
    public function testUsuarioGettersSettersYRelaciones(): void
    {
        $usuario = new Usuario();
        $torneo = new Torneo();

        $usuario
            ->setUsername('usuario_test')
            ->setPassword('hash')
            ->setRoles(['ROLE_ADMIN', 'ROLE_ADMIN', 'ROLE_USER'])
            ->setEmail('usuario@example.com')
            ->setNombre('Nombre')
            ->setApellido('Apellido');

        self::assertSame('usuario_test', $usuario->getUsername());
        self::assertSame('usuario_test', $usuario->getUserIdentifier());
        self::assertSame('hash', $usuario->getPassword());
        self::assertSame(['ROLE_ADMIN', 'ROLE_USER'], array_values($usuario->getRoles()));
        self::assertSame('usuario@example.com', $usuario->getEmail());
        self::assertSame('Nombre', $usuario->getNombre());
        self::assertSame('Apellido', $usuario->getApellido());

        $usuario->addTorneosCreado($torneo);
        self::assertCount(1, $usuario->getTorneosCreados());
        self::assertSame($usuario, $torneo->getCreador());

        $usuario->removeTorneosCreado($torneo);
        self::assertCount(0, $usuario->getTorneosCreados());
        self::assertNull($torneo->getCreador());

        $usuario->addTorneosColaborador($torneo);
        self::assertCount(1, $usuario->getTorneosColaborador());
        self::assertCount(1, $torneo->getColaborador());

        $usuario->removeTorneosColaborador($torneo);
        self::assertCount(0, $usuario->getTorneosColaborador());
        self::assertCount(0, $torneo->getColaborador());

        $usuario->setCreatedAt();
        $usuario->setUpdatedAt();
        self::assertInstanceOf(\DateTimeImmutable::class, $usuario->getCreatedAt());
        self::assertInstanceOf(\DateTimeImmutable::class, $usuario->getUpdatedAt());

        $usuario
            ->setEmail(null)
            ->setNombre(null)
            ->setApellido(null);

        self::assertNull($usuario->getEmail());
        self::assertNull($usuario->getNombre());
        self::assertNull($usuario->getApellido());

        $usuario->eraseCredentials();
        self::assertTrue(true);
    }
}

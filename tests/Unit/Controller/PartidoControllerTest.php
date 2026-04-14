<?php

declare(strict_types=1);

namespace App\Tests\Unit\Controller;

use App\Controller\PartidoController;
use App\Entity\Usuario;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\User\UserInterface;

class PartidoControllerTest extends TestCase
{
    public function testGetLogUserIdRetornaAnonCuandoNoHayUsuario(): void
    {
        $controller = new class () extends PartidoController {
            public function getUser(): ?UserInterface
            {
                return null;
            }
        };

        $method = new \ReflectionMethod(PartidoController::class, 'getLogUserId');
        $method->setAccessible(true);

        $result = $method->invoke($controller);

        self::assertSame('anon', $result);
    }

    public function testGetLogUserIdRetornaIdCuandoHayUsuario(): void
    {
        $usuario = $this->createMock(Usuario::class);
        $usuario->method('getId')->willReturn(42);

        $controller = new class ($usuario) extends PartidoController {
            public function __construct(private Usuario $usuario)
            {
            }

            public function getUser(): ?UserInterface
            {
                return $this->usuario;
            }
        };

        $method = new \ReflectionMethod(PartidoController::class, 'getLogUserId');
        $method->setAccessible(true);

        $result = $method->invoke($controller);

        self::assertSame('42', $result);
    }
}

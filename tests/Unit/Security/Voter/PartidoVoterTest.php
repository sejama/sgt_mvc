<?php

declare(strict_types=1);

namespace App\Tests\Unit\Security\Voter;

use App\Entity\Partido;
use App\Enum\EstadoPartido;
use App\Security\Voter\PartidoVoter;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class PartidoVoterTest extends TestCase
{
    private PartidoVoter $voter;

    protected function setUp(): void
    {
        $this->voter = new PartidoVoter();
    }

    public function testSupportsCargarResultadoEnPartido(): void
    {
        $partido = new Partido();
        
        $result = $this->invokePrivateMethod($this->voter, 'supports', [
            PartidoVoter::CARGAR_RESULTADO,
            $partido
        ]);
        
        self::assertTrue($result);
    }

    public function testNoSupportsUnatributeDistinto(): void
    {
        $partido = new Partido();
        
        $result = $this->invokePrivateMethod($this->voter, 'supports', [
            'OTRO_ATRIBUTO',
            $partido
        ]);
        
        self::assertFalse($result);
    }

    public function testNoSupportsEntityDistinta(): void
    {
        $result = $this->invokePrivateMethod($this->voter, 'supports', [
            PartidoVoter::CARGAR_RESULTADO,
            new \stdClass()
        ]);
        
        self::assertFalse($result);
    }

    public function testUsuarioNoAutenticadoRecibeFalse(): void
    {
        $user = null; // No authenticated user
        
        $token = $this->createMock(TokenInterface::class);
        $token->method('getUser')->willReturn($user);
        
        $partido = new Partido();
        
        $result = $this->invokePrivateMethod($this->voter, 'voteOnAttribute', [
            PartidoVoter::CARGAR_RESULTADO,
            $partido,
            $token
        ]);
        
        self::assertFalse($result);
    }

    public function testRoleAdminTieneAccesoSiempreAPartido(): void
    {
        $user = $this->createMock(UserInterface::class);
        $user->method('getRoles')->willReturn(['ROLE_ADMIN', 'ROLE_USER']);
        
        $token = $this->createMock(TokenInterface::class);
        $token->method('getUser')->willReturn($user);
        
        $partido = (new Partido())->setEstado(EstadoPartido::FINALIZADO->value);
        
        $result = $this->invokePrivateMethod($this->voter, 'voteOnAttribute', [
            PartidoVoter::CARGAR_RESULTADO,
            $partido,
            $token
        ]);
        
        self::assertTrue($result);
    }

    public function testRolePlanilleroTieneAccesoAPartidoNoPendiente(): void
    {
        $user = $this->createMock(UserInterface::class);
        $user->method('getRoles')->willReturn(['ROLE_PLANILLERO']);
        
        $token = $this->createMock(TokenInterface::class);
        $token->method('getUser')->willReturn($user);
        
        $partido = (new Partido())->setEstado(EstadoPartido::PROGRAMADO->value);
        
        $result = $this->invokePrivateMethod($this->voter, 'voteOnAttribute', [
            PartidoVoter::CARGAR_RESULTADO,
            $partido,
            $token
        ]);
        
        self::assertTrue($result);
    }

    public function testRolePlanilleroNoTieneAccesoAPartidoFinalizado(): void
    {
        $user = $this->createMock(UserInterface::class);
        $user->method('getRoles')->willReturn(['ROLE_PLANILLERO']);
        
        $token = $this->createMock(TokenInterface::class);
        $token->method('getUser')->willReturn($user);
        
        $partido = (new Partido())->setEstado(EstadoPartido::FINALIZADO->value);
        
        $result = $this->invokePrivateMethod($this->voter, 'voteOnAttribute', [
            PartidoVoter::CARGAR_RESULTADO,
            $partido,
            $token
        ]);
        
        self::assertFalse($result);
    }

    public function testOtroRoleRecibeFalse(): void
    {
        $user = $this->createMock(UserInterface::class);
        $user->method('getRoles')->willReturn(['ROLE_USER']);
        
        $token = $this->createMock(TokenInterface::class);
        $token->method('getUser')->willReturn($user);
        
        $partido = (new Partido())->setEstado('Pendiente');
        
        $result = $this->invokePrivateMethod($this->voter, 'voteOnAttribute', [
            PartidoVoter::CARGAR_RESULTADO,
            $partido,
            $token
        ]);
        
        self::assertFalse($result);
    }

    private function invokePrivateMethod(object $object, string $methodName, array $parameters = []): mixed
    {
        $reflection = new \ReflectionClass($object);
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);
        return $method->invokeArgs($object, $parameters);
    }
}

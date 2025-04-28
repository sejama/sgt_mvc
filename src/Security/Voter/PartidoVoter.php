<?php

namespace App\Security\Voter;

use App\Entity\Partido;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class PartidoVoter extends Voter
{
    public const CARGAR_RESULTADO = 'CARGAR_RESULTADO';

    protected function supports(string $attribute, $subject): bool
    {
        // Este voter solo se aplica al atributo CARGAR_RESULTADO y a la entidad Partido
        return $attribute === self::CARGAR_RESULTADO && $subject instanceof Partido;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof UserInterface) {
            return false; // El usuario no está autenticado
        }

        /** @var Partido $partido */
        $partido = $subject;

        // Si el usuario tiene el rol ROLE_ADMIN, siempre tiene acceso
        if (in_array('ROLE_ADMIN', $user->getRoles(), true)) {
            return true;
        }

        // Si el usuario tiene el rol ROLE_PLANILLERO, solo puede acceder si el partido no está finalizado
        if (in_array('ROLE_PLANILLERO', $user->getRoles(), true)) {
            return $partido->getEstado() !== \App\Enum\EstadoPartido::FINALIZADO->value;
        }

        return false; // Otros roles no tienen acceso
    }
}
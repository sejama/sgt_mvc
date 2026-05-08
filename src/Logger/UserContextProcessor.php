<?php

declare(strict_types=1);

namespace App\Logger;

use Monolog\Attribute\AsMonologProcessor;
use Monolog\LogRecord;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

#[AsMonologProcessor(channel: 'sgt')]
final class UserContextProcessor
{
    public function __construct(
        private TokenStorageInterface $tokenStorage
    ) {}

    public function __invoke(LogRecord $record): LogRecord
    {
        $token = $this->tokenStorage->getToken();
        $user = $token?->getUser();

        if ($user === null) {
            return $record;
        }

        return $record->with(extra: [
            ...$record->extra,
            'user' => $user->getUserIdentifier(),
        ]);
    }
}

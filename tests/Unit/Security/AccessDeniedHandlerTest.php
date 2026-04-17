<?php

declare(strict_types=1);

namespace App\Tests\Unit\Security;

use App\Security\AccessDeniedHandler;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class AccessDeniedHandlerTest extends TestCase
{
    public function testHandleRedirectsToForbiddenErrorRoute(): void
    {
        $urlGenerator = $this->createMock(UrlGeneratorInterface::class);
        $urlGenerator->expects($this->once())
            ->method('generate')
            ->with('app_error_forbidden')
            ->willReturn('/error/forbidden');

        $handler = new AccessDeniedHandler($urlGenerator);
        $request = Request::create('/admin/torneo/');
        $exception = new AccessDeniedException('Access denied');

        $response = $handler->handle($request, $exception);

        self::assertInstanceOf(RedirectResponse::class, $response);
        self::assertSame('/error/forbidden', $response->getTargetUrl());
        self::assertSame(302, $response->getStatusCode());
    }
}

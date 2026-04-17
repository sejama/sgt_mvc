<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ErrorPagesFunctionalTest extends WebTestCase
{
    public function testTorneoInexistenteMuestraPaginaNoEncontrada(): void
    {
        $client = static::createClient();

        $client->request('GET', '/torneo/torneo-inexistente-xyz');

        self::assertResponseStatusCodeSame(404);
        self::assertStringContainsString('Pagina no encontrada', (string) $client->getResponse()->getContent());
    }

    public function testRutaInexistenteMuestraPaginaNoEncontrada(): void
    {
        $client = static::createClient();

        $client->request('GET', '/admin/usuario/1');

        self::assertResponseStatusCodeSame(404);
        self::assertStringContainsString('Pagina no encontrada', (string) $client->getResponse()->getContent());
    }

    public function testError404MuestraPaginaNoEncontrada(): void
    {
        $client = static::createClient();

        $client->request('GET', '/error?status=404');

        self::assertResponseStatusCodeSame(404);
        self::assertStringContainsString('Pagina no encontrada', (string) $client->getResponse()->getContent());
    }

    public function testError401MuestraNoAutorizado(): void
    {
        $client = static::createClient();

        $client->request('GET', '/error?status=401');

        self::assertResponseStatusCodeSame(401);
        self::assertStringContainsString('No autorizado', (string) $client->getResponse()->getContent());
    }

    public function testError403MuestraNoAutorizado(): void
    {
        $client = static::createClient();

        $client->request('GET', '/no-autorizado');

        self::assertResponseStatusCodeSame(403);
        self::assertStringContainsString('No autorizado', (string) $client->getResponse()->getContent());
    }
}

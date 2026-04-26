<?php

declare(strict_types=1);

namespace App\Tests\Unit\Utils;

use App\Utils\RichTextSanitizer;
use PHPUnit\Framework\TestCase;

class RichTextSanitizerTest extends TestCase
{
    public function testSanitizeRemueveTagsYAtributosPeligrosos(): void
    {
        $html = '<p onclick="alert(1)">Hola</p><script>alert(1)</script><a href="javascript:alert(2)">link</a>';

        $result = RichTextSanitizer::sanitize($html);

        $this->assertStringNotContainsString('onclick', $result);
        $this->assertStringNotContainsString('<script', $result);
        $this->assertStringNotContainsString('javascript:', $result);
        $this->assertStringContainsString('<p>Hola</p>', $result);
    }

    public function testSanitizeConservaFormatoQuillPermitido(): void
    {
        $html = '<p class="ql-align-center" style="text-align: center; color: rgb(230, 0, 0); background-color: rgb(255, 194, 102); position:absolute">Texto</p>';

        $result = RichTextSanitizer::sanitize($html);

        $this->assertStringContainsString('class="ql-align-center"', $result);
        $this->assertStringContainsString('text-align: center', $result);
        $this->assertStringContainsString('color: rgb(230, 0, 0)', $result);
        $this->assertStringContainsString('background-color: rgb(255, 194, 102)', $result);
        $this->assertStringNotContainsString('position:absolute', $result);
    }

    public function testSanitizeVacioONullRetornaVacio(): void
    {
        $this->assertSame('', RichTextSanitizer::sanitize(''));
        $this->assertSame('', RichTextSanitizer::sanitize(null));
        $this->assertSame('', RichTextSanitizer::sanitize('   '));
    }

    public function testSanitizeDesenvuelveTagNoPermitidoYConservaContenidoInterno(): void
    {
        $html = '<section><p>Texto <strong>valido</strong></p></section>';

        $result = RichTextSanitizer::sanitize($html);

        $this->assertStringNotContainsString('<section', $result);
        $this->assertStringContainsString('<p>Texto <strong>valido</strong></p>', $result);
    }

    public function testSanitizeAgregaRelCuandoAnchorTieneTargetBlank(): void
    {
        $html = '<a href="https://example.com" target="_blank">sitio</a>';

        $result = RichTextSanitizer::sanitize($html);

        $this->assertStringContainsString('href="https://example.com"', $result);
        $this->assertStringContainsString('target="_blank"', $result);
        $this->assertStringContainsString('rel="noopener noreferrer"', $result);
    }

    public function testSanitizeEliminaStyleConExpressionYCaracteresInvalidos(): void
    {
        $html = '<p style="text-align: center; color: expression(alert(1)); background-color: rgb(10, 10, 10); color: red@">x</p>';

        $result = RichTextSanitizer::sanitize($html);

        $this->assertStringContainsString('text-align: center', $result);
        $this->assertStringContainsString('background-color: rgb(10, 10, 10)', $result);
        $this->assertStringNotContainsString('expression', $result);
        $this->assertStringNotContainsString('red@', $result);
    }

    public function testSanitizeClassPermiteSoloPrefijosEsperados(): void
    {
        $html = '<p class="ql-align-center text-red foo bar">hola</p>';

        $result = RichTextSanitizer::sanitize($html);

        $this->assertStringContainsString('class="ql-align-center text-red"', $result);
        $this->assertStringNotContainsString('foo', $result);
        $this->assertStringNotContainsString('bar', $result);
    }

    public function testSanitizeHrefPermiteRelativoYBloqueaDataUri(): void
    {
        $html = '<a href="/interna">ok</a><a href="data:text/html;base64,AA==">mal</a>';

        $result = RichTextSanitizer::sanitize($html);

        $this->assertStringContainsString('<a href="/interna">ok</a>', $result);
        $this->assertStringContainsString('<a>mal</a>', $result);
        $this->assertStringNotContainsString('data:text/html', $result);
    }
}

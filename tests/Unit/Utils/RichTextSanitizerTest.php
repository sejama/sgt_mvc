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
}

<?php

declare(strict_types=1);

namespace App\Utils;

final class RichTextSanitizer
{
    private const ALLOWED_TAGS = [
        'p', 'br', 'strong', 'em', 'u', 's',
        'h1', 'h2', 'h3', 'h4',
        'ol', 'ul', 'li',
        'span', 'div', 'a',
        'blockquote', 'code', 'pre',
    ];

    private const ATTRIBUTES_BY_TAG = [
        '*' => ['class', 'style'],
        'a' => ['href', 'target', 'rel'],
    ];

    private const ALLOWED_STYLE_PROPERTIES = [
        'text-align',
        'color',
        'background-color',
    ];

    public static function sanitize(?string $html): string
    {
        if ($html === null || trim($html) === '') {
            return '';
        }

        $document = new \DOMDocument();
        $wrappedHtml = '<!DOCTYPE html><html><body><div id="sanitizer-root">' . $html . '</div></body></html>';

        libxml_use_internal_errors(true);
        $document->loadHTML($wrappedHtml, \LIBXML_HTML_NOIMPLIED | \LIBXML_HTML_NODEFDTD);
        libxml_clear_errors();

        $root = $document->getElementById('sanitizer-root');
        if (!$root instanceof \DOMElement) {
            return '';
        }

        self::sanitizeNodeChildren($root);

        return trim(self::getInnerHtml($root));
    }

    private static function sanitizeNodeChildren(\DOMNode $node): void
    {
        $child = $node->firstChild;
        while ($child !== null) {
            $nextSibling = $child->nextSibling;

            if (!$child instanceof \DOMElement) {
                $child = $nextSibling;
                continue;
            }

            $tagName = strtolower($child->tagName);

            if (!in_array($tagName, self::ALLOWED_TAGS, true)) {
                if (in_array($tagName, ['script', 'style', 'iframe', 'object', 'embed', 'form', 'input', 'button', 'textarea'], true)) {
                    $child->parentNode?->removeChild($child);
                    $child = $nextSibling;
                    continue;
                }

                while ($child->firstChild !== null) {
                    $child->parentNode?->insertBefore($child->firstChild, $child);
                }
                $child->parentNode?->removeChild($child);

                // Reiniciar sobre el mismo nodo padre para sanitizar hijos reinsertados.
                self::sanitizeNodeChildren($node);
                return;
            }

            self::sanitizeAttributes($child);
            self::sanitizeNodeChildren($child);

            $child = $nextSibling;
        }
    }

    private static function sanitizeAttributes(\DOMElement $element): void
    {
        $tagName = strtolower($element->tagName);
        $allowedAttributes = array_unique(array_merge(
            self::ATTRIBUTES_BY_TAG['*'] ?? [],
            self::ATTRIBUTES_BY_TAG[$tagName] ?? []
        ));

        $attributesToRemove = [];
        foreach ($element->attributes as $attribute) {
            $name = strtolower($attribute->name);
            $value = $attribute->value;

            if (str_starts_with($name, 'on')) {
                $attributesToRemove[] = $name;
                continue;
            }

            if (!in_array($name, $allowedAttributes, true)) {
                $attributesToRemove[] = $name;
                continue;
            }

            if ($name === 'class') {
                $sanitizedClass = self::sanitizeClassValue($value);
                if ($sanitizedClass === '') {
                    $attributesToRemove[] = $name;
                } else {
                    $element->setAttribute('class', $sanitizedClass);
                }
                continue;
            }

            if ($name === 'style') {
                $sanitizedStyle = self::sanitizeStyleValue($value);
                if ($sanitizedStyle === '') {
                    $attributesToRemove[] = $name;
                } else {
                    $element->setAttribute('style', $sanitizedStyle);
                }
                continue;
            }

            if ($name === 'href' && !self::isSafeHref($value)) {
                $attributesToRemove[] = $name;
                continue;
            }

            if ($name === 'target' && $value === '_blank') {
                $element->setAttribute('rel', 'noopener noreferrer');
            }
        }

        foreach ($attributesToRemove as $attribute) {
            $element->removeAttribute($attribute);
        }
    }

    private static function sanitizeClassValue(string $classValue): string
    {
        $tokens = preg_split('/\s+/', trim($classValue)) ?: [];
        $allowedTokens = array_filter(
            $tokens,
            static fn (string $token): bool => (bool) preg_match('/^(ql-[a-z0-9_-]+|text-[a-z0-9_-]+)$/i', $token)
        );

        return implode(' ', $allowedTokens);
    }

    private static function sanitizeStyleValue(string $styleValue): string
    {
        $result = [];
        $declarations = explode(';', $styleValue);

        foreach ($declarations as $declaration) {
            $parts = explode(':', $declaration, 2);
            if (count($parts) !== 2) {
                continue;
            }

            $property = strtolower(trim($parts[0]));
            $value = trim($parts[1]);

            if (!in_array($property, self::ALLOWED_STYLE_PROPERTIES, true)) {
                continue;
            }

            if (preg_match('/expression|javascript:|url\s*\(/i', $value)) {
                continue;
            }

            if (!preg_match('/^[#(),.%\s\-a-zA-Z0-9]+$/', $value)) {
                continue;
            }

            $result[] = $property . ': ' . $value;
        }

        return implode('; ', $result);
    }

    private static function isSafeHref(string $href): bool
    {
        $normalized = trim(strtolower($href));
        if ($normalized === '' || str_starts_with($normalized, 'javascript:') || str_starts_with($normalized, 'data:')) {
            return false;
        }

        if (preg_match('/^(https?:|mailto:|tel:|\/|#)/', $normalized)) {
            return true;
        }

        return !str_contains($normalized, ':');
    }

    private static function getInnerHtml(\DOMElement $element): string
    {
        $html = '';
        foreach ($element->childNodes as $childNode) {
            $html .= $element->ownerDocument?->saveHTML($childNode) ?? '';
        }

        return $html;
    }
}

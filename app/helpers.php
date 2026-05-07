<?php

declare(strict_types=1);

if (!function_exists('slugify')) {
    /**
     * Convert a string to a URL-friendly slug.
     */
    function slugify(string $text): string
    {
        // Transliterate non-ASCII characters
        $text = transliterator_transliterate('Any-Latin; Latin-ASCII', $text) ?? $text;
        $text = strtolower(trim($text));
        $text = preg_replace('/[^a-z0-9]+/', '-', $text);

        return trim($text, '-');
    }
}

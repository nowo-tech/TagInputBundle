<?php

declare(strict_types=1);

namespace Nowo\TagInputBundle\Form;

use function preg_match;
use function preg_replace;

/**
 * Turns the delimiter-less `pattern` option (shared with the Tagify client as a JS RegExp source)
 * into a PCRE regex: unescaped `/` are escaped for the delimiter and the `u` flag is added so
 * multibyte tags are matched per character.
 */
final class TagPattern
{
    public static function toRegex(string $pattern): string
    {
        // Escape every "/" that is preceded by an even number of backslashes (i.e. not already escaped).
        $escaped = (string) preg_replace('#(?<!\\\\)((?:\\\\\\\\)*)/#', '$1\\/', $pattern);

        return '/' . $escaped . '/u';
    }

    public static function isValid(string $pattern): bool
    {
        return @preg_match(self::toRegex($pattern), '') !== false;
    }
}

<?php

declare(strict_types=1);

namespace Nowo\TagInputBundle\Tests\Unit\Form;

use Nowo\TagInputBundle\Form\TagPattern;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Nowo\TagInputBundle\Form\TagPattern
 */
final class TagPatternTest extends TestCase
{
    public function testToRegexEscapesDelimiterAndAddsUnicodeFlag(): void
    {
        self::assertSame('/^a\/b$/u', TagPattern::toRegex('^a/b$'));
        self::assertSame('/^a\/b$/u', TagPattern::toRegex('^a\/b$'));
        self::assertSame('/^a\\\\\/b$/u', TagPattern::toRegex('^a\\\\/b$'));
    }

    public function testIsValid(): void
    {
        self::assertTrue(TagPattern::isValid('^[a-z]+/[0-9]+$'));
        self::assertTrue(TagPattern::isValid('^\p{L}+$'));
        self::assertFalse(TagPattern::isValid('([a-z'));
        // A "/" can no longer close the delimiter and inject modifiers: it is matched literally.
        self::assertTrue(TagPattern::isValid('^a/b$/e'));
        self::assertSame(1, preg_match(TagPattern::toRegex('a/e'), 'a/e'));
    }
}

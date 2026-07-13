<?php

declare(strict_types=1);

namespace Nowo\TagInputBundle\Tests\Unit\Form\DataTransformer;

use Nowo\TagInputBundle\Form\DataTransformer\TagsToValueTransformer;
use Nowo\TagInputBundle\Form\ValueFormat;
use PHPUnit\Framework\TestCase;
use ReflectionMethod;
use Symfony\Component\Form\Exception\TransformationFailedException;

/**
 * @covers \Nowo\TagInputBundle\Form\DataTransformer\TagsToValueTransformer
 */
final class TagsToValueTransformerTest extends TestCase
{
    public function testTransformArrayToJson(): void
    {
        $transformer = new TagsToValueTransformer(ValueFormat::ARRAY);

        self::assertSame('["php","symfony"]', $transformer->transform(['php', 'symfony']));
        self::assertSame('', $transformer->transform([]));
        self::assertSame('', $transformer->transform(null));
    }

    public function testTransformFromCommaSeparatedStringModel(): void
    {
        $transformer = new TagsToValueTransformer(ValueFormat::ARRAY);

        self::assertSame('["php","symfony"]', $transformer->transform('php, symfony'));
    }

    public function testReverseTransformJsonObjectsToArray(): void
    {
        $transformer = new TagsToValueTransformer(ValueFormat::ARRAY);

        self::assertSame(
            ['php', 'symfony'],
            $transformer->reverseTransform('[{"value":"php"},{"value":"symfony"}]'),
        );
    }

    public function testReverseTransformJsonStringArray(): void
    {
        $transformer = new TagsToValueTransformer(ValueFormat::ARRAY);

        self::assertSame(['php'], $transformer->reverseTransform('["php"]'));
    }

    public function testReverseTransformCommaSeparatedStringFormat(): void
    {
        $transformer = new TagsToValueTransformer(ValueFormat::STRING);

        self::assertSame('php,symfony', $transformer->reverseTransform('php, symfony'));
    }

    public function testReverseTransformEmptyValues(): void
    {
        $arrayTransformer  = new TagsToValueTransformer(ValueFormat::ARRAY);
        $stringTransformer = new TagsToValueTransformer(ValueFormat::STRING);

        self::assertSame([], $arrayTransformer->reverseTransform(''));
        self::assertSame([], $arrayTransformer->reverseTransform('   '));
        self::assertSame('', $stringTransformer->reverseTransform(''));
    }

    public function testReverseTransformInvalidJsonThrows(): void
    {
        $transformer = new TagsToValueTransformer(ValueFormat::ARRAY);

        $this->expectException(TransformationFailedException::class);
        $transformer->reverseTransform('{invalid');
    }

    public function testWhitelistRejectsUnknownTag(): void
    {
        $transformer = new TagsToValueTransformer(
            ValueFormat::ARRAY,
            whitelist: ['php'],
        );

        $this->expectException(TransformationFailedException::class);
        $transformer->reverseTransform('["symfony"]');
    }

    public function testPatternRejectsInvalidTag(): void
    {
        $transformer = new TagsToValueTransformer(
            ValueFormat::ARRAY,
            pattern: '^[a-z]+$',
        );

        $this->expectException(TransformationFailedException::class);
        $transformer->reverseTransform('["PHP"]');
    }

    public function testMaxTagsLimitsOutput(): void
    {
        $transformer = new TagsToValueTransformer(
            ValueFormat::ARRAY,
            maxTags: 2,
        );

        self::assertSame(['php', 'symfony'], $transformer->reverseTransform('["php","symfony","twig"]'));
    }

    public function testDuplicatesDisabledRemovesRepeatedTags(): void
    {
        $transformer = new TagsToValueTransformer(ValueFormat::ARRAY, duplicates: false);

        self::assertSame(['php'], $transformer->reverseTransform('["php","php"]'));
    }

    public function testTrimDisabledKeepsWhitespace(): void
    {
        $transformer = new TagsToValueTransformer(ValueFormat::ARRAY, trim: false);

        self::assertSame([' php '], $transformer->reverseTransform('[" php "]'));
    }

    public function testTransformSkipsDuplicatesWhenDisabled(): void
    {
        $transformer = new TagsToValueTransformer(ValueFormat::ARRAY, duplicates: false);

        self::assertSame('["php"]', $transformer->transform(['php', 'php']));
    }

    public function testTransformRespectsMaxTags(): void
    {
        $transformer = new TagsToValueTransformer(ValueFormat::ARRAY, maxTags: 2);

        self::assertSame('["php","symfony"]', $transformer->transform(['php', 'symfony', 'twig']));
    }

    public function testReverseTransformIgnoresNonStringDecodedItems(): void
    {
        $transformer = new TagsToValueTransformer(ValueFormat::ARRAY);

        self::assertSame(['php'], $transformer->reverseTransform('[{"value":"php"},42,null]'));
    }

    public function testTransformIgnoresUnsupportedModelValues(): void
    {
        $transformer = new TagsToValueTransformer(ValueFormat::ARRAY);
        $value       = 42;

        // @phpstan-ignore argument.type (invalid model value)
        self::assertSame('', $transformer->transform($value));
    }

    public function testTransformSkipsNonStringArrayItems(): void
    {
        $transformer = new TagsToValueTransformer(ValueFormat::ARRAY);
        $value       = ['php', 42, null];

        // @phpstan-ignore argument.type (mixed tag list)
        self::assertSame('["php"]', $transformer->transform($value));
    }

    public function testTransformSkipsEmptyTagsAfterTrim(): void
    {
        $transformer = new TagsToValueTransformer(ValueFormat::ARRAY);

        self::assertSame('["php"]', $transformer->transform(['php', '   ', '']));
    }

    public function testReverseTransformReturnsEmptyForJsonObjectWithoutTagValues(): void
    {
        $transformer = new TagsToValueTransformer(ValueFormat::ARRAY);

        self::assertSame([], $transformer->reverseTransform('{}'));
    }

    public function testReverseTransformSkipsEmptySubmittedTags(): void
    {
        $transformer = new TagsToValueTransformer(ValueFormat::ARRAY);

        self::assertSame(['php'], $transformer->reverseTransform('["php","   "]'));
    }

    public function testParseSubmittedTagsReturnsEmptyForWhitespaceOnly(): void
    {
        $transformer = new TagsToValueTransformer(ValueFormat::ARRAY);
        $method      = new ReflectionMethod(TagsToValueTransformer::class, 'parseSubmittedTags');

        self::assertSame([], $method->invoke($transformer, '   '));
    }
}

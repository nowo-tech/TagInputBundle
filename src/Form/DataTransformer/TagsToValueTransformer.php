<?php

declare(strict_types=1);

namespace Nowo\TagInputBundle\Form\DataTransformer;

use JsonException;
use Nowo\TagInputBundle\Form\ValueFormat;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

use function count;
use function implode;
use function in_array;
use function is_array;
use function is_string;
use function json_decode;
use function json_encode;
use function preg_match;
use function sprintf;
use function trim;

use const JSON_THROW_ON_ERROR;

/**
 * Normalizes tag data between model (array or comma-separated string) and view (JSON for Tagify).
 *
 * @implements DataTransformerInterface<array<int, string>|string, string>
 */
final class TagsToValueTransformer implements DataTransformerInterface
{
    /**
     * @param list<string> $whitelist
     */
    public function __construct(
        private readonly ValueFormat $valueFormat,
        private readonly bool $trim = true,
        private readonly ?string $pattern = null,
        private readonly array $whitelist = [],
        private readonly bool $duplicates = false,
        private readonly ?int $maxTags = null,
    ) {
    }

    /**
     * @param array<int, string>|string|null $value
     */
    public function transform(mixed $value): string
    {
        $tags = $this->normalizeModelTags($value);

        if ($tags === []) {
            return '';
        }

        return json_encode($tags, JSON_THROW_ON_ERROR);
    }

    /**
     * @return array<int, string>|string
     */
    public function reverseTransform(mixed $value): array|string
    {
        if (!is_string($value) || trim($value) === '') {
            return $this->emptyModelValue();
        }

        try {
            $tags = $this->parseSubmittedTags($value);
        } catch (JsonException) {
            throw new TransformationFailedException('Invalid tag payload.');
        }

        $tags = $this->normalizeSubmittedTags($tags);

        if ($this->valueFormat === ValueFormat::STRING) {
            return implode(',', $tags);
        }

        return $tags;
    }

    /**
     * @return list<string>
     */
    private function normalizeModelTags(mixed $value): array
    {
        if (in_array($value, [null, '', []], true)) {
            return [];
        }

        if (is_string($value)) {
            $items = array_map(trim(...), explode(',', $value));
        } elseif (is_array($value)) {
            $items = $value;
        } else {
            return [];
        }

        $tags = [];
        foreach ($items as $tag) {
            if (!is_string($tag)) {
                continue;
            }

            $normalized = $this->normalizeTag($tag);
            if ($normalized === '') {
                continue;
            }

            if (!$this->duplicates && in_array($normalized, $tags, true)) {
                continue;
            }

            $tags[] = $normalized;

            if ($this->maxTags !== null && count($tags) >= $this->maxTags) {
                break;
            }
        }

        return $tags;
    }

    /**
     * @return list<string>
     */
    private function parseSubmittedTags(string $value): array
    {
        $trimmed = trim($value);

        if ($trimmed === '') {
            return [];
        }

        if ($trimmed[0] === '[' || $trimmed[0] === '{') {
            /** @var mixed $decoded */
            $decoded = json_decode($trimmed, true, 512, JSON_THROW_ON_ERROR);

            return $this->extractTagsFromDecoded(is_array($decoded) ? $decoded : []);
        }

        return array_map(trim(...), explode(',', $trimmed));
    }

    /**
     * @param array<int|string, mixed> $decoded
     *
     * @return list<string>
     */
    private function extractTagsFromDecoded(array $decoded): array
    {
        $tags = [];

        foreach ($decoded as $item) {
            if (is_string($item)) {
                $tags[] = $item;
                continue;
            }

            if (is_array($item) && isset($item['value']) && is_string($item['value'])) {
                $tags[] = $item['value'];
            }
        }

        return $tags;
    }

    /**
     * @param list<string> $tags
     *
     * @return list<string>
     */
    private function normalizeSubmittedTags(array $tags): array
    {
        $normalized = [];

        foreach ($tags as $tag) {
            $tag = $this->normalizeTag($tag);
            if ($tag === '') {
                continue;
            }

            if ($this->whitelist !== [] && !in_array($tag, $this->whitelist, true)) {
                throw new TransformationFailedException(sprintf('Tag "%s" is not allowed.', $tag));
            }

            if ($this->pattern !== null && $this->pattern !== '' && preg_match('/' . $this->pattern . '/', $tag) !== 1) {
                throw new TransformationFailedException(sprintf('Tag "%s" does not match the required pattern.', $tag));
            }

            if (!$this->duplicates && in_array($tag, $normalized, true)) {
                continue;
            }

            $normalized[] = $tag;

            if ($this->maxTags !== null && count($normalized) >= $this->maxTags) {
                break;
            }
        }

        return $normalized;
    }

    private function normalizeTag(string $tag): string
    {
        return $this->trim ? trim($tag) : $tag;
    }

    /**
     * @return array<int, string>|string
     */
    private function emptyModelValue(): array|string
    {
        return $this->valueFormat === ValueFormat::STRING ? '' : [];
    }
}

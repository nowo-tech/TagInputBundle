# Usage

Use `TagType` in any Symfony form:

```php
use Nowo\TagInputBundle\Form\TagType;
use Nowo\TagInputBundle\Form\ValueFormat;

$builder->add('tags', TagType::class, [
    'placeholder' => 'Add tags and press Enter',
    'max_tags' => 10,
    'whitelist' => ['php', 'symfony', 'twig'],
    'input_class' => 'form-control',
]);
```

The field value is an array of strings by default, for example `['php', 'symfony']`.

Use `value_format => ValueFormat::STRING` to receive a comma-separated string instead.

## Twig and translations

Override bundle templates and translation keys from your application. See [Configuration — Twig overrides](CONFIGURATION.md#twig-overrides) and [Configuration — Translations](CONFIGURATION.md#translations).

## Assets

Publish and include the bundle assets:

```bash
php bin/console assets:install public
```

```twig
<link rel="stylesheet" href="{{ asset('bundles/nowotaginput/tag-input.css') }}">
<script src="{{ asset('bundles/nowotaginput/tag-input.js') }}"></script>
```

## Customization

- `value_format`: `array` (default) or `string`
- `max_tags`: maximum number of tags
- `whitelist`: allowed tag values (enables suggestions)
- `pattern`: regex pattern without delimiters
- `duplicates`: allow duplicate tags
- `dropdown_enabled`: Tagify dropdown for whitelist suggestions
- `placeholder`: input placeholder
- `container_class`, `input_class`: CSS classes

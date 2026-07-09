# Tag Input Bundle
[![CI](https://github.com/nowo-tech/TagInputBundle/actions/workflows/ci.yml/badge.svg)](https://github.com/nowo-tech/TagInputBundle/actions/workflows/ci.yml) [![Packagist Version](https://img.shields.io/packagist/v/nowo-tech/tag-input-bundle.svg?style=flat)](https://packagist.org/packages/nowo-tech/tag-input-bundle) [![Packagist Downloads](https://img.shields.io/packagist/dt/nowo-tech/tag-input-bundle.svg)](https://packagist.org/packages/nowo-tech/tag-input-bundle) [![License](https://img.shields.io/badge/license-MIT-blue.svg)](LICENSE) [![PHP](https://img.shields.io/badge/PHP-8.1%2B-777BB4?logo=php)](https://php.net) [![Symfony](https://img.shields.io/badge/Symfony-6.0%2B%20%7C%207.4%2B%20%7C%208.0%20%7C%208.1%2B-000000?logo=symfony)](https://symfony.com) [![GitHub stars](https://img.shields.io/github/stars/nowo-tech/TagInputBundle.svg?style=social&label=Star)](https://github.com/nowo-tech/TagInputBundle) [![Coverage](https://img.shields.io/badge/Coverage-100%25-brightgreen)](#tests-and-coverage)

> ⭐ **Found this useful?** [Install from Packagist](https://packagist.org/packages/nowo-tech/tag-input-bundle) · Give it a **star** on [GitHub](https://github.com/nowo-tech/TagInputBundle) so more developers can find it.

Symfony `FormType` for multi-tag text inputs powered by [Tagify](https://github.com/yairEO/tagify).

FrankenPHP worker mode: Not declared as supported for this bundle at the moment.

## Features

- `TagType::class` for keywords, labels, emails, skills, categories, and more.
- Tagify UI on a standard text input with Twig form themes.
- Model value as `array<string>` or comma-separated `string`.
- Whitelist, max tags, pattern validation, duplicates, and dropdown suggestions.
- TypeScript + Vite assets bundling Tagify in `src/Resources/assets`.

## Documentation

- [Installation](docs/INSTALLATION.md)
- [Configuration](docs/CONFIGURATION.md)
- [Usage](docs/USAGE.md)
- [Contributing](docs/CONTRIBUTING.md)
- [Changelog](docs/CHANGELOG.md)
- [Upgrading](docs/UPGRADING.md)
- [Release](docs/RELEASE.md)
- [Security](docs/SECURITY.md)
- [Engram](docs/ENGRAM.md)
- [Spec-driven development](docs/SPEC-DRIVEN-DEVELOPMENT.md)
- [GitHub Spec Kit](docs/SPEC-KIT.md)

### Additional documentation

- [Demo notes](docs/DEMO-FRANKENPHP.md)

## Quick usage

```php
use Nowo\TagInputBundle\Form\TagType;

$builder->add('tags', TagType::class, [
    'placeholder' => 'Add tags and press Enter',
    'max_tags' => 10,
    'whitelist' => ['php', 'symfony', 'twig'],
    'input_class' => 'form-control',
]);
```

Include the bundle assets in your layout or form template:

```twig
<link rel="stylesheet" href="{{ asset('bundles/nowotaginput/tag-input.css') }}">
<script src="{{ asset('bundles/nowotaginput/tag-input.js') }}"></script>
```

Run `php bin/console assets:install public` after installing the bundle.

The submitted value is an array of strings by default, e.g. `['php', 'symfony']`.

## Version information

Current stable release: **v1.0.2** ([changelog](docs/CHANGELOG.md)).

## Tests and coverage

- PHP: 100%
- TS/JS: 96%
- Python: N/A

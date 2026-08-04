# Installation

## Requirements

- PHP `>=8.1` (<8.6). Symfony **8.0** and **8.1** require **PHP 8.4+**.
- Symfony **7.4**, **8.0**, or **8.1** (minimum tested minors). The bundle also supports Symfony 6.x and 7.0–7.3 when constraints resolve.

## Composer

```bash
composer require nowo-tech/tag-input-bundle
```

## Enable the bundle

### With Symfony Flex

The recipe enables the bundle, adds `config/packages/nowo_tag_input.yaml`, and reminds you to install assets. Adjust configuration as needed (see [Configuration](CONFIGURATION.md)).

### Without Flex

Register the bundle manually:

```php
// config/bundles.php
return [
    Nowo\TagInputBundle\NowoTagInputBundle::class => ['all' => true],
];
```

Create `config/packages/nowo_tag_input.yaml` (see [Configuration](CONFIGURATION.md) for options).

## Assets

The bundle ships built assets at `src/Resources/public/tag-input.js` and `tag-input.css`. After `composer require`, install assets in your app:

```bash
php bin/console assets:install public
```

The bundle registers the Symfony assets package `nowo_tag_input` (`base_path` `/bundles/nowotaginput`). Include assets in your layout or form template:

```twig
<link rel="stylesheet" href="{{ asset('tag-input.css', 'nowo_tag_input') }}">
<script src="{{ asset('tag-input.js', 'nowo_tag_input') }}"></script>
```

Contributors rebuild frontend assets with:

```bash
pnpm install
pnpm run build
php bin/console assets:install public
```

## Twig Extra Bundle (REQ-TWIG-004)

This package ships Twig templates. Host applications **must** install and enable Twig Extra:

```bash
composer require twig/extra-bundle twig/string-extra
```

Register `Twig\Extra\TwigExtraBundle\TwigExtraBundle` in `config/bundles.php` (Flex usually does this). Demos already include the same stack. The package `release-check` runs `make check-twig-extra` to guard this contract.

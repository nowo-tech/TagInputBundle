# Upgrading

This document describes upgrade notes for `TagInputBundle`.

## Current compatibility baseline

- PHP: `>=8.1` (<8.6). Symfony **8.0** and **8.1** require **PHP 8.4+**.
- Symfony components: `^6.0 || ^7.0 || ^8.0` (CI matrix tests **7.4**, **8.0**, **8.1**).

## Public API reminders

- Main form type: `Nowo\TagInputBundle\Form\TagType`
- Value format enum: `Nowo\TagInputBundle\Form\ValueFormat` (`array` | `string`)
- Root config key: `nowo_tag_input`
- Global config options (defaults for every `TagType` field):
  - `value_format` (`array` | `string`, default `array`)
  - `trim` (default `true`)
  - `pattern` (optional regex without delimiters)
  - `whitelist` (default `[]`)
  - `duplicates` (default `false`)
  - `max_tags` (optional integer)
  - `dropdown_enabled` (default `true`)
  - `placeholder` (default `''`)
  - `form_theme` (default `form_div_layout.html.twig`)

Per-field options override globals; see [Configuration](CONFIGURATION.md) and [Usage](USAGE.md).

## 1.0.0 (2026-07-05)

Initial public release. There is no earlier tagged version to migrate from.

After `composer require nowo-tech/tag-input-bundle`:

1. Enable the bundle (Flex recipe or manual registration in `config/bundles.php`).
2. Review `config/packages/nowo_tag_input.yaml` if the recipe was applied.
3. Run `php bin/console assets:install public` and include `bundles/nowotaginput/tag-input.css` and `tag-input.js` in your layout.
4. Use `TagType::class` in forms; model data is `array<string>` unless you set `value_format` to `string`.

## Breaking changes

No breaking changes are documented after `1.0.0`.

When a future release introduces BC breaks, this file will include:

- affected version
- old behavior vs new behavior
- migration steps

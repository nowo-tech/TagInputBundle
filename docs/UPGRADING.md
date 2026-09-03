# Upgrading

## Table of contents

- [From 1.1.3 to 1.2.0](#from-113-to-120)
- [From 1.1.2 to 1.1.3](#from-112-to-113)


## From 1.1.3 to 1.2.0

The default form theme now wraps the Tagify field in `<nowo-tag-input>`. Include the same `tag-input.js` asset. Custom theme overrides should use `<nowo-tag-input>` as the outer host (`data-controller="nowo-tag-input"` fields still initialize).

```bash
composer update nowo-tech/tag-input-bundle
php bin/console assets:install
```

## From 1.1.2 to 1.1.3

Review the [CHANGELOG](CHANGELOG.md) entry. PHP **8.2+** may now be required.

```bash
composer update nowo-tech/tag-input-bundle
```

## From 1.1.2 to 1.1.3

Review the [CHANGELOG](CHANGELOG.md) entry. PHP **8.2+** may now be required.

```bash
composer update nowo-tech/tag-input-bundle
```

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

# Upgrading

## 1.0.6 (2026-07-30)

Documentation polish for Twig form-theme overrides (REQ-TWIG-001 freeze rule). **No config or API migration.**

```bash
composer require nowo-tech/tag-input-bundle:^1.0.6
```

See [USAGE.md — Overriding templates](USAGE.md#overriding-templates-req-twig-001). Hosts already on **1.0.5** need no application changes.

## 1.0.5 (2026-07-29)

Named Symfony asset package and compliance remedia. **Template update recommended** if you load Tagify assets via Twig.

### Asset package (REQ-ASSETS-004)

- The bundle registers package `nowo_tag_input` (`base_path` `/bundles/nowotaginput`).
- Prefer:

```twig
<link rel="stylesheet" href="{{ asset('tag-input.css', 'nowo_tag_input') }}">
<script src="{{ asset('tag-input.js', 'nowo_tag_input') }}"></script>
```

instead of `asset('bundles/nowotaginput/tag-input.css')` (still works after `assets:install`, but the named package is the supported path).

### Other

- FrankenPHP worker mode is declared friendly; demo Symfony 8 uses PHP **8.5**.
- Contributors: PHPStan + `phpstan-frankenphp` rulesets after `composer install`.

Upgrade from `1.0.4` with `composer update nowo-tech/tag-input-bundle` — no form/config API changes.

## 1.0.4 (2026-07-16)

Maintainer / community-docs release. **No breaking changes** for bundle consumers.

- Code of Conduct and `docs/GITHUB_CI.md` (REQ-GIT-001: no Cursor co-author trailers).
- Local hooks/scripts and CI `git-hygiene` job; `release-check` runs `check-no-cursor-coauthor`.
- Contributor toolchain: `@types/node` 26.1.1; Composer lock refresh (php-cs-fixer, rector, demo `twig-inspector-bundle` v1.0.36).

Upgrade from `1.0.3` with `composer update nowo-tech/tag-input-bundle` — no application code changes required.

## 1.0.3 (2026-07-13)

Maintenance release. **No breaking changes** for bundle consumers.

- Contributor toolchain: `@types/node` 26, `codecov/codecov-action` v7.
- Composer lock refresh (php-cs-fixer, rector; demo `twig-inspector-bundle` v1.0.35).
- Rector skips Symfony test fixture cache (`tests/Fixtures/app/var`).

Upgrade from `1.0.2` with `composer update nowo-tech/tag-input-bundle` — no application code changes required.

## 1.0.2 (2026-07-09)

Documentation and maintainer-tooling release. **No breaking changes** for bundle consumers.

- GitHub Spec Kit baseline (`.specify/`, `specs/001-baseline/`, Cursor skills) and new [`SPEC-KIT.md`](SPEC-KIT.md).
- [`SECURITY.md`](SECURITY.md) and [`SPEC-DRIVEN-DEVELOPMENT.md`](SPEC-DRIVEN-DEVELOPMENT.md) corrected for TagInputBundle (removed stale OTP-input wording).
- Demo Makefile variable rename (`DOCKER_COMPOSE`); demo lock-file cleanup.

Upgrade from `1.0.1` with `composer update nowo-tech/tag-input-bundle` — no application code changes required.

## 1.0.1 (2026-07-05)

Maintenance release. **No breaking changes** for bundle consumers.

- New placeholder translations: `de`, `fr`, `it`, `nl`, `pt` (domain `NowoTagInputBundle`).
- Demo Docker images install the `intl` PHP extension (demo infrastructure only).
- Contributor toolchain bumps: TypeScript 6, Vite 8, happy-dom 20, and updated GitHub Actions pins.

Upgrade from `1.0.0` with `composer update nowo-tech/tag-input-bundle` — no application code changes required.

## 1.0.0 (2026-07-05)

Initial public release. There is no earlier tagged version to migrate from.

After `composer require nowo-tech/tag-input-bundle`:

1. Enable the bundle (Flex recipe or manual registration in `config/bundles.php`).
2. Review `config/packages/nowo_tag_input.yaml` if the recipe was applied.
3. Run `php bin/console assets:install public` and include assets via the named package: `asset('tag-input.css', 'nowo_tag_input')` and `asset('tag-input.js', 'nowo_tag_input')`.
4. Use `TagType::class` in forms; model data is `array<string>` unless you set `value_format` to `string`.

## Breaking changes

No breaking changes are documented after `1.0.0` (including `1.0.1`, `1.0.2`, `1.0.3`, and `1.0.4`).

When a future release introduces BC breaks, this file will include:

- affected version
- old behavior vs new behavior
- migration steps

## Unreleased

## To 1.1.2

No application upgrade steps.

```bash
composer update nowo-tech/tag-input-bundle
```

## To 1.1.1

No application upgrade steps. **Demos only:** Hot Reload Bundle `^1.4` (FrankenPHP Mercure/`hot_reload`, `dev`/`test`). Shipped demos are Symfony 8 only (Symfony 6/7 demo apps removed).

```bash
composer update nowo-tech/tag-input-bundle
php bin/console cache:clear
```

## To 1.1.0

From **1.0.6** — Adds required Twig Extra (REQ-TWIG-004) and Twig-CS-Fixer. Register TwigExtraBundle if Flex did not.

```bash
composer update nowo-tech/tag-input-bundle
php bin/console cache:clear
```

### Twig Extra Bundle (REQ-TWIG-004)

Hosts that render this bundle's Twig templates must install:

```bash
composer require twig/extra-bundle twig/string-extra
```

and enable `Twig\Extra\TwigExtraBundle\TwigExtraBundle`. Flex recipes usually register it automatically.

### Twig-CS-Fixer (maintainers)

Package maintainers: `composer twig:lint` / `composer twig:fix` use `.twig-cs-fixer.php` over `src/` (and `templates/` when present).


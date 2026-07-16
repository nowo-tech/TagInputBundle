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
3. Run `php bin/console assets:install public` and include `bundles/nowotaginput/tag-input.css` and `tag-input.js` in your layout.
4. Use `TagType::class` in forms; model data is `array<string>` unless you set `value_format` to `string`.

## Breaking changes

No breaking changes are documented after `1.0.0` (including `1.0.1`, `1.0.2`, `1.0.3`, and `1.0.4`).

When a future release introduces BC breaks, this file will include:

- affected version
- old behavior vs new behavior
- migration steps

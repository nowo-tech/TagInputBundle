# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/).

## [Unreleased]

### Added

### Changed

### Fixed

### Removed

## [1.0.3] - 2026-07-13

### Changed

- Contributor toolchain (dev only): `@types/node` **26.1.0**.
- GitHub Actions: `codecov/codecov-action@v7`.
- Composer lock refresh: `friendsofphp/php-cs-fixer` **v3.95.13**, `rector/rector` **2.5.6**; demo locks updated for `nowo-tech/twig-inspector-bundle` **v1.0.35**.
- `.gitignore`: ignore `.cursor/sandbox.json` (machine-specific Cursor sandbox config).
- Rector: skip `tests/Fixtures/app/var` (Symfony test cache; aligned with PHPStan exclusions).
- Internal refactors from Rector **2.5.6** (first-class callables, reflection cleanup); no behavior change.

## [1.0.2] - 2026-07-09

### Added

- [GitHub Spec Kit](https://github.com/github/spec-kit) baseline: `.specify/`, Cursor Agent skills (`.cursor/skills/speckit-*`), and [`specs/001-baseline/`](../specs/001-baseline/) with full `src/` code inventory.
- [`docs/SPEC-KIT.md`](SPEC-KIT.md): installation, structure, and maintainer usage manual.

### Changed

- [`docs/SPEC-DRIVEN-DEVELOPMENT.md`](SPEC-DRIVEN-DEVELOPMENT.md): three-layer model (Spec Kit + product behavior + `REQ-*` traceability); user stories aligned with tag-input domain.
- [`docs/SECURITY.md`](SECURITY.md): threat model and release checklist corrected for Tagify/tag options (`pattern`, `whitelist`, `max_tags`) — removed stale OTP-bundle wording.
- Demo Makefiles (`demo/symfony7`, `demo/symfony8`): `COMPOSE` renamed to `DOCKER_COMPOSE` (Nowo standard).
- README: link to `docs/SPEC-KIT.md` in the canonical `## Documentation` section.
- Composer lock files refreshed (bundle root and demos).

### Fixed

- Demo `symfony.lock` (symfony7): removed stale `nowo-tech/otp-input-bundle` entry.

## [1.0.1] - 2026-07-05

### Added

- Translations for `de`, `fr`, `it`, `nl`, and `pt` in the `NowoTagInputBundle` domain.
- `intl` PHP extension in demo Dockerfiles (`demo/symfony7`, `demo/symfony8`) for Symfony intl features.

### Changed

- Dev toolchain (contributors only): TypeScript **6.0.3**, Vite **8.1.3**, happy-dom **20.10.6**.
- GitHub Actions: `actions/checkout@v7`, `actions/cache@v6`, `actions/github-script@v9`.
- [CONFIGURATION.md](CONFIGURATION.md): translation locales list updated (`en`, `es`, `de`, `fr`, `it`, `nl`, `pt`).

### Fixed

- None.

## [1.0.0] - 2026-07-05

First public release of **TagInputBundle** on [GitHub](https://github.com/nowo-tech/TagInputBundle).

### Added

- `TagType` Symfony form type with Tagify UI for multi-tag text inputs.
- `TagsToValueTransformer` mapping Tagify JSON payloads to model values as `array<string>` or comma-separated `string` (`ValueFormat` enum).
- Global defaults and form theme selection via `nowo_tag_input` configuration (`value_format`, `trim`, `pattern`, `whitelist`, `duplicates`, `max_tags`, `dropdown_enabled`, `placeholder`, `form_theme`).
- Twig form themes for div, table, Bootstrap 3–5 (incl. horizontal), Foundation 5–6, and Tailwind 2 layouts.
- TypeScript + Vite assets (`tag-input.ts`, `logger.ts`) built to `src/Resources/public/tag-input.js` and `tag-input.css`.
- Translations (`NowoTagInputBundle` domain) for `en` and `es`.
- Symfony Flex recipe (`.symfony/recipe/nowo-tech/tag-input-bundle/1.0/`).
- FrankenPHP demos under `demo/symfony7` and `demo/symfony8` with Web Profiler, debug toolbar, and Twig Inspector.
- PHPUnit suite (`tests/Unit`, `tests/Integration`) with **100%** PHP line coverage; Vitest suite for frontend assets.
- Documentation set (`INSTALLATION`, `CONFIGURATION`, `USAGE`, `SECURITY`, `UPGRADING`, `RELEASE`, `ENGRAM`, `SPEC-DRIVEN-DEVELOPMENT`, `DEMO-FRANKENPHP`).
- Twig and translation override procedures in [CONFIGURATION.md](CONFIGURATION.md).
- CI (PHPUnit, PHP-CS-Fixer, PHPStan, Rector dry-run, coverage), release workflows, Dependabot (Composer, GitHub Actions, npm), and Scrutinizer integration.
- Alignment with Nowo bundle standards (`REQ-*` traceability, Makefile `release-check`, Engram MCP).

[1.0.3]: https://github.com/nowo-tech/TagInputBundle/releases/tag/v1.0.3
[1.0.2]: https://github.com/nowo-tech/TagInputBundle/releases/tag/v1.0.2
[1.0.1]: https://github.com/nowo-tech/TagInputBundle/releases/tag/v1.0.1
[1.0.0]: https://github.com/nowo-tech/TagInputBundle/releases/tag/v1.0.0

# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/).

## [Unreleased]

### Changed

- **Web Component:** the form theme now renders `<nowo-tag-input>` (light DOM). `tag-input.js` defines the custom element and still initializes fields with `data-controller="nowo-tag-input"`.


## [1.1.3] - 2026-08-24

### Changed

- Raise minimum PHP to **8.2** and sync README badge (REQ-SF-001).
- **Docs:** PHP-FIG PSR evaluation (REQ-CS-007).

### Notes

- **No API or configuration changes** for integrators unless noted above.

[1.1.3]: https://github.com/nowo-tech/TagInputBundle/releases/tag/v1.1.3

## [1.1.2] - 2026-08-19

### Security

- **CI:** run `composer audit --locked` after dependency install (REQ-SEC / P3).

## [1.1.1] - 2026-08-18

### Changed

- **Demos:** pin `nowo-tech/hot-reload-bundle` to `^1.4` with FrankenPHP Mercure/`hot_reload` (`dev`/`test` only).
- **Demos:** Symfony 8 only; Symfony 6/7 demo apps removed.

[1.1.1]: https://github.com/nowo-tech/TagInputBundle/releases/tag/v1.1.1

## [1.1.0] - 2026-08-04

### Added
- **REQ-TWIG-004:** require `twig/extra-bundle` + `twig/string-extra`; `make check-twig-extra` in `release-check`; demos register `TwigExtraBundle`.
- **Twig-CS-Fixer:** `vincentlanglet/twig-cs-fixer`, `.twig-cs-fixer.php`, `composer twig:lint` / `twig:fix`.

[1.1.0]: https://github.com/nowo-tech/TagInputBundle/releases/tag/v1.1.0

## [1.0.6] - 2026-07-30

### Documentation

- USAGE: **Overriding templates (REQ-TWIG-001)** — TOC, freeze rule, prefer `form_theme` over full-file forks, full `<subpath>` table for form themes.
- CONFIGURATION: Twig overrides section points to USAGE (single source of truth).

### Changed

- Demo / fixture `reference.php` refresh (php-cs-fixer).

## [1.0.5] - 2026-07-29

### Added

- Named Symfony assets package `nowo_tag_input` (`base_path` `/bundles/nowotaginput`) registered via DI `prepend` (REQ-ASSETS-004); require `symfony/asset`.
- `make demo-smoke` / per-demo `make verify` (HTTP 200) and `.github/workflows/demo-smoke.yml` (REQ-TEST-011 / REQ-MAKE-003).
- `make down-dev`; absolute `DOCKER_BIN` in Makefiles (REQ-MAKE-010).
- FrankenPHP-friendly banner under `docs/images/` (REQ-DOCS-017 / DEMO-008).
- PHPStan CI job; explicit `ignoreErrors: []` in `phpstan.neon.dist` (REQ-CS-006).
- **REQ-CS-005:** `nowo-tech/phpstan-frankenphp` in `require-dev` with classic + worker rulesets.

### Changed

- PHPUnit / CI: `SYMFONY_DEPRECATIONS_HELPER=max[direct]=0` (REQ-SF-005).
- `demo/symfony8` image: `dunglas/frankenphp:1-php8.5-alpine` (REQ-DEMO-010).
- Composer keywords: `php`, `frankenphp` (REQ-PKG-004).
- Docs: use `asset('…', 'nowo_tag_input')`; README Documentation order; GitHub About (REQ-DOCS-018).
- Declared FrankenPHP worker mode friendly (was “not supported”).

## [1.0.4] - 2026-07-16

### Added

- [`CODE_OF_CONDUCT.md`](../CODE_OF_CONDUCT.md) (Contributor Covenant) and links from README / Contributing.
- [`docs/GITHUB_CI.md`](GITHUB_CI.md): REQ-GIT-001 (no Cursor co-author trailers in git history).
- Local git hygiene: `.githooks/commit-msg`, `.scripts/check-no-cursor-coauthor.sh`, `.scripts/strip-cursor-coauthor-from-history.sh`, Cursor rule `.cursor/rules/01-git-commits.mdc`.
- CI job `git-hygiene` and Makefile targets `setup-hooks`, `check-no-cursor-coauthor`, `strip-cursor-coauthor-from-history` (wired into `release-check`).

### Changed

- Contributor toolchain (dev only): `@types/node` **26.1.1**.
- Composer lock refresh: `friendsofphp/php-cs-fixer` **v3.95.15**, `rector/rector` **2.5.7**; demo locks updated for `nowo-tech/twig-inspector-bundle` **v1.0.36**.
- [`docs/CONTRIBUTING.md`](CONTRIBUTING.md) and [`docs/RELEASE.md`](RELEASE.md): document REQ-GIT-001 hooks and pre-push co-author check.

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

[Unreleased]: https://github.com/nowo-tech/TagInputBundle/compare/v1.1.1...HEAD
[1.0.6]: https://github.com/nowo-tech/TagInputBundle/compare/v1.0.5...v1.0.6
[1.0.5]: https://github.com/nowo-tech/TagInputBundle/compare/v1.0.4...v1.0.5
[1.0.4]: https://github.com/nowo-tech/TagInputBundle/releases/tag/v1.0.4
[1.0.3]: https://github.com/nowo-tech/TagInputBundle/releases/tag/v1.0.3
[1.0.2]: https://github.com/nowo-tech/TagInputBundle/releases/tag/v1.0.2
[1.0.1]: https://github.com/nowo-tech/TagInputBundle/releases/tag/v1.0.1
[1.0.0]: https://github.com/nowo-tech/TagInputBundle/releases/tag/v1.0.0

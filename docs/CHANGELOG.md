# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/).

## Table of contents

- [[Unreleased]](#unreleased)
- [[1.5.3] - 2026-10-25](#153---2026-10-25)
- [[1.5.2] - 2026-10-20](#152---2026-10-20)
- [[1.5.1] - 2026-10-18](#151---2026-10-18)
- [[1.5.0] - 2026-10-13](#150---2026-10-13)
- [[1.4.0] - 2026-10-13](#140---2026-10-13)
- [[1.3.2] - 2026-10-13](#132---2026-10-13)
- [[1.3.1] - 2026-10-09](#131---2026-10-09)
- [[1.3.0] - 2026-09-28](#130---2026-09-28)
- [[1.2.2] - 2026-09-27](#122---2026-09-27)
- [[1.2.1] - 2026-09-25](#121---2026-09-25)
- [[1.2.0] - 2026-09-03](#120---2026-09-03)
- [[1.1.3] - 2026-08-24](#113---2026-08-24)
- [[1.1.2] - 2026-08-19](#112---2026-08-19)
- [[1.1.1] - 2026-08-18](#111---2026-08-18)
- [[1.1.0] - 2026-08-04](#110---2026-08-04)
- [[1.0.6] - 2026-07-30](#106---2026-07-30)
- [[1.0.5] - 2026-07-29](#105---2026-07-29)
- [[1.0.4] - 2026-07-16](#104---2026-07-16)
- [[1.0.3] - 2026-07-13](#103---2026-07-13)
- [[1.0.2] - 2026-07-09](#102---2026-07-09)
- [[1.0.1] - 2026-07-05](#101---2026-07-05)
- [[1.0.0] - 2026-07-05](#100---2026-07-05)

## [Unreleased]

### Changed

- Docs: USAGE states that host and demo forms are Symfony Form Types rendered with `form_start` / the `not child.rendered` child loop / `form_end`, and that raw `<form>` / `<input>` must not be reintroduced; `check-no-raw-html-form` enforces it in `release-check` (REQ-TWIG-005 rule 9).
- Tests: TypeScript coverage now measures every shipped source (including `logger.ts`) and reaches 100% lines (98.66% branches); `vitest.config.ts` enforces thresholds and CI runs a new `frontend` job (Vitest coverage gate + build) (REQ-TEST-003).
- Tests: `composer test-coverage-100` (`.scripts/check-coverage.php`) fails below 100% Clover coverage; `make test-coverage` and the CI coverage job use it, and `make test-coverage` / `make test-ts` keep the test exit status instead of losing it in a pipe (REQ-TEST-003).
- Tooling: `make validate-translations` runs `yaml-lint` plus `.scripts/validate-translations.php` (catalogue naming, 7 required locales, non-empty messages, key parity against `en`) and is part of `release-check` (REQ-MAKE-004).
- Demo: the Symfony 8 demo port is 8073 everywhere (Compose fallback, `.env.test`, `DEFAULT_URI`, Makefiles, docs); Compose passes `PORT` into the container and lets `.env` override `APP_ENV` / `APP_DEBUG` (REQ-DEMO-005).
- Demo: e2e Playwright image aligned with the locked `@playwright/test` 1.64.1.
- Docs: `docs/DEMO-FRANKENPHP.md` documents the FrankenPHP files, starting the demo in development and in production mode, and a troubleshooting table (REQ-DEMO-002); README test coverage figures refreshed (REQ-TEST-007).

## [1.5.3] - 2026-10-25

Reproducible JS bundle: the build stamp is a hash of the sources instead of the build time. See [UPGRADING](UPGRADING.md#to-153).

### Changed

- **Assets:** reproducible bundle build. `__TAG_INPUT_BUILD_TIME__` now holds a build id (a hash of the TypeScript/CSS sources and `pnpm-lock.yaml`) instead of the build time, so rebuilding unchanged sources (`make assets`, `make release-check`) no longer rewrites the committed JS; the console line reads `script loaded, build: <id>`.
- Developer tooling: `make release-check` runs the full REQ-MAKE-002 chain with `ensure-up` first, then the
  Cursor co-author, new `check-open-prs` (REQ-REL-003), Twig `extra` and new `check-no-raw-html-form` (REQ-TWIG-005)
  gates, composer-sync, cs-fix, cs-check, rector-dry, phpstan, igor, test-coverage, the demo release-check and test-ts.
- Demo: `tag_demo/show.html.twig` renders the form body through the child loop (`{% for child in form %}` +
  `not child.rendered` + `form_row(child)`) instead of `form_row(form.tags)`, per REQ-TWIG-003.

[1.5.3]: https://github.com/nowo-tech/TagInputBundle/releases/tag/v1.5.3

## [1.5.2] - 2026-10-20

Dependency refresh (bundle and demos), php-cs-fixer ParallelConfig(2). See [UPGRADING](UPGRADING.md#to-152).

### Changed

- Dependencies refreshed (Composer and pnpm/npm lock files of the bundle and its demos, campaign 2026-10-19); shipped assets rebuilt.
- `.php-cs-fixer.dist.php`: `new ParallelConfig(2)` (REQ-CS-001).

### Documentation

- Documentation: wave-1 REQ compliance — README section order (Quick usage before Version information, License section), canonical FrankenPHP claim, documentation list order (extras under Additional documentation), CONTRIBUTING Cursor Agent queue settings (REQ-IDE-006), demo compose project name `tag-input-bundle-demo-symfony-8`, tables of contents on long docs, Spec Kit inventory refresh (34/34, `TagPattern`).

[1.5.2]: https://github.com/nowo-tech/TagInputBundle/releases/tag/v1.5.2

## [1.5.1] - 2026-10-18

Placeholder translated once (no missing message). See [UPGRADING](UPGRADING.md#to-151).

### Fixed

- **Placeholder translated twice (profiler missing message):** `TagType` put the already translated placeholder in
  the `placeholder` attribute, which the form theme translates again in the field domain ("Add tags and press Enter"
  logged as missing in `NowoTagInputBundle`). The attribute now carries the untranslated option (translated once by
  the theme); the frontend data attribute `data-nowo-tag-input-placeholder-value` stays translated by the type.
  Without a translator, or with `translation_domain: false`, both keep the previous values. Test rendering the widget
  with a `DataCollectorTranslator` (en / es) asserting no missing message; `symfony/translation` added to require-dev.

[1.5.1]: https://github.com/nowo-tech/TagInputBundle/releases/tag/v1.5.1

## [1.5.0] - 2026-10-13

### Fixed

- The shipped `tag.placeholder` translations are now used: `TagType` translates the `placeholder` option with the field `translation_domain` before writing the `placeholder` and `data-nowo-tag-input-placeholder-value` attributes (the frontend reads the data attribute, which Twig never translated), and `nowo_tag_input.placeholder` defaults to `tag.placeholder`.
- CI/tests: the Symfony 6.4 cell now pins every Symfony component of the graph (incl. transitive `symfony/console`, `symfony/validator`, ...) to the cell constraint and fails if any component escapes the cell major; on PHP 8.4/8.5 it previously mixed `symfony/form` 6.4 with 8.x components and died on incompatible signatures.

### Changed

- Minimum Symfony version is now 6.4 (`^6.4 || ^7.0 || ^8.0`), matching what CI tests; a Symfony 6.4 cell was added to the CI matrix.
- Development: `vitest` and `@vitest/coverage-v8` upgraded to 4.x.

[1.5.0]: https://github.com/nowo-tech/TagInputBundle/releases/tag/v1.5.0

## [1.4.0] - 2026-10-13

### Security

- `pattern` is now compiled with an escaped delimiter and the `u` flag: a `/` in the pattern no longer breaks validation (or injects modifiers), and multibyte tags are matched per character. Invalid patterns are rejected in the bundle configuration and by the `TagType` `pattern` option instead of failing on submit.
- `max_tags`: submission processing stops at the first tag over the limit instead of validating the whole payload first.

### Fixed

- Numeric `whitelist` entries (e.g. `[1, 2]` from YAML) now match submitted tags; the whitelist is compared as strings (`TagType` normalizes the option to strings).
- docs: `max_tags` default is `50`, not `null`.

### Changed

- `form_theme` is now an enum of the supported layouts; an unknown value fails at configuration load instead of silently falling back to `form_div_layout.html.twig`.

[1.4.0]: https://github.com/nowo-tech/TagInputBundle/releases/tag/v1.4.0

## [1.3.2] - 2026-10-13

### Changed

- Development: `composer.json` pins `config.platform.php` to 8.2.0 so the committed lock stays installable on the minimum PHP; CI overrides the platform per matrix cell.
- Repository: `.twig-cs-fixer.cache` is now git-ignored.

### Dependencies

- `@types/node` 26.6.4 → 26.6.5 (frontend tooling; #56).
- `vite` 8.3.2 → 8.3.4 (frontend tooling; #57).
- `happy-dom` 20.14.5 → 20.14.6 (frontend tooling; #58).

[1.3.2]: https://github.com/nowo-tech/TagInputBundle/releases/tag/v1.3.2

## [1.3.1] - 2026-10-09

### Changed

- Rebuilt `tag-input.js` / `tag-input.css` with `@yaireo/tagify` 4.39.0 and Vite 8.3.2 (published assets were still built from tagify 4.38).

### Fixed

- `pnpm-lock.yaml` `vite` specifier synced with `package.json` (`^8.3.2`) so frozen-lockfile installs work again.

### Dependencies

- `igor-php/igor-php` require-dev constraint bumped to `^0.10.0`; npm `@yaireo/tagify` 4.39.0, `vite` 8.3.2, `@types/node` 26.6.4 (Dependabot).
- Dev lock refreshed: Symfony 7.4.20, Twig 3.30.0, PHPStan 2.3.1, `phpstan/phpstan-symfony` 2.1.0, Rector 2.7.0, `nowo-tech/phpstan-frankenphp` 1.2.3.
- Demo `symfony8`: Symfony 8.1.8, Twig 3.30.0.

[1.3.1]: https://github.com/nowo-tech/TagInputBundle/releases/tag/v1.3.1

## [1.3.0] - 2026-09-28

### Security

- Default `max_tags` is **50**; overflow on submit raises `TransformationFailedException` (fail-closed).

### Added

- **REQ-DEMO-013:** Playwright e2e under `demo/symfony8/e2e/` (`make test-e2e`), `demo-screenshots` target, and README gallery with full demo context (navbar + tag card + WebProfiler; `docs/images/demo/overview.png`, `interaction.png`).

### Changed

- **Demo screenshots:** capture navbar + main + profiler instead of a card-only crop.

## [1.2.2] - 2026-09-27

### Added

- **REQ-CS-008:** `igor-php/igor-php` (require-dev only), root `igor.json`, Composer/`Makefile` `igor` target, and `release-check` wiring for FrankenPHP worker-state audit.

[1.3.0]: https://github.com/nowo-tech/TagInputBundle/releases/tag/v1.3.0
[1.2.2]: https://github.com/nowo-tech/TagInputBundle/releases/tag/v1.2.2

## [1.2.1] - 2026-09-25

### Added

- FrankenPHP worker audit for sticky Kernel (`FRANKENPHP_RESET_KERNEL=false`): [FRANKENPHP-WORKER-AUDIT.md](FRANKENPHP-WORKER-AUDIT.md) — verdict **100% compatible** (Scenario B).
- Unit regression `TagTypeTest::testSharedInstanceDoesNotLeakOptionsAcrossConsecutiveBuilds` (REQ-FP-001).

### Changed

- PHPStan: `ruleset-classic` + `ruleset-worker-strict` + `ruleset-hardening` (was classic + worker).
- README / demo docs: claim **100% compatible** with worker + `reset_kernel: false`.
- Specs / code inventory: Web Component sources (`nowo-tag-input-element.ts`, `tag-input-lib.ts`) and REQ-FP-001.
- Contributor toolchain (dev only): Dependabot bumps since `1.2.0` (`@types/node`, `happy-dom`, `vite`, `rector`, `phpstan`, `php-cs-fixer`, `twig-cs-fixer`, `twig/extra-bundle`).

### Notes

- **No API or configuration changes** for integrators. Hosts may run FrankenPHP workers with `FRANKENPHP_RESET_KERNEL=false` without bundle-specific reset hooks.

[1.2.1]: https://github.com/nowo-tech/TagInputBundle/releases/tag/v1.2.1

## [1.2.0] - 2026-09-03

### Changed

- **Web Component:** the form theme now renders `<nowo-tag-input>` (light DOM). `tag-input.js` defines the custom element and still initializes fields with `data-controller="nowo-tag-input"`.
- **Deps (dev):** bump `rector/rector`, `friendsofphp/php-cs-fixer`, `@types/node`, `happy-dom`; refresh Composer lockfiles.

### Notes

- Integrators: keep the same `tag-input.js` asset. Custom theme overrides should use `<nowo-tag-input>` as the outer host.

[1.2.0]: https://github.com/nowo-tech/TagInputBundle/releases/tag/v1.2.0

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

[Unreleased]: https://github.com/nowo-tech/TagInputBundle/compare/v1.5.3...HEAD
[1.0.6]: https://github.com/nowo-tech/TagInputBundle/compare/v1.0.5...v1.0.6
[1.0.5]: https://github.com/nowo-tech/TagInputBundle/compare/v1.0.4...v1.0.5
[1.0.4]: https://github.com/nowo-tech/TagInputBundle/releases/tag/v1.0.4
[1.0.3]: https://github.com/nowo-tech/TagInputBundle/releases/tag/v1.0.3
[1.0.2]: https://github.com/nowo-tech/TagInputBundle/releases/tag/v1.0.2
[1.0.1]: https://github.com/nowo-tech/TagInputBundle/releases/tag/v1.0.1
[1.0.0]: https://github.com/nowo-tech/TagInputBundle/releases/tag/v1.0.0

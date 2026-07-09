# Feature Specification: TagInputBundle baseline (100% code coverage)

**Feature Branch**: `001-baseline`  
**Created**: 2026-07-07  
**Status**: Active  
**Input**: Backfill GitHub Spec Kit baseline documenting 100% of production code in `src/`.

**Related docs**: [`docs/SPEC-DRIVEN-DEVELOPMENT.md`](../../docs/SPEC-DRIVEN-DEVELOPMENT.md), [`docs/CONFIGURATION.md`](../../docs/CONFIGURATION.md), [`docs/USAGE.md`](../../docs/USAGE.md)  
**Code inventory (traceability)**: [`code-inventory.md`](code-inventory.md)

---

## User Scenarios & Testing

### User Story 1 — Tag-style multi input (Priority: P1)

As a form author, I add `TagType` to a Symfony form so users enter keywords, labels, or emails as removable chips instead of raw comma text.

**Independent Test**: Render a form with `TagType`, type tokens separated by Enter, submit — model receives `array<string>` (default) or comma-separated string when `value_format=string`.

**Acceptance Scenarios**:

1. **Given** default config, **When** the field submits `['php', 'symfony']`, **Then** `TagsToValueTransformer` normalizes, trims, and validates against `max_tags`, `pattern`, and `whitelist`.
2. **Given** `duplicates=false`, **When** user adds the same tag twice, **Then** Tagify rejects the duplicate client-side and transformer deduplicates on submit.
3. **Given** `value_format=string`, **When** form binds, **Then** view displays comma-joined value and model receives a single string.

---

### User Story 2 — Configure defaults globally (Priority: P2)

As an integrator, I set bundle-wide defaults (`value_format`, `max_tags`, `whitelist`, `placeholder`, `form_theme`) in YAML so individual fields inherit sensible options.

**Acceptance Scenarios**:

1. **Given** `config/packages/nowo_tag_input.yaml`, **When** extension loads, **Then** `NowoTagInputExtension` injects defaults into the `TagType` service constructor.
2. **Given** per-field options override globals, **When** form builds, **Then** field options win over bundle defaults.

---

### User Story 3 — Theme and assets (Priority: P2)

As an integrator, I pick a Symfony form theme matching my CSS framework and load bundled Tagify assets.

**Acceptance Scenarios**:

1. **Given** `form_theme` config or `{% form_theme form '...' %}`, **When** field renders, **Then** matching Twig block outputs Tagify markup and `data-nowo-tag-input-*` Stimulus values.
2. **Given** `assets:install` ran, **When** layout includes `bundles/nowotaginput/tag-input.js` + `.css`, **Then** Tagify initializes on fields with `data-controller="nowo-tag-input"`.

---

### Edge Cases

- Empty submission: transformer returns `[]` or `''` per format.
- Invalid whitelist JSON in data attribute: widget logs warning and skips whitelist.
- Invalid regex pattern: widget logs warning and skips pattern validation.
- Disabled field: Tagify not initialized; value preserved.
- Legacy `Resources/public/` assets supported when Vite build not used.

---

## Requirements

### Bundle & DI

- **FR-BUNDLE-001**: `NowoTagInputBundle` MUST register `TwigPathsPass` and expose alias `nowo_tag_input` via `NowoTagInputExtension`.
- **FR-DI-001**: `services.yaml` MUST autowire `TagType` with constructor defaults from `%nowo_tag_input.*%` parameters.
- **FR-CFG-001**: `Configuration` MUST define `nowo_tag_input` keys: `value_format`, `trim`, `pattern`, `whitelist`, `duplicates`, `max_tags`, `dropdown_enabled`, `placeholder`, `form_theme`.
- **FR-CFG-002**: Extension MUST load `services.yaml` and map config into TagType factory arguments.
- **FR-TWIG-001**: `TwigPathsPass` MUST `addPath()` for `Resources/views` under namespace `NowoTagInputBundle`.

### Form layer

- **FR-FORM-001**: `TagType` MUST extend `TextType`, expose options (`container_class`, `input_class`, `placeholder`, `value_format`, `trim`, `pattern`, `whitelist`, `duplicates`, `max_tags`, `dropdown_enabled`), attach `TagsToValueTransformer`, and set Stimulus/data attributes on the view.
- **FR-FORM-002**: `TagsToValueTransformer` MUST convert between view string and model (`array<string>` or comma-separated string), applying trim, pattern, whitelist, duplicate, and max-tag rules.
- **FR-FORM-003**: `ValueFormat` enum MUST define `array` and `string` backed cases used by transformer and config.

### Form themes

- **FR-THEME-001**: Base `tag_input_theme.html.twig` MUST render Tagify widget blocks for `TagType`.
- **FR-THEME-002**: Framework variants (`bootstrap3/4/5`, horizontal layouts, `foundation5/6`, `table`, `tailwind2`) MUST extend base blocks with framework-specific CSS classes.

### Frontend widget

- **FR-UI-001**: `tag-input.ts` MUST scan inputs with `data-controller` containing `nowo-tag-input`, parse data attributes into Tagify settings (maxTags, whitelist, pattern, duplicates, dropdown, placeholder), and sync Tagify value back to the native input on change.
- **FR-UI-002**: `tag-input.css` MUST style Tagify container to align with bundle form themes.
- **FR-UI-003**: `logger.ts` MUST provide namespaced debug logging gated by build-time flags.

### Legacy & i18n

- **FR-LEGACY-001**: `Resources/public/tag-input.js` and `tag-input.css` MUST remain publishable fallbacks for apps not consuming Vite output.
- **FR-I18N-001**: Translation files (`de`, `en`, `es`, `fr`, `it`, `nl`, `pt`) MUST supply validator/label strings for `NowoTagInputBundle` domain.

### Build

- **FR-BUILD-001**: Vite pipeline MUST emit `Resources/public/tag-input.js` and `tag-input.css` from `Resources/assets/src/`; maintainers run build before release when sources change.

---

## Key Entities

- **ValueFormat**: `array` | `string` — persisted model shape for `TagType`.
- **TagifySettings** (frontend): parsed from `data-nowo-tag-input-*` attributes driving widget behavior.

---

## Success Criteria

- **SC-001**: 100% of production files in `src/` appear in [`code-inventory.md`](code-inventory.md) with requirement IDs (31/31 mapped; `*.test.ts` excluded).
- **SC-002**: Documented config keys match `Configuration.php`.
- **SC-003**: `composer qa` passes (PHPUnit, PHPStan, Vitest).
- **SC-004**: Tag field round-trips array and string formats in demo apps.

---

## Assumptions

- Integrators run `assets:install` and include bundle JS/CSS in layout or form template.
- Tagify is bundled via npm; consumers do not install Tagify separately.
- Demos under `demo/` illustrate integration but are not Packagist API.

---

## Explicit non-goals

- Server-side autocomplete API (whitelist is static config only).
- Tag persistence as separate entities (value is plain string/array on the form model).
- Production use without loading bundle assets.

---

## Validation

| Check | Command |
| --- | --- |
| Full QA | `composer qa` or `make release-check` |
| PHP tests | `vendor/bin/phpunit` |
| TS tests | `pnpm test` |
| Inventory | Row count matches `find src -type f ! -name '*.test.ts'` |

When changing behavior, update this spec, `code-inventory.md`, tests, and integrator docs.

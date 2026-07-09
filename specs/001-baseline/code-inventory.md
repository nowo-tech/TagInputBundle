# Code inventory — 100% traceability

**Baseline spec**: [`spec.md`](spec.md)  
**Package**: `nowo-tech/tag-input-bundle`  
**Last audited**: 2026-07-07

This file proves that **every production source artifact** under `src/` is referenced by the baseline specification. Vitest sources (`*.test.ts`) and demo trees are out of Packagist scope.

## PHP classes (`src/**/*.php`)

| Source file | Spec section | Requirement IDs |
| --- | --- | --- |
| `NowoTagInputBundle.php` | Bundle entry | FR-BUNDLE-001 |
| `DependencyInjection/Configuration.php` | Config tree | FR-CFG-001 |
| `DependencyInjection/NowoTagInputExtension.php` | DI extension | FR-CFG-002 |
| `DependencyInjection/Compiler/TwigPathsPass.php` | Twig namespace path | FR-TWIG-001 |
| `Form/TagType.php` | Tag form type | FR-FORM-001 |
| `Form/DataTransformer/TagsToValueTransformer.php` | Model/view transform | FR-FORM-002 |
| `Form/ValueFormat.php` | Value format enum | FR-FORM-003 |

## TypeScript & CSS production (`src/Resources/assets/src/`)

| Source file | Spec section | Requirement IDs |
| --- | --- | --- |
| `tag-input.ts` | Tagify widget init | FR-UI-001 |
| `tag-input.css` | Widget styling | FR-UI-002 |
| `logger.ts` | Debug logging | FR-UI-003 |

## Legacy JavaScript & CSS (`src/Resources/public/`)

| Source file | Spec section | Requirement IDs |
| --- | --- | --- |
| `tag-input.js` | Legacy/built widget | FR-LEGACY-001, FR-BUILD-001 |
| `tag-input.css` | Legacy/built styles | FR-LEGACY-001, FR-BUILD-001 |

## Symfony config (`src/Resources/config/`)

| Source file | Spec section | Requirement IDs |
| --- | --- | --- |
| `services.yaml` | Service wiring | FR-DI-001 |

## Twig form themes (`src/Resources/views/Form/`)

| Source file | Spec section | Requirement IDs |
| --- | --- | --- |
| `tag_input_theme.html.twig` | Base theme | FR-THEME-001 |
| `tag_input_theme_bootstrap3.html.twig` | Bootstrap 3 variant | FR-THEME-002 |
| `tag_input_theme_bootstrap3_horizontal.html.twig` | Bootstrap 3 horizontal | FR-THEME-002 |
| `tag_input_theme_bootstrap4.html.twig` | Bootstrap 4 variant | FR-THEME-002 |
| `tag_input_theme_bootstrap4_horizontal.html.twig` | Bootstrap 4 horizontal | FR-THEME-002 |
| `tag_input_theme_bootstrap5.html.twig` | Bootstrap 5 variant | FR-THEME-002 |
| `tag_input_theme_bootstrap5_horizontal.html.twig` | Bootstrap 5 horizontal | FR-THEME-002 |
| `tag_input_theme_foundation5.html.twig` | Foundation 5 variant | FR-THEME-002 |
| `tag_input_theme_foundation6.html.twig` | Foundation 6 variant | FR-THEME-002 |
| `tag_input_theme_table.html.twig` | Table layout variant | FR-THEME-002 |
| `tag_input_theme_tailwind2.html.twig` | Tailwind 2 variant | FR-THEME-002 |

## Translations (`src/Resources/translations/`)

| Source file | Spec section | Requirement IDs |
| --- | --- | --- |
| `NowoTagInputBundle.de.yaml` | German catalog | FR-I18N-001 |
| `NowoTagInputBundle.en.yaml` | English catalog | FR-I18N-001 |
| `NowoTagInputBundle.es.yaml` | Spanish catalog | FR-I18N-001 |
| `NowoTagInputBundle.fr.yaml` | French catalog | FR-I18N-001 |
| `NowoTagInputBundle.it.yaml` | Italian catalog | FR-I18N-001 |
| `NowoTagInputBundle.nl.yaml` | Dutch catalog | FR-I18N-001 |
| `NowoTagInputBundle.pt.yaml` | Portuguese catalog | FR-I18N-001 |

## Coverage summary

| Category | Files | Mapped |
| --- | ---: | ---: |
| PHP classes | 7 | 7 |
| TS/CSS production | 3 | 3 |
| Legacy JS/CSS | 2 | 2 |
| YAML config | 1 | 1 |
| Twig themes | 11 | 11 |
| Translations | 7 | 7 |
| **Total production sources** | **31** | **31** |

Excluded from count: `Resources/assets/src/tag-input.test.ts` (Vitest only).

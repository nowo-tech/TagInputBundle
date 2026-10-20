# Configuration

- [Options](#options)
- [form_theme](#form_theme)
- [Translations](#translations)
- [Twig overrides](#twig-overrides)

```yaml
# config/packages/nowo_tag_input.yaml
nowo_tag_input:
  value_format: array
  trim: true
  duplicates: false
  dropdown_enabled: true
  placeholder: 'tag.placeholder'
  form_theme: 'bootstrap_5_layout.html.twig'
```

## Table of contents

- [Options](#options)
- [form_theme](#form_theme)
- [Translations](#translations)
  - [How to override (application)](#how-to-override-application)
- [Twig overrides](#twig-overrides)

## Options

| Key | Default | Description |
|-----|---------|-------------|
| `value_format` | `array` | Model format: `array` or `string` (comma-separated) |
| `trim` | `true` | Trim whitespace from each tag |
| `pattern` | `null` | Optional regex source without delimiters (a `/` needs no escaping); matched with the PCRE `u` (UTF-8) flag on the server and as a JS `RegExp` in Tagify. Must compile, otherwise configuration / form creation fails |
| `whitelist` | `[]` | Allowed tag values (numbers are compared as strings) |
| `duplicates` | `false` | Allow duplicate tags |
| `max_tags` | `50` | Maximum number of tags accepted on submit (fail-closed: the submission fails as soon as the limit is exceeded) |
| `dropdown_enabled` | `true` | Enable Tagify dropdown for whitelist |
| `placeholder` | `tag.placeholder` | Default placeholder: a translation key or literal text, translated with the field `translation_domain` (default `NowoTagInputBundle`) before it reaches the `placeholder` and `data-nowo-tag-input-placeholder-value` attributes. `''` disables it. Without a translator service the bundled key renders no placeholder |
| `form_theme` | `form_div_layout.html.twig` | Twig form theme base layout |

## form_theme

Supported values (any other value is rejected when the configuration is loaded):

- `form_div_layout.html.twig`
- `form_table_layout.html.twig`
- `bootstrap_5_layout.html.twig`
- `bootstrap_5_horizontal_layout.html.twig`
- `bootstrap_4_layout.html.twig`
- `bootstrap_4_horizontal_layout.html.twig`
- `bootstrap_3_layout.html.twig`
- `bootstrap_3_horizontal_layout.html.twig`
- `foundation_5_layout.html.twig`
- `foundation_6_layout.html.twig`
- `tailwind_2_layout.html.twig`

## Translations

Translation domain: **`NowoTagInputBundle`** (CamelCase, matching the bundle name).

The bundle ships YAML files for `en`, `es`, `de`, `fr`, `it`, `nl`, and `pt` under `src/Resources/translations/`. Symfony loads application translations first; missing keys fall back to the bundle.

### How to override (application)

1. Use the same domain: `NowoTagInputBundle`.
2. Create a file in your application:
   - `translations/NowoTagInputBundle.<locale>.yaml` (or `.xlf` if your project uses XLF).
3. Override only the keys you need. Keys not defined in the app file use the bundle default.

Example — Spanish override:

```yaml
# translations/NowoTagInputBundle.es.yaml
tag:
  placeholder: 'Añade etiquetas y pulsa Enter'
```

4. Clear the Symfony cache in dev if translations do not appear: `php bin/console cache:clear`.

`TagType` uses `translation_domain: NowoTagInputBundle` by default. Override per field with the standard Symfony `translation_domain` form option.

## Twig overrides

**REQ-TWIG-001.** Namespace: **`NowoTagInputBundle`**. Full procedure, freeze rule, and the overridable `<subpath>` table: [USAGE.md — Overriding templates](USAGE.md#overriding-templates-req-twig-001). Theme selection follows `form_theme` — [form_theme](#form_theme).

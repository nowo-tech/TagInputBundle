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
  placeholder: ''
  form_theme: 'bootstrap_5_layout.html.twig'
```

## Options

| Key | Default | Description |
|-----|---------|-------------|
| `value_format` | `array` | Model format: `array` or `string` (comma-separated) |
| `trim` | `true` | Trim whitespace from each tag |
| `pattern` | `null` | Optional regex pattern (without delimiters) |
| `whitelist` | `[]` | Allowed tag values |
| `duplicates` | `false` | Allow duplicate tags |
| `max_tags` | `null` | Maximum number of tags |
| `dropdown_enabled` | `true` | Enable Tagify dropdown for whitelist |
| `placeholder` | `''` | Default placeholder |
| `form_theme` | `form_div_layout.html.twig` | Twig form theme base layout |

## form_theme

Supported values:

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

The bundle ships YAML files for `en` and `es` under `src/Resources/translations/`. Symfony loads application translations first; missing keys fall back to the bundle.

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

Application templates under `templates/bundles/NowoTagInputBundle/` **always win** over the copies inside the package. The bundle registers paths via `TwigPathsPass` so Symfony resolves app overrides first.

| Bundle path | Override in your app |
|-------------|----------------------|
| `@NowoTagInputBundle/Form/tag_input_theme.html.twig` | `templates/bundles/NowoTagInputBundle/Form/tag_input_theme.html.twig` |
| `@NowoTagInputBundle/Form/tag_input_theme_bootstrap5.html.twig` | `templates/bundles/NowoTagInputBundle/Form/tag_input_theme_bootstrap5.html.twig` |
| `@NowoTagInputBundle/Form/tag_input_theme_bootstrap4.html.twig` | `templates/bundles/NowoTagInputBundle/Form/tag_input_theme_bootstrap4.html.twig` |
| `@NowoTagInputBundle/Form/tag_input_theme_bootstrap3.html.twig` | `templates/bundles/NowoTagInputBundle/Form/tag_input_theme_bootstrap3.html.twig` |
| `@NowoTagInputBundle/Form/tag_input_theme_table.html.twig` | `templates/bundles/NowoTagInputBundle/Form/tag_input_theme_table.html.twig` |
| `@NowoTagInputBundle/Form/tag_input_theme_foundation5.html.twig` | `templates/bundles/NowoTagInputBundle/Form/tag_input_theme_foundation5.html.twig` |
| `@NowoTagInputBundle/Form/tag_input_theme_foundation6.html.twig` | `templates/bundles/NowoTagInputBundle/Form/tag_input_theme_foundation6.html.twig` |
| `@NowoTagInputBundle/Form/tag_input_theme_tailwind2.html.twig` | `templates/bundles/NowoTagInputBundle/Form/tag_input_theme_tailwind2.html.twig` |

Horizontal Bootstrap variants follow the same pattern (`tag_input_theme_bootstrap5_horizontal.html.twig`, etc.).

Theme selection follows `form_theme` in `config/packages/nowo_tag_input.yaml`; override the row that matches your active layout.

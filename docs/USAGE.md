# Usage

## Table of contents

- [Form type](#form-type)
- [Overriding templates (REQ-TWIG-001)](#overriding-templates-req-twig-001)
- [Translations](#translations)
- [Assets](#assets)
- [Customization](#customization)

## Form type

Use `TagType` in any Symfony form:

```php
use Nowo\TagInputBundle\Form\TagType;
use Nowo\TagInputBundle\Form\ValueFormat;

$builder->add('tags', TagType::class, [
    'placeholder' => 'Add tags and press Enter',
    'max_tags' => 10,
    'whitelist' => ['php', 'symfony', 'twig'],
    'input_class' => 'form-control',
]);
```

The field value is an array of strings by default, for example `['php', 'symfony']`.

Use `value_format => ValueFormat::STRING` to receive a comma-separated string instead.

## Overriding templates (REQ-TWIG-001)

The bundle registers the Twig namespace **`@NowoTagInputBundle/`**. Application files under **`templates/bundles/NowoTagInputBundle/`** **always win** over the copies inside the package (`TwigPathsPass` adds the bundle views path after application paths so your copies are tried first).

**Freeze rule:** a full-file override hides vendor updates for that `<subpath>` until you delete or manually merge it. Prefer selecting the matching form theme via **`nowo_tag_input.form_theme`** (see [CONFIGURATION.md](CONFIGURATION.md#form_theme)) and only override the one theme file you customise.

**Procedure**

1. Identify the `<subpath>` from the table below (path relative to `src/Resources/views/`).
2. Create in your application: `templates/bundles/NowoTagInputBundle/<subpath>` (same relative path and filename).
3. Clear the cache in dev if needed: `php bin/console cache:clear`.

Example — override the default form theme:

```text
templates/bundles/NowoTagInputBundle/Form/tag_input_theme.html.twig
```

Logical names look like `@NowoTagInputBundle/Form/tag_input_theme.html.twig`.

**Overridable templates**

| Subpath | Purpose |
| --- | --- |
| `Form/tag_input_theme.html.twig` | Default form theme (`form_div_layout`) |
| `Form/tag_input_theme_table.html.twig` | Table form layout |
| `Form/tag_input_theme_bootstrap3.html.twig` | Bootstrap 3 |
| `Form/tag_input_theme_bootstrap3_horizontal.html.twig` | Bootstrap 3 horizontal |
| `Form/tag_input_theme_bootstrap4.html.twig` | Bootstrap 4 |
| `Form/tag_input_theme_bootstrap4_horizontal.html.twig` | Bootstrap 4 horizontal |
| `Form/tag_input_theme_bootstrap5.html.twig` | Bootstrap 5 |
| `Form/tag_input_theme_bootstrap5_horizontal.html.twig` | Bootstrap 5 horizontal |
| `Form/tag_input_theme_foundation5.html.twig` | Foundation 5 |
| `Form/tag_input_theme_foundation6.html.twig` | Foundation 6 |
| `Form/tag_input_theme_tailwind2.html.twig` | Tailwind 2 |

Pick the row that matches `form_theme` in `config/packages/nowo_tag_input.yaml` (or your app `twig.form_themes`).

## Translations

Override translation keys from your application. See [Configuration — Translations](CONFIGURATION.md#translations).

## Assets

Publish and include the bundle assets:

```bash
php bin/console assets:install public
```

```twig
<link rel="stylesheet" href="{{ asset('tag-input.css', 'nowo_tag_input') }}">
<script src="{{ asset('tag-input.js', 'nowo_tag_input') }}"></script>
```

The widget is `<nowo-tag-input>` (light DOM: native input wrapped by Tagify). Legacy `[data-nowo-tag-container="1"]` hosts still initialize.

## Customization

- `value_format`: `array` (default) or `string`
- `max_tags`: maximum number of tags
- `whitelist`: allowed tag values (enables suggestions)
- `pattern`: regex pattern without delimiters
- `duplicates`: allow duplicate tags
- `dropdown_enabled`: Tagify dropdown for whitelist suggestions
- `placeholder`: input placeholder
- `container_class`, `input_class`: CSS classes

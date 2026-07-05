<?php

declare(strict_types=1);

namespace Nowo\TagInputBundle\Form;

use Nowo\TagInputBundle\Form\DataTransformer\TagsToValueTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

use function is_array;
use function is_string;
use function json_encode;

use const JSON_THROW_ON_ERROR;

/**
 * @extends AbstractType<array<int, string>|string>
 */
final class TagType extends AbstractType
{
    /**
     * @param list<string> $defaultWhitelist
     */
    public function __construct(
        private readonly string $defaultValueFormat = ValueFormat::ARRAY->value,
        private readonly bool $defaultTrim = true,
        private readonly ?string $defaultPattern = null,
        private readonly array $defaultWhitelist = [],
        private readonly bool $defaultDuplicates = false,
        private readonly ?int $defaultMaxTags = null,
        private readonly bool $defaultDropdownEnabled = true,
        private readonly string $defaultPlaceholder = '',
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->addModelTransformer(new TagsToValueTransformer(
            $options['value_format'],
            $options['trim'],
            $options['pattern'],
            $options['whitelist'],
            $options['duplicates'],
            $options['max_tags'],
        ));
    }

    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        $view->vars['tag_container_class'] = $options['container_class'];
        $view->vars['tag_input_class']     = $options['input_class'];
        $view->vars['tag_placeholder']     = $options['placeholder'];
        $view->vars['tag_disabled']        = $options['disabled'];

        $view->vars['attr']['data-controller'] = trim(($view->vars['attr']['data-controller'] ?? '') . ' nowo-tag-input');
        $view->vars['attr']['class']           = trim(($view->vars['attr']['class'] ?? '') . ' ' . $options['input_class']);

        if ($options['max_tags'] !== null) {
            $view->vars['attr']['data-nowo-tag-input-max-tags-value'] = (string) $options['max_tags'];
        }

        if ($options['whitelist'] !== []) {
            $view->vars['attr']['data-nowo-tag-input-whitelist-value'] = json_encode($options['whitelist'], JSON_THROW_ON_ERROR);
        }

        if ($options['pattern'] !== null && $options['pattern'] !== '') {
            $view->vars['attr']['data-nowo-tag-input-pattern-value'] = $options['pattern'];
        }

        $view->vars['attr']['data-nowo-tag-input-duplicates-value']       = $options['duplicates'] ? '1' : '0';
        $view->vars['attr']['data-nowo-tag-input-dropdown-enabled-value'] = $options['dropdown_enabled'] ? '1' : '0';
        $view->vars['attr']['data-nowo-tag-input-placeholder-value']      = $options['placeholder'];
        $view->vars['attr']['placeholder']                                = $options['placeholder'];

        if (!isset($view->vars['value']) || !is_string($view->vars['value'])) {
            $modelData = $form->getData();
            if (is_array($modelData) && $modelData !== []) {
                $view->vars['value'] = json_encode(array_values($modelData), JSON_THROW_ON_ERROR);
            } elseif (is_string($modelData) && $modelData !== '') {
                $tags                = array_map('trim', explode(',', $modelData));
                $view->vars['value'] = json_encode(array_values(array_filter($tags, static fn (string $tag): bool => $tag !== '')), JSON_THROW_ON_ERROR);
            }
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'value_format'       => ValueFormat::from($this->defaultValueFormat),
            'trim'               => $this->defaultTrim,
            'pattern'            => $this->defaultPattern,
            'whitelist'          => $this->defaultWhitelist,
            'duplicates'         => $this->defaultDuplicates,
            'max_tags'           => $this->defaultMaxTags,
            'dropdown_enabled'   => $this->defaultDropdownEnabled,
            'placeholder'        => $this->defaultPlaceholder,
            'container_class'    => 'nowo-tag-input',
            'input_class'        => 'nowo-tag-input__field',
            'empty_data'         => [],
            'required'           => false,
            'translation_domain' => 'NowoTagInputBundle',
        ]);

        $resolver->setAllowedTypes('value_format', [ValueFormat::class, 'string']);
        $resolver->setAllowedValues('value_format', static fn (mixed $value): bool => $value instanceof ValueFormat || ValueFormat::tryFrom((string) $value) !== null);
        $resolver->setAllowedTypes('trim', ['bool']);
        $resolver->setAllowedTypes('pattern', ['null', 'string']);
        $resolver->setAllowedTypes('whitelist', ['array']);
        $resolver->setAllowedTypes('duplicates', ['bool']);
        $resolver->setAllowedTypes('max_tags', ['null', 'int']);
        $resolver->setAllowedTypes('dropdown_enabled', ['bool']);
        $resolver->setAllowedTypes('placeholder', ['string']);
        $resolver->setAllowedTypes('container_class', ['string']);
        $resolver->setAllowedTypes('input_class', ['string']);

        $resolver->setNormalizer('value_format', static function (mixed $options, mixed $value): ValueFormat {
            if ($value instanceof ValueFormat) {
                return $value;
            }

            return ValueFormat::from((string) $value);
        });

        $resolver->setNormalizer('empty_data', static function (mixed $options, mixed $value): array|string {
            $format = $options['value_format'] ?? ValueFormat::ARRAY;

            if ($format instanceof ValueFormat && $format === ValueFormat::STRING) {
                return '';
            }

            if (is_string($format) && ValueFormat::tryFrom($format) === ValueFormat::STRING) {
                return '';
            }

            return [];
        });
    }

    public function getParent(): string
    {
        return TextType::class;
    }

    public function getBlockPrefix(): string
    {
        return 'nowo_tag_input';
    }
}

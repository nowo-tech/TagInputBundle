<?php

declare(strict_types=1);

namespace App\Form;

use Nowo\FormKitBundle\Attribute\FormKitConfig;
use Nowo\FormKitBundle\Form\FormOptionsTrait;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * REQ-DEMO-015: a FormKit form (profile `demo`); the bundle's TagType is added through FormKit's addTagInput().
 *
 * @extends AbstractType<array{tags: array<int, string>|string}>
 */
#[FormKitConfig('demo')]
final class TagDemoType extends AbstractType
{
    use FormOptionsTrait;

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addTagInput($builder, 'tags', [
            ...$options['tag_options'],
            'help' => $options['tag_help'],
            // FormKit's profile sets the `messages` domain; the bundled placeholder (tag.placeholder) lives in its own domain
            'translation_domain' => 'NowoTagInputBundle',
        ]);
        $this->addWithDefaults($builder, 'submit', SubmitType::class, [
            'label' => 'Submit tags',
            'attr'  => ['class' => 'nowo-ui-btn nowo-ui-btn--primary'],
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['tag_options' => [], 'tag_help' => '']);
        $resolver->setAllowedTypes('tag_options', 'array');
        $resolver->setAllowedTypes('tag_help', 'string');
    }
}

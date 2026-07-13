<?php

declare(strict_types=1);

namespace Nowo\TagInputBundle\Tests\Unit\Form;

use Nowo\TagInputBundle\Form\TagType;
use Nowo\TagInputBundle\Form\ValueFormat;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\Forms;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;

/**
 * @covers \Nowo\TagInputBundle\Form\TagType
 */
final class TagTypeTest extends TestCase
{
    public function testSubmitReturnsArrayByDefault(): void
    {
        $factory = Forms::createFormFactoryBuilder()
            ->addType(new TagType())
            ->getFormFactory();

        $form = $factory->create(TagType::class, []);
        $form->submit('["php","symfony"]');

        self::assertTrue($form->isSynchronized());
        self::assertSame(['php', 'symfony'], $form->getData());
    }

    public function testSubmitReturnsCommaSeparatedStringWhenConfigured(): void
    {
        $factory = Forms::createFormFactoryBuilder()
            ->addType(new TagType())
            ->getFormFactory();

        $form = $factory->create(TagType::class, '', [
            'value_format' => ValueFormat::STRING,
        ]);
        $form->submit('[{"value":"php"},{"value":"symfony"}]');

        self::assertTrue($form->isSynchronized());
        self::assertSame('php,symfony', $form->getData());
    }

    public function testBuildViewExposesTagVariablesAndDataAttributes(): void
    {
        $factory = Forms::createFormFactoryBuilder()
            ->addType(new TagType())
            ->getFormFactory();

        $form = $factory->create(TagType::class, ['php'], [
            'max_tags'         => 5,
            'whitelist'        => ['php', 'symfony'],
            'pattern'          => '^[a-z]+$',
            'duplicates'       => true,
            'dropdown_enabled' => false,
            'placeholder'      => 'Add tags',
            'container_class'  => 'custom-container',
            'input_class'      => 'custom-input',
            'attr'             => ['data-controller' => 'existing-controller'],
        ]);

        $view = $form->createView();

        self::assertSame('custom-container', $view->vars['tag_container_class']);
        self::assertSame('custom-input', $view->vars['tag_input_class']);
        self::assertSame('Add tags', $view->vars['tag_placeholder']);
        self::assertSame('existing-controller nowo-tag-input', $view->vars['attr']['data-controller']);
        self::assertSame('5', $view->vars['attr']['data-nowo-tag-input-max-tags-value']);
        self::assertSame('["php","symfony"]', $view->vars['attr']['data-nowo-tag-input-whitelist-value']);
        self::assertSame('^[a-z]+$', $view->vars['attr']['data-nowo-tag-input-pattern-value']);
        self::assertSame('1', $view->vars['attr']['data-nowo-tag-input-duplicates-value']);
        self::assertSame('0', $view->vars['attr']['data-nowo-tag-input-dropdown-enabled-value']);
    }

    public function testGetParentAndBlockPrefix(): void
    {
        $type = new TagType();

        self::assertSame(\Symfony\Component\Form\Extension\Core\Type\TextType::class, $type->getParent());
        self::assertSame('nowo_tag_input', $type->getBlockPrefix());
    }

    public function testBuildViewEncodesInitialArrayValue(): void
    {
        $type               = new TagType();
        $view               = new FormView();
        $view->vars['attr'] = [];

        $form = $this->createMock(FormInterface::class);
        $form->method('getData')->willReturn(['php', 'symfony']);

        $options = [
            'container_class'  => 'container',
            'input_class'      => 'input',
            'placeholder'      => '',
            'max_tags'         => null,
            'whitelist'        => [],
            'pattern'          => null,
            'duplicates'       => false,
            'dropdown_enabled' => true,
            'disabled'         => false,
        ];

        $type->buildView($view, $form, $options);

        self::assertSame('["php","symfony"]', $view->vars['value']);
    }

    public function testBuildViewEncodesInitialStringValue(): void
    {
        $type               = new TagType();
        $view               = new FormView();
        $view->vars['attr'] = [];

        $form = $this->createMock(FormInterface::class);
        $form->method('getData')->willReturn('alpha, beta');

        $options = [
            'container_class'  => 'container',
            'input_class'      => 'input',
            'placeholder'      => '',
            'max_tags'         => null,
            'whitelist'        => [],
            'pattern'          => null,
            'duplicates'       => false,
            'dropdown_enabled' => true,
            'disabled'         => false,
        ];

        $type->buildView($view, $form, $options);

        self::assertSame('["alpha","beta"]', $view->vars['value']);
    }

    public function testValueFormatStringOptionFromString(): void
    {
        $factory = Forms::createFormFactoryBuilder()
            ->addType(new TagType())
            ->getFormFactory();

        $form = $factory->create(TagType::class, '', [
            'value_format' => 'string',
        ]);
        $form->submit('["a","b"]');

        self::assertSame('a,b', $form->getData());
    }

    public function testInvalidValueFormatIsRejected(): void
    {
        $factory = Forms::createFormFactoryBuilder()
            ->addType(new TagType())
            ->getFormFactory();

        $this->expectException(InvalidOptionsException::class);
        $factory->create(TagType::class, [], ['value_format' => 'invalid']);
    }

    public function testEmptyDataNormalizerUsesEmptyStringForStringFormatOption(): void
    {
        $resolver = new \Symfony\Component\OptionsResolver\OptionsResolver();
        (new TagType())->configureOptions($resolver);

        $reflection = new ReflectionClass(\Symfony\Component\OptionsResolver\OptionsResolver::class);
        $property   = $reflection->getProperty('normalizers');
        /** @var array<string, list<callable>> $normalizers */
        $normalizers = $property->getValue($resolver);
        $normalizer  = $normalizers['empty_data'][0];

        self::assertSame('', $normalizer(['value_format' => 'string'], ''));
    }

    public function testBuildViewPreservesExistingStringValue(): void
    {
        $type                = new TagType();
        $view                = new FormView();
        $view->vars['attr']  = [];
        $view->vars['value'] = '["existing"]';

        $form = $this->createMock(FormInterface::class);

        $options = [
            'container_class'  => 'container',
            'input_class'      => 'input',
            'placeholder'      => '',
            'max_tags'         => null,
            'whitelist'        => [],
            'pattern'          => null,
            'duplicates'       => false,
            'dropdown_enabled' => true,
            'disabled'         => true,
        ];

        $type->buildView($view, $form, $options);

        self::assertSame('["existing"]', $view->vars['value']);
        self::assertTrue($view->vars['tag_disabled']);
    }
}

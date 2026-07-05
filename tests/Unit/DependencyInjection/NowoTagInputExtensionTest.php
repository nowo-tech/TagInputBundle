<?php

declare(strict_types=1);

namespace Nowo\TagInputBundle\Tests\Unit\DependencyInjection;

use Nowo\TagInputBundle\DependencyInjection\NowoTagInputExtension;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * @covers \Nowo\TagInputBundle\DependencyInjection\NowoTagInputExtension
 */
final class NowoTagInputExtensionTest extends TestCase
{
    public function testGetAlias(): void
    {
        $extension = new NowoTagInputExtension();
        self::assertSame('nowo_tag_input', $extension->getAlias());
    }

    public function testLoadRegistersParameters(): void
    {
        $extension = new NowoTagInputExtension();
        $container = new ContainerBuilder();
        $container->setParameter('kernel.bundles_metadata', []);

        $extension->load([[
            'value_format'     => 'string',
            'trim'             => false,
            'pattern'          => '^[a-z]+$',
            'whitelist'        => ['php'],
            'duplicates'       => true,
            'max_tags'         => 5,
            'dropdown_enabled' => false,
            'placeholder'      => 'Add tags',
            'form_theme'       => 'bootstrap_5_layout.html.twig',
        ]], $container);

        self::assertSame('string', $container->getParameter('nowo_tag_input.value_format'));
        self::assertFalse($container->getParameter('nowo_tag_input.trim'));
        self::assertSame('^[a-z]+$', $container->getParameter('nowo_tag_input.pattern'));
        self::assertSame(['php'], $container->getParameter('nowo_tag_input.whitelist'));
        self::assertTrue($container->getParameter('nowo_tag_input.duplicates'));
        self::assertSame(5, $container->getParameter('nowo_tag_input.max_tags'));
        self::assertFalse($container->getParameter('nowo_tag_input.dropdown_enabled'));
        self::assertSame('Add tags', $container->getParameter('nowo_tag_input.placeholder'));
        self::assertSame('bootstrap_5_layout.html.twig', $container->getParameter('nowo_tag_input.form_theme'));
    }

    public function testPrependSkipsWhenTwigExtensionMissing(): void
    {
        $extension = new NowoTagInputExtension();
        $container = new ContainerBuilder();

        $extension->prepend($container);

        self::assertSame([], $container->getExtensionConfig('twig'));
    }

    public function testPrependAddsMappedTwigThemeAndFallback(): void
    {
        $extension = new NowoTagInputExtension();

        $container = new ContainerBuilder();
        $container->registerExtension(new class extends \Symfony\Component\DependencyInjection\Extension\Extension {
            public function load(array $configs, ContainerBuilder $container): void
            {
            }

            public function getAlias(): string
            {
                return 'twig';
            }
        });

        $container->prependExtensionConfig('nowo_tag_input', [
            'form_theme' => 'bootstrap_5_layout.html.twig',
        ]);
        $extension->prepend($container);
        $twigConfigs = $container->getExtensionConfig('twig');
        self::assertSame('@NowoTagInputBundle/Form/tag_input_theme_bootstrap5.html.twig', $twigConfigs[0]['form_themes'][0]);

        $container2 = new ContainerBuilder();
        $container2->registerExtension(new class extends \Symfony\Component\DependencyInjection\Extension\Extension {
            public function load(array $configs, ContainerBuilder $container): void
            {
            }

            public function getAlias(): string
            {
                return 'twig';
            }
        });
        $container2->prependExtensionConfig('nowo_tag_input', [
            'form_theme' => 'unknown_theme.html.twig',
        ]);
        $extension->prepend($container2);
        $twigConfigs2 = $container2->getExtensionConfig('twig');
        self::assertSame('@NowoTagInputBundle/Form/tag_input_theme.html.twig', $twigConfigs2[0]['form_themes'][0]);
    }
}

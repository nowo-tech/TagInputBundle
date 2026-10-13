<?php

declare(strict_types=1);

namespace Nowo\TagInputBundle\Tests\Unit\DependencyInjection;

use Nowo\TagInputBundle\DependencyInjection\Configuration;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Symfony\Component\Config\Definition\Processor;

/**
 * @covers \Nowo\TagInputBundle\DependencyInjection\Configuration
 */
final class ConfigurationTest extends TestCase
{
    public function testGetConfigTreeBuilderReturnsTreeWithAlias(): void
    {
        $config = new Configuration();
        $tree   = $config->getConfigTreeBuilder();
        self::assertSame(Configuration::ALIAS, $tree->buildTree()->getName());
    }

    public function testProcessConfigurationWithDefaults(): void
    {
        $processor = new Processor();
        $config    = $processor->processConfiguration(new Configuration(), []);

        self::assertSame('array', $config['value_format']);
        self::assertTrue($config['trim']);
        self::assertNull($config['pattern']);
        self::assertSame([], $config['whitelist']);
        self::assertFalse($config['duplicates']);
        self::assertSame(50, $config['max_tags']);
        self::assertTrue($config['dropdown_enabled']);
        self::assertSame('tag.placeholder', $config['placeholder']);
        self::assertSame('form_div_layout.html.twig', $config['form_theme']);
    }

    public function testProcessConfigurationWithCustomValues(): void
    {
        $processor = new Processor();
        $config    = $processor->processConfiguration(new Configuration(), [[
            'value_format'     => 'string',
            'trim'             => false,
            'pattern'          => '^[a-z]+$',
            'whitelist'        => ['php', 'symfony'],
            'duplicates'       => true,
            'max_tags'         => 5,
            'dropdown_enabled' => false,
            'placeholder'      => 'Add tags',
            'form_theme'       => 'bootstrap_5_layout.html.twig',
        ]]);

        self::assertSame('string', $config['value_format']);
        self::assertFalse($config['trim']);
        self::assertSame('^[a-z]+$', $config['pattern']);
        self::assertSame(['php', 'symfony'], $config['whitelist']);
        self::assertTrue($config['duplicates']);
        self::assertSame(5, $config['max_tags']);
        self::assertFalse($config['dropdown_enabled']);
        self::assertSame('Add tags', $config['placeholder']);
        self::assertSame('bootstrap_5_layout.html.twig', $config['form_theme']);
    }

    public function testInvalidPatternIsRejected(): void
    {
        $this->expectException(InvalidConfigurationException::class);
        (new Processor())->processConfiguration(new Configuration(), [['pattern' => '([a-z']]);
    }

    public function testNonStringPatternIsRejected(): void
    {
        $this->expectException(InvalidConfigurationException::class);
        (new Processor())->processConfiguration(new Configuration(), [['pattern' => 42]]);
    }

    public function testPatternWithSlashAndEmptyPatternAreAccepted(): void
    {
        $processor = new Processor();

        self::assertSame('^a/b$', $processor->processConfiguration(new Configuration(), [['pattern' => '^a/b$']])['pattern']);
        self::assertSame('', $processor->processConfiguration(new Configuration(), [['pattern' => '']])['pattern']);
    }

    public function testUnknownFormThemeIsRejected(): void
    {
        $this->expectException(InvalidConfigurationException::class);
        (new Processor())->processConfiguration(new Configuration(), [['form_theme' => 'unknown_theme.html.twig']]);
    }
}

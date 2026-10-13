<?php

declare(strict_types=1);

namespace Nowo\TagInputBundle\DependencyInjection;

use Nowo\TagInputBundle\Form\TagPattern;
use Nowo\TagInputBundle\Form\TagType;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

use function array_keys;
use function is_string;

final class Configuration implements ConfigurationInterface
{
    public const ALIAS = 'nowo_tag_input';

    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder(self::ALIAS);
        $root        = $treeBuilder->getRootNode();

        $root
            ->children()
                ->enumNode('value_format')
                    ->values(['array', 'string'])
                    ->defaultValue('array')
                ->end()
                ->booleanNode('trim')
                    ->defaultTrue()
                ->end()
                ->scalarNode('pattern')
                    ->defaultNull()
                    ->info('Regex source without delimiters (shared with the Tagify client); must compile.')
                    ->validate()
                        ->ifTrue(static fn (mixed $value): bool => $value !== null && (!is_string($value) || ($value !== '' && !TagPattern::isValid($value))))
                        ->thenInvalid('Invalid tag pattern %s: it must be a valid regular expression without delimiters.')
                    ->end()
                ->end()
                ->arrayNode('whitelist')
                    ->scalarPrototype()->end()
                    ->defaultValue([])
                ->end()
                ->booleanNode('duplicates')
                    ->defaultFalse()
                ->end()
                ->integerNode('max_tags')
                    ->min(1)
                    ->defaultValue(50)
                    ->info('Maximum tags accepted from the client (fail-closed on overflow).')
                ->end()
                ->booleanNode('dropdown_enabled')
                    ->defaultTrue()
                ->end()
                ->scalarNode('placeholder')
                    ->info('Default placeholder: a translation key or literal text, translated with the field translation_domain. Empty string disables it.')
                    ->defaultValue(TagType::DEFAULT_PLACEHOLDER_KEY)
                ->end()
                ->enumNode('form_theme')
                    ->values(array_keys(NowoTagInputExtension::FORM_THEME_MAP))
                    ->defaultValue('form_div_layout.html.twig')
                ->end()
            ->end();

        return $treeBuilder;
    }
}

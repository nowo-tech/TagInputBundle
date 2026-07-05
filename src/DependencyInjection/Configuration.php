<?php

declare(strict_types=1);

namespace Nowo\TagInputBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

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
                    ->defaultNull()
                ->end()
                ->booleanNode('dropdown_enabled')
                    ->defaultTrue()
                ->end()
                ->scalarNode('placeholder')
                    ->defaultValue('')
                ->end()
                ->scalarNode('form_theme')
                    ->defaultValue('form_div_layout.html.twig')
                ->end()
            ->end();

        return $treeBuilder;
    }
}

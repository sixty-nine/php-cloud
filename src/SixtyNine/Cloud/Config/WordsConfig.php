<?php

namespace SixtyNine\Cloud\Config;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class WordsConfig implements ConfigurationInterface
{
    /** @var TreeBuilder  */
    protected $treeBuilder;

    protected $rootNode;

    public function __construct(TreeBuilder $treeBuilder, $rootNode)
    {
        $this->treeBuilder = $treeBuilder;
        $this->rootNode = $rootNode;
    }

    public function getConfigTreeBuilder()
    {
        $this->rootNode
            ->children()
                ->arrayNode('words')
                    ->children()
                    ->arrayNode('blacklist')
                        ->useAttributeAsKey('name')
                            ->prototype('scalar')->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $this->treeBuilder;
    }
}

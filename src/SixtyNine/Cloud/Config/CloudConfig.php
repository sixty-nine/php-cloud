<?php


namespace SixtyNine\Cloud\Config;

use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;

class CloudConfig implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('cloud_config');

        $configs = array(
            new PaletteConfig($treeBuilder, $rootNode),
            new FiltersConfig($treeBuilder, $rootNode),
            new WordsConfig($treeBuilder, $rootNode),
        );

        /** @var ConfigurationInterface $config */
        foreach ($configs as $config) {
            $treeBuilder = $config->getConfigTreeBuilder();
        }

        return $treeBuilder;
    }
}

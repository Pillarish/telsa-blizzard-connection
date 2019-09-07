<?php

namespace Telsa\BlizzardConnection\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('telsa_blizzard_connection');

        $rootNode->children()
            ->scalarNode('client_id')->end()
            ->scalarNode('client_secret')->end()
            ->end();

        return $treeBuilder;
    }

}

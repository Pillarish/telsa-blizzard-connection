<?php

namespace Telsa\BlizzardConnection\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('telsa_blizzard_connection');

        if (method_exists($treeBuilder, 'getRootNode')) {
            $rootNode = $treeBuilder->getRootNode();
        } else {
            // BC layer for symfony/config 4.1 and older
            $rootNode = $treeBuilder->root('telsa_blizzard_connection', 'array');
        }

        $rootNode->children()
            ->scalarNode('client_id')->info('Required for connection to Blizzard API')->end()
            ->scalarNode('client_secret')->end()
            ->end();

        return $treeBuilder;
    }

}

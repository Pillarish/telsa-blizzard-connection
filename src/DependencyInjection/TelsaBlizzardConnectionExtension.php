<?php

namespace Telsa\BlizzardConnection\DependencyInjection;

use Exception;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class TelsaBlizzardConnectionExtension extends Extension
{
    /**
     * @param array $configs
     * @param ContainerBuilder $container
     * @throws Exception
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));

        $loader->load('services.yml');

        $configuration = $this->getConfiguration($configs, $container);
        $config = $this->processConfiguration($configuration, $configs);

        $communityCharacterProfileApiDef = $container->getDefinition('telsa_blizzard_connection.apis.world_of_warcraft.community_character_profile_api');
        $communityCharacterProfileApiDef->setArgument(1, $config['client_id']);
        $communityCharacterProfileApiDef->setArgument(2, $config['client_secret']);

        $achievementApiDef = $container->getDefinition('telsa_blizzard_connection.apis.world_of_warcraft.achievements_api');
        $achievementApiDef->setArgument(1, $config['client_id']);
        $achievementApiDef->setArgument(2, $config['client_secret']);

        $characterProfileApiDef = $container->getDefinition('telsa_blizzard_connection.apis.world_of_warcraft.character_profile_api');
        $characterProfileApiDef->setArgument(1, $config['client_id']);
        $characterProfileApiDef->setArgument(2, $config['client_secret']);

        $realmStatusApiDef = $container->getDefinition('telsa_blizzard_connection.apis.world_of_warcraft.realm_status_api');
        $realmStatusApiDef->setArgument(1, $config['client_id']);
        $realmStatusApiDef->setArgument(2, $config['client_secret']);

        $guildProfileApiDef = $container->getDefinition('telsa_blizzard_connection.apis.world_of_warcraft.guild_profile_api');
        $guildProfileApiDef->setArgument(1, $config['client_id']);
        $guildProfileApiDef->setArgument(2, $config['client_secret']);
    }


}

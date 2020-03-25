<?php

namespace Telsa\BlizzardConnection\Apis\WorldOfWarcraft;

use Telsa\BlizzardConnection\Apis\BlizzardConnection;

class GuildProfileApi extends BlizzardConnection
{
	private $endPoint = "data/wow/guild/";

	protected $apiName = "guild_profile";

    protected $namespace = "profile";

    /**
     * @var string
     */
	private $guildName;

    /**
     * @var string
     */
	private $realm;

	public function getGuildProfile()
	{
        return $this->actionRequest($this->generateEndpoint(), []);
	}

    public function getGuildAchievements()
    {
        return $this->actionRequest($this->generateEndpoint('achievements'), []);
    }

    public function getGuildRoster()
    {
        return $this->actionRequest($this->generateEndpoint('roster'), []);
    }

    /**
     * @param string $endpoint
     * @return string
     */
	private function generateEndpoint(string $endpoint = '')
    {
        return $this->endPoint . urlencode($this->realm) . "/" . urlencode($this->guildName) . ($endpoint ? '/' . urlencode($endpoint) : '');
    }

    /**
     * @param string $guildName
     */
    public function setGuildName(string $guildName): void
    {
        $this->guildName = $guildName;
    }

    /**
     * @param string $realm
     */
    public function setRealm(string $realm): void
    {
        $this->realm = $realm;
    }
}

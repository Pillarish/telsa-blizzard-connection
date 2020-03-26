<?php

namespace Telsa\BlizzardConnection\Apis\WorldOfWarcraft;

use Telsa\BlizzardConnection\Apis\BlizzardConnection;

class CharacterProfileApi extends BlizzardConnection
{
	private $endPoint = "profile/wow/character/";

	protected $apiName = "character_profile";

    protected $namespace = 'profile';

    /**
     * @var string
     */
	private $characterName;

    /**
     * @var string
     */
	private $realm;

	public function getCharacterProfile()
	{
        return $this->actionRequest($this->generateEndpoint(), []);
	}

    public function getCharacterAchievements()
    {
        return $this->actionRequest($this->generateEndpoint('achievements'), []);
    }

    public function getCharacterMounts()
    {
        return $this->actionRequest($this->generateEndpoint('collections/mounts'), []);
    }

    public function getCharacterMedia()
    {
        return $this->actionRequest($this->generateEndpoint('character-media'), []);
    }

    /**
     * @param string $endpoint
     * @return string
     */
	private function generateEndpoint(string $endpoint = '')
    {
        return $this->endPoint . urlencode($this->realm) . "/" . urlencode(strtolower($this->characterName)) . ($endpoint ? '/' . urlencode($endpoint) : '');
    }

    /**
     * @param string $characterName
     */
    public function setCharacterName(string $characterName): void
    {
        $this->characterName = $characterName;
    }

    /**
     * @param string $realm
     */
    public function setRealm(string $realm): void
    {
        $this->realm = $realm;
    }
}

<?php

namespace Telsa\BlizzardConnection\Apis\WorldOfWarcraft;

use Telsa\BlizzardConnection\Apis\BlizzardConnection;

class CharacterProfileApi extends BlizzardConnection
{
	private $endPoint = "/wow/character/";

	protected $apiName = "wow_community";

    /**
     * @var string
     */
	private $characterName;

    /**
     * @var string
     */
	private $realm;

	public function getCharacter($fields = array())
	{
        return $this->actionRequest($this->generateEndpoint(), array(
			"fields" => implode(",", $fields)
		));
	}

	public function getCharacterMounts()
	{
        return $this->actionRequest($this->generateEndpoint(), array(
			"fields" => "mounts"
		));
	}

	public function getCharacterTalents()
	{
        return $this->actionRequest($this->generateEndpoint(), array(
			"fields" => "talents"
		));
	}

	public function getCharacterForMountPlanner()
	{
	    return $this->actionRequest($this->generateEndpoint(), array(
			"fields" => "mounts,talents"
		));
	}

	private function generateEndpoint()
    {
        return $this->endPoint . urlencode($this->realm) . "/" . urlencode($this->characterName);
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

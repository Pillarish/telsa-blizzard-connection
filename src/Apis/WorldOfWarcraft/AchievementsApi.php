<?php

namespace Telsa\BlizzardConnection\Apis\WorldOfWarcraft;

use Telsa\BlizzardConnection\Apis\BlizzardConnection;

class AchievementsApi extends BlizzardConnection
{
	private $endPoint = "data/wow/";

	protected $apiName = "achievement_data";

	protected $namespace = 'static-eu';

    /**
     * @var string
     */
	private $characterName;

    /**
     * @var string
     */
	private $realm;

    public function getAchievementIndex()
    {
        return $this->actionRequest($this->generateEndpoint('achievement/index'), []);
    }

    public function getAchievementDetails(int $achievementId)
    {
        return $this->actionRequest($this->generateEndpoint('achievement/' . $achievementId), []);
    }

    /**
     * @param string $endpoint
     * @return string
     */
	private function generateEndpoint(string $endpoint = '')
    {
        return $this->endPoint . urlencode($endpoint);
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

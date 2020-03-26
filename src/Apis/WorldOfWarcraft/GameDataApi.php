<?php

namespace Telsa\BlizzardConnection\Apis\WorldOfWarcraft;

use Telsa\BlizzardConnection\Apis\BlizzardConnection;

class GameDataApi extends BlizzardConnection
{
	private $endPoint = "data/wow/";

	protected $apiName = "game_data";

    protected $namespace = 'static';

    public function getMountsIndex()
	{
        return $this->actionRequest($this->generateEndpoint('mount/index'), []);
	}

    public function getMount(int $id)
    {
        return $this->actionRequest($this->generateEndpoint('mount/' . $id), []);
    }

    /**
     * @param string $endpoint
     * @return string
     */
	private function generateEndpoint(string $endpoint = '')
    {
        return $this->endPoint . ($endpoint ? '/' . urlencode($endpoint) : '');
    }
}

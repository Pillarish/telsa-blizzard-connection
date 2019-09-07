<?php

namespace Telsa\BlizzardConnection\Apis\WorldOfWarcraft;

use Telsa\BlizzardConnection\Apis\BlizzardConnection;

class RealmStatusApi extends BlizzardConnection
{
	private $endPoint = "/wow/realm/status";

	protected $apiName = "wow_community";

	public function getRealms($fields = array())
	{
        return $this->actionRequest($this->endPoint, array(
			"fields" => implode(",", $fields)
		));
	}
}

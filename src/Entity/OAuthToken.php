<?php

namespace Telsa\BlizzardConnection\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="tbc_oauth_token")
 */
class OAuthToken
{
	/**
	 * @var int
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $authId;

	/**
	 * @var string
	 * @ORM\Column(type="string")
	 */
	private $accessToken;

	/**
	 * @var string
	 * @ORM\Column(type="string")
	 */
	private $tokenType;

	/**
	 * @var int
	 * @ORM\Column(type="integer")
	 */
	private $expiresIn;

	/**
	 * @var string
	 * @ORM\Column(type="string")
	 */
	private $scope;

	/**
	 * @var int
	 * @ORM\Column(type="integer")
	 */
	private $timeStamp;

	/**
	 * @var string
	 * @ORM\Column(type="string")
	 */
	private $apiName;

    /**
     * @param $rawAuth string
     * @param $apiName
     * @param $time
     * @return OAuthToken
     */
	public static function createToken($rawAuth, $apiName, $time)
	{
	    $auth = json_decode($rawAuth);

		$newToken = new self();
		$newToken->setAccessToken($auth->access_token);
		$newToken->setApiName($apiName);
		$newToken->setExpiresIn($auth->expires_in);
		$newToken->setScope($auth->scope);
		$newToken->setTokenType($auth->token_type);
		$newToken->setTimeStamp($time);

		return $newToken;
	}

	/**
	 * @return int
	 */
	public function getAuthId()
	{
		return $this->authId;
	}

	/**
	 * @param int $authId
	 */
	public function setAuthId($authId)
	{
		$this->authId = $authId;
	}

	/**
	 * @return string
	 */
	public function getAccessToken()
	{
		return $this->accessToken;
	}

	/**
	 * @param string $accessToken
	 */
	public function setAccessToken($accessToken)
	{
		$this->accessToken = $accessToken;
	}

	/**
	 * @return string
	 */
	public function getTokenType()
	{
		return $this->tokenType;
	}

	/**
	 * @param string $tokenType
	 */
	public function setTokenType($tokenType)
	{
		$this->tokenType = $tokenType;
	}

	/**
	 * @return int
	 */
	public function getExpiresIn()
	{
		return $this->expiresIn;
	}

	/**
	 * @param int $expiresIn
	 */
	public function setExpiresIn($expiresIn)
	{
		$this->expiresIn = $expiresIn;
	}

	/**
	 * @return string
	 */
	public function getScope()
	{
		return $this->scope;
	}

	/**
	 * @param string $scope
	 */
	public function setScope($scope)
	{
		$this->scope = $scope;
	}

	/**
	 * @return int
	 */
	public function getTimeStamp()
	{
		return $this->timeStamp;
	}

	/**
	 * @param int $timeStamp
	 */
	public function setTimeStamp($timeStamp)
	{
		$this->timeStamp = $timeStamp;
	}

	/**
	 * @return string
	 */
	public function getApiName()
	{
		return $this->apiName;
	}

	/**
	 * @param string $apiName
	 */
	public function setApiName($apiName)
	{
		$this->apiName = $apiName;
	}

	/**
	 * Compares timestamp and expire time with the current time
	 * to determine if the token has expired
	 * @return bool
	 */
	public function hasExpired()
	{
		if (($this->timeStamp + $this->expiresIn) < time()) {
			return true;
		}

		return false;
	}
}

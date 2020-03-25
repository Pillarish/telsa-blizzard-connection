<?php

namespace Telsa\BlizzardConnection\Apis;

use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\InvalidArgumentException;
use Telsa\BlizzardConnection\Regions\EU;
use Telsa\BlizzardConnection\Regions\RegionInterface;
use Telsa\BlizzardConnection\Entity\OAuthToken;
use Telsa\BlizzardConnection\Regions\US;

abstract class BlizzardConnection
{
	/**
	 * @var string
	 */
	private $clientId;

	/**
	 * @var string
	 */
	private $clientSecret;

	/**
	 * @var RegionInterface
	 */
	private $region;

	/**
	 * @var string
	 */
	protected $apiName;

    /**
     * @var string
     */
	protected $namespace;

    /**
     * @var EntityManagerInterface
     */
	protected $em;

	public function __construct(EntityManagerInterface $em, $clientId, $clientSecret)
	{
		$this->em = $em;
		$this->clientId = $clientId;
		$this->clientSecret = $clientSecret;
	}

	/**
	 * @param string $regionName
	 */
	public function setRegion(string $regionName)
	{
        switch ($regionName) {
            case "us":
                $this->region = new US();
                break;
            case "eu":
            default:
                $this->region = new EU();
                break;
        }
	}

	/**
	 * Should be good for all API connections
	 * @param string $endpoint
	 * @param array $params
	 * @return string
	 */
	final protected function actionRequest($endpoint, array $params)
	{
	    $params = array_merge($params, ['locale' => $this->region->getLocale()]);

		$url = sprintf('%s%s?%s', $this->region->getApiBaseUrl(), $endpoint, http_build_query($params));

		// initialise cURL
		$curl = curl_init();

		//Set cURL options
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_HEADER, false);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

		// Set the http headers
        $httpHeaders = array(
            'Content-Type: application/json',
            "Authorization: Bearer " . $this->getAuthorisation()->getAccessToken()
        );

        // If the api requires a namespace, build it
        if ($this->namespace) {
            $httpHeaders[] = "Battlenet-Namespace: " . $this->namespace . '-' . strtolower($this->region->getName());
        }

		curl_setopt($curl, CURLOPT_HTTPHEADER, $httpHeaders);

		// run the request
		$output = curl_exec($curl);

		curl_close($curl);
		return $output;
	}

	/**
	 * This method will get the OAuth token for the request to use.
	 * This will first check the database for a current one.
	 * Then if the current one has expired, get a new one
	 * @return OAuthToken
	 */
	private function getAuthorisation()
	{
		if (!$this->apiName) {
			throw new InvalidArgumentException("The API Name must be set in order to use the Blizzard Connection Service.");
		}

		// Get token from database
		/* @var OAuthToken $token */
		$token = $this->em->getRepository(OAuthToken::class)->findOneBy(array("apiName" => $this->apiName . "_" . $this->region->getName()));

		// Check that it exists and has not expired
		if (
			$token &&
			!$token->hasExpired()
		) {
			return $token;
		}

		// Get a new token
		/* @var OAuthToken $newToken */
		$newToken = $this->getNewAuthToken();

		// If the old token existed then overwrite it
		if ($token) {
			$this->em->remove($token);
		}

		$this->em->persist($newToken);

		$this->em->flush();

		return $newToken;
	}

	/**
	 * @return string
	 */
	public function getNewAuthToken()
	{
		// Set a timestamp before the call to stop any overlap of the token expiry
		$time = time();
		$params = array(
			'grant_type' => 'client_credentials',
			'client_id' => $this->clientId,
			'client_secret' => $this->clientSecret,
			'scope' => "wow.profile"
		);
		$url_params = http_build_query($params);

		// initialise cURL
		$curl = curl_init();

		//Set cURL options
		curl_setopt($curl, CURLOPT_URL, $this->region->getOAuthBaseUrl() . "/oauth/token");
		// curl_setopt($curl, CURLOPT_HEADER, false);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

		curl_setopt($curl, CURLOPT_POSTFIELDS, $url_params);

		// run the request
		$output = curl_exec($curl);

		curl_close($curl);

		$newToken = OAuthToken::createToken($output, $this->apiName . "_" . $this->region->getName(), $time);

		return $newToken;
	}
}

<?php

namespace Telsa\BlizzardConnection\Apis;

use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\InvalidArgumentException;
use Telsa\BlizzardConnection\Regions\EU;
use Telsa\BlizzardConnection\Regions\RegionInterface;
use Telsa\BlizzardConnection\Entity\OAuthToken;
use Telsa\BlizzardConnection\Regions\US;

// TODO: Add a connection to get the mount master list
// TODO: Add a connection to get the realm list

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
	 * Should be able to make any call to blizzard apis
	 * @param string $endpoint
	 * @param array $params
	 * @return string
	 */
	final protected function actionRequest($endpoint, array $params)
	{
		$url = sprintf('%s%s?%s', $this->region->getApiBaseUrl(), $endpoint, http_build_query($params));

		// initialise cURL
		$curl = curl_init();

		//Set cURL options
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_HEADER, false);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

		// Set the OAuth 2 header
		curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , "Authorization: Bearer " . $this->getAuthorisation()->getAccessToken()));

		// only for use from within the totemic proxy
		if ($this->proxyRequired()) {
			curl_setopt($curl, CURLOPT_PROXY, 'srukproxy01.totemic.local');
			curl_setopt($curl, CURLOPT_PROXYPORT, 3128);
		}

		// run the request
		$output = curl_exec($curl);

		curl_close($curl);
		return $output;
	}

	/**
	 * This method will get the OAuth token for the request to use.
	 * This will first check the database for a current one.
	 * Then if the current one has expired, get a new one
	 * @param $region string
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
	 * @param $region string
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

		// only for use from within the totemic proxy
		if ($this->proxyRequired()) {
			curl_setopt($curl, CURLOPT_PROXY, 'srukproxy01.totemic.local');
			curl_setopt($curl, CURLOPT_PROXYPORT, 3128);
		}

		// run the request
		$output = curl_exec($curl);

		curl_close($curl);

		$newToken = OAuthToken::createToken($output, $this->apiName . "_" . $this->region->getName(), $time);

		return $newToken;
	}

	private function proxyRequired()
	{
		if (getenv("APPLICATION_ENV") === "totemic") {
		    // For now my PC has been exempted from the proxy, so no need for this
            // Just change this back to return true if that ever gets changed
			return false;
		}
		return false;
	}
}

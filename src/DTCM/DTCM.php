<?php
namespace DTCM;

use DTCM\Authentication\AccessToken;
use DTCM\HttpClients\HttpClientsFactory;
use DTCM\HttpClients\DTCMCurl;
use DTCM\HttpClients\DTCMCurlHttpClient;

/**
 * Class DTCM
 *
 * @package DTCM
 */
class DTCM
{
    /**
     * @var DTCMClient The DTCM client service.
     */
    protected $client;

    /**
     * Instantiates a new DTCM super-class object.
     *
     * @param array $config
     *
     * @throws DTCMException
     */
    public function __construct(array $config = [])
    {
        //@todo: get config from laravel.
        $config = array_merge([
            'http_client_handler' => null,
            'persistent_data_handler' => null,
        ], $config);
        $this->client = new DTCMClient(HttpClientsFactory::createHttpClient('curl'));
        $this->setDefaultAccessToken($config['AccessToken']);
    }

    /**
     * Returns the DTCMClient service.
     *
     * @return DTCMClient
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * Returns the default AccessToken entity.
     *
     * @return AccessToken|null
     */
    public function getDefaultAccessToken()
    {
        return $this->defaultAccessToken;
    }

    /**
     * Sets the default access token to use with requests.
     *
     * @param AccessToken|string $accessToken The access token to save.
     *
     * @throws \InvalidArgumentException
     */
    public function setDefaultAccessToken($accessToken = null)
    {
        if (is_string($accessToken)) {
            $this->defaultAccessToken = new AccessToken($accessToken);

            return;
        }

        if ($accessToken instanceof AccessToken) {
            $this->defaultAccessToken = $accessToken;

            return;
        }

        throw new \InvalidArgumentException('The default access token must be of type "string" or DTCM\AccessToken');
    }

    /**
     * Sends a request to DTCM and returns the result.
     *
     * @param string                  $method
     * @param string                  $endpoint
     * @param array                   $params
     * @param AccessToken|string|null $accessToken
     *
     * @return DTCMResponse
     *
     * @throws DTCMException
     */
    public function sendRequest($method, $endpoint, array $params = [], array $header = [],  array $data = [], $json = false)
    {
        $request = $this->request($method, $endpoint, $params, $header, $data, $json);
        return $this->lastResponse = $this->client->sendRequest($request);
    }

    /**
     * Instantiates a new DTCMRequest entity.
     *
     * @param string                  $method
     * @param string                  $endpoint
     * @param array                   $params
     * @param AccessToken|string|null $accessToken
     *
     * @return DTCMRequest
     *
     * @throws DTCMException
     */
    public function request($method, $endpoint, array $params = [], array $header = [], array $data = [], $json = false)
    {
        $accessToken = $this->defaultAccessToken;
        $request = new DTCMRequest(
            $accessToken,
            $method,
            $endpoint,
            $params,
            $header,
            $data,
            $json
        );
        return $request; 
    }
}

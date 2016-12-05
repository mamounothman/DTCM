<?php
namespace DTCM;

use DTCM\Authentication\AccessToken;
use DTCM\Exceptions\DTCMException;
use DTCM\Http\RequestBodyUrlEncoded;

/**
 * Class Request
 *
 * @package DTCM
 */
class DTCMRequest
{
    /**
     * @var string|null The access token to use for this request.
     */
    protected $accessToken;

    /**
     * @var string The HTTP method for this request.
     */
    protected $method;

    /**
     * @var string DTCM endpoint for this request.
     */
    protected $endpoint;

    /**
     * @var array The headers to send with this request.
     */
    protected $headers = [];

    /**
     * @var array The parameters to send with this request.
     */
    protected $params = [];

    /**
     * @var array The data to send with this request.
     */
    protected $data = [];

    /**
     * @var indicate if post data posted as JSON.
     */
    protected $json = false;

    /**
     * Creates a new Request entity.
     *
     * @param AccessToken|string|null $accessToken
     * @param string|null             $method
     * @param string|null             $endpoint
     * @param array|null              $params
     */
    public function __construct($accessToken = null, $method = null, $endpoint = null, array $params = [], array $headers = [], array $data = [])
    {
        $this->setAccessToken($accessToken);
        $this->setMethod($method);
        $this->setEndpoint($endpoint);
        $this->setParams($params);
        $this->setData($data);
        $this->setHeaders($headers);
    }

    /**
     * Set the access token for this request.
     *
     * @param AccessToken|string
     *
     * @return DTCMkRequest
     */
    public function setAccessToken($accessToken)
    {
        $this->accessToken = $accessToken;
        if ($accessToken instanceof AccessToken) {
            $this->accessToken = $accessToken->getValue();
        }
        return $this;
    }

    /**
     * Return the access token for this request.
     *
     * @return string|null
     */
    public function getAccessToken()
    {
        return $this->accessToken;
    }

    /**
     * Return the access token for this request as an AccessToken entity.
     *
     * @return AccessToken|null
     */
    public function getAccessTokenEntity()
    {
        return $this->accessToken ? new AccessToken($this->accessToken) : null;
    }

    /**
     * Validate that an access token exists for this request.
     *
     * @throws DTCMException
     */
    public function validateAccessToken()
    {
        $accessToken = $this->getAccessToken();
        if (!$accessToken) {
            throw new DTCMException('You must provide an access token.');
        }
    }

    /**
     * Set the HTTP method for this request.
     *
     * @param string
     */
    public function setMethod($method)
    {
        $this->method = strtoupper($method);
    }

    /**
     * Return the HTTP method for this request.
     *
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * Validate that the HTTP method is set.
     *
     * @throws DTCMException
     */
    public function validateMethod()
    {
        if (!$this->method) {
            throw new DTCMException('HTTP method not specified.');
        }

        if (!in_array($this->method, ['GET', 'POST', 'DELETE', 'PUT'])) {
            throw new DTCMException('Invalid HTTP method specified.');
        }
    }

    /**
     * Set the endpoint for this request.
     *
     * @param string
     *
     * @return DTCMRequest
     *
     * @throws DTCMException
     */
    public function setEndpoint($endpoint)
    {
        // Harvest the access token from the endpoint to keep things in sync
        $this->endpoint = $endpoint;
        return $this;
    }

    /**
     * Return the endpoint for this request.
     *
     * @return string
     */
    public function getEndpoint()
    {
        return $this->endpoint;
    }

    /**
     * Generate and return the headers for this request.
     *
     * @return array
     */
    public function getHeaders()
    {
        $headers = static::getDefaultHeaders();

        return array_merge($this->headers, $headers);
    }

    /**
     * Returns the body of the request as URL-encoded.
     *
     * @return RequestBodyUrlEncoded
     */
    public function getUrlEncoded()
    {
        $params = $this->getParams();

        return new RequestBodyUrlEncoded($params);
    }

    /**
     * Set the headers for this request.
     *
     * @param array $headers
     */
    public function setHeaders(array $headers)
    {
        $this->headers = array_merge($this->headers, $headers);
    }

    /**
     * Set the params for this request.
     *
     * @param array $params
     *
     * @return DTCMRequest
     */
    public function setParams(array $params = [])
    {
        $this->params = $params;
        return $this;
    }

    /**
     * Generate and return the params for this request.
     *
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * Generate and return the params for this request.
     *
     * @return array
     */
    public function setData($data = [])
    {
        $this->data = $data;
    }

    /**
     * Generate and return the params for this request.
     *
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Generate and return the URL for this request.
     *
     * @return string
     */
    public function getUrl()
    {
        $this->validateMethod();
        $url = $this->getEndpoint();
        return $url;
    }

    /**
     * Return the default headers that every request should use.
     *
     * @return array
     */
    public static function getDefaultHeaders()
    {
        return [
            'Content-Type' => 'application/json'
        ];
    }
}

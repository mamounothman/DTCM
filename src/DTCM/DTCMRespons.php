<?php
namespace DTCM;

use DTCM\Exceptions\DTCMException;

/**
 * Class DTCMResponse
 *
 * @package DTCM
 */
class DTCMRespons
{
    /**
     * @var int The HTTP status code response from DTCM resposne.
     */
    protected $httpStatusCode;

    /**
     * @var array The headers returned from DTCM resposne.
     */
    protected $headers;

    /**
     * @var string The raw body of the response from DTCM resposne.
     */
    protected $body;

    /**
     * @var array The decoded body of the DTCM response.
     */
    protected $decodedBody = [];

    /**
     * @var DTCMRequest The original request that returned this response.
     */
    protected $request;

    /**
     * @var DTCMException The exception thrown by this request.
     */
    protected $thrownException;

    /**
     * Creates a new Response entity.
     *
     * @param DTCMRequest $request
     * @param string|null     $body
     * @param int|null        $httpStatusCode
     * @param array|null      $headers
     */
    public function __construct(DTCMRequest $request, $body = null, $httpStatusCode = null, array $headers = [])
    {
        $this->request = $request;
        $this->body = $body;
        $this->httpStatusCode = $httpStatusCode;
        $this->headers = $headers;

        $this->decodeBody();
    }

    /**
     * Return the original request that returned this response.
     *
     * @return DTCMRequest
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * Return the access token that was used for this response.
     *
     * @return string|null
     */
    public function getAccessToken()
    {
        return $this->request->getAccessToken();
    }

    /**
     * Return the HTTP status code for this response.
     *
     * @return int
     */
    public function getHttpStatusCode()
    {
        return $this->httpStatusCode;
    }

    /**
     * Return the HTTP headers for this response.
     *
     * @return array
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * Return the raw body response.
     *
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * Return the decoded body response.
     *
     * @return array
     */
    public function getDecodedBody()
    {
        return $this->decodedBody;
    }

    /**
     * Returns true if DTCM request returned an error message.
     *
     * @return boolean
     */
    public function isError()
    {
        return isset($this->decodedBody['error']);
    }

    /**
     * Throws the exception.
     *
     * @throws DTCMResponseException
     */
    public function throwException()
    {
        throw $this->thrownException;
    }

    /**
     * Instantiates an exception to be thrown later.
     */
    public function makeException()
    {
        $this->thrownException = DTCMResponseException::create($this);
    }

    /**
     * Returns the exception that was thrown for this request.
     *
     * @return DTCMResponseException|null
     */
    public function getThrownException()
    {
        return $this->thrownException;
    }

    /**
     * Convert the raw response into an array if possible.
     */
    public function decodeBody()
    {
        if(is_object($this->body)) {
            $this->decodedBody;
        }
        else if (is_string($this->body)) {
            $this->decodedBody = json_decode($this->body, true);    
        }

        if ($this->isError()) {
            $this->makeException();
        }
    }
}

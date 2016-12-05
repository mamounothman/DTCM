<?php
namespace DTCM;

use DTCM\HttpClients\DTCMHttpClientInterface;
use DTCM\HttpClients\DTCMCurlHttpClient;
use DTCM\Exceptions\DTCMException;
use DTCM\DTCMResponse;

/**
 * Class DTCMClient
 *
 * @package DTCM
 */
class DTCMClient
{
    /**
     * @const int The timeout in seconds for a normal request.
     */
    const DEFAULT_REQUEST_TIMEOUT = 60;

    /**
     * @var DTCMHttpClientInterface HTTP client handler.
     */
    protected $httpClientHandler;

    /**
     * Instantiates a new DTCMClient object.
     *
     * @param DTCMHttpClientInterface|null $httpClientHandler
     */
    public function __construct(DTCMHttpClientInterface $httpClientHandler = null)
    {
        $this->httpClientHandler = $httpClientHandler ?: $this->detectHttpClientHandler();
    }

    /**
     * Sets the HTTP client handler.
     *
     * @param DTCMHttpClientInterface $httpClientHandler
     */
    public function setHttpClientHandler(DTCMHttpClientInterface $httpClientHandler)
    {
        $this->httpClientHandler = $httpClientHandler;
    }

    /**
     * Returns the HTTP client handler.
     *
     * @return DTCMHttpClientInterface
     */
    public function getHttpClientHandler()
    {
        return $this->httpClientHandler;
    }

    /**
     * Detects which HTTP client handler to use.
     *
     * @return DTCMHttpClientInterface
     */
    public function detectHttpClientHandler()
    {
        //@todo: Handle default HttpClientHandler if the curl extension is not loaded.
        return extension_loaded('curl') ? new DTCMCurlHttpClient() : new DTCMCurlHttpClient();
        //throw new DTCMException("No HttpClientsHandler! please make sure you have php curl extension is loaded or implement your own HttpClientsHandler", 1);
        ;
    }

    /**
     * Prepares the request for sending to the client handler.
     *
     * @param DTCMRequest $request
     *
     * @return array
     */
    public function prepareRequestMessage(DTCMRequest $request)
    {

        $requestBody = $request->getUrlEncoded();
        $method = $request->getMethod();
        $header = $request->getHeaders();
        $data = $request->getData();
        $query_string_params = $requestBody->getBody();
        $url = $request->getEndpoint();

        if(!empty($requestBody)) {
            $url .= '?' . $requestBody->getBody();
        }

        return [
            $url,
            $method,
            $header,
            $query_string_params,
            $data
        ];
    }

    /**
     * Makes the request to DTCM and returns the result.
     *
     * @param DTCMRequest $request
     *
     * @return DTCMResponse
     *
     * @throws DTCMException
     */
    public function sendRequest(DTCMRequest $request)
    {
                                      //params, header, data
        list($url, $method, $headers, $query_string_params, $data) = $this->prepareRequestMessage($request);
        
        $headers['Authorization'] = 'Bearer ' . $request->getAccessToken();
        
        // Since file uploads can take a while, we need to give more time for uploads
        // $timeOut = static::DEFAULT_REQUEST_TIMEOUT;
        // Don't catch to allow it to bubble up.
        $jSonResponse = $this->httpClientHandler->send($url, $method, $query_string_params, $headers, $data);

        $returnResponse = new DTCMRespons(
            $request,
            $jSonResponse->getBody(),
            $jSonResponse->getHttpResponseCode(),
            $jSonResponse->getHeaders()
        );
        // var_dump($jSonResponse->getBody());
        if ($returnResponse->isError()) {
            throw $returnResponse->getThrownException();
        }

        return $returnResponse;
    }
}

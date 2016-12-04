<?php
namespace DTCM\HttpClients;

use DTCM\Exceptions\DTCMException;
use DTCM\Http\DTCMJsonRespons;

/**
 * Class DTCMCurlHttpClient
 *
 * @package DTCM
 */
class DTCMCurlHttpClient implements DTCMHttpClientInterface
{
    /**
     * @var string The client error message
     */
    protected $curlErrorMessage = '';

    /**
     * @var int The curl client error code
     */
    protected $curlErrorCode = 0;

    /**
     * @var string|boolean The raw response from the server
     */
    protected $rawResponse;

    /**
     * @var dtcmCurl Procedural curl as object
     */
    protected $dtcmCurl;

    /**
     * @param dtcmCurl|null Procedural curl as object
     */
    public function __construct($dtcmCurl = null)
    {
        $this->dtcmCurl = $dtcmCurl ?: new DTCMCurl();
    }

    /**
     * @inheritdoc
     */
    public function send($url, $method, $query_string_params, array $headers = [], array $data = [], $json = false)
    {
        $this->openConnection($url, $method, $query_string_params, $headers, $data, 60, $json);
        $this->sendRequest();

        if ($curlErrorCode = $this->dtcmCurl->errno()) {
            throw new DTCMException($this->dtcmCurl->error(), $curlErrorCode);
        }

        // Separate the raw headers from the raw body
        list($rawHeaders, $rawBody) = $this->extractResponseHeadersAndBody();
        $this->closeConnection();
        return new DTCMJsonRespons($rawHeaders, $rawBody);
    }

    /**
     * Opens a new curl connection.
     *
     * @param string $url     The endpoint to send the request to.
     * @param string $method  The request method.
     * @param string $query_string_params  The query string parameters of the request.
     * @param array  $headers The request headers.
     * @param int    $timeOut The timeout in seconds for the request.
     */
    public function openConnection($url, $method, $query_string_params, array $headers = [], array $data = [], $timeOut = null, $json)
    {
        $options = [
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_HTTPHEADER => $this->compileRequestHeaders($headers),
            CURLOPT_URL => $url,
            CURLOPT_CONNECTTIMEOUT => 30,
            CURLOPT_TIMEOUT => $timeOut,
            CURLOPT_RETURNTRANSFER => true, // Follow 301 redirects
            CURLOPT_HEADER => true, // Enable header processing
        ];
        
        if ($method !== "GET") {
            $options[CURLOPT_POSTFIELDS] = ($json) ? json_encode($data) : $data;
        }

        $this->dtcmCurl->init();
        $this->dtcmCurl->setoptArray($options);
    }

    /**
     * Closes an existing curl connection
     */
    public function closeConnection()
    {
        $this->dtcmCurl->close();
    }

    /**
     * Send the request and get the raw response from curl
     */
    public function sendRequest()
    {
        $this->rawResponse = $this->dtcmCurl->exec();
    }

    /**
     * Compiles the request headers into a curl-friendly format.
     *
     * @param array $headers The request headers.
     *
     * @return array
     */
    public function compileRequestHeaders(array $headers)
    {
        $return = [];

        foreach ($headers as $key => $value) {
            $return[] = $key . ': ' . $value;
        }
        return $return;
    }

    /**
     * Extracts the headers and the body into a two-part array
     *
     * @return array
     */
    public function extractResponseHeadersAndBody()
    {
        $parts = explode("\r\n\r\n", $this->rawResponse);
        $rawBody = array_pop($parts);
        $rawHeaders = implode("\r\n\r\n", $parts);

        return [trim($rawHeaders), trim($rawBody)];
    }
}

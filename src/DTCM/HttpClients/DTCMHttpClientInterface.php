<?php
namespace DTCM\HttpClients;

/**
 * Interface DTCMHttpClientInterface
 *
 * @package DTCM
 */
interface DTCMHttpClientInterface
{
    /**
     * Sends a request to the server and returns the raw response.
     *
     * @param string $url     The endpoint to send the request to.
     * @param string $method  The request method.
     * @param string $body    The body of the request.
     * @param array  $headers The request headers.
     * @param int    $timeOut The timeout in seconds for the request.
     *
     *
     * @throws \DTCM\Exceptions\DTCMException
     */
    public function send($url, $method, $query_string_params, array $headers = [], array $data = [], $json = false);
}

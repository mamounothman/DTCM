<?php
namespace DTCM\HttpClients;

use InvalidArgumentException;
use Exception;

class HttpClientsFactory
{
    private function __construct()
    {
        // a factory constructor should never be invoked
    }

    /**
     * HTTP client generation.
     *
     * @param DTCMHttpClientInterface|Client|string|null $handler
     *
     * @throws Exception If the cURL extension or the Guzzle client aren't available (if required).
     * @throws InvalidArgumentException If the http client handler isn't "curl", "stream", "guzzle", or an instance of DTCM\HttpClients\DTCMHttpClientInterface.
     *
     * @return DTCMHttpClientInterface
     */
    public static function createHttpClient($handler)
    {
        if (!$handler) {
            return self::detectDefaultClient();
        }

        throw new InvalidArgumentException('The http client handler must be set to "curl", or must be an instance of DTCM\HttpClients\DTCMHttpClientInterface');
    }

    /**
     * Detect default HTTP client.
     *
     * @return DTCMHttpClientInterface
     */
    private static function detectDefaultClient()
    {
        if (extension_loaded('curl')) {
            return new DTCMCurlHttpClient();
        }

        throw new Exception("Error Processing Request, Curl lib must be installed and enabled to work with this library.", 1);
    }
}

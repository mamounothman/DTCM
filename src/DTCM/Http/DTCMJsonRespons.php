<?php

namespace DTCM\Http;

/**
 * Class DTCMJsonRespons
 *
 * @package DTCM
 */
class DTCMJsonRespons extends DTCMRawResponse
{
  /**
     * Creates a new GraphRawResponse entity.
     *
     * @param string|array $headers        The headers as a raw string or array.
     * @param string       $body           The raw response body.
     * @param int          $httpStatusCode The HTTP response code (if sending headers as parsed array).
     */
    public function __construct($headers, $body, $httpStatusCode = null)
    {
      parent::__construct($headers, $body, $httpStatusCode);
    }

    /**
     * Return the body of the response as JSON object.
     *
     * @return string
     */
    public function getBody()
    {
        return json_decode($this->body);
    }
}
<?php
namespace DTCM\Http;

/**
 * Class RequestBodyUrlEncoded
 *
 * @package DTCM
 */
class RequestBodyUrlEncoded implements RequestBodyInterface
{
    /**
     * @var array The parameters to send with this request.
     */
    protected $params = [];

    /**
     * Creates a new DTCMUrlEncodedBody entity.
     *
     * @param array $params
     */
    public function __construct(array $params)
    {
        $this->params = $params;
    }

    /**
     * Create a query string from params array.
     * @return string.
     */
    public function getBody()
    {
        return http_build_query($this->params, null, '&');
    }
}

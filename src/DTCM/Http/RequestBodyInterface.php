<?php
namespace DTCM\Http;

/**
 * Interface
 *
 * @package DTCM
 */
interface RequestBodyInterface
{
    /**
     * Get the body of the request to send to DTCM.
     *
     * @return string
     */
    public function getBody();
}

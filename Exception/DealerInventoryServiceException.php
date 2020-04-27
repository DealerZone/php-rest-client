<?php

namespace DealerInventory\Client\Exception;

use GuzzleHttp\Exception\InvalidArgumentException;
use RuntimeException;

class DealerInventoryServiceException extends RuntimeException
{
    /** @var array $responseCode */
    private $responseBody;

    /** @var integer $responseCode */
    private $responseCode;

    public function __construct($responseBody, $responseCode)
    {
        try {
            $this->responseBody = \GuzzleHttp\json_decode($responseBody, true);
        } catch (InvalidArgumentException $e) {
            $this->responseBody = $responseBody;
        }
        $this->responseCode = $responseCode;

        parent::__construct($responseBody." [HTTP $responseCode]", 0, null);
    }

    /**
     * @return array
     */
    public function getResponseBody()
    {
        return $this->responseBody;
    }

    /**
     * @return int
     */
    public function getResponseCode()
    {
        return $this->responseCode;
    }
}

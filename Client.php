<?php

namespace DealerInventory\Client;

use DealerInventory\Client\Exception\DealerInventoryServiceException;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\RequestOptions;

class Client
{
    protected static $dns = 'https://dealerinventory.app';

    protected $clientKey;

    /** @var \GuzzleHttp\Client */
    protected $guzzle;

    public function __construct(string $clientKey)
    {
        $this->clientKey = $clientKey;
    }

    protected function get(string $path): array
    {
        try {
            $response = $this->guzzle()->request('GET', $path);
        } catch (ClientException $e) {
            $response = $e->getResponse();
        }

        if($response->getStatusCode() != 200) {
            throw new DealerInventoryServiceException($response->getBody()->getContents(), $response->getStatusCode());
        }

        return $this->jsonDecode($response->getBody()->getContents(), true);
    }

    protected function post(string $path, $body): array
    {
        try {
            $response = $this->guzzle()->request('POST', $path, [
                RequestOptions::JSON => $body,
            ]);
        } catch (ClientException $e) {
            $response = $e->getResponse();
        }

        if(!in_array($response->getStatusCode(), [200, 201])) {
            throw new DealerInventoryServiceException($response->getBody()->getContents(), $response->getStatusCode());
        }

        return $this->jsonDecode($response->getBody()->getContents(), true);
    }

    public function _setGuzzle($guzzle)
    {
        $this->guzzle = $guzzle;
    }

    protected function getData(string $path): array
    {
        return $this->get($path)['data'];
    }

    /**
     * @return \GuzzleHttp\Client
     */
    protected function guzzle()
    {
        if(empty($this->guzzle)) {
            $this->guzzle = new \GuzzleHttp\Client([
                'base_uri' => self::dns() . '/api/' . $this->clientKey . '/',
                'headers' => [
                    'X-Client-Key' => $this->clientKey,
                    'User-Agent' => 'PHP-DealerInventory-Client PHP/' . PHP_VERSION,
                ]
            ]);
        }

        return $this->guzzle;
    }

    protected static function dns()
    {
        $dns = getenv('DEALERINVENTORY_DNS');

        if($dns) {
            return $dns;
        }

        return self::$dns;
    }

    private function jsonDecode(string $json, bool $assoc)
    {
        $data = \json_decode($json, $assoc);
        if (\JSON_ERROR_NONE !== \json_last_error()) {
            throw new \InvalidArgumentException(
                'json_decode error: ' . \json_last_error_msg()
            );
        }

        return $data;
    }

}

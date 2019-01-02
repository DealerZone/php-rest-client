<?php

namespace VehicleInventory\Client;

use JsonSerializable;
use RuntimeException;
use GuzzleHttp\Client;
use VehicleInventory\Client\InfoDto;

class VehicleInventory
{
    public function __construct(string $clientKey)
    {
        $this->clientKey = $clientKey;
    }

    /**
     * @return InfoDto
     */
    public function info()
    {
        $res = $this->guzzle()->request('GET', 'info');

        $data = \GuzzleHttp\json_decode($res->getBody()->getContents(), true)['data'];

        return new InfoDto($data);
    }

    private function guzzle()
    {
        $client = new Client([
            'base_uri' => 'http://vehicleinventory.app/api/',
            'headers'  => [
                'X-Client-Key' => $this->clientKey,
                'User-Agent' => 'PHP-VehicleInventory-Client PHP/' . PHP_VERSION,
            ]
        ]);

        return $client;
    }
}
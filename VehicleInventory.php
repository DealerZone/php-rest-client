<?php

namespace VehicleInventory\Client;

use JsonSerializable;
use RuntimeException;
use GuzzleHttp\Client;
use Illuminate\Support\Collection;
use VehicleInventory\Client\Dto\InfoDto;
use VehicleInventory\Client\Dto\CategoryDto;

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
        return new InfoDto(
            $this->get('info')
        );
    }
    
    /**
     * @return Collection
     */
    public function categories()
    {
        $data = $this->get('category');

        return (new Collection($data))->map(function($attributes){
            return new CategoryDto($attributes);
        });
    }

    private function get($path): array
    {
        $res = $this->guzzle()->request('GET', $path);

        if($res->getStatusCode() != 200) {
            throw new \Exception($res->getBody()->getContents());
        }

        $data = \GuzzleHttp\json_decode($res->getBody()->getContents(), true)['data'];

        return $data;
    }

    private function guzzle()
    {
        $client = new Client([
            'base_uri' => 'http://vehicleinventory.app/api/'.$this->clientKey.'/',
            'headers'  => [
                'X-Client-Key' => $this->clientKey,
                'User-Agent' => 'PHP-VehicleInventory-Client PHP/' . PHP_VERSION,
            ]
        ]);

        return $client;
    }
}
<?php

namespace DealerInventory\Client;

use JsonSerializable;
use RuntimeException;
use GuzzleHttp\Client;
use DealerInventory\Client\Dto\InfoDto;
use DealerInventory\Client\Dto\MakeDto;
use Tightenco\Collect\Support\Collection;
use DealerInventory\Client\Dto\VehicleDto;
use DealerInventory\Client\Dto\CategoryDto;

class DealerInventory
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
     * @return MakeDto
     */
    public function makes()
    {
        return new MakeDto(
            $this->get('make')
        );
    }
    
    /**
     * @return VehicleDto
     */
    public function show($slug)
    {
        return new VehicleDto(
            $this->get('vehicle/show/'.$slug)
        );
    }

    /**
     * @return Collection|VehicleDto[]
     */
    public function listed()
    {
        return (new Collection(
            $this->get('vehicle/listed')
        ))->map(function($value){
            return new VehicleDto($value);
        });
    }

    /**
     * @return Collection|VehicleDto[]
     */
    public function sold()
    {
        return (new Collection(
            $this->get('vehicle/sold')
        ))->map(function($value){
            return new VehicleDto($value);
        });
    }

    /**
     * @return Collection|VehicleDto[]
     */
    public function featured()
    {
        return (new Collection(
            $this->get('vehicle/featured')
        ))->map(function($value){
            return new VehicleDto($value);
        });
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
            'base_uri' => 'https://dealerinventory.app/api/'.$this->clientKey.'/',
            'headers'  => [
                'X-Client-Key' => $this->clientKey,
                'User-Agent' => 'PHP-DealerInventory-Client PHP/' . PHP_VERSION,
            ]
        ]);

        return $client;
    }
}
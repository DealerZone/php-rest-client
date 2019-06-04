<?php

namespace DealerInventory\Client;

use DealerInventory\Client\Collection\PaginationCollection;
use DealerInventory\Client\Dto\MessageDto;
use DealerInventory\Client\Exception\DealerInventoryServiceException;
use GuzzleHttp\Client;
use DealerInventory\Client\Dto\InfoDto;
use DealerInventory\Client\Dto\MakeDto;
use DealerInventory\Client\Dto\ModelDto;
use Tightenco\Collect\Support\Collection;
use DealerInventory\Client\Dto\VehicleDto;
use DealerInventory\Client\Dto\CategoryDto;

class DealerInventory
{
    private static $domain = 'https://dealerinventory.app';

    private $clientKey;

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
            $this->getData('info')
        );
    }

    /**
     * @return Collection|MakeDto[]
     */
    public function makes()
    {
        return (new Collection(
            $this->getData('make')
        ))->map(function($value){
            $value['models'] = (new Collection($value['models']))->map(function($value){
                return new ModelDto($value);
            });
            return new MakeDto($value);
        });
    }

    /**
     * @return VehicleDto
     */
    public function vehicle($slug)
    {
        return new VehicleDto(
            $this->getData('vehicle/show/'.$slug)
        );
    }

    /**
     * @param integer page
     * @param Filters|array filters
     * @return PaginationCollection|VehicleDto[]
     */
    public function listed(int $page = 1, $filters = [])
    {
        $params = http_build_query((array) $filters);

        $result = $this->get("vehicle/listed?page=$page&$params");

        $collection = new PaginationCollection($result['data']);
        $collection
            ->setLinks($result['links'])
            ->setMeta($result['meta']);

        return $collection->map(function($value){
            return new VehicleDto($value);
        });
    }

    /**
     * @return PaginationCollection|VehicleDto[]
     */
    public function sold(int $page = 1)
    {
        $result = $this->get("vehicle/sold?page=$page");

        $collection = new PaginationCollection($result['data']);
        $collection
            ->setLinks($result['links'])
            ->setMeta($result['meta']);

        return $collection->map(function($value){
            return new VehicleDto($value);
        });
    }

    /**
     * @return Collection|VehicleDto[]
     */
    public function featured()
    {
        return (new Collection(
            $this->getData('vehicle/featured')
        ))->map(function($value){
            return new VehicleDto($value);
        });
    }

    /**
     * @return Collection
     */
    public function categories()
    {
        $data = $this->getData('category');

        return (new PaginationCollection($data))->map(function($attributes){
            return new CategoryDto($attributes);
        });
    }

    private function get(string $path): array
    {
        $res = $this->guzzle()->request('GET', $path);

        if($res->getStatusCode() != 200) {
            throw new DealerInventoryServiceException($res->getBody()->getContents());
        }

        $result = \GuzzleHttp\json_decode($res->getBody()->getContents(), true);

        return $result;
    }

    /**
     * @param MessageDto $message
     */
    public function message($message)
    {
        if(is_array($message)) {
            $message = new MessageDto($message);
        }

        $res = $this->guzzle()->post('contact/message', [
            'json'=>$message,
        ]);

        if($res->getStatusCode() != 204) {
            throw new DealerInventoryServiceException($res->getBody()->getContents());
        }
    }

    public function inquire(string $vehicleSlug, MessageDto $message)
    {
        if(is_array($message)) {
            $message = new MessageDto($message);
        }

        $message->slug = $vehicleSlug;
        $res = $this->guzzle()->post("contact/inquire/$vehicleSlug", [
            'json'=>$message,
        ]);

        if($res->getStatusCode() != 204) {
            throw new DealerInventoryServiceException($res->getBody()->getContents());
        }
    }

    private function getData(string $path): array
    {
        return $this->get($path)['data'];
    }

    private function guzzle()
    {
        $client = new Client([
            'base_uri' => self::$domain.'/api/'.$this->clientKey.'/',
            'headers'  => [
                'X-Client-Key' => $this->clientKey,
                'User-Agent' => 'PHP-DealerInventory-Client PHP/' . PHP_VERSION,
            ]
        ]);

        return $client;
    }
}

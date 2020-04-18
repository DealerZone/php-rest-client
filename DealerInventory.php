<?php

namespace DealerInventory\Client;

use DealerInventory\Client\Collection\PaginationCollection;
use DealerInventory\Client\Dto\MessageDto;
use DealerInventory\Client\Exception\DealerInventoryServiceException;
use GuzzleHttp\Client;
use DealerInventory\Client\Dto\InfoDto;
use DealerInventory\Client\Dto\MakeDto;
use DealerInventory\Client\Dto\ModelDto;
use GuzzleHttp\RequestOptions;
use Tightenco\Collect\Support\Collection;
use DealerInventory\Client\Dto\VehicleDto;
use DealerInventory\Client\Dto\CategoryDto;

class DealerInventory
{
    private static $dns = 'https://dealerinventory.app';

    private $clientKey;

    /** @var Client */
    private $guzzle;

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
     * @param boolean all return all makes, even ones with no stock
     * @return Collection|MakeDto[]
     */
    public function makes($all = false)
    {
        return (new Collection(
            $this->getData('make?all='.(int) $all)
        ))->map(function($value){
            $value['models'] = (new Collection($value['models']))->map(function($value){
                return new ModelDto($value);
            });
            return new MakeDto($value);
        });
    }

    /**
     * @param string $slug
     * @return MakeDto
     */
    public function make($slug)
    {
        return new MakeDto($this->getData('make/'.$slug));
    }

    /**
     * @param string $slug
     * @return ModelDto
     */
    public function model($makeSlug, $modelSlug)
    {
        return new ModelDto($this->getData('make/'.$makeSlug.'/model/'.$modelSlug));
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
    public function listed(int $page, $filters = [])
    {
        unset($filters['page']);
        $params = http_build_query((array) $filters);

        $result = $this->get("vehicle/listed?page=$page&$params");

        $collection = new PaginationCollection(
            $result['data'],
            $result['meta'],
            $result['links']
        );

        return $collection->map(function($value){
            return new VehicleDto($value);
        });
    }

    /**
     * @return Collection|VehicleDto[]
     */
    public function all()
    {
        $result = $this->get("vehicle/listed/all");

        return (new Collection($result['data']))->map(function($value){
            return new VehicleDto($value);
        });
    }

    /**
     * @return PaginationCollection|VehicleDto[]
     */
    public function sold(int $page)
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

        return (new Collection($data))->map(function($attributes){
            return new CategoryDto($attributes);
        });
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
            RequestOptions::JSON => $message->toArray(),
        ]);

        if($res->getStatusCode() != 204) {
            throw new DealerInventoryServiceException($res->getBody()->getContents(), $res->getStatusCode());
        }
    }

    public function inquire(string $vehicleSlug, MessageDto $message)
    {
        if(is_array($message)) {
            $message = new MessageDto($message);
        }

        $message->vehicle_slug = $vehicleSlug;
        $res = $this->guzzle()->post("contact/inquire/$vehicleSlug", [
            RequestOptions::JSON => $message,
        ]);

        if($res->getStatusCode() != 204) {
            throw new DealerInventoryServiceException($res->getBody()->getContents(), $res->getStatusCode());
        }
    }

    private function get(string $path): array
    {
        $res = $this->guzzle()->request('GET', $path);

        if($res->getStatusCode() != 200) {
            throw new DealerInventoryServiceException($res->getBody()->getContents(), $res->getStatusCode());
        }

        $result = \GuzzleHttp\json_decode($res->getBody()->getContents(), true);

        return $result;
    }

    public function _setGuzzle($guzzle)
    {
        $this->guzzle = $guzzle;
    }

    private function getData(string $path): array
    {
        return $this->get($path)['data'];
    }

    private function guzzle()
    {
        if(empty($this->guzzle)) {
            $this->guzzle = new Client([
                'base_uri' => self::dns() . '/api/' . $this->clientKey . '/',
                'headers' => [
                    'X-Client-Key' => $this->clientKey,
                    'User-Agent' => 'PHP-DealerInventory-Client PHP/' . PHP_VERSION,
                ]
            ]);
        }

        return $this->guzzle;
    }

    private static function dns()
    {
        $dns = getenv('DEALERINVENTORY_DNS');

        if($dns) {
            return $dns;
        }

        return self::$dns;
    }
}

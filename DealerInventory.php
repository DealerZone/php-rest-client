<?php

namespace DealerInventory\Client;

use DealerInventory\Client\Collection\PaginationCollection;
use DealerInventory\Client\Dto\AutoPartDto;
use DealerInventory\Client\Dto\FindDto;
use DealerInventory\Client\Dto\MessageDto;
use DealerInventory\Client\Dto\RelatedDto;
use DealerInventory\Client\Exception\DealerInventoryServiceException;
use DealerInventory\Client\Dto\InfoDto;
use DealerInventory\Client\Dto\MakeDto;
use DealerInventory\Client\Dto\ModelDto;
use GuzzleHttp\RequestOptions;
use Illuminate\Support\Collection;
use DealerInventory\Client\Dto\VehicleDto;
use DealerInventory\Client\Dto\CategoryDto;

class DealerInventory extends Client
{
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
     * @param array $parameters
     * @return FindDto
     */
    public function find($parameters)
    {
        $response = $this->post('vehicle/find', $parameters);

        return new FindDto([
            'success' => $response['success'],
            'vehicle' => new RelatedDto($response['data']),
        ]);
    }

    /**
     * @param string $slug
     * @return Collection|RelatedDto[]
     */
    public function related($slug, $limit = 5)
    {
        $result = $this->get("vehicle/related/$slug?limit=".$limit);

        $collection = new Collection($result['data']);

        return $collection->map(function($value){
            return new RelatedDto($value);
        });
    }

    /**
     * @param integer page
     * @param Filters|array filters
     * @return PaginationCollection|VehicleDto[]
     *
     * Filters:
     *   price
     *   sort
     *   make
     *   model
     *   recent
     *   search
     *   category
     *   location
     *   title
     *   condition
     *   stock_number
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
     * @return PaginationCollection
     */
    public function availableAutoParts($page = 1, $filters = [])
    {
        unset($filters['page']);
        $params = http_build_query((array) $filters);

        $result = $this->get("auto-part/available?page=$page&$params");

        $collection = new PaginationCollection(
            $result['data'],
            $result['meta'],
            $result['links']
        );

        return $collection->map(function($value){
            return new AutoPartDto($value);
        });
    }

    /**
     * @param $slug
     */
    public function autoPart($slug)
    {
        $data = $this->getData('auto-part/show/'.$slug);

        return new AutoPartDto($data);
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

    public function addToWaitingList(string $vehicleSlug, string $email)
    {
        $this->post("vehicle/waiting-list/$vehicleSlug", [
            'email' => $email,
        ]);
    }
}

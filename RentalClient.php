<?php

namespace DealerInventory\Client;

use DealerInventory\Client\Dto\RentalDto;
use Tightenco\Collect\Support\Collection;

class RentalClient extends Client
{
    /**
     * List all rentals available
     * @return Collection|RentalDto[]
     */
    public function rentals()
    {
        $result = $this->get("rental");

        $collection = new Collection($result['data']);

        return $collection->map(function($value){
            return new RentalDto($value);
        });
    }

    /**
     * @param string slug
     * @return RentalDto
     */
    public function rental($slug)
    {
        return new RentalDto(
            $this->getData('rental/'.$slug)
        );
    }
}

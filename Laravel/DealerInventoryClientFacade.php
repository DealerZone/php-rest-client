<?php

namespace DealerInventory\Client\Laravel;

use DealerInventory\Client\DealerInventory;
use Illuminate\Support\Facades\Facade;

class DealerInventoryClientFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return DealerInventory::class;
    }
}

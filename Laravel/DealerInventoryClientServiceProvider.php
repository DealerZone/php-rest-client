<?php

namespace DealerInventory\Client\Laravel;

use DealerInventory\Client\DealerInventory;
use Illuminate\Support\ServiceProvider;

class DealerInventoryClientServiceProvider extends ServiceProvider
{
    /** @var bool */
    protected $defer = true;

    public function register()
    {
        $this->app->singleton(DealerInventory::class, function ($app) {
            $key = config('dealerinventory.client_key');

            if(empty($key)) {
                throw new DealerInventoryClientException('Config value not found. You must set config \'dealerinventory.client_key\'');
            }

            return new DealerInventory(config('dealerinventory.client_key'));
        });
    }

    public function provides()
    {
        return [DealerInventory::class];
    }
}

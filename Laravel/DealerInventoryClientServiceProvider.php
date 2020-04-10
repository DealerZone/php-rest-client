<?php

namespace DealerInventory\Client\Laravel;

use DealerInventory\Client\DealerInventory;
use DealerInventory\Client\Exception\DealerInventoryClientException;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class DealerInventoryClientServiceProvider extends ServiceProvider implements DeferrableProvider
{
    public function register()
    {
        $this->app->singleton(DealerInventory::class, function ($app) {
            $key = config('dealerinventory.client_key');

            if(empty($key) && !app()->runningInConsole()) {
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

<?php

namespace DealerInventory\Client\Laravel;

use App\Client\Laravel\CachedDealerInventoryClient;
use DealerInventory\Client\DealerInventory;
use DealerInventory\Client\Exception\DealerInventoryClientException;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class DealerInventoryClientServiceProvider extends ServiceProvider implements DeferrableProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__.'/config.php' => config_path('dealerinventory.php'),
        ]);
    }

    public function register()
    {
        $this->app->singleton(DealerInventory::class, function ($app) {
            $key = config('dealerinventory.client_key');

            if(empty($key) && !app()->runningInConsole()) {
                throw new DealerInventoryClientException('Config value not found. You must set config \'dealerinventory.client_key\'');
            }

            return new CachedDealerInventoryClient(config('dealerinventory.client_key'), \Cache::driver());
        });
    }

    public function provides()
    {
        return [DealerInventory::class];
    }
}

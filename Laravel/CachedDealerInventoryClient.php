<?php

namespace DealerInventory\Client\Laravel;

use Carbon\Carbon;
use DealerInventory\Client\DealerInventory;
use Illuminate\Cache\Repository;

class CachedDealerInventoryClient extends DealerInventory
{
    /** @var Repository */
    private Repository $cache;

    /** @var int */
    private $cacheMinutes;

    /** @var string */
    private $cachePrefix;

    public function __construct(string $clientKey, Repository $cache)
    {
        parent::__construct($clientKey);

        $this->cache = $cache;

        $this->cacheMinutes = config('dealerinventory.cache_minutes', 45);

        $this->cachePrefix = config('dealerinventory.cache_prefix', 'dealerinventory');
    }

    /**
     * @inheritDoc
     */
    public function featured()
    {
        return $this->cache->remember($this->cachePrefix.'.featured', Carbon::now()->addMinutes($this->cacheMinutes), function() {
            return parent::featured();
        });
    }

    /**
     * @inheritDoc
     */
    public function sold(int $page)
    {
        return $this->cache->remember($this->cachePrefix.'.sold.'.$page, Carbon::now()->addMinutes($this->cacheMinutes * 2), function() use($page) {
            return parent::sold($page);
        });
    }

    /**
     * @inheritDoc
     */
    public function categories()
    {
        return $this->cache->remember($this->cachePrefix.'.categories', Carbon::now()->addMinutes($this->cacheMinutes * 10), function() {
            return parent::categories();
        });
    }

    /**
     * @inheritDoc
     */
    public function info()
    {
        return $this->cache->remember($this->cachePrefix.'.info', Carbon::now()->addMinutes($this->cacheMinutes), function() {
            return parent::info();
        });
    }

    /**
     * @inheritDoc
     */
    public function vehicle($slug)
    {
        return $this->cache->remember($this->cachePrefix.'.vehicle.'.$slug, Carbon::now()->addMinutes($this->cacheMinutes), function() use($slug) {
            return parent::vehicle($slug);
        });
    }

    /**
     * @inheritDoc
     */
    public function make($slug)
    {
        return $this->cache->remember($this->cachePrefix.'.make.'.$slug, Carbon::now()->addMinutes($this->cacheMinutes), function() use($slug) {
            return parent::make($slug);
        });
    }
}

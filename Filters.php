<?php

namespace DealerInventory\Client;

/**
 * @property array filters
 */
class Filters
{
    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    public static function make()
    {
        return new static;
    }
}
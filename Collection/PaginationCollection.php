<?php

namespace DealerInventory\Client\Collection;

use Tightenco\Collect\Support\Collection;

class PaginationCollection extends Collection
{
    private $links;
    private $meta;

    public function links(): LinksDto
    {
        return $this->links;
    }

    public function meta(): MetaDto
    {
        return $this->meta;
    }

    public function setLinks(array $links)
    {
        $this->links = new LinksDto($links);
        return $this;
    }

    public function setMeta(array $meta)
    {
        $this->meta = new MetaDto($meta);
        return $this;
    }

    public function hasNextPage(): bool
    {
        return $this->meta->current_page < $this->meta->last_page;
    }

    public function hasPrevPage(): bool
    {
        return $this->meta->current_page > 1;
    }
}
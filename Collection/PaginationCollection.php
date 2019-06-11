<?php

namespace DealerInventory\Client\Collection;

use Tightenco\Collect\Support\Collection;

class PaginationCollection extends Collection
{
    /** @var LinksDto */
    private $links;

    /** @var MetaDto */
    private $meta;

    public function __construct($data = [], $meta = null, $links = null)
    {
        parent::__construct($data);

        if($meta) {
            $this->setMeta($meta);
        }

        if($links) {
            $this->setLinks($links);
        }
    }

    /**
     * @return LinksDto
     */
    public function links()
    {
        return $this->links;
    }

    /**
     * @return MetaDto
     */
    public function meta()
    {
        return $this->meta;
    }

    /**
     * @param array $links
     * @return $this
     */
    public function setLinks($links)
    {
        $this->links = new LinksDto($links);
        return $this;
    }

    /**
     * @param array $meta
     * @return $this
     */
    public function setMeta($meta)
    {
        if($meta) {
            $this->meta = new MetaDto($meta);
        }
        return $this;
    }

    /**
     * @return bool
     */
    public function shouldRedirect()
    {
        return !empty($this->meta->redirect);
    }

    /**
     * @return string
     */
    public function redirectUrl()
    {
        return $this->meta->redirect;
    }

    /**
     * @return bool
     */
    public function hasNextPage()
    {
        return $this->meta->current_page < $this->meta->last_page;
    }

    /**
     * @return bool
     */
    public function hasPrevPage(): bool
    {
        return $this->meta->current_page > 1;
    }

    /**
     * @inheritDoc
     */
    public function map(callable $callback)
    {
        $keys = array_keys($this->items);

        $items = array_map($callback, $this->items, $keys);

        return new static(array_combine($keys, $items), $this->meta, $this->links);
    }
}

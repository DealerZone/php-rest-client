<?php

namespace DealerInventory\Client\Dto;

use JsonSerializable;
use RuntimeException;
use Tightenco\Collect\Contracts\Support\Arrayable;
use Tightenco\Collect\Support\Collection;

abstract class Base implements JsonSerializable, Arrayable
{
    /** @var array */
    protected $values = [];

    /** @var array */
    protected $casts = [];

    /**
     * @param iterable $values
     */
    public function __construct($values = [])
    {
        if($values instanceof self) {
            $values = $values->toArray();
        }

        $this->fill($values);
    }

    /**
     * @param array $values
     * @return $this
     */
    protected function fill(iterable $values = [])
    {
        if (!empty($values)) {
            foreach ($values as $field => $value) {
                $this->setValue($field, $value);
            }
        }

        return $this;
    }

    /**
     * @param string $field
     * @param mixed $value
     * @return $this
     */
    protected function setValue($field, $value)
    {
        $this->values[$field] = $this->cast($field, $value);

        return $this;
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return $this->values;
    }

    /**
     * @param string $field
     * @param mixed $value
     * @throws RuntimeException
     */
    public function __set($field, $value)
    {
        throw new RuntimeException("Cannot Update `{$field}` - Fields are Immutable");
    }

    /**
     * @param string $field
     * @return mixed
     */
    public function __get($field)
    {
        if (isset($this->values[$field])) {
            return $this->values[$field];
        }

        return null;
    }

    /**
     * @param string $field
     * @return bool
     */
    public function __isset($field)
    {
        return isset($this->values[$field]);
    }

    private function cast(string $field, $value)
    {
        if(array_key_exists($field, $this->casts)) {
            $castTo = $this->casts[$field];

            // maybe, if numeric index, do a collection of objects
            // then if string indexed to a object
            // that might be a better way, but i don't know how to handle empty arrays
            if(is_array($castTo)) {
                $value = (new Collection($value))->mapInto($castTo[0]);
            } elseif(class_exists($castTo)) {
                $value = new $castTo($value);
            }
        }

        return $value;
    }
}

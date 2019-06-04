<?php

namespace DealerInventory\Client\Dto;

use JsonSerializable;
use RuntimeException;

abstract class Dto implements JsonSerializable
{
    /** @var array */
    protected $values = [];

    /** @var array */
    protected $casts = [];

    public function __construct(iterable $values = [])
    {
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
        throw new RuntimeException('Fields are immutable');
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
            if(is_a($value, $castTo)) {
                // already set, do nothing
            } elseif(class_exists($castTo)) {
                $value = new $castTo($value);
            }
        }

        return $value;
    }
}

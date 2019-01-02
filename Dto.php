<?php

namespace VehicleInventory\Client;

use JsonSerializable;
use RuntimeException;

abstract class Dto implements JsonSerializable
{
    /**
     * @var array
     */
    protected $values = array();

    public function __construct(array $values = array())
    {
        $this->fill($values);
    }

    /**
     * @param array $values
     * @return $this
     */
    protected function fill(array $values = array())
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
        $this->values[$field] = $value;

        return $this;
    }

    /**
     * @return array
     */
    public function jsonSerialize()
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
}

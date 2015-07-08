<?php

namespace Choccybiccy\Telegram;

/**
 * Class Entity
 * @package Choccybiccy\Telegram
 */
class Entity
{

    /**
     * @var array
     */
    protected $data = [];

    /**
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        foreach($data as $key => $value) {
            $this->set($key, $value);
        }
    }

    /**
     * @param string $key
     * @param mixed $value
     * @return $this
     */
    public function set($key, $value)
    {
        $this->data[$key] = $value;
        $entity = $this->mapToEntity($key, $value);
        if($entity instanceof Entity) {
            $this->data[$key] = $value;
        }
        return $this;
    }

    /**
     * Get from data
     *
     * @param string $key
     * @return mixed
     */
    public function get($key)
    {
        if($this->exists($key)) {
            return $this->data[$key];
        }
        return null;
    }

    /**
     * @param string $key
     * @return bool
     */
    public function exists($key)
    {
        if(array_key_exists($key, $this->data)) {
            return true;
        }
        return false;
    }

    /**
     * @param string $key
     * @param mixed $value
     */
    public function __set($key, $value)
    {
        $this->set($key, $value);
    }

    /**
     * @param string $key
     * @return mixed|null
     */
    public function __get($key)
    {
        return $this->get($key);
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $array = [];
        foreach($this->data as $key => $value) {
            if($value instanceof Entity) {
                $value = $value->toArray();
            }
            $array[$key] = $value;
        }
        return $array;
    }

    /**
     * Map a value to another entity
     *
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function mapToEntity($key, $value)
    {
    }
}

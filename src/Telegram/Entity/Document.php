<?php

namespace Choccybiccy\Telegram\Entity;

/**
 * Class Document
 * @package Choccybiccy\Telegram\Entity
 */
class Document extends Entity
{

    /**
     * @param string $key
     * @param mixed $value
     * @return mixed
     */
    public function mapToEntity($key, $value)
    {
        switch($key)
        {
            case "thumb":
                return new PhotoSize($value);
                break;
        }
    }
}
<?php

namespace Choccybiccy\Telegram\Entity;

use Choccybiccy\Telegram\Entity;

/**
 * Class Video
 * @package Choccybiccy\Telegram\Entity
 */
class Video extends Entity
{

    /**
     * @param string $key
     * @param mixed $value
     * @return PhotoSize|void
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

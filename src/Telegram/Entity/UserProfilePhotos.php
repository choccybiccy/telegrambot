<?php

namespace Choccybiccy\Telegram\Entity;

use Choccybiccy\Telegram\Entity;

/**
 * Class UserProfilePhotos
 * @package Choccybiccy\Telegram\Entity
 */
class UserProfilePhotos extends Entity
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
            case "photos":
                $return = [];
                foreach($value as $photoSize) {
                    $return[] = new PhotoSize($photoSize);
                }
                break;
        }
    }
}
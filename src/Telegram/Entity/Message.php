<?php

namespace Choccybiccy\Telegram\Entity;

use Choccybiccy\Telegram\Entity;

/**
 * Class Message
 * @package Choccybiccy\Telegram\Entity
 */
class Message extends Entity
{

    /**
     * @param string $key
     * @param mixed $value
     * @return Entity|Entity[]|void
     */
    public function mapToEntity($key, $value)
    {
        switch($key)
        {
            case "date":
                return new \DateTime(date("Y-m-d H:i:s", $value));
                break;
            case "from":
            case "forward_from":
            case "new_chat_participant":
            case "left_chat_participant":
                return new User($value);
                break;
            case "chat":
                if (array_key_exists("title", $value)) {
                    return new GroupChat($value);
                }
                return new User($value);
                break;
            case "reply_to_message":
                return new Message($value);
                break;
            case "audio":
                return new Audio($value);
                break;
            case "document":
                return new Document($value);
                break;
            case "sticker":
                return new Sticker($value);
                break;
            case "video":
                return new Video($value);
                break;
            case "contact":
                return new Contact($value);
                break;
            case "location":
                return new Location($value);
                break;
            case "photo":
            case "new_chat_photo":
                $return = [];
                foreach ($value as $photoSize) {
                    $return[] = new PhotoSize($photoSize);
                }
                return $return;
                break;
        }
    }
}

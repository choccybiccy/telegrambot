<?php

namespace Choccybiccy\Telegram\Entity;

use Choccybiccy\Telegram\Entity;

/**
 * Class Update
 * @package Choccybiccy\Telegram\Entity
 */
class Update extends Entity
{

    /**
     * @param string $key
     * @param mixed $value
     * @return Message|void
     */
    public function mapToEntity($key, $value)
    {
        switch ($key) {
            case "message":
                return new Message($value);
        }
    }
}

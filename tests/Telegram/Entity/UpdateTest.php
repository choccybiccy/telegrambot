<?php

namespace Choccybiccy\Telegram\Entity;

use Choccybiccy\Telegram\EntityTest;

/**
 * Class VideoTest
 * @package Choccybiccy\Telegram\Entity
 */
class VideoTest extends EntityTest
{

    /**
     * @var array
     */
    protected $entityMaps = [
        "thumb" => "PhotoSize",
    ];
}

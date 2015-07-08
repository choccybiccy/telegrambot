<?php

namespace Choccybiccy\Telegram\Entity;

/**
 * Class GroupChat
 * @package Choccybiccy\Telegram\Entity
 */
class InputFile
{

    /**
     * @var string
     */
    protected $fileContents;

    /**
     * @param string $fileContents
     */
    public function __construct($fileContents)
    {
        $this->fileContents($fileContents);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->fileContents;
    }
}

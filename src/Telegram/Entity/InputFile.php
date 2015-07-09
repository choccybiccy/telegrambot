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
        $this->setFileContents($fileContents);
    }

    /**
     * @return string
     */
    public function getFileContents()
    {
        return $this->fileContents;
    }

    /**
     * @param $fileContents
     * @return $this
     */
    public function setFileContents($fileContents)
    {
        $this->fileContents = $fileContents;
        return $this;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->fileContents;
    }
}

<?php

namespace Choccybiccy\Telegram\Entity;

/**
 * Class InputFileTest
 * @package Choccybiccy\Telegram\Entity
 */
class InputFileTest extends \PHPUnit_Framework_TestCase
{

    /**
     *
     */
    public function testConstruct()
    {

        $entity = new InputFile("testdata");
        $this->assertEquals("testdata", $entity->getFileContents());
        $this->assertEquals("testdata", (string) $entity);

    }
}
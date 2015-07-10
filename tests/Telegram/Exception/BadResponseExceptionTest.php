<?php

namespace Choccybiccy\Telegram\Exception;

use Choccybiccy\Telegram\Entity\Response;

/**
 * Class BadResponseExceptionTest
 * @package Choccybiccy\Telegram\Exception
 */
class BadResponseExceptionTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Test Response sets
     */
    public function testSetGetResponse()
    {
        $response = new Response();
        $exception = new BadResponseException();
        $exception->setResponse($response);
        $this->assertEquals($response, $exception->getResponse());
    }
}

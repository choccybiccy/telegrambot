<?php

namespace Choccybiccy\Telegram;

use Choccybiccy\Telegram\Entity\Message;

/**
 * Class CommandTest
 * @package Choccybiccy\Telegram
 */
class CommandTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @param string $trigger
     * @param array $methods
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getMockCommand($trigger = null, $methods = [])
    {
        $command = $this->getMockBuilder("Choccybiccy\\Telegram\\Command")
            ->setConstructorArgs([$trigger])
            ->setMethods($methods);
        return $command->getMockForAbstractClass();
    }

    /**
     * Test that trigger is set when provided
     */
    public function testConstructSetsTrigger()
    {
        $command = $this->getMockCommand("trigger");
        $this->assertEquals("trigger", $command->getTrigger());
    }

    /**
     * Test validation fails correctly
     */
    public function testValidateTriggerThrowsInvalidArgumentException()
    {
        $this->setExpectedException("\\InvalidArgumentException");
        $this->getMockCommand("this should fail!");
    }

    /**
     * Test command throws \LogicException when no trigger is set
     */
    public function testCommandThrowsLogicExceptionWhenNoTrigger()
    {
        $this->setExpectedException("\\LogicException");
        $this->getMockCommand();
    }

    /**
     * Test run works as expected
     */
    public function testRun()
    {
        $argument = "test argument";
        $apiClient = new ApiClient("test");
        $message = new Message();
        $command = $this->getMockCommand("trigger", ["execute"]);
        $command->expects($this->once())
            ->method("execute")
            ->with($argument, $message);

        $command->run($argument, $message, $apiClient);
        $this->assertEquals($apiClient, $command->getApiClient());
    }
}
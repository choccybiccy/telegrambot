<?php

namespace Choccybiccy\Telegram;

use Choccybiccy\Telegram\Entity\Update;
use Choccybiccy\Telegram\Traits\ReflectionMethods;

/**
 * Class CommandHandlerTest
 * @package Choccybiccy\Telegram
 */
class CommandHandlerTest extends \PHPUnit_Framework_TestCase
{

    use ReflectionMethods;

    /**
     * @param array $methods
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getMockCommand($methods = [])
    {
        return $this->getMockBuilder("Choccybiccy\\Telegram\\Command")
            ->setMethods($methods)
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();
    }

    /**
     * Test construct sets apiClient
     */
    public function testConstructSetsApiClient()
    {
        $apiClient = new ApiClient("test");
        $handler = new CommandHandler($apiClient);
        $this->assertEquals($apiClient, $this->getProtectedProperty($handler, "apiClient"));
    }

    /**
     * Test commands are registered properly
     */
    public function testRegisterCommand()
    {
        $handler = new CommandHandler(new ApiClient("test"));
        $command = $this->getMockCommand();
        $handler->register($command);
        $this->assertEquals([$command], $this->getProtectedProperty($handler, "commands"));
    }

    /**
     * Test run triggers the run on the command
     */
    public function testRun()
    {

        $apiClient = new ApiClient("test");

        $args = "arg1 arg2";
        $update = new Update(["message" => ["text" => "/test " . $args]]);

        $command = $this->getMockCommand(["run"]);
        $command->setTrigger("test");
        $command->expects($this->once())
            ->method("run")
            ->with($args, $update->message, $apiClient);

        $handler = new CommandHandler($apiClient);
        $handler->register($command);

        $handler->run($update);

    }
}

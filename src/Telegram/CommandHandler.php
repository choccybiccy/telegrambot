<?php

namespace Choccybiccy\Telegram;

use Choccybiccy\Telegram\Entity\Update;

/**
 * Class CommandHandler
 * @package Choccybiccy\Telegram
 */
class CommandHandler
{

    /**
     * @var ApiClient
     */
    protected $apiClient;

    /**
     * @var Command[]
     */
    protected $commands = [];

    /**
     * Constructor
     *
     * @param ApiClient $apiClient
     */
    public function __construct(ApiClient $apiClient)
    {
        $this->apiClient = $apiClient;
    }

    /**
     * Register a command
     *
     * @param Command $command
     */
    public function register(Command $command)
    {
        $this->commands[] = $command;
    }

    /**
     * @param Update $update
     */
    public function run(Update $update)
    {

        /** @var Entity\Message $message */
        $message = $update->message;
        $text = $message->text;

        if (substr($text, 0, 1) == "/") {
            foreach ($this->commands as $command) {
                if (preg_match('/^\/' . $command->getTrigger() . '\s?(.*)$/', $text, $matches)) {
                    $command->run(trim($matches[1]), $message, $this->apiClient);
                }
            }
        }

    }
}

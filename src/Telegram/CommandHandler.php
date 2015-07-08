<?php

namespace Choccybiccy\Telegram;

use Choccybiccy\Telegram\Entity\Update;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;

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
    protected $commands;

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
     * @param $command
     */
    public function register(Command $command)
    {
        $this->commands[] = $command;
    }

    /**
     * @param Request $request
     */
    public function handleUpdate(Request $request)
    {

        $json = new ParameterBag((array) json_decode($request->getContent(), true));
        $update = new Update($json->all());

    }
}
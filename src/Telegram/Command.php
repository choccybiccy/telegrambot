<?php

namespace Choccybiccy\Telegram;

/**
 * Interface Command
 * @package Choccybiccy\Telegram
 */
interface Command
{

    /**
     * Run the command
     *
     * @return mixed
     */
    public function run();
}
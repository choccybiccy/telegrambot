<?php

namespace Choccybiccy\Telegram;

use Choccybiccy\Telegram\Entity\Message;
use Choccybiccy\Telegram\Entity\ReplyKeyboardMarkup;

/**
 * Class Command
 * @package Choccybiccy\Telegram
 */
abstract class Command
{

    /**
     * @var string
     */
    protected $trigger;

    /**
     * @var ApiClient
     */
    protected $apiClient;

    /**
     * @var bool
     */
    protected $isReply = false;

    /**
     * @var string
     */
    protected $originalArgument;

    /**
     * @var Message
     */
    protected $originalMessage;

    /**
     * @param string $trigger
     */
    public function __construct($trigger = null)
    {

        if ($trigger !== null) {
            $this->setTrigger($trigger);
        }

        $this->configure();

        if (!$this->trigger) {
            throw new \LogicException("The command defined in \"".get_class($this)."\" cannot have an empty trigger");
        }

    }

    /**
     * Configure the command
     */
    public function configure()
    {
    }

    /**
     * Set the trigger
     *
     * @param string $trigger
     * @return $this
     */
    public function setTrigger($trigger)
    {
        $this->validateTrigger($trigger);
        $this->trigger = $trigger;
        return $this;
    }

    /**
     * Get trigger
     *
     * @return string
     */
    public function getTrigger()
    {
        return $this->trigger;
    }

    /**
     * Validate a trigger contains only a word, underscore or hyphen character
     *
     * @param $trigger
     * @throws \InvalidArgumentException
     */
    private function validateTrigger($trigger)
    {
        if (!preg_match('/^[\w\-]+$/', $trigger)) {
            throw new \InvalidArgumentException("Command trigger \"" . $trigger . "\" is invalid");
        }
    }

    /**
     * @return ApiClient
     */
    public function getApiClient()
    {
        return $this->apiClient;
    }

    /**
     * Set the Api instance
     *
     * @param ApiClient $apiClient
     * @return $this
     */
    public function setApiClient(ApiClient $apiClient)
    {
        $this->apiClient = $apiClient;
        return $this;
    }

    /**
     * Execute the command
     *
     * @param string $argument
     * @param Message $message
     * @return mixed
     */
    abstract protected function execute($argument, Message $message);

    /**
     * Run the command
     *
     * @param string $argument
     * @param Message $message
     * @param ApiClient $apiClient
     */
    public function run($argument, Message $message, ApiClient $apiClient)
    {
        $this->setApiClient($apiClient);
        $this->execute($argument, $message);
    }

    /**
     * Run the command with the users reply
     *
     * @param string $reply
     * @param Message $message
     * @param string $originalArgument
     * @param Message $originalMessage
     * @param ApiClient $apiClient
     */
    public function runReply(
        $reply,
        Message $message,
        $originalArgument,
        Message $originalMessage,
        ApiClient $apiClient
    ) {
        $this->setApiClient($apiClient);
        $this->isReply = true;
        $this->originalArgument = $originalArgument;
        $this->originalMessage = $originalMessage;
        $this->execute($reply, $message);
    }

    /**
     * Is the user replying to a reply prompt?
     *
     * @return bool
     */
    protected function isReply()
    {
        return (bool) $this->isReply;
    }

    /**
     * Get the original argument before the reply prompt
     *
     * @return string
     */
    protected function getOriginalArgument()
    {
        return $this->originalArgument;
    }

    /**
     * Get the original message before the reply prompt
     *
     * @return Message
     */
    protected function getOriginalMessage()
    {
        return $this->originalMessage;
    }

    /**
     * Prompt the sender for a reply
     *
     * @param string $text
     * @param Message $message
     * @param ReplyKeyboardMarkup $replyKeyboardMarkup
     * @param bool $disableWebPagePreview
     * @return Message
     */
    protected function promptReply(
        $text,
        Message $message,
        ReplyKeyboardMarkup $replyKeyboardMarkup,
        $disableWebPagePreview = false
    ) {

        $text = $text . " [" . trim(preg_replace("/\s\s+/", " ", ($message->text))) . "]";
        return $this->getApiClient()
            ->sendMessage(
                $message->chat->id,
                $text,
                $disableWebPagePreview,
                $message->message_id,
                $replyKeyboardMarkup
            );
    }
}

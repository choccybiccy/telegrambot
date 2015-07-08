<?php

namespace Choccybiccy\Telegram;

use GuzzleHttp\Client as Client;
use Choccybiccy\Telegram\Entity;
use Choccybiccy\Telegram\Entity\ForceReply;
use Choccybiccy\Telegram\Entity\InputFile;
use Choccybiccy\Telegram\Entity\Message;
use Choccybiccy\Telegram\Entity\ReplyKeyboardHide;
use Choccybiccy\Telegram\Entity\ReplyKeyboardMarkup;
use Choccybiccy\Telegram\Entity\Update;
use Choccybiccy\Telegram\Entity\User;
use Choccybiccy\Telegram\Entity\UserProfilePhotos;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class ApiClient
 * @package Choccybiccy\Telegram
 */
class ApiClient extends Client
{

    const CHAT_ACTION_TYPING = "typing";
    const CHAT_ACTION_UPLOAD_PHOTO = "upload_photo";
    const CHAT_ACTION_RECORD_VIDEO = "record_video";
    const CHAT_ACTION_UPLOAD_VIDEO = "upload_video";
    const CHAT_ACTION_RECORD_AUDIO = "record_audio";
    const CHAT_ACTION_UPLOAD_AUDIO = "upload_audio";
    const CHAT_ACTION_UPLOAD_DOCUMENT = "upload_document";
    const CHAT_ACTION_FIND_LOCATION = "find_location";

    /**
     * @var string
     */
    protected $apiUrl = "https://api.telegram.org/";

    /**
     * @var string
     */
    protected $authenticationToken;

    /**
     * @param string $authenticationToken
     * @param array $config
     */
    public function __construct($authenticationToken, array $config = [])
    {
        parent::__construct($config);
        $this->setAuthenticationToken($authenticationToken);
    }

    /**
     * Get bot user instance
     *
     * @return User
     */
    public function getMe()
    {
        $response = $this->apiRequest("getMe");
        return $this->entityFromBody((string) $response->getBody(), "Entity\\User");
    }

    /**
     * Send a message
     *
     * @param int $chatId
     * @param string $text
     * @param bool|null $disableWebPagePreview
     * @param int|null $replyToMessageId
     * @param ReplyKeyboardMarkup|ReplyKeyboardHide|ForceReply|null $replyMarkup
     * @return Message
     */
    public function sendMessage($chatId, $text, $disableWebPagePreview = null, $replyToMessageId = null, $replyMarkup = null)
    {
    }

    /**
     * @param int $chatId
     * @param int $fromChatId
     * @param int $messageId
     * @return Message
     */
    public function forwardMessage($chatId, $fromChatId, $messageId)
    {
    }

    /**
     * @param int $chatId
     * @param InputFile|string $photo
     * @param string|null $caption
     * @param int|null $replyToMessageId
     * @param ReplyKeyboardMarkup|ReplyKeyboardHide|ForceReply|null $replyMarkup
     */
    public function sendPhoto($chatId, $photo, $caption = null, $replyToMessageId = null, $replyMarkup = null)
    {
    }

    /**
     * @param int $chatId
     * @param InputFile|string $audio
     * @param int|null $replyToMessageId
     * @param ReplyKeyboardMarkup|ReplyKeyboardHide|ForceReply|null $replyMarkup
     * @return Message
     */
    public function sendAudio($chatId, $audio, $replyToMessageId = null, $replyMarkup = null)
    {
    }

    /**
     * @param int $chatId
     * @param InputFile|string $document
     * @param int|null $replyToMessageId
     * @param ReplyKeyboardMarkup|ReplyKeyboardHide|ForceReply|null $replyMarkup
     * @return Message
     */
    public function sendDocument($chatId, $document, $replyToMessageId = null, $replyMarkup = null)
    {

    }

    /**
     * @param int $chatId
     * @param InputFile|string $sticker
     * @param int|null $replyToMessageId
     * @param ReplyKeyboardMarkup|ReplyKeyboardHide|ForceReply|null $replyMarkup
     * @return Message
     */
    public function sendSticker($chatId, $sticker, $replyToMessageId = null, $replyMarkup = null)
    {

    }

    /**
     * @param int $chatId
     * @param InputFile|string $video
     * @param int|null $replyToMessageId
     * @param ReplyKeyboardMarkup|ReplyKeyboardHide|ForceReply|null $replyMarkup
     * @return Message
     */
    public function sendVideo($chatId, $video, $replyToMessageId = null, $replyMarkup = null)
    {

    }

    /**
     * @param int $chatId
     * @param string $latitude
     * @param string $longitude
     * @param int|null $replyToMessageId
     * @param ReplyKeyboardMarkup|ReplyKeyboardHide|ForceReply|null $replyMarkup
     * @return Message
     */
    public function sendLocation($chatId, $latitude, $longitude, $replyToMessageId = null, $replyMarkup = null)
    {

    }

    /**
     * @param int $chatId
     * @param string $action
     * @return Entity
     */
    public function sendChatAction($chatId, $action)
    {

    }

    /**
     * @param int $userId
     * @param int $offset
     * @param int $limit
     * @return UserProfilePhotos
     */
    public function getUserProfilePhotos($userId, $offset, $limit)
    {

    }

    /**
     * @param int|null $offset
     * @param int|null $limit
     * @param int|null $timeout
     * @return Update[]
     */
    public function getUpdates($offset = null, $limit = null, $timeout = null)
    {

    }

    /**
     * @param string $url
     * @return Entity
     */
    public function setWebhook($url)
    {
        $response = $this->apiRequest("setWebhook", ["url" => $url]);
        return $this->entityFromBody((string) $response->getBody(), "Entity");
    }

    /**
     * @param string $endpoint
     * @param array $data
     * @return \Psr\Http\Message\ResponseInterface
     */
    protected function apiRequest($endpoint, array $data = [])
    {
        $botToken = "bot" . $this->authenticationToken;
        return $this->post($this->apiUrl . $botToken . "/" . $endpoint, ["form_params" => $data]);
    }

    /**
     * @param $authenticationToken
     * @return $this
     */
    public function setAuthenticationToken($authenticationToken)
    {
        $this->authenticationToken = $authenticationToken;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAuthenticationToken()
    {
        return $this->authenticationToken;
    }

    /**
     * Create an entity from request or response body
     *
     * @param string $body
     * @param string $entity
     * @return Entity
     */
    public function entityFromBody($body, $entity)
    {
        $json = new ParameterBag((array) json_decode($body, true));
        return new $entity($json->all());
    }
}
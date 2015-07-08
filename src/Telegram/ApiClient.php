<?php

namespace Choccybiccy\Telegram;

use GuzzleHttp\Client as Client;
use Choccybiccy\Telegram\Entity;
use Choccybiccy\Telegram\Entity\ForceReply;
use Choccybiccy\Telegram\Entity\InputFile;
use Choccybiccy\Telegram\Entity\Message;
use Choccybiccy\Telegram\Entity\ReplyKeyboardHide;
use Choccybiccy\Telegram\Entity\ReplyKeyboardMarkup;
use Choccybiccy\Telegram\Entity\Response;
use Choccybiccy\Telegram\Entity\Update;
use Choccybiccy\Telegram\Entity\User;
use Choccybiccy\Telegram\Entity\UserProfilePhotos;
use Symfony\Component\HttpFoundation\ParameterBag;

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
        return $this->entityFromBody($response->getBody(), "Entity\\User");
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
        $response = $this->apiRequest("sendMessage", [
            "chat_id" => $chatId,
            "text" => $text,
            "disable_web_page_preview" => $disableWebPagePreview,
            "reply_to_message_id" => $replyToMessageId,
            "reply_markup" => $replyMarkup->toArray(),
        ]);
        return $this->entityFromBody($response->getBody(), "Entity\\Message");
    }

    /**
     * @param int $chatId
     * @param int $fromChatId
     * @param int $messageId
     * @return Message
     */
    public function forwardMessage($chatId, $fromChatId, $messageId)
    {
        $response = $this->apiRequest("forwardMessage", [
            "chat_id" => $chatId,
            "from_chat_id" => $fromChatId,
            "message_id" => $messageId,
        ]);
        return $this->entityFromBody($response->getBody(), "Entity\\Message");
    }

    /**
     * @param int $chatId
     * @param InputFile|string $photo
     * @param string|null $caption
     * @param int|null $replyToMessageId
     * @param ReplyKeyboardMarkup|ReplyKeyboardHide|ForceReply|null $replyMarkup
     * @return Message
     */
    public function sendPhoto($chatId, $photo, $caption = null, $replyToMessageId = null, $replyMarkup = null)
    {
        $response = $this->apiRequest("sendPhoto", [
            "chat_id" => $chatId,
            "photo" => (string) $photo,
            "caption" => $caption,
            "reply_to_message_id" => $replyToMessageId,
            "reply_markup" => $replyMarkup->toArray()
        ]);
        return $this->entityFromBody($response->getBody(), "Entity\\Message");
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
        $response = $this->apiRequest("sendPhoto", [
            "chat_id" => $chatId,
            "audio" => (string) $audio,
            "reply_to_message_id" => $replyToMessageId,
            "reply_markup" => $replyMarkup->toArray()
        ]);
        return $this->entityFromBody($response->getBody(), "Entity\\Message");
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
        $response = $this->apiRequest("sendPhoto", [
            "chat_id" => $chatId,
            "document" => (string) $document,
            "reply_to_message_id" => $replyToMessageId,
            "reply_markup" => $replyMarkup->toArray()
        ]);
        return $this->entityFromBody($response->getBody(), "Entity\\Message");
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
        $response = $this->apiRequest("sendPhoto", [
            "chat_id" => $chatId,
            "sticker" => (string) $sticker,
            "reply_to_message_id" => $replyToMessageId,
            "reply_markup" => $replyMarkup->toArray()
        ]);
        return $this->entityFromBody($response->getBody(), "Entity\\Message");
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
        $response = $this->apiRequest("sendPhoto", [
            "chat_id" => $chatId,
            "video" => (string) $video,
            "reply_to_message_id" => $replyToMessageId,
            "reply_markup" => $replyMarkup->toArray()
        ]);
        return $this->entityFromBody($response->getBody(), "Entity\\Message");
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
        $response = $this->apiRequest("sendLocation", [
            "chat_id" => $chatId,
            "latitude" => $latitude,
            "longitude" => $longitude,
            "reply_to_message_id" => $replyToMessageId,
            "reply_markup" => $replyMarkup->toArray(),
        ]);
        return $this->entityFromBody($response->getBody(), "Entity\\Message");
    }

    /**
     * @param int $chatId
     * @param string $action
     * @return Response
     */
    public function sendChatAction($chatId, $action)
    {
        $response = $this->apiRequest("sendChatAction", [
            "chat_id" => $chatId,
            "action" => $action,
        ]);
        return $this->entityFromBody($response->getBody(), "Entity\\Response");
    }

    /**
     * @param int $userId
     * @param int $offset
     * @param int $limit
     * @return UserProfilePhotos
     */
    public function getUserProfilePhotos($userId, $offset, $limit)
    {
        $response = $this->apiRequest("getUserProfilePhotos", [
            "user_id" => $userId,
            "offset" => $offset,
            "limit" => $limit,
        ]);
        return $this->entityFromBody($response->getBody(), "Entity\\UserProfilePhotos");
    }

    /**
     * @param int|null $offset
     * @param int|null $limit
     * @param int|null $timeout
     * @return Update[]
     */
    public function getUpdates($offset = null, $limit = null, $timeout = null)
    {
        $response = $this->apiRequest("getUpdates", [
            "offset" => $offset,
            "limit" => $limit,
            "timeout" => $timeout,
        ]);
        $updates = $this->decodeJson($response->getBody());
        $return = [];
        foreach($updates as $update) {
            $return[] = new Update($update);
        }
        return $return;
    }

    /**
     * @param string $url
     * @return Response
     */
    public function setWebhook($url)
    {
        $response = $this->apiRequest("setWebhook", ["url" => $url]);
        return $this->entityFromBody((string) $response->getBody(), "Entity\\Response");
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
     * @param $string
     * @return array
     */
    protected function decodeJson($string)
    {
        return (array) json_decode((string) $string, true);
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
        $json = new ParameterBag($this->decodeJson($body));
        return new $entity($json->all());
    }
}

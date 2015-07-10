<?php

namespace Choccybiccy\Telegram;

use Choccybiccy\Telegram\Entity\InputFile;
use Choccybiccy\Telegram\Entity\Message;
use Choccybiccy\Telegram\Entity\ReplyKeyboardMarkup;
use Choccybiccy\Telegram\Entity\Response;
use Choccybiccy\Telegram\Entity\Update;
use Choccybiccy\Telegram\Entity\User;
use Choccybiccy\Telegram\Entity\UserProfilePhotos;
use Choccybiccy\Telegram\Exception\BadResponseException;
use Choccybiccy\Telegram\Traits\ReflectionMethods;

/**
 * Class ApiClientTest
 * @package Choccybiccy\Telegram
 */
class ApiClientTest extends \PHPUnit_Framework_TestCase
{

    use ReflectionMethods;

    /**
     * @param string $token
     * @param array|null $methods
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getMockApiClient($token, $methods = null)
    {
        return $this->getMockBuilder("Choccybiccy\\Telegram\\ApiClient")
            ->setConstructorArgs([$token])
            ->setMethods($methods)
            ->getMock();
    }

    /**
     * Test set/get authenticationToken
     */
    public function testSetGetAuthenticationToken()
    {
        $apiClient = new ApiClient("test");
        $this->assertEquals("test", $apiClient->getAuthenticationToken());
        $apiClient->setAuthenticationToken("another");
        $this->assertEquals("another", $apiClient->getAuthenticationToken());
    }

    /**
     * Test decode json works
     */
    public function testDecodeJson()
    {
        $data = ["data" => "test", "more" => ["data" => "test"]];
        $json = json_encode($data);
        $apiClient = new ApiClient("test");
        $this->assertEquals($data, $this->runProtectedMethod($apiClient, "decodeJson", [$json]));
    }

    /**
     * Test entityFromBody populates an entity
     */
    public function testEntityFromBody()
    {
        $apiClient = new ApiClient("test");
        $data = ["data" => "test"];
        $json = json_encode($data);

        $entity = $apiClient->entityFromBody($json, new Entity());
        $this->assertEquals($data, $this->getProtectedProperty($entity, "data"));
    }

    /**
     * Test api request
     */
    public function testApiRequest()
    {

        $token = "test";
        $endpoint = "example";
        $apiClient = $this->getMockApiClient($token, ["post"]);
        $apiUrl = $this->getProtectedProperty($apiClient, "apiUrl");
        $data = ["some" => "data"];

        $fullUrl = $apiUrl . "bot" . $token . "/" . $endpoint;

        $apiClient->expects($this->once())
            ->method("post")
            ->with($fullUrl, ["form_params" => $data]);

        $this->runProtectedMethod($apiClient, "apiRequest", [$endpoint, $data]);

    }

    /**
     * @param string $endpoint
     * @param Entity $entity
     * @param array|null $params
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function apiClientTest($endpoint, $entity, $params = null)
    {

        $data = json_encode(["test" => "data"]);

        $response = $this->getMockBuilder("Psr\\Http\\Message\\ResponseInterface")
            ->disableOriginalConstructor()
            ->setMethods(["getBody"])
            ->getMockForAbstractClass();
        $response->expects($this->once())
            ->method("getBody")
            ->willReturn($data);

        $apiClient = $this->getMockApiClient("test", ["apiRequest", "entityFromBody"]);

        $method = $apiClient->expects($this->once())
            ->method("apiRequest");
        if (count($params)) {
            $method->with($endpoint, $params);
        } else {
            $method->with($endpoint);
        }
        $method->willReturn($response);

        $apiClient->expects($this->once())
            ->method("entityFromBody")
            ->with($data, $entity);
        return $apiClient;
    }

    public function testGetMe()
    {
        /** @var ApiClient $apiClient */
        $apiClient = $this->apiClientTest("getMe", new User());
        $apiClient->getMe();
    }

    public function testSendMessage()
    {
        $replyMarkup = new ReplyKeyboardMarkup([]);

        /** @var ApiClient $apiClient */
        $apiClient = $this->apiClientTest("sendMessage", new Message(), [
            "chat_id" => 1,
            "text" => "test",
            "disable_web_page_preview" => false,
            "reply_to_message_id" => null,
            "reply_markup" => $replyMarkup->toArray(),
        ]);

        $apiClient->sendMessage(1, "test", false, null, $replyMarkup);
    }

    public function testForwardMessage()
    {
        /** @var ApiClient $apiClient */
        $apiClient = $this->apiClientTest("forwardMessage", new Message(), [
            "chat_id" => 1,
            "from_chat_id" => 2,
            "message_id" => 3,
        ]);
        $apiClient->forwardMessage(1, 2, 3);
    }

    public function testSendPhoto()
    {

        $photo = new InputFile("test");
        $replyMarkup = new ReplyKeyboardMarkup([]);

        /** @var ApiClient $apiClient */
        $apiClient = $this->apiClientTest("sendPhoto", new Message(), [
            "chat_id" => 1,
            "photo" => $photo,
            "caption" => "caption",
            "reply_to_message_id" => 2,
            "reply_markup" => $replyMarkup->toArray(),
        ]);

        $apiClient->sendPhoto(1, $photo, "caption", 2, $replyMarkup);

    }

    public function testSendAudio()
    {

        $file = new InputFile("test");
        $replyMarkup = new ReplyKeyboardMarkup([]);

        /** @var ApiClient $apiClient */
        $apiClient = $this->apiClientTest("sendAudio", new Message(), [
            "chat_id" => 1,
            "audio" => $file,
            "reply_to_message_id" => 2,
            "reply_markup" => $replyMarkup->toArray(),
        ]);

        $apiClient->sendAudio(1, $file, 2, $replyMarkup);

    }

    public function testSendDocument()
    {

        $file = new InputFile("test");
        $replyMarkup = new ReplyKeyboardMarkup([]);

        /** @var ApiClient $apiClient */
        $apiClient = $this->apiClientTest("sendDocument", new Message(), [
            "chat_id" => 1,
            "document" => $file,
            "reply_to_message_id" => 2,
            "reply_markup" => $replyMarkup->toArray(),
        ]);

        $apiClient->sendDocument(1, $file, 2, $replyMarkup);

    }

    public function testSendSticker()
    {

        $file = new InputFile("test");
        $replyMarkup = new ReplyKeyboardMarkup([]);

        /** @var ApiClient $apiClient */
        $apiClient = $this->apiClientTest("sendSticker", new Message(), [
            "chat_id" => 1,
            "sticker" => $file,
            "reply_to_message_id" => 2,
            "reply_markup" => $replyMarkup->toArray(),
        ]);

        $apiClient->sendSticker(1, $file, 2, $replyMarkup);

    }

    public function testSendVideo()
    {

        $file = new InputFile("test");
        $replyMarkup = new ReplyKeyboardMarkup([]);

        /** @var ApiClient $apiClient */
        $apiClient = $this->apiClientTest("sendVideo", new Message(), [
            "chat_id" => 1,
            "video" => $file,
            "reply_to_message_id" => 2,
            "reply_markup" => $replyMarkup->toArray(),
        ]);

        $apiClient->sendVideo(1, $file, 2, $replyMarkup);

    }

    public function testSendLocation()
    {

        $replyMarkup = new ReplyKeyboardMarkup([]);

        /** @var ApiClient $apiClient */
        $apiClient = $this->apiClientTest("sendLocation", new Message(), [
            "chat_id" => 1,
            "latitude" => "lat",
            "longitude" => "long",
            "reply_to_message_id" => 2,
            "reply_markup" => $replyMarkup->toArray(),
        ]);

        $apiClient->sendLocation(1, "lat", "long", 2, $replyMarkup);

    }

    public function testSendChatAction()
    {

        /** @var ApiClient $apiClient */
        $apiClient = $this->apiClientTest("sendChatAction", new Response(), [
            "chat_id" => 1,
            "action" => ApiClient::CHAT_ACTION_TYPING,
        ]);

        $apiClient->sendChatAction(1, ApiClient::CHAT_ACTION_TYPING);

    }

    public function testSetWebhook()
    {

        $url = "http://my.test.domain/webhook";

        /** @var ApiClient $apiClient */
        $apiClient = $this->apiClientTest("setWebhook", new Response(), [
            "url" => $url,
        ]);

        $apiClient->setWebhook($url);

    }

    public function testGetUserProfilePhotos()
    {

        /** @var ApiClient $apiClient */
        $apiClient = $this->apiClientTest("getUserProfilePhotos", new UserProfilePhotos(), [
            "user_id" => 1,
            "offset" => 0,
            "limit" => 5
        ]);

        $apiClient->getUserProfilePhotos(1, 0, 5);

    }

    public function testGetUpdates()
    {

        $data = [
            ["test" => "data1"],
            ["test" => "data2"],
        ];

        $response = $this->getMockBuilder("Psr\\Http\\Message\\ResponseInterface")
            ->disableOriginalConstructor()
            ->setMethods(["getBody"])
            ->getMockForAbstractClass();
        $response->expects($this->once())
            ->method("getBody")
            ->willReturn(json_encode($data));

        $apiClient = $this->getMockApiClient("test", ["apiRequest"]);

        $apiClient->expects($this->once())
            ->method("apiRequest")
            ->with("getUpdates", ["offset" => 0, "limit" => 5, "timeout" => 30])
            ->willReturn($response);

        $expected = [];
        foreach ($data as $update) {
            $expected[] = new Update($update);
        }
        $this->assertEquals($expected, $apiClient->getUpdates(0, 5, 30));
    }

    /**
     * Test apiRequest throws appropriate exception and sets the Response entity in the exception
     */
    public function testApiRequestThrowsException()
    {

        $data = ["message" => "Something went wrong!"];

        $response = $this->getMockBuilder("Psr\\Http\\Message\\ResponseInterface")
            ->disableOriginalConstructor()
            ->setMethods(["getBody"])
            ->getMockForAbstractClass();
        $response->expects($this->once())
            ->method("getBody")
            ->willReturn(json_encode($data));

        $exception = $this->getMockBuilder("GuzzleHttp\\Exception\\BadResponseException")
            ->disableOriginalConstructor()
            ->setMethods(["getResponse"])
            ->getMock();
        $exception->expects($this->once())
            ->method("getResponse")
            ->willReturn($response);

        $apiClient = $this->getMockApiClient("test", ["post"]);
        $apiClient->expects($this->once())
            ->method("post")
            ->willThrowException($exception);

        try {
            $this->runProtectedMethod($apiClient, "apiRequest", ["test"]);
        } catch (BadResponseException $e) {
            $this->assertInstanceOf("Choccybiccy\\Telegram\\Entity\\Response", $e->getResponse());
            $this->assertEquals($data, $this->getProtectedProperty($e->getResponse(), "data"));
        }
    }
}

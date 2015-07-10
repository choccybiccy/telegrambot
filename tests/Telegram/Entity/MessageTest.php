<?php

namespace Choccybiccy\Telegram\Entity;

use Choccybiccy\Telegram\EntityTest;

/**
 * Class MessageTest
 * @package Choccybiccy\Telegram\Entity
 */
class MessageTest extends EntityTest
{

    /**
     * @var array
     */
    protected $entityMaps = [
        "from" => "User",
        "forward_from" => "User",
        "new_chat_participant" => "User",
        "left_chat_participant" => "User",
        "reply_to_message" => "Message",
        "audio" => "Audio",
        "document" => "Document",
        "sticker" => "Sticker",
        "video" => "Video",
        "contact" => "Contact",
        "location" => "Location",
    ];

    /**
     * Test mapping entities
     */
    public function testMapEntity()
    {

        $entity = new Message();

        $dateTime = $entity->mapToEntity("date", time());
        $this->assertInstanceOf("\\DateTime", $dateTime);

        $groupChat = $entity->mapToEntity("chat", ["title" => "group"]);
        $this->assertInstanceOf("Choccybiccy\\Telegram\\Entity\\GroupChat", $groupChat);

        $user = $entity->mapToEntity("chat", ["username" => "example"]);
        $this->assertInstanceOf("Choccybiccy\\Telegram\\Entity\\User", $user);

        $expected = [
            new PhotoSize(["data" => "example"]),
        ];
        $photo = $entity->mapToEntity("photo", [["data" => "example"]]);
        $this->assertEquals($expected, $photo);
        $photo = $entity->mapToEntity("new_chat_photo", [["data" => "example"]]);
        $this->assertEquals($expected, $photo);

    }
}

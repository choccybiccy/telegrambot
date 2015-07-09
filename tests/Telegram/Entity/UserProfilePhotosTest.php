<?php

namespace Choccybiccy\Telegram\Entity;

use Choccybiccy\Telegram\EntityTest;

/**
 * Class UserProfilePhotosTest
 * @package Choccybiccy\Telegram\Entity
 */
class UserProfilePhotosTest extends EntityTest
{

    /**
     * Test mapping entities
     */
    public function testMapEntity()
    {

        $entity = new UserProfilePhotos();

        $expected = [
            new PhotoSize(["data" => "example"]),
        ];
        $photo = $entity->mapToEntity("photos", [["data" => "example"]]);
        $this->assertEquals($expected, $photo);

        $this->assertNull($entity->mapToEntity("entityDoesntExist", ["data" => "example"]));

    }
}
<?php

namespace Choccybiccy\Telegram;

use Choccybiccy\Telegram\Traits\ReflectionMethods;

/**
 * Class EntityTest
 * @package Choccybiccy\Telegram
 */
class EntityTest extends \PHPUnit_Framework_TestCase
{

    use ReflectionMethods;

    /**
     * @var array
     */
    protected $entityMaps = [];

    /**
     * @param array|null $methods
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getMockEntity($methods = null)
    {
        return $this->getMockBuilder("Choccybiccy\\Telegram\\Entity")
            ->disableOriginalConstructor()
            ->setMethods($methods)
            ->getMock();
    }

    /**
     * Test data is populated in the constructor
     */
    public function testConstructPopulatesData()
    {
        $data = [
            "example" => "data",
        ];
        $entity = new Entity($data);
        $this->assertEquals($data, $this->getProtectedProperty($entity, "data"));
    }

    public function testSetUsesEntity()
    {
        $data = [
            "example" => "data",
        ];
        $entity = $this->getMockEntity(["mapToEntity"]);
        $entity->expects($this->once())
            ->method("mapToEntity")
            ->with("example", "data")
            ->willReturn(new Entity($data));

        $entity->set("example", "data");
        $this->assertInstanceOf("Choccybiccy\\Telegram\\Entity", $entity->get("example"));

    }

    /**
     * Test get returns null if key doesn't exist
     */
    public function testGetReturnsNullWhenKeyNotExists()
    {
        $entity = new Entity([]);
        $this->assertNull($entity->get("doesntExist"));
    }

    /**
     * Test __get and __set use data
     */
    public function testMagicMethods()
    {
        $entity = new Entity(["example" => "data"]);
        $entity->example = "newData";
        $this->assertEquals("newData", $entity->example);
    }

    /**
     * Test toArray
     */
    public function testToArray()
    {

        $data = [
            "entity" => [
                "example" => "data",
            ],
            "date" => strtotime(date("Y-m-d H:i:s")),
        ];

        $entity = $this->getMockEntity(["mapToEntity"]);
        $entity->expects($this->at(0))
            ->method("mapToEntity")
            ->with("entity", $data["entity"])
            ->willReturn(new Entity($data['entity']));
        $entity->expects($this->at(1))
            ->method("mapToEntity")
            ->with("date", $data['date'])
            ->willReturn(new \DateTime(date("Y-m-d H:i:s", $data['date'])));

        $entity->populate($data);
        $this->assertEquals($data, $entity->toArray());

    }

    /**
     * Test map to entity returns a copy of the entity
     */
    public function testMapToEntity()
    {
        if (count($this->entityMaps)) {
            $class = substr(get_class($this), 0, -4);
            $namespace = __NAMESPACE__ . "\\Entity";

            /** @var Entity $entity */
            $parentEntity = new $class;

            foreach ($this->entityMaps as $key => $entity) {
                $entityToMap = $namespace . "\\" . $entity;
                $mappedEntity = $parentEntity->mapToEntity($key, ["data" => "example"]);
                $this->assertInstanceOf($entityToMap, $mappedEntity);

            }

            $this->assertNull($parentEntity->mapToEntity("dontCreateEntity", ["data" => "message"]));


        }
    }
}

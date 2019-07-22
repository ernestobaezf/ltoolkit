<?php
/**
 * @author Ernesto Baez 
 */

namespace ErnestoBaezF\L5CoreToolbox\tests\Unit\Serializers;


use ErnestoBaezF\L5CoreToolbox\Serializers\BaseSerializer;
use ErnestoBaezF\L5CoreToolbox\Test\Environment\TestCase;

class BaseSerializerTest extends TestCase
{
    /**
     * Serialize a collection
     */
    public function test_collection()
    {
        $object = $this->getMockBuilder(BaseSerializer::class)
            ->disableOriginalConstructor()
            ->disableOriginalClone()
            ->disableArgumentCloning()
            ->disallowMockingUnknownTypes()
            ->setMethods(["transform"])
            ->getMock();

        $object->expects($this->exactly(2))->method("transform");

        $method = self::getMethod("collection", BaseSerializer::class);
        $result = $method->invokeArgs($object, [collect(["data1", "data2"])]);

        self::assertTrue(is_array($result));
    }

    /**
     * Serialize an item
     */
    public function test_item()
    {
        $object = $this->getMockBuilder(BaseSerializer::class)
            ->disableOriginalConstructor()
            ->disableOriginalClone()
            ->disableArgumentCloning()
            ->disallowMockingUnknownTypes()
            ->setMethods(["transform"])
            ->getMock();

        $object->method("transform")->willReturnArgument(0);

        $method = self::getMethod("item", BaseSerializer::class);
        $result = $method->invokeArgs($object, ["data1"]);

        self::assertEquals("data1", $result);
    }

    /**
     * Serialize a collection
     */
    public function test_serialize()
    {
        $object = $this->getMockBuilder(BaseSerializer::class)
            ->disableOriginalConstructor()
            ->disableOriginalClone()
            ->disableArgumentCloning()
            ->disallowMockingUnknownTypes()
            ->setMethods(["transform"])
            ->getMock();

        $object->expects(self::exactly(2))->method("transform")->willReturnArgument(0);

        $method = self::getMethod("serialize", BaseSerializer::class);
        $result = $method->invokeArgs($object, [["data1", "data2"]]);

        self::assertEquals(["data1", "data2"], $result);
    }

    /**
     * Serialize an item
     */
    public function test_serialize_2()
    {
        $object = $this->getMockBuilder(BaseSerializer::class)
            ->disableOriginalConstructor()
            ->disableOriginalClone()
            ->disableArgumentCloning()
            ->disallowMockingUnknownTypes()
            ->setMethods(["transform"])
            ->getMock();

        $object->expects(self::once())->method("transform")->willReturnArgument(0);

        $method = self::getMethod("serialize", BaseSerializer::class);
        $result = $method->invokeArgs($object, ["data1"]);

        self::assertEquals("data1", $result);
    }
}

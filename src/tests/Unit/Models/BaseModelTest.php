<?php
/**
 * @author Ernesto Baez 
 */

namespace LToolkit\Test\Unit\Models;


use LToolkit\Models\BaseModel;
use LToolkit\Interfaces\IEntity;
use LToolkit\Test\Environment\TestCase;

class BaseModelTest extends TestCase
{
    /**
     * Get model id
     */
    public function test_getId()
    {
        $object = $this->mockClass(BaseModel::class, "getId");

        $property = $this->getMethod("setId", BaseModel::class);
        $propResult = $property->invokeArgs($object, [1]);

        $method = self::getMethod("getId", BaseModel::class);
        $result = $method->invoke($object);

        self::assertEquals($object, $propResult);
        self::assertEquals(1, $result);
    }

    /**
     * Get model id
     */
    public function test_fromStdClass()
    {
        $object = $this->getMockBuilder(BaseModel::class)
            ->disableOriginalConstructor()
            ->disableOriginalClone()
            ->disableArgumentCloning()
            ->onlyMethods(["getFillable", "fillable", "fill", "syncOriginal"])
            ->getMock();

        $object->expects(self::once())->method("getFillable")->willReturnCallback(function() {
            return ["field1", "field2"];
        });

        $object->expects(self::exactly(2))->method("fillable");

        $object->expects(self::once())->method("fill");

        $object->expects(self::once())->method("syncOriginal");

        $stdClass = new \stdClass();
        $stdClass->id = 1;

        $method = self::getMethod("fromStdClass", BaseModel::class);

        /** @var IEntity $result */
        $result = $method->invokeArgs($object, [$stdClass]);

        self::assertInstanceOf(IEntity::class, $result);
    }
}

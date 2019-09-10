<?php

namespace LToolkit\Test\Environment;

use ReflectionClass;
use PHPUnit\Framework\MockObject\MockObject;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected static function getMethod($name, $class) {
        $class = new ReflectionClass($class);
        $method = $class->getMethod($name);
        $method->setAccessible(true);
        return $method;
    }

    protected static function getProperty($name, $class) {
        $class = new ReflectionClass($class);
        return $class->getProperty($name);
    }

    /**
     * @param string $class
     * @param string $method
     * @param mixed $result
     * @return MockObject
     */
    protected function mockClass($class, $method=null, $result=null)
    {
        $object = $this->getMockBuilder($class)
            ->disableOriginalConstructor()
            ->disableOriginalClone()
            ->disableArgumentCloning()
            ->disallowMockingUnknownTypes();

        if ($method) {
            $object = $object->onlyMethods([$method])->getMock();

            $object->method($method)->willReturn($result);
        } else {
            $object = $object->getMock();
        }

        return $object;
    }
}

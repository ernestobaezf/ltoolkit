<?php

namespace ErnestoBaezF\L5CoreToolbox\Test\Environment;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected static function getMethod($name, $class) {
        $class = new \ReflectionClass($class);
        $method = $class->getMethod($name);
        $method->setAccessible(true);
        return $method;
    }

    protected static function getProperty($name, $class) {
        $class = new \ReflectionClass($class);
        return $class->getProperty($name);
    }

    /**
     * @param string $class
     * @param string $method
     * @param mixed $result
     * @return \PHPUnit\Framework\MockObject\MockObject
     * @throws \ReflectionException
     */
    protected function mockClass($class, $method=null, $result=null)
    {
        $object = $this->getMockBuilder($class)
            ->disableOriginalConstructor()
            ->disableOriginalClone()
            ->disableArgumentCloning()
            ->disallowMockingUnknownTypes();

        if ($method) {
            $object = $object->setMethods([$method])->getMock();

            $object->method($method)->willReturn($result);
        } else {
            $object = $object->getMock();
        }

        return $object;
    }
}

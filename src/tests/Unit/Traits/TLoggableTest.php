<?php
/**
 * @author Ernesto Baez
 */

namespace ErnestoBaezF\L5CoreToolbox\tests\Unit\Traits;

use Exception;
use ReflectionObject;
use ReflectionException;
use Illuminate\Support\Facades\Log;
use ErnestoBaezF\L5CoreToolbox\Traits\TLoggable;
use ErnestoBaezF\L5CoreToolbox\Test\Environment\TestCase;

/**
 * Class TLoggableTest
 * @package ErnestoBaezF\L5CoreToolbox\Traits
 */
class TLoggableTest extends TestCase
{
    /**
     * Testing call functions to log the execution
     * Context: Successful execution and call log twice
     */
    public function test__call_1()
    {
        $object = $this->getMockForTrait(
            TLoggable::class,
            [],
            "LoggableTest",
            true,
            true,
            true,
            ['action']
        );

        $_response = "Method action response";
        $object->method('action')->willReturn($_response);

        Log::shouldReceive("log")->times(2);

        $response = call_user_func_array([$object, "logAction"], []);

        self::assertEquals($_response, $response);
    }

    /**
     * Testing call functions to log the execution
     * Context: Failed execution because the method action does not exist
     * (and the invocation does not have the prefix log)
     */
    public function test__call_2()
    {
        $object = $this->getMockForTrait(
            TLoggable::class,
            [],
            "LoggableTest1",
            true,
            true,
            true
        );

        Log::shouldReceive("log")->times(0);

        $this->expectException(Exception::class);
        call_user_func_array([$object, "action"], []);
    }

    /**
     * Testing call functions to log the execution
     * Context: Failed execution because the method action does not exist
     */
    public function test__call_3()
    {
        $object = $this->getMockForTrait(
            TLoggable::class,
            [],
            "LoggableTest2",
            true,
            true,
            true
        );

        Log::shouldReceive("log")->times(0);

        $this->expectException(Exception::class);
        call_user_func_array([$object, "logAction"], []);
    }


    /**
     * Testing call functions to log the execution
     * Context: Successful execution with params
     */
    public function test__call_4()
    {
        $object = $this->getMockForTrait(
            TLoggable::class,
            [],
            "LoggableTest3",
            true,
            true,
            true,
            ["action"]
        );

        $_response = "Method action response";
        $object->method('action')->willReturnCallback(function ($param1, $param2) use ($_response)
        {
            self::assertEquals("param1", $param1);
            self::assertEquals("param2", $param2);

            return $_response;
        });

        Log::shouldReceive("log")->times(2);

        $response = call_user_func_array([$object, "logAction"], ["param1", "param2"]);

        self::assertEquals($_response, $response);
    }

    /**
     * Get the log level configuration. Level 'debug' by default
     *
     * @throws ReflectionException
     */
    public function test_logLevel()
    {
        $object = $this->getMockForTrait(
            TLoggable::class,
            [],
            "LoggableTest",
            true,
            true,
            true
        );

        $class = new ReflectionObject($object);
        $method = $class->getMethod("logLevel");
        $method->setAccessible(true);
        $response = $method->invoke($object);

        self::assertEquals("debug", $response);
    }
}

<?php
/**
 * @author Ernesto Baez 
 */

namespace ErnestoBaezF\L5CoreToolbox\tests\Unit\Traits;


use Exception;
use ReflectionObject;
use ReflectionException;
use Illuminate\Support\Facades\Log;
use ErnestoBaezF\L5CoreToolbox\Traits\TLogAction;
use ErnestoBaezF\L5CoreToolbox\Test\Environment\TestCase;

class TLogActionTest extends TestCase
{
    /**
     * @throws ReflectionException
     */
    public function test_logAction()
    {
        $object = $this->getMockForTrait(
            TLogAction::class,
            [],
            "",
            true,
            true,
            true
        );

        $class = new ReflectionObject($object);
        $method = $class->getMethod("logAction");
        $method->setAccessible(true);
        $response = $method->invokeArgs($object, [""]);

        self::assertFalse($response);
    }

    /**
     * Test evaluate function
     * Context: Configured to no log the actions and no exception thrown
     *
     * @throws ReflectionException
     */
    public function test_evaluate_1()
    {
        $result = "result";

        $called = false;
        $closure = function () use (&$called, $result) {
            $called = true;

            return $result;
        };

        $functionName = "";
        $payload = "";

        $object = $this->getMockForTrait(
            TLogAction::class,
            [],
            "",
            true,
            true,
            true,
            ["logAction"]
        );
        $object->method("logAction")->willReturn(false);

        $class = new ReflectionObject($object);
        $method = $class->getMethod("evaluate");
        $method->setAccessible(true);
        $response = $method->invokeArgs($object, [$closure, $functionName, $payload]);

        self::assertTrue($called);
        self::assertTrue($response == $result);
    }

    /**
     * Test evaluate function
     * Context: Configured to log the actions and no exception thrown
     *
     * @throws ReflectionException
     */
    public function test_evaluate_2()
    {
        $result = "result";

        $called = false;
        $closure = function () use (&$called, $result) {
            $called = true;

            return $result;
        };

        $functionName = "";
        $payload = "";

        $object = $this->getMockForTrait(
            TLogAction::class,
            [],
            "",
            true,
            true,
            true,
            ["logAction"]
        );
        $object->method("logAction")->willReturn(true);

        Log::shouldReceive("info")->once();

        $class = new ReflectionObject($object);
        $method = $class->getMethod("evaluate");
        $method->setAccessible(true);
        $response = $method->invokeArgs($object, [$closure, $functionName, $payload]);

        self::assertTrue($called);
        self::assertTrue($response == $result);
    }

    /**
     * Test evaluate function
     * Context: Configured to log the actions and exception thrown
     *
     * @throws ReflectionException
     */
    public function test_evaluate_3()
    {
        $result = "result";

        $called = false;
        $closure = function () use (&$called, $result) {
            $called = true;

            return $result;
        };

        $functionName = "";
        $payload = "";

        $object = $this->getMockForTrait(
            TLogAction::class,
            [],
            "",
            true,
            true,
            true,
            ["logAction"]
        );
        $object->method("logAction")->willThrowException(new Exception("Exception"));

        $this->expectException(Exception::class);
        Log::shouldReceive("error")->once();

        $class = new ReflectionObject($object);
        $method = $class->getMethod("evaluate");
        $method->setAccessible(true);
        $method->invokeArgs($object, [$closure, $functionName, $payload]);

        self::assertTrue($called);
    }

}

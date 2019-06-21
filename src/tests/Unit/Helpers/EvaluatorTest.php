<?php
/**
 * @author Ernesto Baez
 */

namespace ErnestoBaezF\L5CoreToolbox\tests\Unit\Helpers;


use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use ErnestoBaezF\L5CoreToolbox\Helpers\Evaluator;
use ErnestoBaezF\L5CoreToolbox\Http\Validators\BasicUpdateValidator;
use ErnestoBaezF\L5CoreToolbox\Interfaces\IEvaluator;
use ErnestoBaezF\L5CoreToolbox\Test\Environment\TestCase;

class EvaluatorTest extends TestCase
{
    /**
     * Set pre-condition to be executed on evaluate call
     *
     * @throws \ReflectionException
     */
    public function test_preCondition()
    {
        $object = $this->getMockBuilder(Evaluator::class)
            ->disableOriginalConstructor()
            ->disableArgumentCloning()
            ->disableOriginalClone()
            ->getMock();

        $method = self::getMethod("preCondition", Evaluator::class);
        $result = $method->invokeArgs($object, [function() {
            return "";
        }]);

        $method = self::getMethod("getPreCondition", Evaluator::class);
        $condition = $method->invoke($object);

        self::assertInstanceOf(Closure::class, $condition);
        self::assertInstanceOf(IEvaluator::class, $result);
    }

    /**
     * Set post-condition to be executed on evaluate call
     *
     * @throws \ReflectionException
     */
    public function test_postCondition()
    {
        $object = $this->getMockBuilder(Evaluator::class)
            ->disableOriginalConstructor()
            ->disableArgumentCloning()
            ->disableOriginalClone()
            ->getMock();

        $method = self::getMethod("postCondition", Evaluator::class);
        $result = $method->invokeArgs($object, [function() {
            return "";
        }]);

        $method = self::getMethod("getPostCondition", Evaluator::class);
        $condition = $method->invoke($object);

        self::assertInstanceOf(Closure::class, $condition);
        self::assertInstanceOf(IEvaluator::class, $result);
    }

    /**
     * Set method to be executed on evaluate call
     *
     * @throws \ReflectionException
     */
    public function test_method()
    {
        $object = $this->getMockBuilder(Evaluator::class)
            ->setConstructorArgs([true])
            ->disableArgumentCloning()
            ->disableOriginalClone()
            ->getMock();

        $method = self::getMethod("mainMethod", Evaluator::class);
        $response = new JsonResponse("Testing started at 4:52 PM ...
/usr/bin/php /home/ernesto/Projects/opv2/vendor/phpunit/phpunit/phpunit --coverage-html build/coverage --testsuite Unit --configuration /home/ernesto/Projects/opv2/phpunit.xml --teamcity
PHPUnit 8.1.3 by Sebastian Bergmann and contributors.
Time: 15.02 seconds, Memory: 62.00 MB
OK (98 tests, 243 assertions)
Generating code coverage report in Clover XML format ... done
Generating code coverage report in HTML format ... done
Process finished with exit code 0");
        $result = $method->invokeArgs($object, [function() use ($response) {
            return $response;
        }]);

        $method = self::getMethod("getMethod", Evaluator::class);
        $method = $method->invoke($object);

        self::assertInstanceOf(Closure::class, $method);
        self::assertInstanceOf(IEvaluator::class, $result);

        Log::shouldReceive("info")->times(2);
        $result = $method();

        self::assertEquals($response, $result);
    }

    /**
     * Evaluate precondition, main method and post-condition
     *
     * @throws \ReflectionException
     */
    public function test_evaluate()
    {
        $object = $this->getMockBuilder(Evaluator::class)
            ->disableOriginalConstructor()
            ->disableArgumentCloning()
            ->disableOriginalClone()
            ->getMock();

        $response = new JsonResponse("response from method");
        $method = self::getMethod("mainMethod", Evaluator::class);
        $method->invokeArgs($object, [function() use ($response) {
            return $response;
        }]);

        $method = self::getMethod("evaluate", Evaluator::class);
        $result = $method->invoke($object);

        self::assertEquals($result, $response);
    }

    /**
     * Evaluate precondition, main method and post-condition and precondition fails
     *
     * @throws \ReflectionException
     */
    public function test_evaluate_preCondition_fails()
    {
        $object = $this->getMockBuilder(Evaluator::class)
            ->disableOriginalConstructor()
            ->disableArgumentCloning()
            ->disableOriginalClone()
            ->setMethods(["getPreCondition"])
            ->getMock();

        $object->method("getPreCondition")->willReturn(function () {
            throw new ValidationException(new BasicUpdateValidator());
        });

        $method = self::getMethod("evaluate", Evaluator::class);

        Log::shouldReceive("error")->once();

        /** @var JsonResponse $result */
        $result = $method->invoke($object);

        self::assertInstanceOf(JsonResponse::class, $result);
        self::assertEquals($result->getStatusCode(), 400);
        self::assertArrayHasKey("error", (array) $result->getData());
        self::assertArrayHasKey("message", (array) $result->getData());
    }


    /**
     * Evaluate precondition, main method and post-condition and post-condition fails
     *
     * @throws \ReflectionException
     */
    public function test_evaluate_postCondition_fails()
    {
        $object = $this->getMockBuilder(Evaluator::class)
            ->disableOriginalConstructor()
            ->disableArgumentCloning()
            ->disableOriginalClone()
            ->setMethods(["getPostCondition"])
            ->getMock();

        $object->method("getPostCondition")->willReturn(function () {
            throw new ValidationException(new BasicUpdateValidator());
        });

        $method = self::getMethod("mainMethod", Evaluator::class);
        $method->invokeArgs($object, [function() {
            return new JsonResponse("response from method");
        }]);

        $method = self::getMethod("evaluate", Evaluator::class);

        Log::shouldReceive("error")->once();

        /** @var JsonResponse $result */
        $result = $method->invoke($object);

        self::assertInstanceOf(JsonResponse::class, $result);
        self::assertEquals($result->getStatusCode(), 400);
        self::assertArrayHasKey("error", (array) $result->getData());
        self::assertArrayHasKey("message", (array) $result->getData());
    }

}

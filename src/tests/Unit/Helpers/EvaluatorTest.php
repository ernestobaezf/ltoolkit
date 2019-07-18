<?php
/**
 * @author Ernesto Baez
 */

namespace ErnestoBaezF\L5CoreToolbox\tests\Unit\Helpers;


use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
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
     * Context: The response length is bigger than 400 characters and the status is 500
     */
    public function test_method_1()
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
Process finished with exit code 0", 500);
        $result = $method->invokeArgs($object, [function() use ($response) {
            return $response;
        }]);

        $method = self::getMethod("getMethod", Evaluator::class);
        $method = $method->invoke($object);

        self::assertInstanceOf(Closure::class, $method);
        self::assertInstanceOf(IEvaluator::class, $result);

        Log::shouldReceive("log")->once();
        Log::shouldReceive("info")->once();
        $response = $method();

        self::assertEquals($response, $response);
    }

    /**
     * Set method to be executed on evaluate call
     * Context: The response length is smaller than 400 characters and the status is 505
     */
    public function test_method_2()
    {
        $object = $this->getMockBuilder(Evaluator::class)
            ->setConstructorArgs([true])
            ->disableArgumentCloning()
            ->disableOriginalClone()
            ->getMock();

        $method = self::getMethod("mainMethod", Evaluator::class);
        $response = new JsonResponse("Testing started at 4:52 PM", 505);
        $result = $method->invokeArgs($object, [function() use ($response) {
            return $response;
        }]);

        $method = self::getMethod("getMethod", Evaluator::class);
        $method = $method->invoke($object);

        self::assertInstanceOf(Closure::class, $method);
        self::assertInstanceOf(IEvaluator::class, $result);

        Log::shouldReceive("log")->once();
        Log::shouldReceive("info")->once();
        $result = $method();

        self::assertEquals($response, $result);
    }

    /**
     * Set method to be executed on evaluate call
     * Context: The response length is smaller than 400 characters and the status is 505
     */
    public function test_method_3()
    {
        $object = $this->getMockBuilder(Evaluator::class)
            ->setConstructorArgs([true])
            ->disableArgumentCloning()
            ->disableOriginalClone()
            ->getMock();

        $method = self::getMethod("mainMethod", Evaluator::class);
        $response = new JsonResponse("Testing started at 4:52 PM", 200);
        $result = $method->invokeArgs($object, [function() use ($response) {
            return $response;
        }]);

        $method = self::getMethod("getMethod", Evaluator::class);
        $method = $method->invoke($object);

        self::assertInstanceOf(Closure::class, $method);
        self::assertInstanceOf(IEvaluator::class, $result);

        Log::shouldReceive("log")->once();
        Log::shouldReceive("info")->once();
        $result = $method();

        self::assertEquals($response, $result);
    }

    /**
     * Set method to be executed on evaluate call
     * Context: The response is not a JsonResponse nor a Response
     */
    public function test_method_4()
    {
        $object = $this->getMockBuilder(Evaluator::class)
            ->setConstructorArgs([true])
            ->disableArgumentCloning()
            ->disableOriginalClone()
            ->getMock();

        $method = self::getMethod("mainMethod", Evaluator::class);
        $response = "Testing started at 4:52 PM";
        $result = $method->invokeArgs($object, [function() use ($response) {
            return $response;
        }]);

        $method = self::getMethod("getMethod", Evaluator::class);
        $method = $method->invoke($object);

        self::assertInstanceOf(Closure::class, $method);
        self::assertInstanceOf(IEvaluator::class, $result);

        Log::shouldReceive("log")->once();
        Log::shouldReceive("info")->once();
        $result = $method();

        self::assertEquals($response, $result);
    }

    /**
     * Set method to be executed on evaluate call
     * Context: The response is not a JsonResponse but a Response
     */
    public function test_method_5()
    {
        $object = $this->getMockBuilder(Evaluator::class)
            ->setConstructorArgs([true])
            ->disableArgumentCloning()
            ->disableOriginalClone()
            ->getMock();

        $method = self::getMethod("mainMethod", Evaluator::class);
        $response = new Response("Testing started at 4:52 PM");
        $result = $method->invokeArgs($object, [function() use ($response) {
            return $response;
        }]);

        $method = self::getMethod("getMethod", Evaluator::class);
        $method = $method->invoke($object);

        self::assertInstanceOf(Closure::class, $method);
        self::assertInstanceOf(IEvaluator::class, $result);

        Log::shouldReceive("log")->once();
        Log::shouldReceive("info")->once();
        $result = $method();

        self::assertEquals($response, $result);
    }

    /**
     * Evaluate precondition, main method and post-condition
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

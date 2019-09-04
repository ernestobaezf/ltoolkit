<?php
/**
 * @author Ernesto Baez 
 */

namespace ltoolkit\Test\Unit\Connectors;


use ReflectionException;
use ltoolkit\Test\Environment\TestCase;
use ltoolkit\Interfaces\IStoreValidator;
use ltoolkit\Interfaces\IUpdateValidator;
use ltoolkit\Connectors\ValidatorResolver;
use ltoolkit\Interfaces\IValidatorResolver;
use ltoolkit\Test\Environment\Http\Controllers\MockAPIController;
use ltoolkit\Test\Environment\Http\Validators\Mock\TestValidator;

class ValidatorResolverTest extends TestCase
{
    /**
     * Add a validator to the list inside the ValidatorResolver
     *
     * @throws ReflectionException
     */
    public function test_add()
    {
        $validatorResolver = $this->getMockBuilder(ValidatorResolver::class)
            ->disableOriginalClone()
            ->disableArgumentCloning()
            ->disallowMockingUnknownTypes()
            ->setConstructorArgs([MockAPIController::class])
            ->getMock();

        $object = new \ReflectionObject($validatorResolver);
        $property = $object->getProperty("validations");
        $property->setAccessible(true);
        $value = $property->getValue($validatorResolver);

        self::assertEquals($value, []);

        $method = self::getMethod("add", ValidatorResolver::class);
        $result = $method->invokeArgs($validatorResolver, ["test", new TestValidator()]);

        $object = new \ReflectionObject($result);
        $property = $object->getProperty("validations");
        $property->setAccessible(true);
        $value = $property->getValue($result);
        self::assertArrayHasKey("test", $value);
        self::assertInstanceOf(TestValidator::class, $value["test"]);

        self::assertInstanceOf(IValidatorResolver::class, $result);
    }

    /**
     * Retrieve a validator listed inside the ValidatorResolver
     */
    public function test_get()
    {
        $validatorResolver = $this->getMockBuilder(ValidatorResolver::class)
            ->disableOriginalClone()
            ->disableArgumentCloning()
            ->disallowMockingUnknownTypes()
            ->setConstructorArgs([MockAPIController::class, ["test" => new TestValidator()]])
            ->getMock();

        $method = self::getMethod("get", ValidatorResolver::class);
        $result = $method->invokeArgs($validatorResolver, ["test"]);

        self::assertInstanceOf(TestValidator::class, $result);
    }

    /**
     * Resolve a validator listed inside the ValidatorResolver
     */
    public function test_get_resolve()
    {
        $validatorResolver = $this->getMockBuilder(ValidatorResolver::class)
            ->disableOriginalClone()
            ->disableArgumentCloning()
            ->disallowMockingUnknownTypes()
            ->setConstructorArgs([MockAPIController::class])
            ->getMock();

        $method = self::getMethod("get", ValidatorResolver::class);
        $result = $method->invokeArgs($validatorResolver, ["test"]);

        self::assertInstanceOf(TestValidator::class, $result);
    }

    /**
     * Resolve default store validator listed inside the ValidatorResolver
     */
    public function test_get_resolve_default_store()
    {
        $validatorResolver = $this->getMockBuilder(ValidatorResolver::class)
            ->disableOriginalClone()
            ->disableArgumentCloning()
            ->disallowMockingUnknownTypes()
            ->getMock();

        $method = self::getMethod("get", ValidatorResolver::class);
        $result = $method->invokeArgs($validatorResolver, ["store"]);

        self::assertInstanceOf(IStoreValidator::class, $result);
    }

    /**
     * Resolve default update validator listed inside the ValidatorResolver
     */
    public function test_get_resolve_default_update()
    {
        $validatorResolver = $this->getMockBuilder(ValidatorResolver::class)
            ->disableOriginalClone()
            ->disableArgumentCloning()
            ->disallowMockingUnknownTypes()
            ->setConstructorArgs([MockAPIController::class])
            ->setMethods(["classExists"])
            ->getMock();

        $validatorResolver->method("classExists")->willReturn(false);

        $method = self::getMethod("get", ValidatorResolver::class);
        $result = $method->invokeArgs($validatorResolver, ["update"]);

        self::assertInstanceOf(IUpdateValidator::class, $result);
    }
}

<?php

namespace LToolkit\Test\Unit\Http\Controllers;

use ReflectionException;
use Illuminate\Support\Facades\Route;
use LToolkit\Interfaces\UnitOfWorkInterface;
use LToolkit\Test\Environment\TestCase;
use LToolkit\Adapters\ValidatorResolver;
use LToolkit\Interfaces\GenericRepositoryInterface;
use LToolkit\Interfaces\ValidatorResolverInterface;
use LToolkit\Test\Environment\DynamicClass;
use LToolkit\Test\Environment\Models\MockModel;
use LToolkit\Http\Controllers\BaseAPIController;
use LToolkit\Http\Validators\BasicUpdateValidator;
use LToolkit\Test\Environment\Adapters\MockUnitOfWork;
use LToolkit\Test\Environment\Models\MockEloquentModel;
use LToolkit\Test\Environment\Serializer\MockSerializer;

class BaseAPIControllerTest extends TestCase
{
    /**
     * Test to get serializer
     *
     * @throws ReflectionException
     */
    public function test_getSerializer()
    {
        /** @var BaseAPIController $class */
        $class = $this->mockClass(BaseAPIController::class, 'getEntity', "");

        $param = new MockSerializer();
        $class->setSerializer($param);

        $method = self::getMethod('getSerializer', BaseAPIController::class);
        $serializer =  $method->invoke($class);

        $this->assertTrue($param == $serializer);
    }

    /**
     * Test to get unit of work
     */
    public function test_getUnitOfWork()
    {
        $uow = app(UnitOfWorkInterface::class);
        $validator = app(ValidatorResolverInterface::class);
        $object = $this->getMockBuilder(BaseAPIController::class)
            ->setConstructorArgs(["unitOfWork" => $uow, "validatorResolver" => $validator])
            ->getMock();

        $method = self::getMethod('getUnitOfWork', BaseAPIController::class);

        $this->assertTrue($method->invoke($object) == $uow);
    }

    /**
     * Test for formatting the response in controllers.
     * Context: Serializer is null, the data in the response is not an EntityInterface and the message is null
     */
    public function test_respond_1()
    {
        $data = 'test';

        $object = $this->getMockBuilder(BaseAPIController::class)
            ->disableOriginalConstructor()
            ->disableOriginalClone()
            ->disableArgumentCloning()
            ->disallowMockingUnknownTypes()
            ->onlyMethods(['getEntity', 'getSerializer'])
            ->getMock();

        $object->method('getSerializer')->willReturn(null);
        $object->method('getEntity')->willReturn("");

        $method = self::getMethod('respond', BaseAPIController::class);
        $response = $method->invokeArgs($object, ["data" => $data]);

        $this->assertNotNull($response);
        $this->assertTrue($data == $response->getData());
        $this->assertTrue(200 == $response->getStatusCode());
    }

    /**
     * Test for formatting the response in controllers.
     * Context: Serializer is null, the data in the response is not an EntityInterface and the message is a string
     */
    public function test_respond_2()
    {
        $data = 'test';
        $message = 'message';

        $object = $this->getMockBuilder(BaseAPIController::class)
            ->disableOriginalConstructor()
            ->disableOriginalClone()
            ->disableArgumentCloning()
            ->disallowMockingUnknownTypes()
            ->onlyMethods(['getEntity', 'getSerializer'])
            ->getMock();

        $object->method('getSerializer')->willReturn(null);
        $object->method('getEntity')->willReturn("");

        $method = self::getMethod('respond', BaseAPIController::class);
        $response = $method->invokeArgs($object, ["data" => $data, "message" => $message]);

        $_response = $response->getData();
        $this->assertNotNull($response);
        $this->assertTrue($data == $_response->data && $message == $_response->message);
        $this->assertTrue(200 == $response->getStatusCode());
    }

    /**
     * Test for formatting the response in controllers.
     * Context: Use serializer, the data in the response is not an EntityInterface and the message is a string
     */
    public function test_respond_3()
    {
        $data = 'test';
        $message = 'message';

        $object = $this->getMockBuilder(BaseAPIController::class)
            ->disableOriginalConstructor()
            ->disableOriginalClone()
            ->disableArgumentCloning()
            ->disallowMockingUnknownTypes()
            ->onlyMethods(['getEntity', 'getSerializer'])
            ->getMock();

        $object->method('getSerializer')->willReturn(new MockSerializer());
        $object->method('getEntity')->willReturn("");

        $method = self::getMethod('respond', BaseAPIController::class);
        $response = $method->invokeArgs($object, ["data" => $data, "message" => $message]);

        $_response = $response->getData();
        $this->assertNotNull($response);
        $this->assertTrue($data == $_response->data && $message == $_response->message);
        $this->assertTrue(200 == $response->getStatusCode());
    }

    /**
     * Test for formatting the response in controllers.
     * Context: Serializer is null, the data in the response is an EntityInterface and the message is a string
     */
    public function test_respond_4()
    {
        $data = new MockModel();
        $message = 'message';

        $object = $this->getMockBuilder(BaseAPIController::class)
            ->disableOriginalConstructor()
            ->disableOriginalClone()
            ->disableArgumentCloning()
            ->disallowMockingUnknownTypes()
            ->onlyMethods(['getEntity', 'getSerializer'])
            ->getMock();

        $object->method('getSerializer')->willReturn(null);
        $object->method('getEntity')->willReturn("");

        $method = self::getMethod('respond', BaseAPIController::class);
        $response = $method->invokeArgs($object, ["data" => $data, "message" => $message]);

        $_response = $response->getData();
        $this->assertNotNull($response);
        $this->assertTrue([] == $_response->data && $message == $_response->message);
        $this->assertTrue(200 == $response->getStatusCode());
    }

    /**
     * Test for formatting the response in controllers.
     * Context: Serializer is null, the data in the response is an EntityInterface and the message is a string
     */
    public function test_getRepository()
    {
        $uow = $this->getMockBuilder(MockUnitOfWork::class)
            ->disableOriginalClone()
            ->disableArgumentCloning()
            ->disallowMockingUnknownTypes()
            ->onlyMethods(["getRepository"])
            ->getMock();
        $uow->expects(self::once())->method("getRepository")->willReturnCallback(function() {
            return $this->app->make(GenericRepositoryInterface::class, ["modelClass" => MockEloquentModel::class]);
        });

        $object = $this->getMockBuilder(BaseAPIController::class)
            ->disableOriginalConstructor()
            ->disableOriginalClone()
            ->disableArgumentCloning()
            ->disallowMockingUnknownTypes()
            ->onlyMethods(['getUnitOfWork', "getEntity"])
            ->getMock();

        $object->method('getUnitOfWork')->willReturn($uow);
        $object->method('getEntity')->willReturn("");

        $method = self::getMethod('getRepository', BaseAPIController::class);
        $response = $method->invoke($object);

        $this->assertInstanceOf(GenericRepositoryInterface::class, $response);
    }

    /**
     * Internal function to get validations
     */
    public function test_validationsClause_2()
    {
        $validator = $this->getMockBuilder(ValidatorResolver::class)
            ->disableOriginalConstructor()
            ->disableOriginalClone()
            ->disableArgumentCloning()
            ->disallowMockingUnknownTypes()
            ->onlyMethods(["get"])
            ->getMock();

        $validateCalled = false;
        $validate = $this->getMockBuilder(BasicUpdateValidator::class)
            ->disableOriginalConstructor()
            ->disableOriginalClone()
            ->disableArgumentCloning()
            ->disallowMockingUnknownTypes()
            ->onlyMethods(["validate"])
            ->getMock();
        $validate->method('validate')->willReturnCallback(function () use (&$validateCalled) {
            $validateCalled = true;
        });

        $validator->method('get')->willReturn($validate);

        $object = $this->getMockBuilder(BaseAPIController::class)
            ->disableOriginalConstructor()
            ->disableOriginalClone()
            ->disableArgumentCloning()
            ->disallowMockingUnknownTypes()
            ->onlyMethods(["getEntity"])
            ->getMock();

        $object->method('getEntity')->willReturn("");

        Route::shouldReceive("current")->andReturn(new DynamicClass(
                [
                    "getActionName" => function() {
                        return "TestController@testAction";
                    }
                ]
            )
        );

        $method = self::getMethod('validationsClause', BaseAPIController::class);
        $clause = $method->invokeArgs($object, [$validator]);

        $called = false;
        $clause("request", function($param) use (&$called) {
            $called = true;
            self::assertEquals($param, "request");
        });

        self::assertTrue($validateCalled);
        self::assertTrue($called);
    }
}

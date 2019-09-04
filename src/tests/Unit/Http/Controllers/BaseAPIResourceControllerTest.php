<?php

namespace ltoolkit\Test\Unit\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use ltoolkit\Interfaces\IUnitOfWork;
use Illuminate\Support\Facades\Response;
use ltoolkit\Test\Environment\TestCase;
use ltoolkit\Interfaces\ICriteriaIterator;
use ltoolkit\Interfaces\IValidatorResolver;
use Illuminate\Http\Response as HttpReponse;
use ltoolkit\Test\Environment\MockExceptionHandler;
use ltoolkit\Http\Controllers\BaseAPIResourceController;
use ltoolkit\Test\Environment\Repositories\MockRepository;
use Illuminate\Contracts\Debug\ExceptionHandler as ExceptionHandlerContract;

class BaseAPIResourceControllerTest extends TestCase
{
    /**
     * Test for retrieving the data.
     * Context: with parameters that force the call to pushCriteria and no limit to call the
     * function all from the repository
     */
    public function test_index_1()
    {
        $request     = Request::create('www.test.com', 'GET', ['test' => 1]);
        $entityClass = "ltoolkit\Test\Environment\Models\Mock";
        $object      = $this->getMockBuilder(BaseAPIResourceController::class)
            ->disableOriginalClone()
            ->disableArgumentCloning()
            ->disallowMockingUnknownTypes()
            ->setMethods(['getEntity', 'getRepository', 'respond'])
            ->setConstructorArgs(
                [
                'unitOfWork'          => app(IUnitOfWork::class),
                'validatorCollection' => app(IValidatorResolver::class),
                'criteria'            => app(ICriteriaIterator::class)]
            )
            ->getMock();

        $object->method('respond')->willReturnCallback(
            function ($data, $message) {
                return Response::json(['data' => $data->toArray(), 'message' => $message]);
            }
        );
        $object->method('getEntity')->willReturn($entityClass);

        $repository = $this->getMockBuilder(MockRepository::class)
            ->disableOriginalClone()
            ->disableArgumentCloning()
            ->disallowMockingUnknownTypes()
            ->setMethods(['pushCriteria'])
            ->setConstructorArgs(['unitOfWork' => app(IUnitOfWork::class)])
            ->getMock();
        $repository->expects(self::once())->method('pushCriteria')->willReturnCallback(
            function () use ($repository) {
                return $repository;
            }
        );

        $object->method('getRepository')->willReturn($repository);

        $method   = self::getMethod('index', BaseAPIResourceController::class);
        $response = $method->invokeArgs($object, [$request]);

        $this->assertEquals("all", $response->getData()->data[0]);
        $this->assertEquals("ltoolkit::messages.entity.retrieved", $response->getData()->message);
    }

    /**
     * Test for retrieving the data with limit
     * Context: with parameters that force the call to pushCriteria and limit to call the
     * function paginate from the repository
     */
    public function test_index_2()
    {
        $request     = Request::create('www.test.com', 'GET', ['test' => 1, 'limit' => 1]);
        $entityClass = "ltoolkit\Test\Environment\Models\Mock";
        $object      = $this->getMockBuilder(BaseAPIResourceController::class)
            ->disableOriginalClone()
            ->disableArgumentCloning()
            ->disallowMockingUnknownTypes()
            ->setMethods(['getEntity', 'getRepository', 'respond'])
            ->setConstructorArgs(
                [
                'unitOfWork'          => app(IUnitOfWork::class),
                'validatorCollection' => app(IValidatorResolver::class),
                'criteria'            => app(ICriteriaIterator::class)]
            )
            ->getMock();

        $object->method('respond')->willReturnCallback(
            function ($data, $message) {
                return Response::json(['data' => $data->toArray(), 'message' => $message]);
            }
        );
        $object->method('getEntity')->willReturn($entityClass);

        $repository = $this->getMockBuilder(MockRepository::class)
            ->disableOriginalClone()
            ->disableArgumentCloning()
            ->disallowMockingUnknownTypes()
            ->setMethods(['pushCriteria'])
            ->setConstructorArgs(['unitOfWork' => app(IUnitOfWork::class)])
            ->getMock();
        $repository->expects(self::once())->method('pushCriteria')->willReturnCallback(
            function () use ($repository) {
                return $repository;
            }
        );

        $object->method('getRepository')->willReturn($repository);

        $method   = self::getMethod('index', BaseAPIResourceController::class);
        $response = $method->invokeArgs($object, [$request]);

        $this->assertEquals("paginate", $response->getData()->data[0]);
        $this->assertEquals("ltoolkit::messages.entity.retrieved", $response->getData()->message);
    }

    /**
     * Test for retrieving the data.
     * Context: Entity found
     */
    public function test_show_1()
    {
        $entityClass = "ltoolkit\Test\Environment\Models\Mock";
        $params      = 1;
        $object      = $this->getMockBuilder(BaseAPIResourceController::class)
            ->disableOriginalClone()
            ->disableArgumentCloning()
            ->disallowMockingUnknownTypes()
            ->setMethods(['getEntity', 'getRepository'])
            ->setConstructorArgs(
                [
                'unitOfWork'          => app(IUnitOfWork::class),
                'validatorCollection' => app(IValidatorResolver::class),
                'criteria'            => app(ICriteriaIterator::class)]
            )
            ->getMock();

        $object->method('getEntity')->willReturn($entityClass);
        $object->method('getRepository')->willReturn(new MockRepository(app(IUnitOfWork::class)));

        $method   = self::getMethod('show', BaseAPIResourceController::class);
        $response = $method->invokeArgs($object, [$params]);

        $this->assertEquals(1, $response->getData()->data->id);
        $this->assertEquals("ltoolkit::messages.entity.retrieved", $response->getData()->message);
        $this->assertEquals(HttpReponse::HTTP_OK, $response->getStatusCode());
    }

    /**
     * Test for retrieving the data.
     * Context: Entity not found
     */
    public function test_show_2()
    {
        $entityClass = "ltoolkit\Test\Environment\Models\Mock";
        $params      = 0;
        $object      = $this->getMockBuilder(BaseAPIResourceController::class)
            ->disableOriginalClone()
            ->disableArgumentCloning()
            ->disallowMockingUnknownTypes()
            ->setMethods(['getEntity', 'getRepository'])
            ->setConstructorArgs(
                [
                'unitOfWork'          => app(IUnitOfWork::class),
                'validatorCollection' => app(IValidatorResolver::class),
                'criteria'            => app(ICriteriaIterator::class)]
            )
            ->getMock();

        $object->method('getEntity')->willReturn($entityClass);
        $object->method('getRepository')->willReturn(new MockRepository(app(IUnitOfWork::class)));

        $method   = self::getMethod('show', BaseAPIResourceController::class);
        $response = $method->invokeArgs($object, [$params]);

        $this->assertEquals("ltoolkit::messages.entity.not_found", $response->getData()->message);
        $this->assertEquals(HttpReponse::HTTP_NOT_FOUND, $response->getStatusCode());
    }

    /**
     * Test for retrieving the data with relations.
     * Context: Entity found
     */
    public function test_show_3()
    {
        $entityClass = "ltoolkit\Test\Environment\Models\Mock";
        $params      = 1;
        $object      = $this->getMockBuilder(BaseAPIResourceController::class)
            ->disableOriginalClone()
            ->disableArgumentCloning()
            ->disallowMockingUnknownTypes()
            ->setMethods(['getEntity', 'getRepository'])
            ->setConstructorArgs(
                [
                    'unitOfWork'          => app(IUnitOfWork::class),
                    'validatorCollection' => app(IValidatorResolver::class),
                    'criteria'            => app(ICriteriaIterator::class)]
            )
            ->getMock();

        $object->method('getEntity')->willReturn($entityClass);
        $object->method('getRepository')->willReturn(new MockRepository(app(IUnitOfWork::class)));

        $method   = self::getMethod('show', BaseAPIResourceController::class);

        $this->app->bind("request", function () {
            return new Request(["with" => "relation1"]);
        });
        $response = $method->invokeArgs($object, [$params]);

        $this->assertEquals(1, $response->getData()->data->id);
        $this->assertEquals("ltoolkit::messages.entity.retrieved", $response->getData()->message);
        $this->assertEquals(HttpReponse::HTTP_OK, $response->getStatusCode());
    }

    /**
     * Test for retrieving the data with relations.
     * Context: Entity found
     */
    public function test_showWithRelationList_1()
    {
        $entityClass = "ltoolkit\Test\Environment\Models\Mock";
        $params      = 1;
        $object      = $this->getMockBuilder(BaseAPIResourceController::class)
            ->disableOriginalClone()
            ->disableArgumentCloning()
            ->disallowMockingUnknownTypes()
            ->setMethods(['getEntity', 'getRepository'])
            ->setConstructorArgs(
                [
                'unitOfWork'          => app(IUnitOfWork::class),
                'validatorCollection' => app(IValidatorResolver::class),
                'criteria'            => app(ICriteriaIterator::class)]
            )
            ->getMock();

        $object->method('getEntity')->willReturn($entityClass);
        $object->method('getRepository')->willReturn(new MockRepository(app(IUnitOfWork::class)));

        $method   = self::getMethod('showWithRelationList', BaseAPIResourceController::class);
        $response = $method->invokeArgs($object, [$params, ["relation1"]]);

        $this->assertEquals(1, $response->getData()->data->id);
        $this->assertEquals("ltoolkit::messages.entity.retrieved", $response->getData()->message);
        $this->assertEquals(HttpReponse::HTTP_OK, $response->getStatusCode());
    }

    /**
     * Test for retrieving the data with relations.
     * Context: Entity not found
     */
    public function test_showWithRelationList_2()
    {
        $entityClass = "ltoolkit\Test\Environment\Models\Mock";
        $params      = 0;
        $object      = $this->getMockBuilder(BaseAPIResourceController::class)
            ->disableOriginalClone()
            ->disableArgumentCloning()
            ->disallowMockingUnknownTypes()
            ->setMethods(['getEntity', 'getRepository'])
            ->setConstructorArgs(
                [
                'unitOfWork'          => app(IUnitOfWork::class),
                'validatorCollection' => app(IValidatorResolver::class),
                'criteria'            => app(ICriteriaIterator::class)]
            )
            ->getMock();

        $object->method('getEntity')->willReturn($entityClass);
        $object->method('getRepository')->willReturn(new MockRepository(app(IUnitOfWork::class)));

        $method   = self::getMethod('showWithRelationList', BaseAPIResourceController::class);
        $response = $method->invokeArgs($object, [$params, ["relation1"]]);

        $this->assertEquals("ltoolkit::messages.entity.not_found", $response->getData()->message);
        $this->assertEquals(HttpReponse::HTTP_NOT_FOUND, $response->getStatusCode());
    }

    /**
     * Test for storing the data.
     * Context: Entity created without errors
     */
    public function test_store_1()
    {
        $entityClass = "ltoolkit\Test\Environment\Models\Mock";
        $object      = $this->getMockBuilder(BaseAPIResourceController::class)
            ->disableOriginalClone()
            ->disableArgumentCloning()
            ->disallowMockingUnknownTypes()
            ->setMethods(['getEntity', 'getRepository'])
            ->setConstructorArgs(
                [
                'unitOfWork'          => app(IUnitOfWork::class),
                'validatorCollection' => app(IValidatorResolver::class),
                'criteria'            => app(ICriteriaIterator::class)]
            )
            ->getMock();

        $object->method('getEntity')->willReturn($entityClass);
        $object->method('getRepository')->willReturn(new MockRepository(app(IUnitOfWork::class)));

        $params   = ['test' => 1];
        $request  = Request::create('www.test.com', 'GET', $params);
        $method   = self::getMethod('store', BaseAPIResourceController::class);
        $response = $method->invokeArgs($object, [$request]);

        $this->assertIsObject($response->getData()->data);
        $this->assertEquals(1, $response->getData()->data->test);
        $this->assertEquals("ltoolkit::messages.entity.saved", $response->getData()->message);
        $this->assertEquals(HttpReponse::HTTP_OK, $response->getStatusCode());
    }

    /**
     * Test for storing the data.
     * Context: Entity create with errors. An exception is thrown and reported
     */
    public function test_store_2()
    {
        $errorReported = false;
        $object      = $this->getMockBuilder(MockExceptionHandler::class)
            ->disableOriginalClone()
            ->disableOriginalConstructor()
            ->disableArgumentCloning()
            ->disallowMockingUnknownTypes()
            ->setMethods(['report'])
            ->getMock();

        $object->method('report')->willReturnCallback(
            function () use (&$errorReported) {
                $errorReported = true;
            }
        );

        $this->app->instance(ExceptionHandlerContract::class, $object);

        $entityClass = "ltoolkit\Test\Environment\Models\Mock";
        $object      = $this->getMockBuilder(BaseAPIResourceController::class)
            ->disableOriginalClone()
            ->disableArgumentCloning()
            ->disallowMockingUnknownTypes()
            ->setMethods(['getEntity', 'getRepository'])
            ->setConstructorArgs(
                [
                'unitOfWork'          => app(IUnitOfWork::class),
                'validatorCollection' => app(IValidatorResolver::class),
                'criteria'            => app(ICriteriaIterator::class)]
            )
            ->getMock();

        $message = "Mock exception";

        $object->method('getEntity')->willReturn($entityClass);
        $object->method('getRepository')->willThrowException(new Exception($message));

        $params   = ['test' => 1];
        $request  = Request::create('www.test.com', 'GET', $params);
        $method   = self::getMethod('store', BaseAPIResourceController::class);
        $response = $method->invokeArgs($object, [$request]);

        $this->assertTrue($errorReported);
        $this->assertNull($response->getData()->data);
        $this->assertEquals($message, $response->getData()->message);
        $this->assertEquals(HttpReponse::HTTP_BAD_REQUEST, $response->getStatusCode());
    }

    /**
     * Test for updating the data.
     */
    public function test_update_1()
    {
        $entityClass = "ltoolkit\Test\Environment\Models\Mock";
        $object = $this->getMockBuilder(BaseAPIResourceController::class)
            ->disableOriginalClone()
            ->disableArgumentCloning()
            ->disallowMockingUnknownTypes()
            ->setMethods(['getEntity', 'getRepository'])
            ->setConstructorArgs(
                [
                'unitOfWork'          => app(IUnitOfWork::class),
                'validatorCollection' => app(IValidatorResolver::class),
                'criteria'            => app(ICriteriaIterator::class)]
            )
            ->getMock();

        $object->method('getEntity')->willReturn($entityClass);
        $object->method('getRepository')->willReturn(new MockRepository(app(IUnitOfWork::class)));

        $params   = ['test' => 1];
        $request  = Request::create('www.test.com', 'GET', $params);
        $method   = self::getMethod('update', BaseAPIResourceController::class);
        $response = $method->invokeArgs($object, [1, $request]);

        $this->assertIsObject($response->getData()->data);
        $this->assertEquals(1, $response->getData()->data->test);
        $this->assertEquals(HttpReponse::HTTP_OK, $response->getStatusCode());
    }

    /**
     * Test for updating the data.
     * Context: Entity not found for update
     */
    public function test_update_2()
    {
        $entityClass = "ltoolkit\Test\Environment\Models\Mock";
        $object = $this->getMockBuilder(BaseAPIResourceController::class)
            ->disableOriginalClone()
            ->disableArgumentCloning()
            ->disallowMockingUnknownTypes()
            ->setMethods(['getEntity', 'getRepository'])
            ->setConstructorArgs(
                [
                'unitOfWork'          => app(IUnitOfWork::class),
                'validatorCollection' => app(IValidatorResolver::class),
                'criteria'            => app(ICriteriaIterator::class)]
            )
            ->getMock();

        $object->method('getEntity')->willReturn($entityClass);
        $object->method('getRepository')->willReturn(new MockRepository(app(IUnitOfWork::class)));

        $params   = ['test' => 1];
        $request  = Request::create('www.test.com', 'GET', $params);
        $method   = self::getMethod('update', BaseAPIResourceController::class);
        $response = $method->invokeArgs($object, [0, $request]);

        $this->assertNull($response->getData()->data);
        $this->assertEquals("ltoolkit::messages.entity.not_found", $response->getData()->message);
        $this->assertEquals(HttpReponse::HTTP_NOT_FOUND, $response->getStatusCode());
    }

    /**
     * Test for update the data.
     * Context: Entity update with errors. An exception is thrown and reported
     */
    public function test_update_3()
    {
        $errorReported = false;
        $object      = $this->getMockBuilder(MockExceptionHandler::class)
            ->disableOriginalClone()
            ->disableOriginalConstructor()
            ->disableArgumentCloning()
            ->disallowMockingUnknownTypes()
            ->setMethods(['report'])
            ->getMock();

        $object->method('report')->willReturnCallback(
            function () use (&$errorReported) {
                $errorReported = true;
            }
        );

        $this->app->instance(ExceptionHandlerContract::class, $object);

        $entityClass = "ltoolkit\Test\Environment\Models\Mock";
        $object      = $this->getMockBuilder(BaseAPIResourceController::class)
            ->disableOriginalClone()
            ->disableArgumentCloning()
            ->disallowMockingUnknownTypes()
            ->setMethods(['getEntity', 'getRepository'])
            ->setConstructorArgs(
                [
                'unitOfWork'          => app(IUnitOfWork::class),
                'validatorCollection' => app(IValidatorResolver::class),
                'criteria'            => app(ICriteriaIterator::class)]
            )
            ->getMock();

        $message = "Mock exception";

        $object->method('getEntity')->willReturn($entityClass);
        $object->method('getRepository')->willThrowException(new Exception($message));

        $params   = ['test' => 1];
        $request  = Request::create('www.test.com', 'GET', $params);
        $method   = self::getMethod('update', BaseAPIResourceController::class);
        $response = $method->invokeArgs($object, [0, $request]);

        $this->assertTrue($errorReported);
        $this->assertNull($response->getData()->data);
        $this->assertEquals($message, $response->getData()->message);
        $this->assertEquals(HttpReponse::HTTP_BAD_REQUEST, $response->getStatusCode());
    }

    /**
     * Test for destroying the data.
     */
    public function test_destroy_1()
    {
        $entityClass = "ltoolkit\Test\Environment\Models\Mock";
        $object = $this->getMockBuilder(BaseAPIResourceController::class)
            ->disableOriginalClone()
            ->disableArgumentCloning()
            ->disallowMockingUnknownTypes()
            ->setMethods(['getEntity', 'getRepository'])
            ->setConstructorArgs(
                [
                'unitOfWork'          => app(IUnitOfWork::class),
                'validatorCollection' => app(IValidatorResolver::class),
                'criteria'            => app(ICriteriaIterator::class)]
            )
            ->getMock();

        $object->method('getEntity')->willReturn($entityClass);
        $object->method('getRepository')->willReturn(new MockRepository(app(IUnitOfWork::class)));

        $method   = self::getMethod('destroy', BaseAPIResourceController::class);
        $response = $method->invokeArgs($object, [1]);

        $this->assertEquals(1, $response->getData()->data);
        $this->assertEquals("ltoolkit::messages.entity.deleted", $response->getData()->message);
        $this->assertEquals(HttpReponse::HTTP_OK, $response->getStatusCode());
    }

    /**
     * Test for destroying the data.
     */
    public function test_destroy_2()
    {
        $entityClass = "ltoolkit\Test\Environment\Models\Mock";
        $object = $this->getMockBuilder(BaseAPIResourceController::class)
            ->disableOriginalClone()
            ->disableArgumentCloning()
            ->disallowMockingUnknownTypes()
            ->setMethods(['getEntity', 'getRepository'])
            ->setConstructorArgs(
                [
                'unitOfWork'          => app(IUnitOfWork::class),
                'validatorCollection' => app(IValidatorResolver::class),
                'criteria'            => app(ICriteriaIterator::class)]
            )
            ->getMock();

        $object->method('getEntity')->willReturn($entityClass);
        $object->method('getRepository')->willReturn(new MockRepository(app(IUnitOfWork::class)));

        $method   = self::getMethod('destroy', BaseAPIResourceController::class);
        $response = $method->invokeArgs($object, [0]);

        $this->assertNull($response->getData()->data);
        $this->assertEquals("ltoolkit::messages.entity.not_found", $response->getData()->message);
        $this->assertEquals(HttpReponse::HTTP_NOT_FOUND, $response->getStatusCode());
    }
}

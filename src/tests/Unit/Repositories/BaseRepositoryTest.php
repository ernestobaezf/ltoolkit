<?php
/**
 * @author Ernesto Baez 
 */

namespace l5toolkit\tests\Unit\Repositories;


use Exception;
use Illuminate\Support\Facades\Log;
use l5toolkit\Interfaces\IEntity;
use l5toolkit\Interfaces\IRepositoryConnector;
use l5toolkit\Interfaces\IUnitOfWork;
use l5toolkit\Repositories\BaseRepository;
use l5toolkit\Test\Environment\Connectors\MockRepositoryConnector;
use l5toolkit\Test\Environment\Connectors\MockUnitOfWork;
use l5toolkit\Test\Environment\DynamicClass;
use l5toolkit\Test\Environment\Models\MockModel;
use l5toolkit\Test\Environment\Models\MockModelNoRelations;
use l5toolkit\Test\Environment\TestCase;

class BaseRepositoryTest extends TestCase
{
    /**
     * Get internal repository
     */
    public function test_getInternalRepository()
    {
        $this->app->bind(IRepositoryConnector::class, MockRepositoryConnector::class);
        $object = $this->getMockBuilder(BaseRepository::class)
            ->disableOriginalClone()
            ->disableArgumentCloning()
            ->disallowMockingUnknownTypes()
            ->setMethods(["model"])
            ->setConstructorArgs([app(IUnitOfWork::class)])
            ->getMock();
        $object->method("model")->willReturn("");

        $method = self::getMethod('getInternalRepository', BaseRepository::class);
        $response = $method->invoke($object);

        self::assertTrue($response instanceof IRepositoryConnector);
    }

    /**
     * Push criteria
     *
     * @throws \ReflectionException
     */
    public function test_pushCriteria_1()
    {
        $this->app->bind(IRepositoryConnector::class, MockRepositoryConnector::class);
        $this->app->extend(IRepositoryConnector::class, function() {
            $object = $this->getMockBuilder(MockRepositoryConnector::class)
                ->disableOriginalConstructor()
                ->disableOriginalClone()
                ->disableArgumentCloning()
                ->disallowMockingUnknownTypes()
                ->getMock();
            $object->expects(self::once())->method("pushCriteria");

            return $object;
        });

        $object = $this->getMockBuilder(BaseRepository::class)
            ->disableOriginalClone()
            ->disableArgumentCloning()
            ->disallowMockingUnknownTypes()
            ->setMethods(["model"])
            ->setConstructorArgs([app(IUnitOfWork::class)])
            ->getMock();
        $object->method("model")->willReturn("");

        $method = self::getMethod('pushCriteria', BaseRepository::class);
        $response = $method->invokeArgs($object, [""]);

        $this->assertTrue($response == $object);
    }

    /**
     * Push criteria with exception
     *
     * @throws \ReflectionException
     */
    public function test_pushCriteria_2()
    {
        $this->app->bind(IRepositoryConnector::class, MockRepositoryConnector::class);
        $this->app->extend(IRepositoryConnector::class, function() {
            $object = $this->getMockBuilder(MockRepositoryConnector::class)
                ->disableOriginalConstructor()
                ->disableOriginalClone()
                ->disableArgumentCloning()
                ->disallowMockingUnknownTypes()
                ->getMock();
            $object->method("pushCriteria")->willThrowException(new Exception("Exception"));

            return $object;
        });

        $this->expectException(Exception::class);
        $object = $this->getMockBuilder(BaseRepository::class)
            ->disableOriginalClone()
            ->disableArgumentCloning()
            ->disallowMockingUnknownTypes()
            ->setMethods(["model"])
            ->setConstructorArgs([app(IUnitOfWork::class)])
            ->getMock();
        $object->method("model")->willReturn("");

        $method = self::getMethod('pushCriteria', BaseRepository::class);
        $method->invokeArgs($object, [""]);
    }

    /**
     * Skip criteria
     *
     * @throws \ReflectionException
     */
    public function test_skipCriteria()
    {
        $this->app->bind(IRepositoryConnector::class, MockRepositoryConnector::class);
        $this->app->extend(IRepositoryConnector::class, function() {
            $object = $this->getMockBuilder(MockRepositoryConnector::class)
                ->disableOriginalConstructor()
                ->disableOriginalClone()
                ->disableArgumentCloning()
                ->disallowMockingUnknownTypes()
                ->getMock();
            $object->expects(self::once())->method("skipCriteria");

            return $object;
        });

        $object = $this->getMockBuilder(BaseRepository::class)
            ->disableOriginalClone()
            ->disableArgumentCloning()
            ->disallowMockingUnknownTypes()
            ->setMethods(["model"])
            ->setConstructorArgs([app(IUnitOfWork::class)])
            ->getMock();
        $object->method("model")->willReturn("");

        $class = new \ReflectionObject($object);
        $method = $class->getMethod("skipCriteria");
        $response = $method->invoke($object);

        $this->assertTrue($response === $object);
    }

    /**
     * Skip criteria
     *
     * @throws \ReflectionException
     */
    public function test_resetCriteria()
    {
        $this->app->bind(IRepositoryConnector::class, MockRepositoryConnector::class);
        $this->app->extend(IRepositoryConnector::class, function() {
            $object = $this->getMockBuilder(MockRepositoryConnector::class)
                ->disableOriginalConstructor()
                ->disableOriginalClone()
                ->disableArgumentCloning()
                ->disallowMockingUnknownTypes()
                ->getMock();
            $object->expects(self::once())->method("resetCriteria");

            return $object;
        });

        $object = $this->getMockBuilder(BaseRepository::class)
            ->disableOriginalClone()
            ->disableArgumentCloning()
            ->disallowMockingUnknownTypes()
            ->setMethods(["model"])
            ->setConstructorArgs([app(IUnitOfWork::class)])
            ->getMock();
        $object->method("model")->willReturn("");

        $class = new \ReflectionObject($object);
        $method = $class->getMethod("resetCriteria");
        $response = $method->invoke($object);

        $this->assertTrue($response === $object);
    }

    /**
     * Set scope query
     *
     * @throws \ReflectionException
     */
    public function test_scopeQuery()
    {
        $this->app->bind(IRepositoryConnector::class, MockRepositoryConnector::class);
        $this->app->extend(IRepositoryConnector::class, function() {
            $object = $this->getMockBuilder(MockRepositoryConnector::class)
                ->disableOriginalConstructor()
                ->disableOriginalClone()
                ->disableArgumentCloning()
                ->disallowMockingUnknownTypes()
                ->getMock();
            $object->expects(self::once())->method("scopeQuery");

            return $object;
        });

        $object = $this->getMockBuilder(BaseRepository::class)
            ->disableOriginalClone()
            ->disableArgumentCloning()
            ->disallowMockingUnknownTypes()
            ->setMethods(["model"])
            ->setConstructorArgs([app(IUnitOfWork::class)])
            ->getMock();
        $object->method("model")->willReturn("");

        $method = self::getMethod('scopeQuery', BaseRepository::class);
        $response = $method->invokeArgs($object, [function(){}]);

        $this->assertTrue($response == $object);
    }

    /**
     * Pop criteria
     *
     * @throws \ReflectionException
     */
    public function test_popCriteria()
    {
        $this->app->bind(IRepositoryConnector::class, MockRepositoryConnector::class);
        $this->app->extend(IRepositoryConnector::class, function() {
            $object = $this->getMockBuilder(MockRepositoryConnector::class)
                ->disableOriginalConstructor()
                ->disableOriginalClone()
                ->disableArgumentCloning()
                ->disallowMockingUnknownTypes()
                ->getMock();
            $object->expects(self::once())->method("popCriteria")->willReturnCallback(function ($criteria) {
                return "Arg passed: $criteria";
            });

            return $object;
        });

        $object = $this->getMockBuilder(BaseRepository::class)
            ->disableOriginalClone()
            ->disableArgumentCloning()
            ->disallowMockingUnknownTypes()
            ->setMethods(["model"])
            ->setConstructorArgs([app(IUnitOfWork::class)])
            ->getMock();
        $object->method("model")->willReturn("");

        $class = new \ReflectionObject($object);
        $method = $class->getMethod("popCriteria");
        $response = $method->invokeArgs($object, ["arg"]);

        $this->assertTrue($response == "Arg passed: arg");
    }

    /**
     * Get criteria
     *
     * @throws \ReflectionException
     */
    public function test_getCriteria()
    {
        $this->app->bind(IRepositoryConnector::class, MockRepositoryConnector::class);
        $this->app->extend(IRepositoryConnector::class, function() {
            $object = $this->getMockBuilder(MockRepositoryConnector::class)
                ->disableOriginalConstructor()
                ->disableOriginalClone()
                ->disableArgumentCloning()
                ->disallowMockingUnknownTypes()
                ->getMock();
            $object->expects(self::once())->method("getCriteria")->willReturnCallback(function () {
                return "Called getCriteria";
            });

            return $object;
        });

        $object = $this->getMockBuilder(BaseRepository::class)
            ->disableOriginalClone()
            ->disableArgumentCloning()
            ->disallowMockingUnknownTypes()
            ->setMethods(["model"])
            ->setConstructorArgs([app(IUnitOfWork::class)])
            ->getMock();
        $object->method("model")->willReturn("");

        $class = new \ReflectionObject($object);
        $method = $class->getMethod("getCriteria");
        $response = $method->invoke($object);

        $this->assertTrue($response == "Called getCriteria");
    }

    /**
     * Get unit of work
     *
     * @throws \ReflectionException
     */
    public function test_unitOfWork()
    {
        $this->app->bind(IRepositoryConnector::class, MockRepositoryConnector::class);
        $object = $this->getMockBuilder(BaseRepository::class)
            ->disableOriginalClone()
            ->disableArgumentCloning()
            ->disallowMockingUnknownTypes()
            ->setMethods(["model"])
            ->setConstructorArgs([app(IUnitOfWork::class)])
            ->getMock();
        $object->method("model")->willReturn("");

        $method = self::getMethod('getUnitOfWork', BaseRepository::class);
        $response = $method->invoke($object);

        self::assertTrue($response instanceof IUnitOfWork);
    }

    /**
     * Get all entities
     *
     * @throws \ReflectionException
     */
    public function test_all()
    {
        $this->app->bind(IRepositoryConnector::class, MockRepositoryConnector::class);
        $this->app->extend(IRepositoryConnector::class, function() {
            $object = $this->getMockBuilder(MockRepositoryConnector::class)
                ->disableOriginalConstructor()
                ->disableOriginalClone()
                ->disableArgumentCloning()
                ->disallowMockingUnknownTypes()
                ->getMock();
            $object->expects(self::once())->method("all")->willReturnCallback(function ()
            {
                return collect();
            });

            return $object;
        });

        $object = $this->getMockBuilder(BaseRepository::class)
            ->disableOriginalClone()
            ->disableArgumentCloning()
            ->disallowMockingUnknownTypes()
            ->setMethods(["model", "evaluate"])
            ->setConstructorArgs([app(IUnitOfWork::class)])
            ->getMock();
        $object->method("model")->willReturn("");
        $object->method("evaluate")->willReturnCallback(function ($closure, $functionName, $payload) {
            return $closure();
        });

        $class = new \ReflectionObject($object);
        $method = $class->getMethod("all");
        $method->invoke($object);
    }

    /**
     * Get all entities paginated
     *
     * @throws \ReflectionException
     */
    public function test_paginate()
    {
        $this->app->bind(IRepositoryConnector::class, MockRepositoryConnector::class);
        $this->app->extend(IRepositoryConnector::class, function() {
            $object = $this->getMockBuilder(MockRepositoryConnector::class)
                ->disableOriginalConstructor()
                ->disableOriginalClone()
                ->disableArgumentCloning()
                ->disallowMockingUnknownTypes()
                ->getMock();
            $object->expects(self::once())->method("paginate")->willReturnCallback(function () {
                return collect();
            });

            return $object;
        });

        $object = $this->getMockBuilder(BaseRepository::class)
            ->disableOriginalClone()
            ->disableArgumentCloning()
            ->disallowMockingUnknownTypes()
            ->setMethods(["model", "evaluate"])
            ->setConstructorArgs([app(IUnitOfWork::class)])
            ->getMock();
        $object->method("model")->willReturn("");
        $object->method("evaluate")->willReturnCallback(function ($closure, $functionName, $payload)
        {
            return $closure();
        });

        $class = new \ReflectionObject($object);
        $method = $class->getMethod("paginate");
        $method->invoke($object);
    }

    /**
     * Get all entities paginated (simplePaginate)
     *
     * @throws \ReflectionException
     */
    public function test_simplePaginate()
    {
        $this->app->bind(IRepositoryConnector::class, MockRepositoryConnector::class);
        $this->app->extend(IRepositoryConnector::class, function() {
            $object = $this->getMockBuilder(MockRepositoryConnector::class)
                ->disableOriginalConstructor()
                ->disableOriginalClone()
                ->disableArgumentCloning()
                ->disallowMockingUnknownTypes()
                ->getMock();
            $object->expects(self::once())->method("simplePaginate")->willReturnCallback(function () {
                return collect();
            });

            return $object;
        });

        $object = $this->getMockBuilder(BaseRepository::class)
            ->disableOriginalClone()
            ->disableArgumentCloning()
            ->disallowMockingUnknownTypes()
            ->setMethods(["model", "evaluate"])
            ->setConstructorArgs([app(IUnitOfWork::class)])
            ->getMock();
        $object->method("model")->willReturn("");
        $object->method("evaluate")->willReturnCallback(function ($closure, $functionName, $payload) {
            return $closure();
        });

        $class = new \ReflectionObject($object);
        $method = $class->getMethod("simplePaginate");
        $method->invoke($object);
    }

    /**
     * Find entity by id
     *
     * @throws \ReflectionException
     */
    public function test_find()
    {
        $id = 1;
        $columns = ["*"];

        $this->app->bind(IRepositoryConnector::class, MockRepositoryConnector::class);
        $this->app->extend(IRepositoryConnector::class, function() use ($id, $columns) {
            $object = $this->getMockBuilder(MockRepositoryConnector::class)
                ->disableOriginalConstructor()
                ->disableOriginalClone()
                ->disableArgumentCloning()
                ->disallowMockingUnknownTypes()
                ->getMock();
            $object->expects(self::once())->method("find")->willReturnCallback(
                function ($_id, $_column) use ($id, $columns) {
                    self::assertTrue($id == $_id);
                    self::assertTrue($columns == $_column);
                    return null;
                });

            return $object;
        });

        $object = $this->getMockBuilder(BaseRepository::class)
            ->disableOriginalClone()
            ->disableArgumentCloning()
            ->disallowMockingUnknownTypes()
            ->setMethods(["model", "evaluate", "setScope"])
            ->setConstructorArgs([app(IUnitOfWork::class)])
            ->getMock();
        $object->method("setScope")->willReturnCallback(function ($arg) {
            return $arg;
        });
        $object->method("model")->willReturn("");
        $object->method("evaluate")->willReturnCallback(function ($closure, $functionName, $payload) {
            return $closure();
        });

        $class = new \ReflectionObject($object);
        $method = $class->getMethod("find");
        $method->invokeArgs($object, [$id, $columns]);

    }

    /**
     * Find entity by field
     *
     * @throws \ReflectionException
     */
    public function test_findByField()
    {
        $called = false;
        $id = 1;
        $columns = ["*"];

        $this->app->bind(IRepositoryConnector::class, MockRepositoryConnector::class);
        $this->app->extend(IRepositoryConnector::class, function() use (&$called, $id, $columns) {
            $object = $this->getMockBuilder(MockRepositoryConnector::class)
                ->disableOriginalConstructor()
                ->disableOriginalClone()
                ->disableArgumentCloning()
                ->disallowMockingUnknownTypes()
                ->getMock();
            $object->method("findByField")->willReturnCallback(
                function ($_id, $value, $_column) use (&$called, $id, $columns)
                {
                    $called = true;

                    self::assertTrue($id == $_id);
                    self::assertTrue($columns == $_column);
                    return collect();
                });

            return $object;
        });

        $object = $this->getMockBuilder(BaseRepository::class)
            ->disableOriginalClone()
            ->disableArgumentCloning()
            ->disallowMockingUnknownTypes()
            ->setMethods(["model", "evaluate", "setScope"])
            ->setConstructorArgs([app(IUnitOfWork::class)])
            ->getMock();
        $object->method("setScope")->willReturnCallback(function ($arg)
        {
            return $arg;
        });
        $object->method("model")->willReturn("");
        $object->method("evaluate")->willReturnCallback(function ($closure, $functionName, $payload)
        {
            return $closure();
        });

        $class = new \ReflectionObject($object);
        $method = $class->getMethod("findByField");
        $method->invokeArgs($object, [$id, [], $columns]);

        $this->assertTrue($called);
    }

    /**
     * Create entity
     *
     * @throws \ReflectionException
     */
    public function test_create()
    {
        $called = false;
        $attributes = [1, 2];

        $this->app->bind(IRepositoryConnector::class, MockRepositoryConnector::class);

        $uow = $this->getMockBuilder(MockUnitOfWork::class)
            ->disableOriginalClone()
            ->disableArgumentCloning()
            ->disallowMockingUnknownTypes()
            ->setMethods(["beginTransaction"])
            ->getMock();
        $uow->method("beginTransaction")->willReturnCallback(function () use (&$called)
        {
            $called = true;
        });

        $object = $this->getMockBuilder(BaseRepository::class)
            ->disableOriginalClone()
            ->disableArgumentCloning()
            ->disallowMockingUnknownTypes()
            ->setMethods(["model", "evaluate", "execute"])
            ->setConstructorArgs([$uow])
            ->getMock();
        $object->method("model")->willReturn("");
        $object->method("execute")->willReturnCallback(function ($functionName, $_attributes)
        use ($attributes)
        {
            self::assertTrue($functionName == 'create');
            self::assertTrue($attributes == $_attributes);

            return new MockModel();
        });

        $object->method("evaluate")->willReturnCallback(function ($closure, $functionName, $payload)
        {
            return $closure();
        });

        $class = new \ReflectionObject($object);
        $method = $class->getMethod("create");
        $method->invokeArgs($object, [$attributes]);

        self::assertTrue($called);
    }

    /**
     * Update entity
     *
     * @throws \ReflectionException
     */
    public function test_update()
    {
        $id = 1;
        $attributes = [2, 3];

        $this->app->bind(IRepositoryConnector::class, MockRepositoryConnector::class);

        $uow = $this->getMockBuilder(MockUnitOfWork::class)
            ->disableOriginalClone()
            ->disableArgumentCloning()
            ->disallowMockingUnknownTypes()
            ->setMethods(["beginTransaction"])
            ->getMock();
        $uow->expects($this->once())->method("beginTransaction");

        $object = $this->getMockBuilder(BaseRepository::class)
            ->disableOriginalClone()
            ->disableArgumentCloning()
            ->disallowMockingUnknownTypes()
            ->setMethods(["model", "evaluate", "execute"])
            ->setConstructorArgs([$uow])
            ->getMock();
        $object->method("model")->willReturn("");
        $object->method("execute")->willReturnCallback(function ($functionName, $_attributes, $_id)
        use ($id, $attributes)
        {
            self::assertTrue($functionName == 'update');
            self::assertTrue($id == $_id);
            self::assertTrue($attributes == $_attributes);

            return new MockModel();
        });

        $object->expects(self::once())->method("evaluate")->willReturnCallback(function ($closure, $functionName, $payload)
        {
            return $closure();
        });

        $class = new \ReflectionObject($object);
        $method = $class->getMethod("update");
        $method->invokeArgs($object, [$id, $attributes]);
    }

    /**
     * Update entity
     *
     * @throws \ReflectionException
     */
    public function test_updateOrCreate()
    {
        $values = [4, 6];
        $attributes = [2, 3];

        $this->app->bind(IRepositoryConnector::class, MockRepositoryConnector::class);

        $uow = $this->getMockBuilder(MockUnitOfWork::class)
            ->disableOriginalClone()
            ->disableArgumentCloning()
            ->disallowMockingUnknownTypes()
            ->setMethods(["beginTransaction"])
            ->getMock();
        $uow->expects(self::once())->method("beginTransaction");

        $object = $this->getMockBuilder(BaseRepository::class)
            ->disableOriginalClone()
            ->disableArgumentCloning()
            ->disallowMockingUnknownTypes()
            ->setMethods(["model", "evaluate", "execute"])
            ->setConstructorArgs([$uow])
            ->getMock();
        $object->method("model")->willReturn("");
        $object->method("execute")->willReturnCallback(function ($functionName, $_attributes, $_values)
        use ($values, $attributes)
        {
            self::assertTrue($functionName == 'updateOrCreate');
            self::assertTrue($values == $_values);
            self::assertTrue($attributes == $_attributes);

            return new MockModel();
        });

        $object->method("evaluate")->willReturnCallback(function ($closure, $functionName, $payload)
        {
            return $closure();
        });

        $class = new \ReflectionObject($object);
        $method = $class->getMethod("updateOrCreate");
        $method->invokeArgs($object, [$attributes, $values]);
    }

    /**
     * Update entity
     *
     * @throws \ReflectionException
     */
    public function test_delete()
    {
        $deleteCalled = false;
        $id = 3;

        $this->app->bind(IRepositoryConnector::class, MockRepositoryConnector::class);
        $this->app->extend(IRepositoryConnector::class, function() use (&$deleteCalled, $id) {
            $object = $this->getMockBuilder(MockRepositoryConnector::class)
                ->disableOriginalConstructor()
                ->disableOriginalClone()
                ->disableArgumentCloning()
                ->disallowMockingUnknownTypes()
                ->getMock();
            $object->method("delete")->willReturnCallback(
                function ($_id) use (&$deleteCalled, $id)
                {
                    $deleteCalled = true;

                    self::assertTrue($id == $_id);
                    return $_id;
                });

            return $object;
        });

        $uow = $this->getMockBuilder(MockUnitOfWork::class)
            ->disableOriginalClone()
            ->disableArgumentCloning()
            ->disallowMockingUnknownTypes()
            ->setMethods(["beginTransaction"])
            ->getMock();
        $uow->expects(self::once())->method("beginTransaction");

        $object = $this->getMockBuilder(BaseRepository::class)
            ->disableOriginalClone()
            ->disableArgumentCloning()
            ->disallowMockingUnknownTypes()
            ->setMethods(["model", "evaluate", "execute"])
            ->setConstructorArgs([$uow])
            ->getMock();
        $object->method("model")->willReturn("");
        $object->method("execute")->willReturnCallback(function ($functionName, $_id)
        use ($id)
        {
            self::assertTrue($functionName == 'delete');
            self::assertTrue($id == $_id);

            return new MockModel();
        });

        $object->method("evaluate")->willReturnCallback(function ($closure, $functionName, $payload)
        {
            return $closure();
        });

        $class = new \ReflectionObject($object);
        $method = $class->getMethod("delete");
        $method->invokeArgs($object, [$id]);

        self::assertTrue($deleteCalled);
    }

    /**
     * Set scope
     * Context: columns without relations
     *
     * @throws \ReflectionException
     */
    public function test_setScope_1()
    {
        $columns = ["*"];
        $object = $this->mockClass(BaseRepository::class);

        $method = $this->getMethod("setScope", BaseRepository::class);
        $result = $method->invokeArgs($object, [$columns]);

        $this->assertTrue($result == $columns);
    }

    /**
     * Set scope
     * Context: columns with relations
     *
     * @throws \ReflectionException
     */
    public function test_setScope_2()
    {
        $columns = ["*", "relations" => MockModel::RELATIONS];
        $object = $this->getMockBuilder(BaseRepository::class)
            ->disableOriginalClone()
            ->disableArgumentCloning()
            ->disableOriginalConstructor()
            ->disallowMockingUnknownTypes()
            ->setMethods(["scopeQuery", "model"])
            ->getMock();
        $object->expects(self::once())->method("scopeQuery");
        $object->method("model")->willReturn("");

        $method = $this->getMethod("setScope", BaseRepository::class);
        $result = $method->invokeArgs($object, [$columns]);

        unset($columns["relations"]);
        $this->assertTrue($result == $columns);
    }

    /**
     * Get Model relations
     * Context: Get clean relations = false
     *
     * @throws \ReflectionException
     */
    public function test_getModelRelations_1()
    {
        $object = $this->mockClass(BaseRepository::class, "model", MockModel::class);

        $method = $this->getMethod("getModelRelations", BaseRepository::class);
        $result = $method->invokeArgs($object, [false]);

        $this->assertTrue($result == MockModel::RELATIONS);
    }

    /**
     * Get Model relations
     * Context: Get clean relations
     *
     * @throws \ReflectionException
     */
    public function test_getModelRelations_2()
    {
        $object = $this->mockClass(BaseRepository::class, "model", MockModel::class);

        $method = $this->getMethod("getModelRelations", BaseRepository::class);
        $result = $method->invoke($object);

        $this->assertTrue($result == ["relation1", "relation2.concatenated", "relation3"]);
    }

    /**
     * Commit and reset unit of work to previous autocommit status
     *
     * @throws \ReflectionException
     */
    public function test_commitAndResetUnitOfWork()
    {
        $param = true;
        $uow = $this->getMockBuilder(MockUnitOfWork::class)
            ->disableOriginalClone()
            ->disableArgumentCloning()
            ->disallowMockingUnknownTypes()
            ->setMethods(["commit", "setAutoCommit"])
            ->getMock();
        $uow->expects(self::once())->method("commit");
        $uow->expects(self::once())->method("setAutoCommit")->willReturnCallback(function ($_param) use ($param)
        {
            self::assertTrue($param == $_param);
        });

        $object = $this->getMockBuilder(BaseRepository::class)
            ->disableOriginalClone()
            ->disableArgumentCloning()
            ->disableOriginalConstructor()
            ->disallowMockingUnknownTypes()
            ->setMethods(["getUnitOfWork", "model"])
            ->getMock();
        $object->method("getUnitOfWork")->willReturn($uow);

        $method = $this->getMethod("commitAndResetUnitOfWork", BaseRepository::class);
        $method->invokeArgs($object, [$param]);
    }

    /**
     * Verify whether the model relations are in the attributes and set autocommit to false if so to save the data for
     * the model and the relation one transaction
     * Context: There is one attribute that is defined in the model relations
     *
     * @throws \ReflectionException
     */
    public function test_checkRelation_1()
    {
        $uow = $this->getMockBuilder(MockUnitOfWork::class)
            ->disableOriginalClone()
            ->disableArgumentCloning()
            ->disallowMockingUnknownTypes()
            ->setMethods(["isAutoCommit", "setAutoCommit"])
            ->getMock();
        $uow->expects(self::once())->method("isAutoCommit")->willReturn(true);
        $uow->expects(self::once())->method("setAutoCommit")->willReturnCallback(function ($param)
        {
            self::assertFalse($param);
        });

        $object = $this->getMockBuilder(BaseRepository::class)
            ->disableOriginalClone()
            ->disableArgumentCloning()
            ->disableOriginalConstructor()
            ->disallowMockingUnknownTypes()
            ->setMethods(["getUnitOfWork", "model"])
            ->getMock();
        $object->method("getUnitOfWork")->willReturn($uow);
        $object->method("model")->willReturn(MockModel::class);

        $method = $this->getMethod("checkRelation", BaseRepository::class);
        list($autoCommit, $relations, $attributes) = $method->invokeArgs($object, [
            [
                "field" => "attribute1",
                "relation1" => [23],
                "relation4" => [23]
            ]]);

        $this->assertTrue($autoCommit);
        $this->assertTrue($relations == ["relation1" => [23]]);
        $this->assertTrue($attributes == ["field" => "attribute1", "relation4" => [23]]);
    }

    /**
     * Verify whether the model relations are in the attributes and set autocommit to false if so to save the data for
     * the model and the relation one transaction
     * Context: The model does not have relations defined
     *
     * @throws \ReflectionException
     */
    public function test_checkRelation_2()
    {
        $uow = $this->getMockBuilder(MockUnitOfWork::class)
            ->disableOriginalClone()
            ->disableArgumentCloning()
            ->disallowMockingUnknownTypes()
            ->setMethods(["isAutoCommit", "setAutoCommit"])
            ->getMock();
        $uow->expects(self::once())->method("isAutoCommit")->willReturn(true);
        $uow->expects(self::exactly(0))->method("setAutoCommit");

        $object = $this->getMockBuilder(BaseRepository::class)
            ->disableOriginalClone()
            ->disableArgumentCloning()
            ->disableOriginalConstructor()
            ->disallowMockingUnknownTypes()
            ->setMethods(["getUnitOfWork", "model"])
            ->getMock();
        $object->method("getUnitOfWork")->willReturn($uow);
        $object->method("model")->willReturn(MockModelNoRelations::class);

        $method = $this->getMethod("checkRelation", BaseRepository::class);
        list($autoCommit, $relations, $attributes) = $method->invokeArgs($object, [
            [
                "field" => "attribute1",
                "relation1" => [23],
                "relation4" => [23]
            ]]);

        $this->assertTrue($autoCommit);
        $this->assertTrue($relations == []);
        $this->assertTrue($attributes == ["field" => "attribute1", "relation1" => [23], "relation4" => [23]]);
    }

    /**
     * Verify whether the model relations are in the attributes and set autocommit to false if so to save the data for
     * the model and the relation one transaction
     * Context: There is no attribute defined in the model relations
     *
     * @throws \ReflectionException
     */
    public function test_checkRelation_3()
    {
        $uow = $this->getMockBuilder(MockUnitOfWork::class)
            ->disableOriginalClone()
            ->disableArgumentCloning()
            ->disallowMockingUnknownTypes()
            ->setMethods(["isAutoCommit", "setAutoCommit"])
            ->getMock();
        $uow->expects(self::once())->method("isAutoCommit")->willReturn(true);
        $uow->expects(self::exactly(0))->method("setAutoCommit");

        $object = $this->getMockBuilder(BaseRepository::class)
            ->disableOriginalClone()
            ->disableArgumentCloning()
            ->disableOriginalConstructor()
            ->disallowMockingUnknownTypes()
            ->setMethods(["getUnitOfWork", "model"])
            ->getMock();
        $object->method("getUnitOfWork")->willReturn($uow);
        $object->method("model")->willReturn(MockModel::class);

        $method = $this->getMethod("checkRelation", BaseRepository::class);
        list($autoCommit, $relations, $attributes) = $method->invokeArgs($object, [
            [
                "field" => "attribute1",
                "field2" => [23],
                "field3" => [23]
            ]]);

        $this->assertTrue($autoCommit);
        $this->assertTrue($relations == []);
        $this->assertTrue($attributes == ["field" => "attribute1", "field2" => [23], "field3" => [23]]);
    }

    /**
     * Helper function to execute functions that alter database and handle/sync relations in one place
     * Context: Test when the uow is auto commit
     *
     * @throws \ReflectionException
     */
    public function test_execute_1()
    {
        $this->app->bind(IRepositoryConnector::class, MockRepositoryConnector::class);

        $uow = $this->getMockBuilder(MockUnitOfWork::class)
            ->disableOriginalClone()
            ->disableArgumentCloning()
            ->disallowMockingUnknownTypes()
            ->setMethods(["commit", "setAutoCommit"])
            ->getMock();
        $uow->expects($this->exactly(3))->method("commit");
        $uow->expects($this->exactly(6))->method("setAutoCommit");

        $called = '';
        $this->app->extend(IRepositoryConnector::class, function() use (&$called) {
            $object = $this->getMockBuilder(MockRepositoryConnector::class)
                ->disableOriginalConstructor()
                ->disableOriginalClone()
                ->disableArgumentCloning()
                ->disallowMockingUnknownTypes()
                ->getMock();

            $functionName = "updateOrCreate";
            $object->method("updateOrCreate")->willReturnCallback(
                function ($attributes=null, $values=null) use (&$called, $functionName)
                {
                    $called = $functionName;

                    self::assertNotNull($attributes);
                    self::assertNotNull($values);

                    return new MockModel();
                });

            $functionName = "update";
            $object->method("update")->willReturnCallback(
                function ($attributes=null, $values=null) use (&$called, $functionName)
                {
                    $called = $functionName;

                    self::assertNotNull($attributes);
                    self::assertNotNull($values);

                    return new MockModel();
                });

            $functionName = "create";
            $object->method("create")->willReturnCallback(
                function ($attributes=null, $values=null) use (&$called, $functionName)
                {
                    $called = $functionName;

                    self::assertNotNull($attributes);
                    self::assertNull($values);

                    return new MockModel();
                });

            return $object;
        });

        $object = $this->getMockBuilder(BaseRepository::class)
            ->disableOriginalClone()
            ->disableArgumentCloning()
            ->disallowMockingUnknownTypes()
            ->setConstructorArgs([$uow])
            ->setMethods(["model"])
            ->getMock();
        $object->method("model")->willReturn(MockModel::class);

        Log::shouldReceive("info")->times(3);

        $method = $this->getMethod("execute", BaseRepository::class);
        $result = $method->invokeArgs($object, ["updateOrCreate", ["relation1" => [2]], []]);
        self::assertEquals("updateOrCreate", $called);
        self::assertTrue($result instanceof IEntity);

        $result = $method->invokeArgs($object, ["update", ["relation1" => [2]], []]);
        self::assertEquals("update", $called);
        self::assertTrue($result instanceof IEntity);

        $result = $method->invokeArgs($object, ["create", ["relation1" => [2]]]);
        self::assertEquals("create", $called);
        self::assertTrue($result instanceof IEntity);
    }

    /**
     * Helper function to execute functions that alter database and handle/sync relations in one place
     * Context: Test when the uow is not auto commit. Test concatenated relations and values as strings in attributes.
     *
     * @throws \ReflectionException
     */
    public function test_execute_2()
    {
        $this->app->bind(IRepositoryConnector::class, MockRepositoryConnector::class);

        $uow = $this->getMockBuilder(MockUnitOfWork::class)
            ->disableOriginalClone()
            ->disableArgumentCloning()
            ->disallowMockingUnknownTypes()
            ->setMethods(["commit", "setAutoCommit", "isAutoCommit"])
            ->getMock();
        $uow->expects($this->exactly(0))->method("commit");
        $uow->expects($this->exactly(3))->method("setAutoCommit");
        $uow->method("isAutoCommit")->willReturn(false);

        $called = '';
        $this->app->extend(IRepositoryConnector::class, function() use (&$called) {
            $object = $this->getMockBuilder(MockRepositoryConnector::class)
                ->disableOriginalConstructor()
                ->disableOriginalClone()
                ->disableArgumentCloning()
                ->disallowMockingUnknownTypes()
                ->getMock();

            $functionName = "updateOrCreate";
            $object->method("updateOrCreate")->willReturnCallback(
                function ($attributes=null, $values=null) use (&$called, $functionName)
                {
                    $called = $functionName;

                    self::assertNotNull($attributes);
                    self::assertNotNull($values);

                    return new MockModel();
                });

            $functionName = "update";
            $object->method("update")->willReturnCallback(
                function ($attributes=null, $values=null) use (&$called, $functionName)
                {
                    $called = $functionName;

                    self::assertNotNull($attributes);
                    self::assertNotNull($values);

                    return new MockModel();
                });

            $functionName = "create";
            $object->method("create")->willReturnCallback(
                function ($attributes=null, $values=null) use (&$called, $functionName)
                {
                    $called = $functionName;

                    self::assertNotNull($attributes);
                    self::assertNull($values);

                    return new MockModel();
                });

            return $object;
        });

        $object = $this->getMockBuilder(BaseRepository::class)
            ->disableOriginalClone()
            ->disableArgumentCloning()
            ->disallowMockingUnknownTypes()
            ->setConstructorArgs([$uow])
            ->setMethods(["model"])
            ->getMock();
        $object->method("model")->willReturn(MockModel::class);

        Log::shouldReceive("info")->times(3);

        $method = $this->getMethod("execute", BaseRepository::class);
        $result = $method->invokeArgs($object,
            [
                "updateOrCreate",
                [
                    "relation1" => [2],
                    "relation2.concatenated" => "[2]"
                ],
                []
            ]
        );

        self::assertEquals("updateOrCreate", $called);
        self::assertTrue($result instanceof IEntity);

        $result = $method->invokeArgs($object, ["update", ["relation1" => [2]], []]);
        self::assertEquals("update", $called);
        self::assertTrue($result instanceof IEntity);

        $result = $method->invokeArgs($object, ["create", ["relation1" => [2]]]);
        self::assertEquals("create", $called);
        self::assertTrue($result instanceof IEntity);
    }

    /**
     * Helper function to execute functions that alter database and handle/sync relations in one place
     * Context: Test when an exception is thrown
     *
     * @throws \ReflectionException
     */
    public function test_execute_3()
    {
        $this->app->bind(IRepositoryConnector::class, MockRepositoryConnector::class);

        $uow = $this->getMockBuilder(MockUnitOfWork::class)
            ->disableOriginalClone()
            ->disableArgumentCloning()
            ->disallowMockingUnknownTypes()
            ->setMethods(["rollback", "isAutoCommit"])
            ->getMock();
        $uow->expects($this->exactly(1))->method("rollback");
        $uow->method("isAutoCommit")->willReturn(false);

        $this->app->extend(IRepositoryConnector::class, function() {
            $object = $this->getMockBuilder(MockRepositoryConnector::class)
                ->disableOriginalConstructor()
                ->disableOriginalClone()
                ->disableArgumentCloning()
                ->disallowMockingUnknownTypes()
                ->getMock();

            $object->method("create")->willReturnCallback(
                function ($attributes=null, $values=null)
                {
                    self::assertNotNull($attributes);
                    self::assertNull($values);

                    return new MockModel();
                });

            return $object;
        });

        $object = $this->getMockBuilder(BaseRepository::class)
            ->disableOriginalClone()
            ->disableArgumentCloning()
            ->disallowMockingUnknownTypes()
            ->setConstructorArgs([$uow])
            ->setMethods(["model"])
            ->getMock();
        $object->method("model")->willThrowException(new Exception("Test"));

        $this->expectException(Exception::class);

        $method = $this->getMethod("execute", BaseRepository::class);
        $method->invokeArgs($object,
            [
                "updateOrCreate",
                [
                    "relation1" => [2],
                    "relation2.concatenated" => "[2]"
                ],
                []
            ]
        );
    }

    public function test_scope()
    {
        $object = $this->getMockBuilder(BaseRepository::class)
            ->disableOriginalClone()
            ->disableArgumentCloning()
            ->disableOriginalConstructor()
            ->disallowMockingUnknownTypes()
            ->setMethods(["model"])
            ->getMock();

        $object->method("model")->willReturn(MockModel::class);

        $method = self::getMethod("scope", BaseRepository::class);
        $closure = $method->invokeArgs($object, [["relation1" => [2], "relation2" => [2]]]);

        $result = $closure(new DynamicClass(
                [
                    "with" => function($param) {
                        return $param[0];
                    }
                ]
            )
        );

        self::assertEquals(["relation1" => [2], "relation2" => [2]], $result);
    }
}

<?php

namespace LToolkit\Test\Unit\Adapters;

use Illuminate\Support\Facades\DB;
use LToolkit\Adapters\UnitOfWork;
use LToolkit\Interfaces\RepositoryResolverInterface;
use LToolkit\Interfaces\UnitOfWorkInterface;
use LToolkit\Test\Environment\Repositories\MockRepository;
use LToolkit\Test\Environment\Adapters\MockRepositoryResolver;
use LToolkit\Test\Environment\TestCase;

class UnitOfWorkTest extends TestCase
{
    /**
     * Test function beginTransaction when autocommit is true.
     *
     * @return void
     * @throws \ReflectionException
     */
    public function test_beginTransaction_1()
    {
        $called = false;
        DB::shouldReceive('beginTransaction')
            ->withNoArgs()
            ->andReturnUsing(
                function () use (&$called) {
                    $called = true;
                    return;
                }
            );

        $uow = new UnitOfWork(true);
        $uow->beginTransaction();

        $class = new \ReflectionObject($uow);
        $property = $class->getProperty('inTransaction');
        $property->setAccessible(true);
        $inTransaction = $property->getValue($uow);

        $this->assertFalse($inTransaction);
        $this->assertFalse($called);
    }

    /**
     * Test function beginTransaction when autocommit is false and called multiple times.
     *
     * @return void
     * @throws \ReflectionException
     */
    public function test_beginTransaction_2()
    {
        DB::shouldReceive('beginTransaction')
            ->withNoArgs()
            ->once();

        $uow = new UnitOfWork(false);
        $uow->beginTransaction();

        $class = new \ReflectionObject($uow);
        $property = $class->getProperty('inTransaction');
        $property->setAccessible(true);
        $inTransaction = $property->getValue($uow);

        $uow->beginTransaction();

        $this->assertTrue($inTransaction);
    }

    /**
     * Test function commit when autocommit is true and when its false. Also when is called without
     * call beginTransaction before
     *
     * @return void
     * @throws \ReflectionException
     */
    public function test_commit()
    {
        $called = false;
        DB::shouldReceive('commit')
            ->withNoArgs()
            ->andReturnUsing(
                function () use (&$called) {
                    $called = true;
                    return;
                }
            );

        DB::shouldReceive('beginTransaction')
            ->once();

        $uow = app(UnitOfWorkInterface::class, ["autoCommit" => true]);

        $class = new \ReflectionObject($uow);
        $property = $class->getProperty('inTransaction');
        $property->setAccessible(true);
        $inTransaction = $property->getValue($uow);

        $uow->commit();
        $this->assertFalse($called);
        $this->assertFalse($inTransaction);

        $uow = app(UnitOfWorkInterface::class, ["autoCommit" => false]);

        $uow->commit();
        $this->assertFalse($called);

        $uow->beginTransaction();

        $class = new \ReflectionObject($uow);
        $property = $class->getProperty('inTransaction');
        $property->setAccessible(true);

        $inTransaction = $property->getValue($uow);
        $this->assertTrue($inTransaction);

        $uow->commit();

        $inTransaction = $property->getValue($uow);
        $this->assertFalse($inTransaction);
        $this->assertTrue($called);

        $called = false;
        $uow->commit();
        $this->assertFalse($called);
    }

    /**
     * Test function rollback when autocommit is true and when its false. Also when is called without
     * call beginTransaction before
     *
     * @return void
     * @throws \ReflectionException
     */
    public function test_rollback()
    {
        $called = false;
        DB::shouldReceive('rollback')
            ->withNoArgs()
            ->andReturnUsing(
                function () use (&$called) {
                    $called = true;
                    return;
                }
            );

        DB::shouldReceive('beginTransaction')->once();

        $uow = app(UnitOfWorkInterface::class, ["autoCommit" => true]);

        $uow->rollback();

        $class = new \ReflectionObject($uow);
        $property = $class->getProperty('inTransaction');
        $property->setAccessible(true);
        $inTransaction = $property->getValue($uow);

        $this->assertFalse($called);
        $this->assertFalse($inTransaction);

        $uow = app(UnitOfWorkInterface::class, ["autoCommit" => false]);

        $uow->rollback();

        $class = new \ReflectionObject($uow);
        $property = $class->getProperty('inTransaction');
        $property->setAccessible(true);

        $this->assertFalse($called);

        $uow->beginTransaction();

        $inTransaction = $property->getValue($uow);
        $this->assertTrue($inTransaction);

        $uow->rollback();
        $this->assertTrue($called);

        $inTransaction = $property->getValue($uow);
        $this->assertFalse($inTransaction);

        $called = false;
        $uow->rollback();
        $this->assertFalse($called);
    }

    /**
     * Get IGeneric repository instance when exists the repository for the given model and is GenericRepositoryInterface
     *
     * @return void
     */
    public function test_isAutocommit()
    {
        app()->bind(RepositoryResolverInterface::class, MockRepositoryResolver::class);

        $uow = app(UnitOfWorkInterface::class);
        $this->assertTrue($uow->isAutocommit());

        $uow = app(UnitOfWorkInterface::class, ["autoCommit" => false]);
        $this->assertFalse($uow->isAutocommit());

        $uow->setAutoCommit(true);
        $this->assertTrue($uow->isAutocommit());
    }

    /**
     * Get IGeneric repository instance when exists the repository for the given model and is GenericRepositoryInterface
     *
     * @return void
     */
    public function test_getRepository()
    {
        $entityClass = "LToolkit\Test\Environment\Models\Mock";

        app()->bind(RepositoryResolverInterface::class, MockRepositoryResolver::class);

        $uow = app(UnitOfWorkInterface::class);
        $repository = $uow->getRepository($entityClass);

        $this->assertTrue($repository instanceof MockRepository);
    }
}

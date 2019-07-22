<?php

namespace ErnestoBaezF\L5CoreToolbox\test\Unit\Connectors;

use Closure;
use Exception;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use ErnestoBaezF\L5CoreToolbox\Connectors\RepositoryFinder;
use ErnestoBaezF\L5CoreToolbox\Connectors\UnitOfWork;
use ErnestoBaezF\L5CoreToolbox\Interfaces\IGenericRepository;
use ErnestoBaezF\L5CoreToolbox\Interfaces\IRemoteRepository;
use ErnestoBaezF\L5CoreToolbox\Test\Environment\Repositories\GenericMockRepository;
use ErnestoBaezF\L5CoreToolbox\Test\Environment\Repositories\MockRemoteRepository;
use ErnestoBaezF\L5CoreToolbox\Test\Environment\Repositories\MockRepository;
use ErnestoBaezF\L5CoreToolbox\Test\Environment\TestCase;
use ReflectionException;

class RepositoryFinderTest extends TestCase
{
    /**
     * Get IGenericRepository when does not exist the repository for the given model
     *
     * @return void
     */
    public function test_findRepositoryClass_1()
    {
        $entityClass = "ErnestoBaezF\L5CoreToolbox\Models\Mock".time();

        $finder = new RepositoryFinder(new UnitOfWork(false));

        $method = self::getMethod("findRepositoryClass", RepositoryFinder::class);
        $repository =  $method->invokeArgs($finder, ["entityClass" => $entityClass]);

        $this->assertTrue($repository == IGenericRepository::class);
    }

    /**
     * Get MockRepository when exists the repository for the given model
     *
     * @return void
     * @throws ReflectionException
     */
    public function test_findRepositoryClass_2()
    {
        $entityClass = "ErnestoBaezF\L5CoreToolbox\Models\Mock";

        $finder = $this->mockClass(RepositoryFinder::class, 'classExists', true);

        $method = self::getMethod("findRepositoryClass", RepositoryFinder::class);
        $repository =  $method->invokeArgs($finder, ["entityClass" => $entityClass]);

        $this->assertTrue($repository == 'ErnestoBaezF\L5CoreToolbox\Repositories\MockRepository');
    }

    /**
     * Get MockRepository when exists the repository for the given model and there is a map
     *
     * @return void
     * @throws ReflectionException
     */
    public function test_findRepositoryClass_3()
    {
        $entityClass = "ErnestoBaezF\L5CoreToolbox\Models\Mock";

        Config::set('L5CoreToolbox.repository_map', [$entityClass => 'MockRepository']);

        $finder = $this->mockClass(RepositoryFinder::class, 'classExists', true);

        $method = self::getMethod("findRepositoryClass", RepositoryFinder::class);
        $repository =  $method->invokeArgs($finder, ["entityClass" => $entityClass]);

        $this->assertTrue($repository == 'MockRepository');
    }

    /**
     * Get MockRepository when does not exist the repository for the given model and there is a map
     *
     * @return void
     * @throws ReflectionException
     */
    public function test_findRepositoryClass_4()
    {
        $entityClass = "ErnestoBaezF\L5CoreToolbox\Models\Mock";

        Config::set('L5CoreToolbox.repository_map', [$entityClass => 'MockRepository']);

        $finder = $this->mockClass(RepositoryFinder::class, 'classExists', false);

        try {
            $method = self::getMethod("findRepositoryClass", RepositoryFinder::class);
            $method->invokeArgs($finder, ["entityClass" => $entityClass]);
        } catch (Exception $exception) {
            $this->assertTrue($exception->getMessage() == 'Repository class MockRepository not found.');

            return;
        }

        $this->fail("Exception expected");
    }

    /**
     * Get MockRepository when exists the repository for the given model and there is a map
     * for model and repository paths
     *
     * @return void
     * @throws ReflectionException
     */
    public function test_findRepositoryClass_5()
    {
        $entityClass = "ErnestoBaezF\L5CoreToolbox\Models\Mock";

        Config::set('L5CoreToolbox.repository_map', ['ErnestoBaezF\L5CoreToolbox\Models' => 'Packages\Custom\RepositoryPath']);

        $finder = $this->mockClass(RepositoryFinder::class, 'classExists', true);

        $method = self::getMethod("findRepositoryClass", RepositoryFinder::class);
        $repository = $method->invokeArgs($finder, ["entityClass" => $entityClass]);
        $this->assertTrue($repository == 'Packages\Custom\RepositoryPath\MockRepository');
    }

    /**
     * Get MockRepository when exists the repository for the given model and there is a map
     * for model and repository paths and the repository does not exist
     *
     * @return void
     * @throws ReflectionException
     */
    public function test_findRepositoryClass_6()
    {
        $entityClass = "ErnestoBaezF\L5CoreToolbox\Models\Mock";

        Config::set('L5CoreToolbox.repository_map', ['ErnestoBaezF\L5CoreToolbox\Models' => 'Packages\Custom\RepositoryPath']);

        $finder = $this->mockClass(RepositoryFinder::class, 'classExists', false);

        $method = self::getMethod("findRepositoryClass", RepositoryFinder::class);
        $repository = $method->invokeArgs($finder, ["entityClass" => $entityClass]);
        $this->assertTrue($repository == IGenericRepository::class);
    }

    /**
     * Get IGeneric repository instance when exists the repository for the given model and is IGenericRepository
     *
     * @return void
     */
    public function test_getRepository_1()
    {
        $entityClass = "ErnestoBaezF\L5CoreToolbox\Test\Environment\Models\Mock";

        Cache::shouldReceive('rememberForever')
            ->once()
            ->with(RepositoryFinder::class."::getRepository($entityClass)", Closure::class)
            ->andReturn(IGenericRepository::class);

        app()->bind(IGenericRepository::class, GenericMockRepository::class);

        $finder = new RepositoryFinder(new UnitOfWork(false));

        $method = self::getMethod("getRepository", RepositoryFinder::class);
        $repository =  $method->invokeArgs($finder, ["entityClass" => $entityClass]);

        $this->assertTrue($repository instanceof IGenericRepository);
    }

    /**
     * Get IGeneric repository instance when exists the repository for the given model and is not IGenericRepository
     *
     * @return void
     */
    public function test_getRepository_2()
    {
        $entityClass = "ErnestoBaezF\L5CoreToolbox\Test\Environment\Models\Mock";

        $finder = new RepositoryFinder(new UnitOfWork(false));

        $method = self::getMethod("getRepository", RepositoryFinder::class);
        $repository =  $method->invokeArgs($finder, ["entityClass" => $entityClass]);

        $this->assertTrue($repository instanceof MockRepository);
    }

    /**
     * Get IRemoteRepository instance
     *
     * @return void
     */
    public function test_getRepository_3()
    {
        $entityClass = "ErnestoBaezF\L5CoreToolbox\Test\Environment\Models\Mock";

        Cache::shouldReceive("rememberForever")->andReturn(MockRemoteRepository::class);
        $finder = new RepositoryFinder(new UnitOfWork(false));

        $method = self::getMethod("getRepository", RepositoryFinder::class);
        $repository =  $method->invokeArgs($finder, ["entityClass" => $entityClass]);

        $this->assertTrue($repository instanceof IRemoteRepository);
    }
}

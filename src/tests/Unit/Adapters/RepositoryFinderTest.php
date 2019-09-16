<?php

namespace LToolkit\Test\Unit\Adapters;

use Closure;
use Exception;
use ReflectionException;
use LToolkit\Adapters\UnitOfWork;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use LToolkit\Test\Environment\TestCase;
use LToolkit\Adapters\RepositoryResolver;
use Psr\Repository\RemoteRepositoryInterface;
use LToolkit\Interfaces\GenericRepositoryInterface;
use LToolkit\Test\Environment\Repositories\MockRepository;
use LToolkit\Test\Environment\Repositories\GenericMockRepository;
use LToolkit\Test\Environment\Repositories\MockRemoteRepository;

class RepositoryFinderTest extends TestCase
{
    /**
     * Get GenericRepositoryInterface when does not exist the repository for the given model
     *
     * @return void
     */
    public function test_findRepositoryClass_1()
    {
        $entityClass = "LToolkit\Models\Mock".time();

        $finder = new RepositoryResolver(new UnitOfWork(false));

        $method = self::getMethod("findRepositoryClass", RepositoryResolver::class);
        $repository =  $method->invokeArgs($finder, ["entityClass" => $entityClass]);

        $this->assertTrue($repository == GenericRepositoryInterface::class);
    }

    /**
     * Get MockRepository when exists the repository for the given model
     *
     * @return void
     * @throws ReflectionException
     */
    public function test_findRepositoryClass_2()
    {
        $entityClass = "LToolkit\Models\Mock";

        $finder = $this->mockClass(RepositoryResolver::class, 'classExists', true);

        $method = self::getMethod("findRepositoryClass", RepositoryResolver::class);
        $repository =  $method->invokeArgs($finder, ["entityClass" => $entityClass]);

        $this->assertTrue($repository == 'LToolkit\Repositories\MockRepository');
    }

    /**
     * Get MockRepository when exists the repository for the given model and there is a map
     *
     * @return void
     * @throws ReflectionException
     */
    public function test_findRepositoryClass_3()
    {
        $entityClass = "LToolkit\Models\Mock";

        Config::set('LToolkit.repository_map', [$entityClass => 'MockRepository']);

        $finder = $this->mockClass(RepositoryResolver::class, 'classExists', true);

        $method = self::getMethod("findRepositoryClass", RepositoryResolver::class);
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
        $entityClass = "LToolkit\Models\Mock";

        Config::set('LToolkit.repository_map', [$entityClass => 'MockRepository']);

        $finder = $this->mockClass(RepositoryResolver::class, 'classExists', false);

        try {
            $method = self::getMethod("findRepositoryClass", RepositoryResolver::class);
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
        $entityClass = "LToolkit\Models\Mock";

        Config::set('LToolkit.repository_map', ['LToolkit\Models' => 'Packages\Custom\RepositoryPath']);

        $finder = $this->mockClass(RepositoryResolver::class, 'classExists', true);

        $method = self::getMethod("findRepositoryClass", RepositoryResolver::class);
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
        $entityClass = "LToolkit\Models\Mock";

        Config::set('LToolkit.repository_map', ['LToolkit\Models' => 'Packages\Custom\RepositoryPath']);

        $finder = $this->mockClass(RepositoryResolver::class, 'classExists', false);

        $method = self::getMethod("findRepositoryClass", RepositoryResolver::class);
        $repository = $method->invokeArgs($finder, ["entityClass" => $entityClass]);
        $this->assertTrue($repository == GenericRepositoryInterface::class);
    }

    /**
     * Get IGeneric repository instance when exists the repository for the given model and is GenericRepositoryInterface
     *
     * @return void
     */
    public function test_getRepository_1()
    {
        $entityClass = "LToolkit\Test\Environment\Models\Mock";

        Cache::shouldReceive('rememberForever')
            ->once()
            ->with(RepositoryResolver::class."::getRepository($entityClass)", Closure::class)
            ->andReturn(GenericRepositoryInterface::class);

        app()->bind(GenericRepositoryInterface::class, GenericMockRepository::class);

        $finder = new RepositoryResolver(new UnitOfWork(false));

        $method = self::getMethod("getRepository", RepositoryResolver::class);
        $repository =  $method->invokeArgs($finder, ["entityClass" => $entityClass]);

        $this->assertTrue($repository instanceof GenericRepositoryInterface);
    }

    /**
     * Get IGeneric repository instance when exists the repository for the given model and is not GenericRepositoryInterface
     *
     * @return void
     */
    public function test_getRepository_2()
    {
        $entityClass = "LToolkit\Test\Environment\Models\Mock";

        $finder = new RepositoryResolver(new UnitOfWork(false));

        $method = self::getMethod("getRepository", RepositoryResolver::class);
        $repository =  $method->invokeArgs($finder, ["entityClass" => $entityClass]);

        $this->assertTrue($repository instanceof MockRepository);
    }

    /**
     * Get RemoteRepositoryInterface instance
     *
     * @return void
     */
    public function test_getRepository_3()
    {
        $entityClass = "LToolkit\Test\Environment\Models\Mock";

        Cache::shouldReceive("rememberForever")->andReturn(MockRemoteRepository::class);
        $finder = new RepositoryResolver(new UnitOfWork(false));

        $method = self::getMethod("getRepository", RepositoryResolver::class);
        $repository =  $method->invokeArgs($finder, ["entityClass" => $entityClass]);

        $this->assertTrue($repository instanceof RemoteRepositoryInterface);
    }
}

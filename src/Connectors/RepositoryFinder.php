<?php
/**
 * @author Ernesto Baez 
 */

namespace LToolkit\Connectors;


use Exception;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use LToolkit\Interfaces\IUnitOfWork;
use LToolkit\Interfaces\IBaseRepository;
use LToolkit\Interfaces\IRepositoryFinder;
use LToolkit\Interfaces\IRemoteRepository;
use LToolkit\Interfaces\IGenericRepository;
use Illuminate\Contracts\Container\BindingResolutionException;

final class RepositoryFinder implements IRepositoryFinder
{
    private const REPOSITORIES_DIRNAME = "Repositories";
    private const REPOSITORY_POSTFIX = "Repository";
    private const ARGUMENT_UNIT_OF_WORK = "unitOfWork";
    private const ARGUMENT_MODEL_CLASS = "modelClass";

    private $unitOfWork;

    public function __construct(IUnitOfWork $unitOfWork)
    {
        $this->unitOfWork = $unitOfWork;
    }

    /**
     * @param  string $entityClass
     * @return IBaseRepository|IRemoteRepository
     *
     * @throws BindingResolutionException
     */
    public function getRepository(string $entityClass)
    {
        $repository = Cache::rememberForever(
            self::class."::getRepository($entityClass)",
            function () use ($entityClass) {
                return $this->findRepositoryClass($entityClass);
            }
        );

        if (in_array(IRemoteRepository::class, class_implements($repository))) {
            return app()->make($repository);
        }

        $arguments = [self::ARGUMENT_UNIT_OF_WORK => $this->unitOfWork];
        if ($repository == IGenericRepository::class) {
            $arguments = [self::ARGUMENT_UNIT_OF_WORK => $this->unitOfWork, self::ARGUMENT_MODEL_CLASS => $entityClass];
        }

        return app()->make($repository, $arguments);
    }

    /**
     * Check if a class exists
     *
     * @param  string $class
     * @return bool
     */
    protected function classExists(string $class): bool
    {
        return class_exists($class);
    }

    /**
     * @param  string $entityClass
     * @return string
     * @throws Exception
     */
    private function findRepositoryClass(string $entityClass): string
    {
        $config = Config::get('LToolkit.repository_map') ?? [];
        $repository = $config[$entityClass] ?? null;

        if ($repository) {
            if (!$this->classExists($repository)) {
                throw new Exception("Repository class $repository not found.");
            }

            return $repository;
        }

        $match = [];
        preg_match("/(\w|_|-)+$/", $entityClass, $match);
        $repositoryName = $match[0].self::REPOSITORY_POSTFIX;

        $entityNamespace = preg_replace("/\\\(\w|_|-)+$/", "", $entityClass);
        $repository = $config[$entityNamespace] ?? null;

        if ($repository) {
            $repository = "$repository\\$repositoryName";
            if ($this->classExists($repository)) {
                return $repository;
            }

            return IGenericRepository::class;
        }

        $repository = preg_replace("/\\\Models/", '\\'.self::REPOSITORIES_DIRNAME, $entityNamespace."\\$repositoryName");

        if ($this->classExists($repository)) {
            return $repository;
        }

        return IGenericRepository::class;
    }

}

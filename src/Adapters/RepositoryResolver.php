<?php
/**
 * @author Ernesto Baez 
 */

namespace LToolkit\Adapters;


use Exception;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Psr\Repository\RepositoryInterface;
use Psr\Repository\UnitOfWorkInterface;
use LToolkit\Interfaces\GenericRepositoryInterface;
use LToolkit\Interfaces\RepositoryResolverInterface;
use Illuminate\Contracts\Container\BindingResolutionException;

final class RepositoryResolver implements RepositoryResolverInterface
{
    private const REPOSITORIES_DIRNAME = "Repositories";
    private const REPOSITORY_POSTFIX = "Repository";
    private const ARGUMENT_UNIT_OF_WORK = "unitOfWork";
    private const ARGUMENT_MODEL_CLASS = "modelClass";

    private $unitOfWork;

    public function __construct(UnitOfWorkInterface $unitOfWork)
    {
        $this->unitOfWork = $unitOfWork;
    }

    /**
     * Find the corresponding repository according to the entity in $entityClass and following the naming convention
     * and mapping configuration
     *
     * @param  string $entityClass
     *
     * @return RepositoryInterface
     *
     * @throws BindingResolutionException
     */
    public function getRepository(string $entityClass): RepositoryInterface
    {
        $repository = Cache::rememberForever(
            self::class."::getRepository($entityClass)",
            function () use ($entityClass) {
                return $this->findRepositoryClass($entityClass);
            }
        );

        $arguments = [self::ARGUMENT_UNIT_OF_WORK => $this->unitOfWork];
        if ($repository == GenericRepositoryInterface::class) {
            $arguments = [self::ARGUMENT_UNIT_OF_WORK => $this->unitOfWork, self::ARGUMENT_MODEL_CLASS => $entityClass];
        }

        return app()->make($repository, $arguments);
    }

    /**
     * Check if a class exists
     *
     * @param  string $class
     *
     * @return bool
     */
    protected function classExists(string $class): bool
    {
        return class_exists($class);
    }

    /**
     * Helping method to find the corresponding repository according to the entity in $entityClass and
     * following the naming convention and mapping configuration
     *
     * @param  string $entityClass
     *
     * @return string
     *
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

            return GenericRepositoryInterface::class;
        }

        $repository = preg_replace("/\\\Models/", '\\'.self::REPOSITORIES_DIRNAME, $entityNamespace."\\$repositoryName");

        if ($this->classExists($repository)) {
            return $repository;
        }

        return GenericRepositoryInterface::class;
    }

}

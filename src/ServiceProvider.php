<?php
/**
 * @author Ernesto Baez
 */

namespace LToolkit;


use LToolkit\Helpers\Evaluator;
use LToolkit\Adapters\UnitOfWork;
use LToolkit\Helpers\MathFunctions;
use LToolkit\Adapters\CriteriaResolver;
use LToolkit\Adapters\ValidatorResolver;
use LToolkit\Adapters\RepositoryResolver;
use LToolkit\Providers\BaseServiceProvider;
use LToolkit\Interfaces\EvaluatorInterface;
use LToolkit\Repositories\GenericRepository;
use LToolkit\Interfaces\UnitOfWorkInterface;
use LToolkit\Interfaces\StoreValidatorInterface;
use Prettus\Repository\Criteria\RequestCriteria;
use LToolkit\Interfaces\UpdateValidatorInterface;
use LToolkit\Http\Validators\BasicStoreValidator;
use LToolkit\Interfaces\CriteriaResolverInterface;
use LToolkit\Http\Validators\BasicUpdateValidator;
use LToolkit\Adapters\BasePrettusRepositoryAdapter;
use LToolkit\Interfaces\GenericRepositoryInterface;
use LToolkit\Interfaces\ValidatorResolverInterface;
use LToolkit\Interfaces\RepositoryAdapterInterface;
use LToolkit\Interfaces\RepositoryResolverInterface;

class ServiceProvider extends BaseServiceProvider
{
    public $bindings = [
        RepositoryResolverInterface::class => RepositoryResolver::class,
        GenericRepositoryInterface::class => GenericRepository::class,
        RepositoryAdapterInterface::class => BasePrettusRepositoryAdapter::class,
        UnitOfWorkInterface::class => UnitOfWork::class,
        StoreValidatorInterface::class => BasicStoreValidator::class,
        UpdateValidatorInterface::class => BasicUpdateValidator::class,
        EvaluatorInterface::class => Evaluator::class,
        ValidatorResolverInterface::class => ValidatorResolver::class
    ];

    /**
     * @inheritDoc
     */
    protected function getPackageName(): string
    {
        return "LToolkit";
    }

    /**
     * @inheritDoc
     */
    protected function getPackageNamespace(): string
    {
        return 'LToolkit';
    }

    /**
     * @inheritDoc
     */
    protected function getPath($path=''): string
    {
        $basePath = __DIR__;

        $path = $basePath.($path ? DIRECTORY_SEPARATOR.$path : $path);

        return $path;
    }

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        $packageName = $this->getPackageName();

        $this->publishes(
            [
                $this->getPath("Config".DIRECTORY_SEPARATOR."$packageName.php") => config_path("$packageName.php"),
            ], 'config'
        );
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            CriteriaResolverInterface::class, function () {
            $requestCriteria = $this->app->get(RequestCriteria::class);
            return $this->app->make(CriteriaResolver::class, ["array" => [$requestCriteria]]);
        });

        $this->app->singleton("math", MathFunctions::class);

        $fileName = $this->getPackageName();

        $this->mergeConfigFrom(
            __DIR__ . DIRECTORY_SEPARATOR."Config/$fileName.php", $fileName
        );
    }
}

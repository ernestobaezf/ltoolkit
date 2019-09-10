<?php
/**
 * @author Ernesto Baez
 */

namespace LToolkit;


use LToolkit\Helpers\Evaluator;
use LToolkit\Adapters\UnitOfWork;
use LToolkit\Helpers\MathFunctions;
use LToolkit\Interfaces\IEvaluator;
use LToolkit\Interfaces\IUnitOfWork;
use LToolkit\Interfaces\IStoreValidator;
use LToolkit\Adapters\CriteriaIterator;
use LToolkit\Adapters\RepositoryResolver;
use LToolkit\Interfaces\IUpdateValidator;
use LToolkit\Adapters\ValidatorResolver;
use LToolkit\Interfaces\ICriteriaIterator;
use LToolkit\Interfaces\IRepositoryResolver;
use LToolkit\Interfaces\IGenericRepository;
use LToolkit\Interfaces\IValidatorResolver;
use LToolkit\Providers\BaseServiceProvider;
use LToolkit\Repositories\GenericRepository;
use LToolkit\Interfaces\IRepositoryAdapter;
use Prettus\Repository\Criteria\RequestCriteria;
use LToolkit\Http\Validators\BasicStoreValidator;
use LToolkit\Http\Validators\BasicUpdateValidator;
use LToolkit\Adapters\BasePrettusConnectorRepository;

class ServiceProvider extends BaseServiceProvider
{
    public $bindings = [
        IRepositoryResolver::class => RepositoryResolver::class,
        IGenericRepository::class => GenericRepository::class,
        IRepositoryAdapter::class => BasePrettusConnectorRepository::class,
        IUnitOfWork::class => UnitOfWork::class,
        IStoreValidator::class => BasicStoreValidator::class,
        IUpdateValidator::class => BasicUpdateValidator::class,
        IEvaluator::class => Evaluator::class,
        IValidatorResolver::class => ValidatorResolver::class
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
                $this->getPath("Config".DIRECTORY_SEPARATOR."$packageName.php") =>
                    config_path("$packageName.php"),
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
            ICriteriaIterator::class, function () {
            $requestCriteria = $this->app->make(RequestCriteria::class);
            return $this->app->make(CriteriaIterator::class, ["array" => [$requestCriteria]]);
        });

        $this->app->singleton("math", MathFunctions::class);

        $fileName = $this->getPackageName();

        $this->mergeConfigFrom(
            __DIR__ . DIRECTORY_SEPARATOR."Config/$fileName.php", $fileName
        );
    }
}

<?php
/**
 * @author Ernesto Baez
 */

namespace l5toolkit;


use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use l5toolkit\Helpers\Evaluator;
use l5toolkit\Connectors\UnitOfWork;
use l5toolkit\Helpers\MathFunctions;
use l5toolkit\Interfaces\IEvaluator;
use l5toolkit\Interfaces\IHttpClient;
use l5toolkit\Interfaces\IUnitOfWork;
use l5toolkit\Interfaces\IStoreValidator;
use l5toolkit\Connectors\CriteriaIterator;
use l5toolkit\Connectors\RepositoryFinder;
use l5toolkit\Interfaces\IUpdateValidator;
use l5toolkit\Connectors\ValidatorResolver;
use l5toolkit\Interfaces\ICriteriaIterator;
use l5toolkit\Interfaces\IRepositoryFinder;
use l5toolkit\Interfaces\IGenericRepository;
use l5toolkit\Interfaces\IValidatorResolver;
use l5toolkit\Providers\BaseServiceProvider;
use l5toolkit\Connectors\HttpClientConnector;
use l5toolkit\Repositories\GenericRepository;
use l5toolkit\Interfaces\IRepositoryConnector;
use Prettus\Repository\Criteria\RequestCriteria;
use l5toolkit\Http\Validators\BasicStoreValidator;
use l5toolkit\Http\Validators\BasicUpdateValidator;
use l5toolkit\Connectors\BasePrettusConnectorRepository;

class ServiceProvider extends BaseServiceProvider
{
    public $bindings = [
        IRepositoryFinder::class => RepositoryFinder::class,
        IGenericRepository::class => GenericRepository::class,
        IRepositoryConnector::class => BasePrettusConnectorRepository::class,
        IUnitOfWork::class => UnitOfWork::class,
        IHttpClient::class => HttpClientConnector::class,
        IStoreValidator::class => BasicStoreValidator::class,
        IUpdateValidator::class => BasicUpdateValidator::class,
        IEvaluator::class => Evaluator::class,
        IValidatorResolver::class => ValidatorResolver::class,
        ClientInterface::class => Client::class,
    ];

    /**
     * @inheritDoc
     */
    protected function getPackageName(): string
    {
        return "l5toolkit";
    }

    /**
     * @inheritDoc
     */
    protected function getPackageNamespace(): string
    {
        return 'l5toolkit';
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
                    config_path("packages".DIRECTORY_SEPARATOR."$packageName.php"),
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

<?php
/**
 * @author Ernesto Baez
 */

namespace LToolkit;


use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use LToolkit\Helpers\Evaluator;
use LToolkit\Connectors\UnitOfWork;
use LToolkit\Helpers\MathFunctions;
use LToolkit\Interfaces\IEvaluator;
use LToolkit\Interfaces\IHttpClient;
use LToolkit\Interfaces\IUnitOfWork;
use LToolkit\Interfaces\IStoreValidator;
use LToolkit\Connectors\CriteriaIterator;
use LToolkit\Connectors\RepositoryFinder;
use LToolkit\Interfaces\IUpdateValidator;
use LToolkit\Connectors\ValidatorResolver;
use LToolkit\Interfaces\ICriteriaIterator;
use LToolkit\Interfaces\IRepositoryFinder;
use LToolkit\Interfaces\IGenericRepository;
use LToolkit\Interfaces\IValidatorResolver;
use LToolkit\Providers\BaseServiceProvider;
use LToolkit\Connectors\HttpClientConnector;
use LToolkit\Repositories\GenericRepository;
use LToolkit\Interfaces\IRepositoryConnector;
use Prettus\Repository\Criteria\RequestCriteria;
use LToolkit\Http\Validators\BasicStoreValidator;
use LToolkit\Http\Validators\BasicUpdateValidator;
use LToolkit\Connectors\BasePrettusConnectorRepository;

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

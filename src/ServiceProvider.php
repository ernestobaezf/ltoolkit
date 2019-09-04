<?php
/**
 * @author Ernesto Baez
 */

namespace ltoolkit;


use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use ltoolkit\Helpers\Evaluator;
use ltoolkit\Connectors\UnitOfWork;
use ltoolkit\Helpers\MathFunctions;
use ltoolkit\Interfaces\IEvaluator;
use ltoolkit\Interfaces\IHttpClient;
use ltoolkit\Interfaces\IUnitOfWork;
use ltoolkit\Interfaces\IStoreValidator;
use ltoolkit\Connectors\CriteriaIterator;
use ltoolkit\Connectors\RepositoryFinder;
use ltoolkit\Interfaces\IUpdateValidator;
use ltoolkit\Connectors\ValidatorResolver;
use ltoolkit\Interfaces\ICriteriaIterator;
use ltoolkit\Interfaces\IRepositoryFinder;
use ltoolkit\Interfaces\IGenericRepository;
use ltoolkit\Interfaces\IValidatorResolver;
use ltoolkit\Providers\BaseServiceProvider;
use ltoolkit\Connectors\HttpClientConnector;
use ltoolkit\Repositories\GenericRepository;
use ltoolkit\Interfaces\IRepositoryConnector;
use Prettus\Repository\Criteria\RequestCriteria;
use ltoolkit\Http\Validators\BasicStoreValidator;
use ltoolkit\Http\Validators\BasicUpdateValidator;
use ltoolkit\Connectors\BasePrettusConnectorRepository;

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
        return "ltoolkit";
    }

    /**
     * @inheritDoc
     */
    protected function getPackageNamespace(): string
    {
        return 'ltoolkit';
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

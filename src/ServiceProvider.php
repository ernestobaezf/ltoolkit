<?php
/**
 * @author Ernesto Baez
 */

namespace ErnestoBaezF\L5CoreToolbox;


use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use Prettus\Repository\Criteria\RequestCriteria;
use ErnestoBaezF\L5CoreToolbox\Helpers\Evaluator;
use ErnestoBaezF\L5CoreToolbox\Connectors\UnitOfWork;
use ErnestoBaezF\L5CoreToolbox\Helpers\MathFunctions;
use ErnestoBaezF\L5CoreToolbox\Interfaces\IEvaluator;
use ErnestoBaezF\L5CoreToolbox\Interfaces\IHttpClient;
use ErnestoBaezF\L5CoreToolbox\Interfaces\IUnitOfWork;
use ErnestoBaezF\L5CoreToolbox\Interfaces\IStoreValidator;
use ErnestoBaezF\L5CoreToolbox\Connectors\CriteriaIterator;
use ErnestoBaezF\L5CoreToolbox\Connectors\RepositoryFinder;
use ErnestoBaezF\L5CoreToolbox\Interfaces\IUpdateValidator;
use ErnestoBaezF\L5CoreToolbox\Connectors\ValidatorResolver;
use ErnestoBaezF\L5CoreToolbox\Interfaces\ICriteriaIterator;
use ErnestoBaezF\L5CoreToolbox\Interfaces\IRepositoryFinder;
use ErnestoBaezF\L5CoreToolbox\Interfaces\IGenericRepository;
use ErnestoBaezF\L5CoreToolbox\Interfaces\IValidatorResolver;
use ErnestoBaezF\L5CoreToolbox\Providers\BaseServiceProvider;
use ErnestoBaezF\L5CoreToolbox\Connectors\HttpClientConnector;
use ErnestoBaezF\L5CoreToolbox\Repositories\GenericRepository;
use ErnestoBaezF\L5CoreToolbox\Interfaces\IRepositoryConnector;
use ErnestoBaezF\L5CoreToolbox\Http\Validators\BasicStoreValidator;
use ErnestoBaezF\L5CoreToolbox\Http\Validators\BasicUpdateValidator;
use ErnestoBaezF\L5CoreToolbox\Connectors\BasePrettusConnectorRepository;

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
        return "L5CoreToolbox";
    }

    /**
     * @inheritDoc
     */
    protected function getPackageNamespace(): string
    {
        return 'ErnestoBaezF\L5CoreToolbox';
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

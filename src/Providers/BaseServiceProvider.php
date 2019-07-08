<?php

namespace ErnestoBaezF\L5CoreToolbox\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

abstract class BaseServiceProvider extends ServiceProvider
{
    /**
     * Return the list of commands to register.
     * Override this method to return the list of command in child service providers packages
     *
     * @return array
     */
    protected function getCommands(): array
    {
        return [];
    }

    /**
     * Return the list of policies to register.
     * Override this method to return the list of policies in child service providers packages
     *
     * @return array
     */
    protected function getPolicies(): array
    {
        return [];
    }

    /**
     * Return the list of gates to register.
     * Override this method to return the list of gates in child service providers packages
     *
     * @return array | string
     */
    protected function getGates()
    {
        // examples
        // return SamplePolicy::class;
        // return ['model1_policy' => SamplePolicy::class, 'model2_policy' => SamplePolicy::class];
        return [];
    }

    /**
     * Get package name used as base and namespace for the packages resources
     *
     * @return string
     */
    protected function getPackageName(): string
    {
        $class = get_class($this);

        $class = explode('\\', $class);
        return str_replace("ServiceProvider", "", $class[count($class) - 1]);
    }

    /**
     * This namespace is applied to your controller, routes, models and Interfaces.
     *
     * @return string
     */
    protected function getPackageNamespace(): string
    {
        return 'Packages\\'.$this->getPackageName();
    }

    /**
     * This namespace is applied to get controllers namespace.
     *
     * @return string
     */
    protected function getControllersNamespace(): string
    {
        return $this->getPackageNamespace().'\Http\Controllers';
    }

    /**
     * Get package path in the filesystem to locate the different resources
     *
     * @param  string $path
     * @return string
     */
    protected function getPath($path=''): string
    {
        $basePath = "packages".DIRECTORY_SEPARATOR.$this->getPackageName();

        $glue = DIRECTORY_SEPARATOR;
        if ($path && !Str::startsWith($path, $glue) && !Str::endsWith($basePath, $glue)) {
            $path = $basePath.$glue.$path;
        } else {
            $path = $basePath.$path;
        }

        return base_path($path);
    }

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $packageName = strtolower($this->getPackageName());

        $this->mapRoutes();
        $this->loadViewsFrom($this->getPath('Resources'.DIRECTORY_SEPARATOR.'views'), $packageName);

        $this->publishes(
            [
                $this->getPath("Resources".DIRECTORY_SEPARATOR."assets") => public_path("vendor".DIRECTORY_SEPARATOR.$packageName),
            ], 'public'
        );

        $this->loadMigrationsFrom($this->getPath('Database'.DIRECTORY_SEPARATOR.'Migrations'));
        $this->loadTranslationsFrom($this->getPath('Resources'.DIRECTORY_SEPARATOR.'lang'), $packageName);

        if ($this->app->runningInConsole()) {
            $this->commands($this->getCommands());
        }

        //Register policies and gates
        $this->registerPolicies();
        $this->registerGates();
    }

    /**
     * Register Policies
     */
    protected function registerPolicies()
    {
        foreach ($this->getPolicies() as $class => $policy) {
            Gate::policy($class, $policy);
        }
    }

    /**
     * Register Gates
     */
    protected function registerGates()
    {
        // Set gate resources
        $gates = $this->getGates();

        if (!$gates) {
            return;
        }

        if (is_array($gates)) {
            foreach ($gates as $key => $value) {
                Gate::resource($key, $value);
            }
        } else {
            Gate::resource(self::getPackageName(), $gates);
        }
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapRoutes()
    {
        Route::middleware('api')
            ->prefix("api/v1/")
            ->namespace($this->getControllersNamespace())
            ->group($this->getPath('Routes'.DIRECTORY_SEPARATOR.'api.php'));
    }
}

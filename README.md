# L5 Core Toolbox

The main goal for this package is to save time by implementing a lot of common action when creating (mainly) and API 
oriented application following different design patterns. Here you can find the basics for:

* Service providers 

* Repositories

* Api controllers

* Validations and more

With this package is possible to structure a project in pseudo-packages. This means you can divide the logic of your 
project in a directory called packages and separate the business of your project in logic units. In this case the 
expected directory structure is:

>
    project
     |+ app
     |- ...
     |- packages
         |- Package1
             |+ Models
             |+ Http
             |+ Repositories
             |+ Resources
             |- Package1ServiceProvider.php
         |- Package2
             |+ Models
             |+ Http
             |+ Repositories
             |+ Resources
             |- Package2ServiceProvider.php
             
And then configure the namespace for this directory in the composer.json file as below:

    "autoload": {
            "psr-4": {
                ...
                "Packages\\": "packages"
            },

Of course, is not mandatory to structure the project as explained. You can do use it as you want.

## Installation

Install via composer:

    composer require ernestobaezf/l5-core-toolbox
    
Then in your `config/app.php` add the following provider to the list:
    
    'providers' => [
         ...
         ErnestoBaezF\L5CoreToolbox\ServiceProvider::class,
    ]

## Service provider

The [service provider](https://laravel.com/docs/5.8/providers) is the entry point to connect packages with Laravel main
 project. Since by using this package you can divide your project in packages, then there are many common operations that 
 can be repeated, so we have created *BaseServiceProvider*. This way each time is required to create a new package is 
 going to be as easy as extends this class in the new package. But there are some things you would like to customize 
 beyond the default implementation and that is what are going to explain next.

## Repository pattern
The repository pattern is to access the model layer (whether local or remote).

This package provide a finder that allows you to get the corresponding Repository for a given model by following conventions 
we are going to define in this section. In the implementation of this pattern 
there are different parts involved:

### UnitOfWork
Because, in theory, the repository is just a collection of entities then is necessary to have a unit to actually modify the
 database. Why is this? Imagine the following cases:
 
* Case 1: you need to alter several entities
 >
    $repository->update($transaction1->toArray(), $transaction1->getId())
    $repository->update($transaction2->toArray(), $transaction2->getId())
    
but, what if you want to avoid send a request to the database each time, for performance reasons, and send only once instead?
 
* Case 2: you need an atomic operation
>
    $repository->create($user->toArray())
    $repository->create($userTransaction->toArray())
    
in this case the right thing is to have an atomic operation, which means to create the transaction related to the user
only if the user was created (and didn't fail in the process).

In this two cases we need what is called the *Unit of Work*, which actually controls the operations to persist the data. 
Normally this part of the logic is transparent since no additional configuration is required to use it because is 
configured by default to auto-commit the changes and persist the data.

But to tackle the previous cases we need to bind a different instance of the unit of work, for instance:

    $this->app->when(SampleAPIController::class)
                ->needs(IUnitOfWork::class)
                ->give(function() {
                    return $this->app->make(UnitOfWork::class, ["autoCommit" => false]);
                });
                
So the previous cases will be like:

* Case 1: you need to alter several entities
 >
    try {
        $repository->update($transaction1->toArray(), $transaction1->getId())
        $repository->update($transaction2->toArray(), $transaction2->getId())
        
        $unitOfWork->commit();
    } catch(Exception) {
        $unitOfWork->rollback();
    }
        
* Case 2: you need an atomic operation
>
    try {
        $repository->create($user->toArray())
        $repository->create($userTransaction->toArray())
        
        $unitOfWork->commit();
    } catch(Exception) {
        $unitOfWork->rollback();
    }

Notice two things from here:

1. We advise to never user *DB::beginTransaction*, *DB::commit* nor *DB::rollback* directly in code. 
Always use the *Unit of Work* instead.

2. Manually commit or rollback by using the *Unit of Work* only if you are in presence of one of the cases exposed 
before or a variation that requires to control the data persistence.

### Repository structure

The *Unit of Work* as the entry point to use the repository has a method `UnitOfWork::getRepository($entityClass)`. This is 
based in a repository finder that discovers repositories related to an entity following the rules below:

1. Returns the repository mapped to the entity in the `config.packages.core.repository_map`. If there is a mapping declared 
and the associated repository does not exist then an exception is thrown.

2. If there is not mapping like in the first step then it returns the associated repository (following the naming convention)
 placed in the repository directory associated to the entity namespace. If an associated repository is not found then a
 base GenericRepository is returned.
 
3. If there is no mapping specified then the repository returned is the result of following the step 2 with the default 
mapping (Packages\<package_name>\Models => Packages\<package_name>\Repositories)

**Naming convention:** The repositories are places always inside the directory *Repositories* and the name starts with the 
                   corresponding model name and ends by *Repository*. For instance: `SampleRepository` for the name of 
                   the repository corresponding to `Package\<package_name>\Models\Sample`.

* BaseRepository: In case you need a more specialized repository then you should always extend the BaseRepository class 
and add the specific methods. 

* GenericRepository: This is the default repository in case there is no specific repository implementation for a given model.

* RemoteRepository: This is the repository used to handle data from remote services. Instead of calling any http client 
(like guzzle) directly and handle the response differently each time, this structure tries to unify the access to data, 
no matter where it comes from.

## API controllers

There is a BaseAPIResourceController which implements IAPIResourceController interface that defines the minimum and commons set 
 of methods (index, show, store, update, destroy) to handle CRUD. This BaseAPIResourceController 
 has injected a *Unit of Work* that allows to get the repositories according the requiring entity class. In this
 controller, the method index has an ICriteriaIterator as parameter, which is linked to CriteriaIterator that has as a default
 param, a RequestCriteria, to filter the results with a criteria [defined in l5-repository package](https://github.com/andersao/l5-repository#using-the-requestcriteria).
 This association can be adapted to different needs in the bindings (see **Service Provider** section).
 
To access remote data, the idea is to follow the same structure and pattern as the BaseAPIResourceController 
by using the RemoteRepository to access remote data. So is needed to:

1. Declare a local model for the remote model 

2. Create the repository for that model extending the RemoteRepository base class 

3. Define the methods corresponding those exposed by the micro-service in the repository created in step 2.

By doing this we are following the same logic pipeline and a lot of code can be reused. By saying this we remark that is not
necessary and we **strongly discourage the use of Guzzle or any other http client directly in code**; use the repository instead.

## Validation

Every action from Controllers that extend BaseAPIController search for the matching Validator by following the conventions below:

1. Inside the directory **Http**, together with directories **Controllers** and **Middleware**, create the directory **Validators**

2. Inside `Http/Validators` create a directory with the name of the controller without the postfix "Controller"

3. Inside create the class `<action_name>Validator` (for instance `StoreValidator`) and define the rules for the given action.

If you want to declare the validators yourself and not follow the convention, you can do it as below:

    class SampleController extends BaseAPIController
    {
        public function __construct(IUnitOfWork $unitOfWork)
        {
            parent::__construct(
                $unitOfWork, app(
                    IValidatorResolver::class, [
                        "className" => static::class,
                        "validations" => [
                            "action" => new ActionValidator()
                        ]
                    ]
                )
            );
        }
        
        ...
    }

## Logs

The activity on every action from Controllers that extend BaseAPIController is logged. This is done by using a middleware. 
Also is recommended to use CustomLogFormatter to get an easy to parse log records (see an example configuration below).

>
    # config/logging.php
    
    'daily' => [
                'driver' => 'daily',
                'path' => storage_path('logs/laravel.log'),
                'formatter' => \ErnestoBaezF\L5CoreToolbox\Formatters\CustomLogFormatter::class,
                'formatter_with' => [
                    "format" => "[%datetime%] %channel%.%level_name% %context% %extra% %message%\n",
                    "dateFormat" => null,
                    "allowInlineLineBreaks" => true,
                    "ignoreEmptyContextAndExtra" => false
                ],
                'level' => 'debug',
                'days' => 14,
            ],

## Localization

Is possible to get the api messages in english and spanish by using the route middleware `Localization` and sending
to the backend the current language in the headers `'X-localization'`. If no language is specified then the one from user 
preference (if specified) or english is used as default.

To set the middleware, in the file `app\Http\Kernel.php` set:

    protected $routeMiddleware = [
        ...
        'l18n' => \ErnestoBaezF\L5CoreToolbox\Http\Middleware\Localization::class,
    ];

## Use case

To create a simple API is as easy as:

1. Create the model in *Model* directory

2. Create a Controller, extend the class *BaseAPIResourceController* and specify the model as follow:


    class SampleAPIController extends BaseAPIResourceController
    {
        protected function getEntity(): string
        {
            return SampleModel::class;
        }
    }


3. If you want to add validations. Create the validations as explained in section **Validations**

4. Declare your routes for the api:

>
    Route::prefix("api/v1/")
           ->namespace($this->getControllersNamespace())
           ->group([middleware => 'api'], function () {
                   Route::resource('sample', 'SampleAPIController')
               });

And that's it. You have and api with a fully functional CRUD.

With th implementation above is possible to:

1. Get a paginated list of elements only by passing the parameter 'limit'. If this parameter is not sent then the default 
result is the complete set of elements.

>
    GET http://{{domain}}/api/v1/sample?limit=20&page=1

2. Filter elements by fields

>
    GET http://{{domain}}/api/v1/sample?search=element1&searchFields=name:like


3. Get relations and its fields for the list of elements.

>
    GET http://{{domain}}/api/v1/users?search=element1&searchFields=name:like&with=roles:id
    
4. Get information from entities related the one being requested

>
    GET http://{{domain}}/api/v1/sample/<id>?with=<relation1>:<fields,...>;<relation2>:<fields,...>
    
    Example
    GET http://{{domain}}/api/v1/users/20?with=roles:id,name 

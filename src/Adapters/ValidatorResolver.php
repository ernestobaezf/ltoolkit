<?php
/**
 * @author Ernesto Baez 
 */

namespace LToolkit\Adapters;


use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;
use LToolkit\Interfaces\ValidatorInterface;
use LToolkit\Interfaces\StoreValidatorInterface;
use LToolkit\Interfaces\UpdateValidatorInterface;
use LToolkit\Interfaces\ValidatorResolverInterface;

/**
 * Class to find the validators given the class name and the method being executed
 *
 * Class ValidatorResolver
 * @package LToolkit\Adapters
 */
final class ValidatorResolver implements ValidatorResolverInterface
{
    const VALIDATORS_DIRNAME = 'Validators';

    const VALIDATOR_POSTFIX = 'Validator';

    protected $validations;

    private $className = '';

    /**
     * @inheritdoc
     */
    public function __construct(string $className='', array $validations = [])
    {
        if ($className && class_exists($className)) {
            $this->className = $className;
        } else {
            $currentRoute = Route::current();
            $path = $currentRoute ? $currentRoute->getActionName() : "";
            $this->className = explode("@", $path ?: "@")[0];
        }

        $this->validations = $validations ?? [];
    }

    /**
     * @inheritdoc
     */
    public function add(string $methodName, ValidatorInterface $validator): ValidatorResolverInterface
    {
        $this->validations[$methodName] = $validator;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function get(string $methodName): ?ValidatorInterface
    {
        $validator = $this->validations[$methodName] ?? null;

        if (!$validator && $this->className) {
            $validator = Cache::rememberForever(
                static::class."::resolve($this->className, $methodName)",
                function () use ($methodName) {
                    return $this->resolve($this->className, $methodName);
                }
            );

            $this->validations[$methodName] = $validator ? app($validator) : null;
        }

        $result = $this->validations[$methodName] ?? null;
        if (!$result) {
            switch ($methodName) {
                case "store":
                    $result = app(StoreValidatorInterface::class);
                    break;
                case "update":
                    $result = app(UpdateValidatorInterface::class);
                    break;
            }
        }

        return $result;
    }

    /**
     * Find Validator according to name convention
     *
     * @param string $className
     * @param string $methodName
     *
     * @return string|null
     */
    private function resolve(string $className, string $methodName): ?string
    {
        $validatorName = Str::studly($methodName).self::VALIDATOR_POSTFIX;

        $match = [];
        preg_match("/(\w|_|-)+$/", $className, $match);
        $entityName = preg_replace("/(APIController|Controller)$/", "", $match[0]);

        $entityNamespace = preg_replace("/\\\(\w|_|-)+$/", "", $className);

        $validator = preg_replace(
            "/\\\Http\\\Controllers/",
            '\\Http\\'.self::VALIDATORS_DIRNAME,
            $entityNamespace."\\$entityName\\$validatorName"
        );

        if ($this->classExists($validator)) {
            return $validator;
        }

        return null;
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
}

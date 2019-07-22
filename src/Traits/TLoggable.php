<?php
/**
 * @author Ernesto Baez
 */

namespace ErnestoBaezF\L5CoreToolbox\Traits;

use Exception;
use ReflectionMethod;
use ReflectionException;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

/**
 * Trait TLoggable
 *
 * @package Packages\Sample\Connectors
 *
 * Log a method execution by call it on one of the following ways:
 *  echo $object->logPublicTestMethodParam(34, "data");
 *  echo $object->log("publicTestMethodParam", 34, "data");
 */
trait TLoggable
{
    protected function logLevel()
    {
        return env('LOGGABLE_LOG_LEVEL', 'debug');
    }

    /**
     * Allow to automatically log you function by calling it the prefix log
     * (Example: $object->logFunctionName(...$params)
     *
     * @param string $method
     * @param mixed $arguments
     *
     * @return mixed
     *
     * @throws Exception
     */
    public function __call($method, $arguments)
    {
        $log = Str::startsWith($method, "log");

        if ($log) {
            $method = Str::substr($method, 3);
            $method = Str::lower(Str::substr($method, 0, 1)).Str::substr($method, 1);

            return call_user_func_array([$this, "log"], array_merge([$method], $arguments));
        }

        throw new Exception( "Method '$method' not found in class ".static::class);
    }


    /**
     * Allow to automatically log you function by calling it the prefix log
     * (Example: $object->log("functionName", ...$params)
     *
     * @param string $method
     * @param mixed ...$arguments
     *
     * @return mixed
     *
     * @throws ReflectionException
     * @throws Exception
     */
    public function log(string $method, ...$arguments)
    {
        if (method_exists(static::class, $method)) {
            $reflection = new ReflectionMethod($this, $method);

            if ($reflection->isPublic()) {
                Log::log($this->logLevel(), "Start execution",
                    [
                        "class" => static::class,
                        "method" => $method,
                        "payload" => json_encode($arguments)
                    ]
                );

                $response = call_user_func_array([$this, $method], $arguments);

                Log::log($this->logLevel(), "End execution",
                    [
                        "class" => static::class,
                        "method" => $method,
                        "payload" => json_encode($arguments),
                        "response" => json_encode($response)
                    ]
                );

                return $response;
            }
        }

        throw new Exception( "Method '$method' not found in class ".static::class);
    }
}

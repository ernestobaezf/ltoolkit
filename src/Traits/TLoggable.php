<?php
/**
 * User: Ernesto Baez <ernesto.baez@cdev.global>
 * Date: 19/07/19 2:54 PM
 */

namespace Packages\Sample\Connectors;

use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use ReflectionException;
use ReflectionMethod;

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
                Log::info("Start execution",
                    [
                        "class" => static::class,
                        "method" => $method,
                        "payload" => json_encode($arguments)
                    ]
                );

                $response = call_user_func_array([$this, $method], $arguments);

                Log::info("End execution",
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

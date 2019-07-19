<?php
/**
 * @author Ernesto Baez 
 */

namespace ErnestoBaezF\L5CoreToolbox\Traits;


use Closure;
use Exception;
use Illuminate\Support\Facades\Log;

trait TLogAction
{
    /**
     * Determines whether the logs should be stored or not for the given function
     *
     * @param  string $functionName
     * @return bool
     */
    protected function logAction(string $functionName): bool
    {
        return true;
    }

    /**
     * @param  Closure $closure
     * @param  string   $functionName
     * @param  mixed    $payload
     * @return mixed
     * @throws Exception
     */
    protected final function evaluate(Closure $closure, string $functionName, $payload="")
    {
        try {
            $result = $closure();

            if ($this->logAction($functionName)) {
                Log::info(
                    "Successful operation",
                    [
                        "type" => "action",
                        "class" => static::class,
                        "method" => $functionName,
                        "payload" => $payload,
                        "response" => $result
                    ]
                );
            }

            return $result;
        } catch (Exception $exception) {
            Log::error($exception->getMessage());

            throw $exception;
        }
    }
}

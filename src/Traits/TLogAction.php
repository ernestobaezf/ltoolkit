<?php
/**
 * @author Ernesto Baez 
 */

namespace ltoolkit\Traits;


use Closure;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;
use ltoolkit\Log\Formatters\CustomLogFormatter;

trait TLogAction
{
    /**
     * Determines whether the logs should be stored or not for the given function
     *
     * @param  string $functionName
     *
     * @return bool
     */
    protected function logAction(string $functionName): bool
    {
        return Config::get('ltoolkit.log_actions', false);
    }

    /**
     * @param Closure $closure
     * @param string  $functionName
     * @param mixed   $payload
     *
     * @return mixed
     *
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
                        "mode" => CustomLogFormatter::MODE_FULL,
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
            if ($this->logAction($functionName)) {
                Log::error($exception->getMessage(),
                    [
                        "mode" => CustomLogFormatter::MODE_FULL,
                        "type" => "action",
                        "class" => static::class,
                        "method" => $functionName,
                        "payload" => $payload,
                        "response" => "unknown due to an error"
                    ]
                );
            }

            throw $exception;
        }
    }
}

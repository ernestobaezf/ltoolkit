<?php
/**
 * @author Ernesto Baez
 */

namespace LToolkit\Helpers;


use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use LToolkit\Interfaces\EvaluatorInterface;
use Illuminate\Support\Facades\Config;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use LToolkit\Log\Formatters\CustomLogFormatter;
use Illuminate\Support\Facades\Response as ResponseFacade;

final class Evaluator implements EvaluatorInterface
{
    private $preCondition;

    private $method;

    private $postCondition;

    private $logInfo;

    public function __construct(bool $logInfo = true)
    {
        $this->logInfo = $logInfo;
    }

    /**
     * Condition to evaluate before the method execution
     *
     * @param  Closure    $closure
     * @return EvaluatorInterface
     */
    public function preCondition(Closure $closure): EvaluatorInterface
    {
        $this->preCondition = $closure;

        return $this;
    }

    /**
     * Return pre condition assigned in set function
     *
     * @return null|Closure
     */
    protected final function getPreCondition()
    {
        return $this->preCondition;
    }

    /**
     * Logic to run
     *
     * @param  Closure $closure
     * @return EvaluatorInterface
     */
    public function mainMethod(Closure $closure): EvaluatorInterface
    {
        $this->method = function () use ($closure) {
            if ($this->logInfo) {
                Log::info("Start execution", ["mode" => CustomLogFormatter::MODE_FULL]);
            }

            $result = $closure();

            if ($this->logInfo) {
                $level = 'info';

                if ($result && $result instanceof Response) {
                    $message = $result->getContent();

                    $logTextLength = Config::get("LToolkit.log_text_length", 3000);
                    if ($message && $logTextLength > 0 && strlen($message) > $logTextLength) {
                        $content = json_decode($message);
                        $_message = $content->message ?? "";

                        $message = $content->data ?? $message;

                        if (!is_string($message)) {
                            $message = json_encode($message);
                        }
                        $message = substr($message, 0, $logTextLength)."\"truncated text...\",\"message\":\"$_message\"}";
                    }

                    if ($result->getStatusCode() >= Response::HTTP_BAD_REQUEST) {
                        $level = 'warning';
                    }

                    if ($result->getStatusCode() >= Response::HTTP_INTERNAL_SERVER_ERROR) {
                        $level = 'error';
                    }
                } else {
                    if (!is_string($result)) {
                        $message = json_encode($result);
                    } else {
                        $message = $result;
                    }
                }

                Log::log($level, "End execution", ["response" => $message]);
            }

            return $result;
        };

        return $this;
    }

    /**
     * Return post condition assigned in set function
     */
    protected final function getMethod(): Closure
    {
        return $this->method;
    }

    /**
     * Condition to evaluate after the method execution
     *
     * @param  Closure $closure
     * @return EvaluatorInterface
     */
    public function postCondition(Closure $closure): EvaluatorInterface
    {
        $this->postCondition = $closure;

        return $this;
    }

    /**
     * Return post condition assigned in set function
     *
     * @return null|Closure
     */
    protected final function getPostCondition()
    {
        return $this->postCondition;
    }

    /**
     * Run closures defined in preCondition, postCondition and method
     *
     * @return JsonResponse
     *
     * @throws ValidationException
     */
    public function evaluate()
    {
        global $logId;
        $time = explode(' ', microtime());
        $logId = sprintf('%d-%06d', $time[1], (float)$time[0] * 1000000);

        $preCondition = $this->getPreCondition();
        if ($preCondition) {
            try {
                $preCondition();
            } catch (ValidationException $exception) {
                $message = $exception->errors();

                Log::error("Pre-condition failed", ["response" => $message]);

                // Handle this exception in the laravel exception Handler
                throw $exception;
            }
        }

        $result =  ($this->getMethod())();

        $postCondition = $this->getPostCondition();
        if ($postCondition) {
            try {
                $postCondition();
            } catch (ValidationException $exception) {
                $message = $exception->errors();

                Log::error("Post-condition failed", ["response" => $message]);

                // Handle this exception in the laravel exception Handler
                throw $exception;
            }
        }

        return $result;
    }
}

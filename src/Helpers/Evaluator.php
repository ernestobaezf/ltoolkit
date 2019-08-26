<?php
/**
 * @author Ernesto Baez
 */

namespace l5toolkit\Helpers;


use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use l5toolkit\Interfaces\IEvaluator;
use Illuminate\Support\Facades\Config;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Response as ResponseFacade;

final class Evaluator implements IEvaluator
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
     * @return IEvaluator
     */
    public function preCondition(Closure $closure): IEvaluator
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
     * @return IEvaluator
     */
    public function mainMethod(Closure $closure): IEvaluator
    {
        $this->method = function () use ($closure) {
            if ($this->logInfo) {
                Log::info("Start execution");
            }

            $result = $closure();

            if ($this->logInfo) {
                $level = 'info';

                if ($result && $result instanceof Response) {
                    $message = $result->getContent();

                    $logTextLength = Config::get("l5toolkit.log_text_length", 3000);
                    if ($message && $logTextLength > 0 && strlen($message) > $logTextLength) {
                        $content = json_decode($message);
                        $_message = $content->message ?? "";

                        $message = $content->data ?? $message;
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
     * @return IEvaluator
     */
    public function postCondition(Closure $closure): IEvaluator
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

                return ResponseFacade::json(
                    [
                        'message' => trans('validation.error.generic_message'),
                        'error' => $message,
                    ], 400
                );
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

                return ResponseFacade::json(
                    [
                        'message' => trans('validation.error.generic_message'),
                        'error' => $message,
                    ], 400
                );
            }
        }

        return $result;
    }
}

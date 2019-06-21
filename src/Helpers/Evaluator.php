<?php
/**
 * @author Ernesto Baez 
 */

namespace ErnestoBaezF\L5CoreToolbox\Helpers;


use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Illuminate\Validation\ValidationException;
use ErnestoBaezF\L5CoreToolbox\Interfaces\IEvaluator;

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
                $message = "";
                $content = $result ? $result->getContent() : $result;

                if ($content && strlen($content) > 400) {
                    $content = json_decode($content);
                    $message = $content->message ?? "";
                    $message = "{\"data\":\"truncated message...\",\"message\":\"$message\"}";
                }

                Log::info("End execution", ["response" => $message]);
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
        $logId = time();

        $preCondition = $this->getPreCondition();
        if ($preCondition) {
            try {
                $preCondition();
            } catch (ValidationException $exception) {
                $message = $exception->errors();

                Log::error("Pre-condition failed", ["response" => $message]);

                return Response::json(
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

                return Response::json(
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
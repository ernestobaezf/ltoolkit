<?php
/**
 * @author Ernesto Baez 
 */


use l5toolkit\Interfaces\IEvaluator;

if (! function_exists('evaluator')) {
    function evaluator(bool $saveLogs = true): IEvaluator
    {
        return app(IEvaluator::class, ['logInfo' => $saveLogs]);
    }
}

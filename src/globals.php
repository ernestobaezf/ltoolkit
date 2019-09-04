<?php
/**
 * @author Ernesto Baez 
 */


use ltoolkit\Interfaces\IEvaluator;

if (! function_exists('evaluator')) {
    function evaluator(bool $saveLogs = true): IEvaluator
    {
        return app(IEvaluator::class, ['logInfo' => $saveLogs]);
    }
}

<?php
/**
 * @author Ernesto Baez 
 */


use ErnestoBaezF\L5CoreToolbox\Interfaces\IEvaluator;

if (! function_exists('evaluator')) {
    function evaluator(bool $saveLogs = true): IEvaluator
    {
        return app(IEvaluator::class, ['logInfo' => $saveLogs]);
    }
}

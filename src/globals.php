<?php
/**
 * @author Ernesto Baez 
 */


use LToolkit\Interfaces\EvaluatorInterface;

if (! function_exists('evaluator')) {
    function evaluator(bool $saveLogs = true): EvaluatorInterface
    {
        return app(EvaluatorInterface::class, ['logInfo' => $saveLogs]);
    }
}

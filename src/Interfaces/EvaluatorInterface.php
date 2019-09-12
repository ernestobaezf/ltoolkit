<?php
/**
 * @author Ernesto Baez 
 */

namespace LToolkit\Interfaces;


use Closure;

interface EvaluatorInterface
{
    function preCondition(Closure $closure): EvaluatorInterface;

    function mainMethod(Closure $closure): EvaluatorInterface;

    function postCondition(Closure $closure): EvaluatorInterface;

    function evaluate();
}

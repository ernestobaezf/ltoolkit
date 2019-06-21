<?php
/**
 * @author Ernesto Baez 
 */

namespace ErnestoBaezF\L5CoreToolbox\Interfaces;


use Closure;

interface IEvaluator
{
    function preCondition(Closure $closure): IEvaluator;

    function mainMethod(Closure $closure): IEvaluator;

    function postCondition(Closure $closure): IEvaluator;

    function evaluate();
}
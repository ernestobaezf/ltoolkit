<?php
/**
 * @author Ernesto Baez 
 */

namespace ErnestoBaezF\L5CoreToolbox\Interfaces;


interface IValidatorResolver
{
    /**
     * IValidatorCollection constructor.
     *
     * @param string $className
     * @param array  $validators
     */
    function __construct(string $className, array $validators);

    /**
     * @param  string     $methodName
     * @param  IValidator $validator
     * @return IValidatorResolver
     */
    function add(string $methodName, IValidator $validator): IValidatorResolver;

    /**
     * @param  string $methodName
     * @return null|IValidator
     */
    function get(string $methodName): ?IValidator;
}

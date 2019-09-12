<?php
/**
 * @author Ernesto Baez 
 */

namespace LToolkit\Interfaces;


interface ValidatorResolverInterface
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
     * @param  ValidatorInterface $validator
     * @return ValidatorResolverInterface
     */
    function add(string $methodName, ValidatorInterface $validator): ValidatorResolverInterface;

    /**
     * @param  string $methodName
     * @return null|ValidatorInterface
     */
    function get(string $methodName): ?ValidatorInterface;
}

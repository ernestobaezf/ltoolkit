<?php
/**
 * @author Ernesto Baez 
 * @author Ernesto Baez  26/04/19 9:37 AM
 */

namespace LToolkit\Http\Validators;


use LToolkit\Interfaces\UpdateValidatorInterface;

/**
 * Class BasicUpdateValidator
 */
class BasicUpdateValidator extends SimpleValidator implements UpdateValidatorInterface
{
    protected $rules = [
        'email' => 'sometimes|required|email'
    ];
}

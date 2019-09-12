<?php
/**
 * @author Ernesto Baez 
 * @author Ernesto Baez  26/04/19 9:37 AM
 */

namespace LToolkit\Http\Validators;


use LToolkit\Interfaces\StoreValidatorInterface;

/**
 * Class BasicStoreValidator
 */
class BasicStoreValidator extends SimpleValidator implements StoreValidatorInterface
{
    protected $rules = [
        'email' => 'sometimes|required|email'
    ];
}

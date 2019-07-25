<?php
/**
 * @author Ernesto Baez 
 * @author Ernesto Baez  26/04/19 9:37 AM
 */

namespace l5toolkit\Http\Validators;


use l5toolkit\Interfaces\IStoreValidator;

/**
 * Class BasicStoreValidator
 */
class BasicStoreValidator extends SimpleValidator implements IStoreValidator
{
    protected $rules = [
        'email' => 'sometimes|required|email'
    ];
}

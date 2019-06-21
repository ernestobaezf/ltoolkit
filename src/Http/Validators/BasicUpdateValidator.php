<?php
/**
 * @author Ernesto Baez 
 * @author Ernesto Baez  26/04/19 9:37 AM
 */

namespace ErnestoBaezF\L5CoreToolbox\Http\Validators;


use ErnestoBaezF\L5CoreToolbox\Interfaces\IUpdateValidator;

/**
 * Class BasicUpdateValidator
 */
class BasicUpdateValidator extends SimpleValidator implements IUpdateValidator
{
    protected $rules = [
        'email' => 'sometimes|required|email'
    ];
}

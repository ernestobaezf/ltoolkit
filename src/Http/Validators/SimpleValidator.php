<?php
/**
 * @author Ernesto Baez 
 * @author Ernesto Baez  26/04/19 9:37 AM
 */

namespace ErnestoBaezF\L5CoreToolbox\Http\Validators;


use Illuminate\Validation\Validator;
use ErnestoBaezF\L5CoreToolbox\Interfaces\IValidator;

/**
 * Class BaseValidator
 */
class SimpleValidator extends Validator implements IValidator
{
    public function __construct()
    {
        parent::__construct(app('translator'), request()->all(), $this->rules, []);
    }
}

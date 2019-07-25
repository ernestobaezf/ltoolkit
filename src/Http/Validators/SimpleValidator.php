<?php
/**
 * @author Ernesto Baez 
 * @author Ernesto Baez  26/04/19 9:37 AM
 */

namespace l5toolkit\Http\Validators;


use Illuminate\Validation\Validator;
use l5toolkit\Interfaces\IValidator;

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

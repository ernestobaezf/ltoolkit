<?php
/**
 * @author Ernesto Baez 
 * @author Ernesto Baez  26/04/19 9:37 AM
 */

namespace LToolkit\Http\Validators;


use Illuminate\Validation\Validator;
use LToolkit\Interfaces\ValidatorInterface;

/**
 * Class BaseValidator
 */
class SimpleValidator extends Validator implements ValidatorInterface
{
    public function __construct()
    {
        parent::__construct(app('translator'), request()->all(), $this->rules, []);
    }
}

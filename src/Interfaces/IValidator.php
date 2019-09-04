<?php
/**
 * @author Ernesto Baez 
 */

namespace ltoolkit\Interfaces;


use Illuminate\Contracts\Validation\Validator;

interface IValidator extends Validator
{
    public function setData(array $data);
}

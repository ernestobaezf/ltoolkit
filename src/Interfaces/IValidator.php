<?php
/**
 * @author Ernesto Baez 
 */

namespace ErnestoBaezF\L5CoreToolbox\Interfaces;


use Illuminate\Contracts\Validation\Validator;

interface IValidator extends Validator
{
    public function setData(array $data);
}
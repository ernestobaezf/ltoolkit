<?php
/**
 * @author Ernesto Baez 
 */

namespace l5toolkit\Interfaces;


use Illuminate\Contracts\Validation\Validator;

interface IValidator extends Validator
{
    public function setData(array $data);
}

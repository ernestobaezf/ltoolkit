<?php
/**
 * @author Ernesto Baez 
 */

namespace LToolkit\Interfaces;


use Illuminate\Contracts\Validation\Validator;

interface ValidatorInterface extends Validator
{
    public function setData(array $data);
}

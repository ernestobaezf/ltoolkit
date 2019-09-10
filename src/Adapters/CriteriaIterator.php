<?php
/**
 * @author Ernesto Baez 
 */

namespace LToolkit\Adapters;


use ArrayIterator;
use LToolkit\Interfaces\ICriteriaIterator;

class CriteriaIterator extends ArrayIterator implements ICriteriaIterator
{
    public function __construct(array $array = array())
    {
        parent::__construct($array, 0);
    }
}

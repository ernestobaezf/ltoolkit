<?php
/**
 * @author Ernesto Baez 
 */

namespace LToolkit\Adapters;


use ArrayIterator;
use LToolkit\Interfaces\CriteriaIteratorInterface;

class CriteriaIterator extends ArrayIterator implements CriteriaIteratorInterface
{
    public function __construct(array $array = array())
    {
        parent::__construct($array, 0);
    }
}

<?php
/**
 * @author Ernesto Baez 
 */

namespace LToolkit\Adapters;


use ArrayIterator;
use LToolkit\Interfaces\CriteriaResolverInterface;

class CriteriaResolver extends ArrayIterator implements CriteriaResolverInterface
{
    public function __construct(array $array = array())
    {
        parent::__construct($array, 0);
    }
}

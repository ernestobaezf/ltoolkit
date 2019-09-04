<?php
/**
 * @author Ernesto Baez 
 */

namespace LToolkit\Connectors;


use LToolkit\Interfaces\ICriteriaIterator;

class CriteriaIterator extends \ArrayIterator implements ICriteriaIterator
{
    public function __construct(array $array = array())
    {
        parent::__construct($array, 0);
    }
}

<?php
/**
 * @author Ernesto Baez 
 */

namespace ltoolkit\Connectors;


use ltoolkit\Interfaces\ICriteriaIterator;

class CriteriaIterator extends \ArrayIterator implements ICriteriaIterator
{
    public function __construct(array $array = array())
    {
        parent::__construct($array, 0);
    }
}

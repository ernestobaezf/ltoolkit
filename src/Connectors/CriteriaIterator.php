<?php
/**
 * @author Ernesto Baez 
 */

namespace l5toolkit\Connectors;


use l5toolkit\Interfaces\ICriteriaIterator;

class CriteriaIterator extends \ArrayIterator implements ICriteriaIterator
{
    public function __construct(array $array = array())
    {
        parent::__construct($array, 0);
    }
}

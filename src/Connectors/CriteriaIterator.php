<?php
/**
 * @author Ernesto Baez 
 */

namespace ErnestoBaezF\L5CoreToolbox\Connectors;


use ErnestoBaezF\L5CoreToolbox\Interfaces\ICriteriaIterator;

class CriteriaIterator extends \ArrayIterator implements ICriteriaIterator
{
    public function __construct(array $array = array())
    {
        parent::__construct($array, 0);
    }
}

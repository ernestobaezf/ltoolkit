<?php
/**
 * @author Ernesto Baez 
 */

namespace ErnestoBaezF\L5CoreToolbox\Test\Environment;


class DynamicClass
{
    private $methods = [];

    public function __construct($methods)
    {
        $this->methods = $methods;
    }

    public function __call($name, $arguments)
    {
        return $this->methods[$name]($arguments);
    }

}
<?php
/**
 * @author Ernesto Baez 
 */

namespace LToolkit\Test\Environment;


class StringSerializableClass
{
    public function __toString()
    {
        return self::class;
    }
}

<?php
/**
 * @author Ernesto Baez 
 */

namespace ltoolkit\Test\Environment;


class StringSerializableClass
{
    public function __toString()
    {
        return self::class;
    }
}

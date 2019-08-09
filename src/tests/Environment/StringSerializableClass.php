<?php
/**
 * @author Ernesto Baez 
 */

namespace l5toolkit\Test\Environment;


class StringSerializableClass
{
    public function __toString()
    {
        return self::class;
    }
}

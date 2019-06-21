<?php


namespace ErnestoBaezF\L5CoreToolbox\Test\Environment\Serializer;


use ErnestoBaezF\L5CoreToolbox\Interfaces\ISerializer;

class MockSerializer implements ISerializer
{

    /**
     * Apply the transformation.
     *
     * @param mixed $data
     * @return mixed
     */
    function serialize($data)
    {
        return $data;
    }
}
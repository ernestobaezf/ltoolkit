<?php


namespace ltoolkit\Test\Environment\Serializer;


use ltoolkit\Interfaces\ISerializer;

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

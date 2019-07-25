<?php


namespace l5toolkit\Test\Environment\Serializer;


use l5toolkit\Interfaces\ISerializer;

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

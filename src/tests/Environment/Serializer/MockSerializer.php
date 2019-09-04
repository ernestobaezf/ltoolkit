<?php


namespace LToolkit\Test\Environment\Serializer;


use LToolkit\Interfaces\ISerializer;

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

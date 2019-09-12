<?php


namespace LToolkit\Test\Environment\Serializer;


use LToolkit\Interfaces\SerializerInterface;

class MockSerializer implements SerializerInterface
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

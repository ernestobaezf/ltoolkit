<?php
/**
 * @author Ernesto Baez
 */

namespace LToolkit\Interfaces;



interface SerializerInterface
{

    /**
     * Apply the transformation.
     *
     * @param  mixed $data
     * @return mixed
     */
    function serialize($data);
}

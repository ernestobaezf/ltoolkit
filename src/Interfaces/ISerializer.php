<?php
/**
 * @author Ernesto Baez
 */

namespace ltoolkit\Interfaces;



interface ISerializer
{

    /**
     * Apply the transformation.
     *
     * @param  mixed $data
     * @return mixed
     */
    function serialize($data);
}

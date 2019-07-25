<?php
/**
 * @author Ernesto Baez
 */

namespace l5toolkit\Interfaces;



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

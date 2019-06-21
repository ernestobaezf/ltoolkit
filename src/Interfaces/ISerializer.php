<?php
/**
 * @author Ernesto Baez
 */

namespace ErnestoBaezF\L5CoreToolbox\Interfaces;



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
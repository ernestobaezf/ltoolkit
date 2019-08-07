<?php
/**
 * @author Ernesto Baez
 */

namespace l5toolkit\Traits;

/**
 * Use this interface to mark classes extending l5toolkit\Traits\TLoggable trait
 *
 * Interface ILoggable
 * @package l5toolkit\Traits;
 */
interface ILoggable
{
    /**
     * Allow to automatically log a function by calling it with the prefix log
     * (Example: $object->log("functionName", ...$params)
     *
     * @param string $method
     * @param mixed ...$arguments
     *
     * @return mixed
     */
    function log(string $method, ...$arguments);
}

<?php
/**
 * @author Ernesto Baez
 */

namespace ltoolkit\Traits;

/**
 * Use this interface to mark classes extending ltoolkit\Traits\TLoggable trait
 *
 * Interface ILoggable
 * @package ltoolkit\Traits;
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

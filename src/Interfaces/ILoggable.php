<?php
/**
 * @author Ernesto Baez
 */

namespace LToolkit\Traits;

/**
 * Use this interface to mark classes extending LToolkit\Traits\TLoggable trait
 *
 * Interface ILoggable
 * @package LToolkit\Traits;
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

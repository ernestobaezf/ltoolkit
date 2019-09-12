<?php
/**
 * @author Ernesto Baez
 */

namespace LToolkit\Interfaces;

/**
 * Use this interface to mark classes extending LToolkit\Traits\TLoggable trait
 *
 * Interface LoggableInterface
 * @package LToolkit\Interfaces;
 */
interface LoggableInterface
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

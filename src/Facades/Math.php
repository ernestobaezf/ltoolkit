<?php
/**
 * @author Ernesto Baez 
 */

namespace l5toolkit\Facades;


use Illuminate\Support\Facades\Facade;

/**
 * Class Math
 * @package l5toolkit\Facades
 *
 * @method static float percentage(float $total, float $amount)
 *
 * @see l5toolkit\Helpers\MathFunctions
 */
class Math extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() { return 'math'; }
}

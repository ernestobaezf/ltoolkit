<?php
/**
 * @author Ernesto Baez 
 */

namespace ErnestoBaezF\L5CoreToolbox\Facades;


use Illuminate\Support\Facades\Facade;

/**
 * Class Math
 * @package ErnestoBaezF\L5CoreToolbox\Facades
 *
 * @method static float percentage(float $total, float $amount)
 *
 * @see ErnestoBaezF\L5CoreToolbox\Helpers\MathFunctions
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

<?php
/**
 * @author Ernesto Baez
 */

namespace LToolkit\Facades;


use Illuminate\Support\Facades\Facade;

/**
 * Class Math
 * @package LToolkit\Facades
 *
 * @method static float percentage(float $total, float $amount)
 * @method static string baseConvert(string $number, int $fromBase, int $toBase)
 * @method static float binaryToDecimal(string $binaryString)
 * @method static string decimalToBinary(int $number)
 * @method static string decimalToHexadecimal(int $number)
 * @method static float hexadecimalToDecimal(string $hexadecimalString)
 * @method static float radianToDegree(float $number)
 * @method static float degreeToRadian(float $number)
 * @method static float octalToDecimal(string $octalString)
 * @method static string decimalToOctal(int $octalString)
 * @method static bool is_finite(float $value)
 * @method static bool is_infinite(float $value)
 * @method static bool is_nan($value)
 * @method static float abs($number)
 * @method static round(float $value, int $precision = 0, int $mode = PHP_ROUND_HALF_UP)
 * @method static float ceil(float $value)
 * @method static float floor(float $value)
 * @method static max($value1, $value2 = null, ...$values)
 * @method static min($value1, $value2 = null, ...$values)
 * @method static int rand(int $min = 0, int $max = null)
 * @method static int getRandMax()
 * @method static int getRandMaxMT()
 * @method static randMT(int $min = 0, int $max = null)
 * @method static sRandMT(int $seed = null, int $mode = MT_RAND_MT19937)
 * @method static randCLCG()
 * @method static int integerDivision(int $dividend,  int $divisor)
 * @method static mod(float $dividend, float $divisor)
 * @method static pow($base, $exp)
 * @method static float sqrt(float $arg)
 * @method static exp(float $arg)
 * @method static expm1(float $arg)
 * @method static float log10(float $arg)
 * @method static float log1p(float $number)
 * @method static float log(float $arg, float $base = null)
 * @method static float acos(float $arg)
 * @method static float acosh(float $arg)
 * @method static float asin(float $arg)
 * @method static float asinh(float $arg)
 * @method static float atan2(float $y, float $x)
 * @method static float atan(float $arg)
 * @method static float atanh(float $arg)
 * @method static float cos(float $arg)
 * @method static float cosh(float $arg)
 * @method static float pi()
 * @method static float sin(float $arg)
 * @method static float sinh(float $arg)
 * @method static float tan(float $arg)
 * @method static float tanh(float $arg)
 * @method static hypot(float $x, float $y)
 *
 * @see LToolkit\Helpers\MathFunctions
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

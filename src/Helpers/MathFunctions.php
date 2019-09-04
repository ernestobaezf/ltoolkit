<?php
/**
 * @author Ernesto Baez 
 */

namespace ltoolkit\Helpers;

use DivisionByZeroError;

/**
 * Class MathFunctions
 *
 * @package ltoolkit\Helpers
 */
class MathFunctions
{
    /**
     * Percentage
     *
     * @param float $total
     * @param float $amount
     *
     * @return float
     */
    public function percentage(float $total, float $amount): float
    {
        $percentage = 0;
        if ($total) {
            $percentage = ($amount * 100) / $total;
        }

        return $percentage;
    }

    /**
     * Convert a number between arbitrary bases
     *
     * @param string $number
     * @param int    $fromBase
     * @param int    $toBase
     *
     * @return string
     */
    public function baseConvert(string $number, int $fromBase, int $toBase): string
    {
        return base_convert($number, $fromBase, $toBase);
    }

    /**
     * Binary to decimal
     *
     * @param string $binaryString
     *
     * @return float
     */
    public function binaryToDecimal(string $binaryString): float
    {
        return bindec($binaryString);
    }

    /**
     * Binary string representation of number
     *
     * @param int $number
     *
     * @return string
     */
    public function decimalToBinary(int $number): string
    {
        return decbin($number);
    }

    /**
     * Decimal to hexadecimal
     *
     * @param int $number
     *
     * @return string
     */
    public function decimalToHexadecimal(int $number): string
    {
        return dechex($number);
    }

    /**
     * Hexadecimal to decimal
     *
     * @param string $hexadecimalString
     *
     * @return float
     */
    public function hexadecimalToDecimal(string $hexadecimalString): float
    {
        return hexdec($hexadecimalString);
    }

    /**
     * Converts the radian number to the equivalent number in degrees
     *
     * @param float $number
     *
     * @return float
     */
    public function radianToDegree(float $number): float
    {
        return rad2deg($number);
    }

    /**
     * Converts the number in degrees to the radian equivalent
     *
     * @param float $number
     *
     * @return float
     */
    public function degreeToRadian(float $number): float
    {
        return deg2rad($number);
    }

    /**
     * Octal to decimal
     *
     * @param string $octalString
     *
     * @return float
     */
    public function octalToDecimal(string $octalString): float
    {
        return octdec($octalString);
    }

    /**
     * Decimal to octal
     *
     * @param int $octalString
     *
     * @return string
     */
    public function decimalToOctal(int $octalString): string
    {
        return decoct($octalString);
    }

    /**
     * Finds whether a value is a legal finite number
     *
     * @param float $value
     *
     * @return bool
     */
    public function is_finite(float $value): bool
    {
        return is_finite($value);
    }

    /**
     * Finds whether a value is a infinite
     *
     * @param float $value
     *
     * @return bool
     */
    public function is_infinite(float $value): bool
    {
        return is_infinite($value);
    }

    /**
     * Finds whether a value is not a number
     *
     * @param mixed $value
     *
     * @return bool
     */
    public function is_nan($value): bool
    {
        return is_nan($value);
    }

    /**
     * Absolute value
     *
     * @param mixed $number The numeric value to process
     *
     * @return float
     */
    public function abs($number): float
    {
        return abs($number);
    }

    /**
     * Returns the rounded value of val to specified precision (number of digits after the decimal point).
     * precision can also be negative or zero (default).
     * Note: PHP doesn't handle strings like "12,300.2" correctly by default. See converting from strings.
     *
     * @param float $value
     * @param int   $precision [optional] The optional number of decimal digits to round to.
     * @param int   $mode [optional]
     * One of PHP_ROUND_HALF_UP,
     * PHP_ROUND_HALF_DOWN,
     * PHP_ROUND_HALF_EVEN, or
     * PHP_ROUND_HALF_ODD.
     *
     * @return float
     */
    public function round(float $value, int $precision = 0, int $mode = PHP_ROUND_HALF_UP)
    {
        return round($value, $precision, $mode);
    }

    /**
     * Round fractions up to the next higher integer.
     * The return value of ceil is still of type
     * float as the value range of float is
     * usually bigger than that of integer.
     *
     * @param float $value
     *
     * @return float
     */
    public function ceil(float $value): float
    {
        return ceil($value);
    }

    /**
     * Round fractions down to the next lowest integer.
     * The return value of floor is still of type
     * float because the value range of float is
     * usually bigger than that of integer.
     *
     * @param float $value
     *
     * @return float
     */
    public function floor(float $value): float
    {
        return floor($value);
    }

    /**
     * Find numerically highest of the parameter values, either within a arg array or two arguments.
     *
     * @param mixed $value1
     * @param mixed $value2
     * @param mixed ...$values
     *
     * @return mixed
     */
    public function max($value1, $value2 = null, ...$values)
    {
        return max($value1, $value2, ...$values);
    }

    /**
     * Find numerically lowest of the parameter values, either within a arg array or two arguments.
     *
     * @param mixed $value1
     * @param mixed $value2
     * @param mixed ...$values
     *
     * @return mixed
     */
    public function min($value1, $value2 = null, ...$values)
    {
        return min($value1, $value2, ...$values);
    }

    /**
     * Generate a pseudo random value between min
     * (or 0) and max (or getRandMax, inclusive).
     *
     * @param int $min
     * @param int $max
     *
     * @return int
     */
    public function rand(int $min = 0, int $max = null): int
    {
        return rand($min, $max);
    }

    /**
     * Show largest possible random value
     *
     * @return int
     */
    public function getRandMax(): int
    {
        return getrandmax();
    }

    /**
     * Show largest possible random value
     *
     * @return int
     */
    public function getRandMaxMT(): int
    {
        return mt_getrandmax();
    }

    /**
     * Generate a random value via the Mersenne Twister Random Number Generator
     *
     * @param int $min
     * @param int $max
     *
     * @return int
     */
    public function randMT(int $min = 0, int $max = null)
    {
        return mt_rand($min, $max);
    }

    /**
     * Seed the random number generator
     *
     * @param int|null $seed
     * @param int      $mode
     */
    public function sRandMT(int $seed = null, int $mode = MT_RAND_MT19937)
    {
        mt_srand($seed, $mode);
    }

    /**
     * Combined linear congruential generator. Generates a pseudo random float value in the range of (0, 1)
     *
     * @return float
     */
    public function randCLCG()
    {
        return lcg_value();
    }

    /**
     * Integer division
     *
     * @param int $dividend Number to be divided.
     * @param int $divisor Number which divides the dividend
     *
     * @return int
     *
     * @throws DivisionByZeroError
     */
    public function integerDivision(int $dividend,  int $divisor): int
    {
        return intdiv($dividend,  $divisor);
    }

    /**
     * Returns the floating point remainder (modulo) of the division
     *
     * @param float $dividend
     * @param float $divisor
     *
     * @return float
     */
    public function mod(float $dividend, float $divisor)
    {
        return fmod($dividend, $divisor);
    }

    /**
     * Exponential expression
     *
     * @param float|int $base
     * @param float|int $exp
     *
     * @return float|int
     */
    public function pow($base, $exp)
    {
        return pow($base, $exp);
    }

    /**
     * Square root
     *
     * @param float $arg
     *
     * @return float
     */
    public function sqrt(float $arg): float
    {
        return sqrt($arg);
    }

    /**
     * Calculates the exponent of <constant>e</constant>
     *
     * @param float $arg
     *
     * @return float
     */
    public function exp(float $arg):float
    {
        return exp($arg);
    }

    /**
     * Returns exp(number) - 1, computed in a way that is accurate even
     * when the value of number is close to zero
     *
     * @param float $arg
     *
     * @return float
     */
    public function expm1(float $arg)
    {
        return expm1($arg);
    }

    /**
     * Base-10 logarithm
     *
     * @param float $arg
     *
     * @return float
     */
    public function log10(float $arg): float
    {
        return log10($arg);
    }

    /**
     * Returns log(1 + number), computed in a way that is accurate even when the value of number is close to zero
     *
     * @param float $number
     *
     * @return float
     */
    public function log1p(float $number): float
    {
        return log1p($number);
    }

    /**
     * Natural logarithm
     *
     * @param float $arg
     * @param float $base
     *
     * @return float
     */
    public function log(float $arg, float $base = 10.0): float
    {
        return log($arg, $base);
    }

    /**
     * Arc cosine
     *
     * @param float $arg
     *
     * @return float The arc cosine of arg in radians.
     */
    public function acos(float $arg): float
    {
        return acos($arg);
    }

    /**
     * Inverse hyperbolic cosine
     *
     * @param float $arg The value to process
     *
     * @return float The inverse hyperbolic cosine of arg
     */
    public function acosh(float $arg): float
    {
        return acosh($arg);
    }

    /**
     * Arc sine of arg in radians
     * @param float $arg The argument to process
     *
     * @return float
     */
    public function asin(float $arg): float
    {
        return asin($arg);
    }

    /**
     * Inverse hyperbolic sine of arg
     *
     * @param float $arg
     *
     * @return float
     */
    public function asinh(float $arg): float
    {
        return asinh($arg);
    }

    /**
     * Arc tangent of two variables
     *
     * @param float $y
     * @param float $x
     *
     * @return float
     */
    public function atan2(float $y, float $x): float
    {
        return atan2($y, $x);
    }

    /**
     * Arc tangent of arg in radians
     *
     * @param float $arg
     *
     * @return float
     */
    public function atan(float $arg): float
    {
        return atan($arg);
    }

    /**
     * Inverse hyperbolic tangent
     *
     * @param float $arg
     *
     * @return float
     */
    public function atanh(float $arg): float
    {
        return atanh($arg);
    }

    /**
     * Cosine
     *
     * @param float $arg An angle in radians
     *
     * @return float
     */
    public function cos(float $arg): float
    {
        return cos($arg);
    }

    /**
     * Hyperbolic cosine
     *
     * @param float $arg
     *
     * @return float
     */
    public function cosh(float $arg): float
    {
        return cosh($arg);
    }

    /**
     * Get value of pi constant
     *
     * @return float
     */
    public function pi(): float
    {
        return pi();
    }

    /**
     * Sine
     *
     * @param float $arg An angle in radians
     *
     * @return float
     */
    public function sin(float $arg): float
    {
        return sin($arg);
    }

    /**
     * Hyperbolic sine
     *
     * @param float $arg
     *
     * @return float
     */
    public function sinh(float $arg): float
    {
        return sinh($arg);
    }

    /**
     * Tangent
     *
     * @param float $arg The argument to process in radians
     *
     * @return float
     */
    public function tan(float $arg): float
    {
        return tan($arg);
    }

    /**
     * Hyperbolic tangent
     *
     * @param float $arg
     *
     * @return float
     */
    public function tanh(float $arg): float
    {
        return tanh($arg);
    }

    /**
     * Calculate the length of the hypotenuse of a right-angle triangle
     *
     * @param float $x
     * @param float $y
     *
     * @return float
     */
    public function hypot(float $x, float $y): float
    {
        return hypot($x, $y);
    }
}

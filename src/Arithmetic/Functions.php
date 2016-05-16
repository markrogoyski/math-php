<?php
namespace Math\Arithmetic;

class Functions {

  /**
   * Sign function (signum function) - sgn
   * Extracts the sign of a real number.
   * https://en.wikipedia.org/wiki/Sign_function
   *
   *          { -1 if x < 0
   * sgn(x) = {  0 if x = 0
   *          {  1 if x > 0
   *
   * @param number $x
   * @return int
   */
  public static function signum( float $x ): int {
    if ( $x == 0 ) {
      return 0;
    }
    return $x < 0 ? -1 : 1;
  }

  /**
   * Sign function (signum function) - sgn
   * Convenience wrapper for signum function.
   */
  public static function sgn( float $x ): int {
    return self::signum($x);
  }
}
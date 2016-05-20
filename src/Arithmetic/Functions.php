<?php
namespace Math\Arithmetic;

use Math\Probability\Combinatorics;

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

  /**
   * Gamma function
   * https://en.wikipedia.org/wiki/Gamma_function
   *
   * For postive integers:
   *  Γ(n) = (n - 1)!
   *
   * For positive real numbers -- approximation:
   *                   ___
   *         __       / 1  /         1      \ n
   *  Γ(n)≈ √2π ℯ⁻ⁿ  /  - | n + ----------- | 
   *                √   n  \    12n - 1/10n /
   *
   * https://www.wolframalpha.com/input/?i=Gamma(n)&lk=3
   *
   * @param number $n
   * @return number
   */
  public static function gamma($n) {
    if ( $n == 0 ) {
      return \INF;
    }
    if ( is_int($n) && $n < 0 ) {
      return -\INF;
    }
    if ( is_int($n) && $n > 0 ) {
      return Combinatorics::factorial( $n - 1 );
    }

    $√2π                    = sqrt( 2 * \M_PI );
    $ℯ⁻ⁿ                    = pow( \M_E, -$n );
    $√1／n                  = sqrt( 1 / $n );
    $⟮n ＋ 1／⟮12n − 1／10n⟯⟯ⁿ = pow( $n + 1 / (12*$n - 1/(10*$n)), $n );

    return $√2π * $ℯ⁻ⁿ * $√1／n * $⟮n ＋ 1／⟮12n − 1／10n⟯⟯ⁿ;
  }
}
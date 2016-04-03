<?php
namespace Math\Probability;

class Combinatorics {

  /**
   * Facatorial (iterative)
   * Represents the number of ways to arrange n things (permutations)
   * n! = n(n - 1)(n - 2) ... 3 * 2 * 1
   *
   * @param  int $n
   * @return int number of permutations of n
   */
  public static function factorial($n) {
    $factorial = 1;
    while ( $n > 0 ) {
      $factorial *= $n;
      $n--;
    }
    return $factorial;
  }

  /**
   * Find number of permutations--ordered arrangements--of n things.
   * n(n - 1)(n - 2) ... 3 * 2 * 1 = n!
   *
   * @param  int $n
   * @return int number of permutations of n
   */
  public static function permutations($n) {
    return self::factorial($n);
  }

  /**
   * Find number of permutations--ordered arrangements--of n things taking only r of them.
   *                    n!
   * P(n,r) = nPr =  -------
   *                 (n - r)
   *
   * @param  int $n
   * @param  int $r
   * @return int number of possible combinations of n objects taken r at a time
   */
  public static function permutationsChooseR( $n, $r ) {
    return self::factorial($n) / self::factorial( $n - $r );
  }

  /**
   * Find number of combinations--groups of r objects that could be formed form a total of n objects.
   * n choose r: number of possible combinations of n objects taken r at a time.
   *       (n)       n!
   * nCr = ( ) = ----------
   *       (r)   (n - r)!r!
   *
   * @param  int $n
   * @param  int $r
   * @return int number of possible combinations of n objects taken r at a time
   */
  public static function combinations( $n, $r ) {
    return self::factorial($n) / ( self::factorial( $n - $r ) * self::factorial($r) );
  }

  /**
   * Find number of combinations with repetition--groups of r objects that could be formed form a total of n objects.
   * n choose r: number of possible combinations of n objects taken r at a time with repetition.
   *        (n)   (n + r - 1)!
   * nC'r = ( ) = ------------
   *        (r)    (n - 1)!r!
   *
   * @param  int $n
   * @param  int $r
   * @return int number of possible combinations of n objects taken r at a time
   */
  public static function combinationsWithRepetition( $n, $r ) {
    return self::factorial( $n + $r - 1) / ( self::factorial( $n - 1 ) * self::factorial($r) );
  }

  /**
   * Multinomial theorem
   * Fines the number of divisions of n items into r distinct nonoverlapping subgroups of sizes n1, n2, n3, etc.
   *        n!
   *   ------------
   *   n1!n2!...nr!
   *
   * @param  int   $n      Number of items
   * @param  array $groups Sizes of each subgroup
   * @return int Number of divisions of n items into r distinct nonoverlapping subgroups
   */
  public static function multinomialTheorem( $n, array $groups ) {
    return self::factorial($n) / array_product( array_map( 'self::factorial', $groups ) );
  }
}
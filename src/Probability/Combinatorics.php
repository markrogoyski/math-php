<?php
namespace Math\Probability;

class Combinatorics {

  /**
   * Facatorial (iterative)
   * Represents the number of ways to arrange n things (permutations)
   * n! = n(n - 1)(n - 2) ... (n - (n - 1))
   *
   * @param  int $n
   * @return int number of permutations of n
   * @throws \Exception
   */
  public static function factorial( int $n ) {
    if ( $n < 0 ) {
      throw new \Exception('Cannot compute factorial of a negative number.');
    }
    $factorial = 1;
    while ( $n > 0 ) {
      $factorial *= $n;
      $n--;
    }
    return $factorial;
  }

  /**
   * Find number of permutations--ordered arrangements--of n things.
   * n(n - 1)(n - 2) ... (n - (n - 1)) = n!
   *
   * @param  int $n
   * @return int number of permutations of n
   * @throws \Exception
   */
  public static function permutations( int $n ) {
    if ( $n < 0 ) {
      throw new \Exception('Cannot compute negative permutations.');
    }
    return self::factorial($n);
  }

  /**
   * Find number of permutations--ordered arrangements--of n things taking only r of them.
   *                    n!
   * P(n,r) = nPr =  --------
   *                 (n - r)!
   *
   * @param  int $n
   * @param  int $r
   * @return int number of possible combinations of n objects taken r at a time
   * @throws \Exception
   */
  public static function permutationsChooseR( int $n, int $r ) {
    if ( $n < 0 ) {
      throw new \Exception('Cannot compute negative permutations.');
    }
    if ( $r > $n ) {
      throw new \Exception('r cannot be larger than n.');
    }

    $n！      = self::factorial($n);
    $⟮n − r⟯！ = self::factorial( $n - $r );

    return $n！ / $⟮n − r⟯！;
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
   * @throws \Exception
   */
  public static function combinations( int $n, int $r ) {
    if ( $n < 0 ) {
      throw new \Exception('Cannot compute negative combinations.');
    }
    if ( $r > $n ) {
      throw new \Exception('r cannot be larger than n.');
    }

    $n！        = self::factorial($n);
    $⟮n − r⟯！r！ = self::factorial( $n - $r ) * self::factorial($r);

    return $n！ / $⟮n − r⟯！r！;
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
   * @throws \Exception
   */
  public static function combinationsWithRepetition( int $n, int $r ) {
    if ( $n < 0 ) {
      throw new \Exception('Cannot compute negative combinations.');
    }
    if ( $r > $n ) {
      throw new \Exception('r cannot be larger than n.');
    }

    $⟮n ＋ r − 1⟯！ = self::factorial( $n + $r - 1 );
    $⟮n − 1⟯！r！   = self::factorial( $n - 1 ) * self::factorial($r);

    return $⟮n ＋ r − 1⟯！ / $⟮n − 1⟯！r！;
  }

  /**
   * Multinomial theorem
   * Finds the number of divisions of n items into r distinct nonoverlapping subgroups of sizes n1, n2, n3, etc.
   *        n!
   *   ------------
   *   n1!n2!...nr!
   *
   * @param  int   $n      Number of items
   * @param  array $groups Sizes of each subgroup
   * @return int Number of divisions of n items into r distinct nonoverlapping subgroups
   */
  public static function multinomialTheorem( int $n, array $groups ) {
    return self::factorial($n) / array_product( array_map( 'self::factorial', $groups ) );
  }
}
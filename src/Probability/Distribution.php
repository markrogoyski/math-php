<?php
namespace Math\Probability;
require_once('Combinatorics.php');

class Distribution {

  /**
   * Binomial distribution
   * https://en.wikipedia.org/wiki/Binomial_distribution
   *
   * P(X = r) = nCr pʳ (1 - p)ⁿ⁻ʳ
   *
   * @param  int   $n number of events
   * @param  int   $r number of successful events
   * @param  float $P probability of success
   * @return number
   */
  public static function binomial( int $n, int $r, float $p ): float {
    if ( $p < 0 || $p > 1 ) {
      throw new \Exception("Probability $p must be between 0 and 1.");
    }

    $nCr       = Combinatorics::combinations( $n, $r );
    $pʳ        = pow( $p, $r );
    $⟮1 − p⟯ⁿ⁻ʳ = pow( 1 - $p, $n - $r );

    return $nCr * $pʳ * $⟮1 − p⟯ⁿ⁻ʳ;
  }

  /**
   * Cumulative binomial distribution
   * Computes and sums the binomial distribution at each of the values in r.
   *
   * @param  int   $n number of events
   * @param  int   $r number of successful events
   * @param  float $P probability of success
   * @return number
   */
  public static function cumulativeBinomial( int $n, int $r, float $p ): float {
    $cumulative_probability = 0;
    for ( $i = $r; $i >= 0; $i-- ) {
      $cumulative_probability += self::binomial( $n, $i, $p );
    }
    return $cumulative_probability;
  }

  /**
   * Negative binomial distribution (Pascal distribution)
   * https://en.wikipedia.org/wiki/Negative_binomial_distribution
   *
   * b(x; r, P) = ₓ₋₁Cᵣ₋₁ Pʳ * (1 - P)ˣ⁻ʳ
   *
   * @param  int   $x number of trials required to produce r successes
   * @param  int   $r number of successful events
   * @param  float $P probability of success on an individual trial
   * @return number
   */
  public static function negativeBinomial( int $x, int $r, float $P ): float {
    if ( $P < 0 || $P > 1 ) {
      throw new \Exception("Probability $P must be between 0 and 1.");
    }

    $ₓ₋₁Cᵣ₋₁   = Combinatorics::combinations( $x - 1, $r - 1 );
    $Pʳ        = pow( $P, $r );
    $⟮1 − P⟯ˣ⁻ʳ = pow( 1 - $P, $x - $r );

    return $ₓ₋₁Cᵣ₋₁ * $Pʳ * $⟮1 − P⟯ˣ⁻ʳ;
  }

  /**
   * Pascal distribution (convenience method for negative binomial distribution)
   * https://en.wikipedia.org/wiki/Negative_binomial_distribution
   *
   * b(x; r, P) = ₓ₋₁Cᵣ₋₁ pʳ * (1 - P)ˣ⁻ʳ
   *
   * @param  int   $x number of trials required to produce r successes
   * @param  int   $r number of successful events
   * @param  float $P probability of success on an individual trial
   * @return number
   */
  public static function pascal( int $x, int $r, float $P ): float {
    return self::negativeBinomial( $x, $r, $P );
  }

  /**
   * Poisson distribution
   * A discrete probability distribution that expresses the probability of a given number of events
   * occurring in a fixed interval of time and/or space if these events occur with a known average rate and independently of the time since the last event.
   * https://en.wikipedia.org/wiki/Poisson_distribution
   *
   *                              λᵏℯ^⁻λ
   * P(k events in an interval) = ------
   *                                k!
   *
   * @param  int   $k events in the interval
   * @param  float $λ average number of successful events per interval
   * @return number   The Poisson probability of observing k successful events in an interval
   */
  public static function poisson( int $k, float $λ ): float {
    if ( $k < 0 || $λ < 0 ) {
      throw new \Exception('k and λ must be greater than 0.');
    }

    $λᵏℯ＾−λ = pow( $λ, $k ) * pow( \M_E, -$λ );
    $k！     = Combinatorics::factorial($k);

    return $λᵏℯ＾−λ / $k！;
  }

  /**
   * Cumulative Poisson Probability (lower culmulative distribution)
   * The probability that the Poisson random variable is greater than some specified lower limit,
   * and less than some specified upper limit.
   *
   *           k  λˣℯ^⁻λ
   * P(k,λ) =  ∑  ------
   *          ₓ₌₀  xᵢ!
   *
   * @param  int   $k events in the interval
   * @param  float $λ average number of successful events per interval
   * @return number   The cumulative Poisson probability
   */
  public static function cumulativePoisson( int $k, float $λ ): float {
    return array_sum( array_map(
      function($k) use ($λ) { return self::poisson( $k, $λ ); },
      range( 0, $k )
    ) );
  }

  /**
   * Continuous uniform distribution
   * Computes the probability of a specific interval within the continuous uniform distribution.
   *
   * @param number $a lower boundary of the distribution
   * @param number $b upper boundary of the distribution
   * @param number $x₁ lower boundary of the probability interval
   * @param number $x₂ upper boundary of the probability interval
   * @return number probability of specific interval
   */
  public static function continuousUniform( $a, $b, $x₁, $x₂ ) {
    if ( ( $x₁ < $a || $x₁ > $b ) || ( $x₂ < $a || $x₂ > $b ) ) {
      throw new \Exception('x values are outside of the distribution.');
    }
    return ( $x₂ - $x₁ ) / ( $b - $a );
  }
}
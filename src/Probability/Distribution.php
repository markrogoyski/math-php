<?php
namespace Math\Probability;
require_once('Combinatorics.php');
require_once( __DIR__ . '/../Statistics/RandomVariable.php' );
require_once('StandardNormalTable.php');

use Math\Statistics\RandomVariable;

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
   * x₂ − x₁
   * -------
   *  b − a
   *
   * @param number $a lower boundary of the distribution
   * @param number $b upper boundary of the distribution
   * @param number $x₁ lower boundary of the probability interval
   * @param number $x₂ upper boundary of the probability interval
   * @return number probability of specific interval
   */
  public static function continuousUniform( float $a, float $b, float $x₁, float $x₂ ) {
    if ( ( $x₁ < $a || $x₁ > $b ) || ( $x₂ < $a || $x₂ > $b ) ) {
      throw new \Exception('x values are outside of the distribution.');
    }
    return ( $x₂ - $x₁ ) / ( $b - $a );
  }

  /**
   * Exponential distribution - probability density function
   * https://en.wikipedia.org/wiki/Exponential_distribution
   *
   * f(x;λ) = λℯ^⁻λx  x ≥ 0
   *        = 0       x < 0
   *
   * @param float $λ often called the rate parameter
   * @param float $x the random variable
   * @return float
   */
  public static function exponential( float $λ, float $x ): float {
    if ( $x < 0 ) {
      return 0;
    }

    return $λ * pow( \M_E, -$λ * $x );
  }

  /**
   * Cumulative exponential distribution - cumulative distribution function
   * https://en.wikipedia.org/wiki/Exponential_distribution
   *
   * f(x;λ) = 1 − ℯ^⁻λx  x ≥ 0
   *        = 0          x < 0
   *
   * @param float $λ often called the rate parameter
   * @param float $x the random variable
   * @return float
   */
  public static function cumulativeExponential( float $λ, float $x ): float {
    if ( $x < 0 ) {
      return 0;
    }

    return 1 - pow( \M_E, -$λ * $x );
  }

  /**
   * Cumulative exponential distribution between two numbers
   * Probability that an exponentially distributed random variable X
   * is between two numbers x₁ and x₂.
   *
   * P(x₁ ≤ X ≤ x₂) = P(X ≤ x₂) − P(X ≤ x₁)
   *                = (1 − ℯ^⁻λx₂) − (1 − ℯ^⁻λx₁)
   *
   * @param float $λ often called the rate parameter
   * @param float $x₁ random variable 1
   * @param float $x₂ random variable 2
   * @return float
   */
  public static function cumulativeExponentialBetweenTwoNumbers( float $λ, float $x₁, float $x₂ ): float {
    return self::cumulativeExponential( $λ, $x₂ ) - self::cumulativeExponential( $λ, $x₁ );
  }

  /**
   * Normal distribution - probability density function
   *
   * https://en.wikipedia.org/wiki/Normal_distribution
   *
   *              1
   * f(x|μ,σ) = ----- ℯ^−⟮x − μ⟯²∕2σ²
   *            σ√⟮2π⟯
   *
   * @param number $x random variable
   * @param number $μ mean
   * @param number $σ standard deviation
   * @return float f(x|μ,σ)
   */
  public static function normal( $x, $μ, $σ ): float {
    $σ√⟮2π⟯ = $σ * sqrt( 2 * \M_PI );

    $⟮x − μ⟯²∕2σ² = pow( ($x - $μ), 2 ) / (2 * $σ**2);

    $ℯ＾−⟮x − μ⟯²∕2σ² = pow( \M_E, -$⟮x − μ⟯²∕2σ² );

    return ( 1 / $σ√⟮2π⟯ ) * $ℯ＾−⟮x − μ⟯²∕2σ²;
  }

  /**
   * Normal distribution - cumulative distribution function
   * Probability of being below X.
   * Area under the normal distribution from -∞ to X. 
   *             _                  _
   *          1 |         / x - μ \  |
   * cdf(x) = - | 1 + erf|  ----- |  |
   *          2 |_        \  σ√2  / _|
   *
   * @param number $x upper bound
   * @param number $μ mean
   * @param number $σ standard deviation
   * @return float cdf(x) below
   */
  public static function cumulativeNormal( $x, $μ, $σ ): float {
    return 1/2 * ( 1 + RandomVariable::erf( ($x - $μ) / ($σ * sqrt(2)) ) );
  }

  /**
   * Normal distribution above - cumulative distribution function
   * Probability of being above X.
   * Area under the normal distribution from X to ∞
   *
   * @param number $x lower bound
   * @param number $μ mean
   * @param number $σ standard deviation
   * @return float cdf(x) above
   */
  public static function cumulativeNormalAbove( $x, $μ, $σ ): float {
    return 1 - self::cumulativeNormal( $x, $μ, $σ );
  }

  /**
   * Normal distribution between two points - cumulative distribution function
   * Probability of being bewteen x₁ and x₂.
   * Area under the normal distribution from x₁ to x₂.
   *
   * @param number x₁ lower bound
   * @param number x₂ upper bound
   * @param number $μ mean
   * @param number $σ standard deviation
   * @return float cdf(x) between
   */
  public static function cumulativeNormalBetween( $x₁, $x₂, $μ, $σ ): float {
    return self::cumulativeNormal( $x₂, $μ, $σ ) - self::cumulativeNormal( $x₁, $μ, $σ );
  }

  /**
   * Normal distribution outside two points - cumulative distribution function
   * Probability of being bewteen below x₁ and above x₂.
   * Area under the normal distribution from -∞ to x₁ and x₂ to ∞.
   *
   * @param number x₁ lower bound
   * @param number x₂ upper bound
   * @param number $μ mean
   * @param number $σ standard deviation
   * @return float cdf(x) between
   */
  public static function cumulativeNormalOutside( $x₁, $x₂, $μ, $σ ): float {
    return self::cumulativeNormal( $x₁, $μ, $σ ) + self::cumulativeNormalAbove( $x₂, $μ, $σ );
  }
}
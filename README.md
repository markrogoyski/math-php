Math PHP
=====================

A modern math library for PHP.

Math PHP is a self-contained mathematics library in pure PHP with no external dependencies. It is actively under development and should be considered a work in progress.

Features
--------
 * Probability
     * Combinatorics
     * Distributions
         - Discrete
         - Continuous
     * Standard Normal Table (Z Table)
     * t Distribution Table
 * Statistics
     * Averages
     * Descriptive
     * Distributions
     * Random Variables
     * Regressions
 * Algebra
 * Special Functions

Setup
-----

 Add the library to your `composer.json` file in your project:

```javascript
{
  "require": {
      "markrogoyski/math-php": "0.0.*"
  }
}
```

Use [composer](http://getcomposer.org) to install the library:

```bash
$ php composer.phar install
```

Composer will install Math PHP inside your vendor folder. Then you can add the following to your
.php files to use the library with Autoloading.

```php
require_once(__DIR__ . '/vendor/autoload.php');
```

### Minimum Requirements
 * PHP 7

Usage
-----

### Probability - Combinatorics
```php
use Math\Probability\Combinatorics;

// Factorial and permutations
$factorial    = Combinatorics::factorial(5);    // Same as permutations
$permutations = Combinatorics::permutations(5); // Same as factorial

// Permutations n choose r
$n = 10;
$r = 4;
$permutations = Combinatorics::permutationsChooseR($n, $r);

// Combinations
$combinations = Combinatorics::combinations($n, $r);
$combinations = Combinatorics::combinationsWithRepetition($n, $r);

// Multinomial Theorem
$n         = 10;
$groups    = [5, 2, 3];
$divisions = Combinatorics::multinomialTheorem($n, $groups);
```

### Probability - Discrete Distributions
```php
use Math\Probability\Distribution\Discrete;

// Binomial distribution - PMF, CDF
$n = 2;   // number of events
$r = 1;   // number of successful events
$P = 0.5; // probability of success
$binomial            = Discrete::binomialPMF($n, $r, $P); // probability mass function
$cumulative_binomial = Discrete::binomialCDF($n, $r, $P); // cumluative distribution function

// Negative binomial distribution (Pascal)
$x = 2;   // number of trials required to produce r successes
$r = 1;   // number of successful events
$P = 0.5; // probability of success on an individual trial
$negative_binomial = Discrete::negativeBinomial($x, $r, $P);  // Same as pascal
$pascal            = Discrete::pascal($x, $r, $P);            // Same as negative binomial

// Poisson distribution - PMF, CDF
$k = 3; // events in the interval
$λ = 2; // average number of successful events per interval
$poisson            = Discrete::poissonPMF($k, $λ); // probability mass function
$cumulative_poisson = Discrete::poissonCDF($k, $λ); // cumulative distribution function
```

### Probability - Continuous Distributions
```php
use Math\Probability\Distribution\Continuous;

// Continuous uniform distribution
$a  = 2;  // lower boundary of distribution
$b  = 10; // upper boundary of distribution
$x₁ = 4;  // lower boundary of probability interval
$x₂ = 6;  // upper boundary of probability interval
$probability = Continuous::uniform($a, $b, $x₁, $x₂);

// Exponential distribution - PDF, CDF
$λ = 1; // rate parameter
$x = 2; // random variable
$pdf = Continuous::exponentialPDF($λ, $x); // probability density function
$cdf = Continuous::exponentialCDF($λ, $x); // cumulative distribution function

// Probability that an exponentially distributed random variable X is between two numbers x₁ and x₂
$x₁ = 2;
$x₂ = 3;
$probability = Continuous::exponentialCDFBetween($λ, $x₁, $x₂);

// Normal distribution - probability density function (pdf)
$μ = 0;
$σ = 1;
$x = 2;
$probability = Continuous::normalPDF($x, $μ, $σ);

// Normal distrubution - cumulative distribution function (cdf)
$μ  = 0;
$σ  = 1;
$x₁ = 1;
$x₂ = 2;
$probability = Continuous::normalCDF($x₁, $μ, $σ);             // from -∞ to X
$probability = Continuous::normalCDFAbove($x₁, $μ, $σ);        // from X to ∞
$probability = Continuous::normalCDFBetween($x₁, $x₂, $μ, $σ); // from x₁ to x₂
$probability = Continuous::normalCDFOutside($x₁, $x₂, $μ, $σ); // from -∞ to x₁ and x₂ to ∞

// Log-normal distribution - PDF, CDF
$μ = 6;
$σ = 2;
$x = 4.3;
$probability = Continuous::logNormalPDF($x, $μ, $σ); // probability density function
$probability = Continuous::logNormalCDF($x, $μ, $σ); // cumulative distribution function

// Pareto distribution - PDF, CDF
$a = 1; // shape parameter
$b = 1; // scale parameter
$x = 2;
$probability = Continuous::paretoPDF($a, $b, $x); // probability denssity function
$probability = Continuous::paretoCDF($a, $b, $x); // cumulative distribution function
```

### Probability - Standard Normal Table (Z Table)
```php
use Math\Probability\StandardNormalTable;

// Get probability from Z-score
$Z           = 1.50;
$probability = StandardNormalTable::getZScoreProbability($Z);

// Access the entire Z table (positive and negative Z-scores)
$z_table     = StandardNormalTable::Z_SCORES;
$probability = $z_table[1.5][0];
```

### Probability - t Distribution Table
```php
use Math\Probability\TDistributionTable;

// Get t critical value from degrees of freedom (ν) and confidence level (cl)
$ν       = 5;
$cl      = 99;
$t_value = TDistributionTable::getOneSidedTValueFromConfidenceLevel($ν, $cl);
$t_value = TDistributionTable::getTwoSidedTValueFromConfidenceLevel($ν, $cl);

// Get t critical value from degrees of freedom (ν) and alpha value (α)
$ν       = 5;
$α       = 0.001;
$t_value = TDistributionTable::getOneSidedTValueFromAlpha($ν, $α);
$t_value = TDistributionTable::getTwoSidedTValueFromAlpha($ν, $α);

// Access the entire t table (one and two sided; confidence levels and alphas)
$t_table = TDistributionTable::ONE_SIDED_CONFIDENCE_LEVEL;
$t_table = TDistributionTable::TWO_SIDED_CONFIDENCE_LEVEL;
$t_value = $t_table[$ν][$cl];

$t_table = TDistributionTable::ONE_SIDED_ALPHA;
$t_table = TDistributionTable::TWO_SIDED_ALPHA;
$t_table = $t_table[$ν][$α];
```

### Statistics - Averages
```php
use Math\Statistics\Average;

$numbers = [13, 18, 13, 14, 13, 16, 14, 21, 13];

// Mean, median, mode
$mean   = Average::mean($numbers);
$median = Average::median($numbers);
$mode   = Average::mode($numbers); // Returns an array — may be multimodal

// Other means of a list of numbers
$geometric_mean      = Average::geometricMean($numbers);
$harmonic_mean       = Average::harmonicMean($numbers);
$contraharmonic_mean = Average::contraharmonicMean($numbers);
$quadratic_mean      = Average::quadraticMean($numbers);  // same as rootMeanSquare
$root_mean_square    = Average::rootMeanSquare($numbers); // same as quadraticMean
$trimean             = Average::trimean($numbers);
$interquartile_mean  = Average::interquartileMean($numbers); // same as iqm
$interquartile_mean  = Average::iqm($numbers);               // same as interquartileMean
$cubic_mean          = Average::cubicMean($numbers);

// Truncated mean (trimmed mean)
$trim_percent   = 25;
$truncated_mean = Average::truncatedMean($numbers, $trim_percent);

// Generalized mean (power mean)
$p                = 2;
$generalized_mean = Average::generalizedMean($numbers, $p); // same as powerMean
$power_mean       = Average::powerMean($numbers, $p);       // same as generalizedMean

// Lehmer mean
$p           = 3;
$lehmer_mean = Average::lehmerMean($numbers, $p);

// Means of two numbers
list($x, $y) = [24, 6];
$agm           = Average::arithmeticGeometricMean($x, $y); // same as agm
$agm           = Average::agm($x, $y);                     // same as arithmeticGeometricMean
$log_mean      = Average::logarithmicMean($x, $y);
$heronian_mean = Average::heronianMean($x, $y);
$identric_mean = Average::identricMean($x, $y);

// Averages report
// Returns array with keys: mean, median, mode, geometric_mean, harmonic_mean, contraharmonic_mean, quadratic_mean, trimean, iqm, cubic_mean
$averages = Average::getAverages($numbers);
```

### Statistics - Descriptive
```php
use Math\Statistics\Descriptive

$numbers = [13, 18, 13, 14, 13, 16, 14, 21, 13];

// Range and midrange
$range    = Descriptive::range($numbers);
$midrange = Descriptive::midrange($numbers);

// Variance (population and sample)
$σ² = Descriptive::populationVariance($numbers);
$S² = Descriptive::sampleVariance($numbers);

// Standard deviation
$σ = Descriptive::standardDeviation($numbers); // Has optional parameter to set population or sample variance

// MAD - mean/median absolute deviations
$mean_mad   = Descriptive::meanAbsoluteDeviation($numbers);
$median_mad = Descriptive::medianAbsoluteDeviation($numbers);

// Quartiles
$quartiles = Descriptive::quartiles($numbers);
// [0% => 13, Q1 => 13, Q2 => 14, Q3 => 17, 100% => 21, IQR => 4]

// IQR - Interquartile range
$IQR = Descriptive::interquartileRange($numbers); // Same as IQR
$IQR = Descriptive::IQR($numbers);                // Same as interquartileRange

// Percentiles
$twentieth_percentile    = Descriptive::percentile($numbers, 20);
$ninety_fifth_percentile = Descriptive::percentile($numbers, 95);

// Midhinge
$midhinge = Descriptive::midhinge($numbers);

// Descriptive stats report
// Returns array with keys: mean, median, mode, range, midrange, variance, standard deviation, mean_mad, median_mad, quartiles
$stats = Descriptive::getStats($numbers); // Has optional parameter to set population or sample variance
```
### Statistics - Distributions
```php
use Math\Statistics\Distribution

$grades = ['A', 'A', 'B', 'B', 'B', 'B', 'C', 'C', 'D', 'F'];

// Frequency distributions (frequency and relative frequency)
$frequencies          = Distribution::frequency($grades);         // [ A => 2,   B => 4,   C => 2,   D => 1,   F => 1   ]
$relative_frequencies = Distribution::relativeFrequency($grades); // [ A => 0.2, B => 0.4, C => 0.2, D => 0.1, F => 0.1 ]

// Cumulative frequency distributions (cumulative and cumulative relative)
$cumulative_frequencies          = Distribution::cumulativeFrequency($grades);         // [ A => 2,   B => 6,   C => 8,   D => 9,   F => 10  ]
$cumulative_relative_frequencies = Distribution::cumulativeRelativeFrequency($grades); // [ A => 0.2, B => 0.6, C => 0.8, D => 0.9, F => 1   ]

// Stem and leaf plot
// Return value is array where keys are the stems, values are the leaves
$values             = [44, 46, 47, 49, 63, 64, 66, 68, 68, 72, 72, 75, 76, 81, 84, 88, 106];
$stem_and_leaf_plot = Distribution::stemAndLeafPlot($values);
// [4 => [4, 6, 7, 9], 5 => [], 6 => [3, 4, 6, 8, 8], 7 => [2, 2, 5, 6], 8 => [1, 4, 8], 9 => [], 10 => [6]]

// Optional second parameter will print stem and leaf plot to STDOUT
Distribution::stemAndLeafPlot($values, Distribution::PRINT);
/*
 4 | 4 6 7 9
 5 |
 6 | 3 4 6 8 8
 7 | 2 2 5 6
 8 | 1 4 8
 9 |
10 | 6
*/
```

### Statistics - Random Variables
```php
use Math\Statistics\RandomVariable

$X = [1, 2, 3, 4 ];
$Y = [2, 3, 4, 5 ];

// Covariance (population and sample)
$σxy = RandomVariable::populationCovariance($X, $Y);
$Sxy = RandomVariable::sampleCovariance($X, $Y);

// Correlation coefficient (population and sample)
$ρxy = RandomVariable::populationCorrelationCoefficient($X, $Y);
$rxy = RandomVariable::sampleCorrelationCoefficient($X, $Y);

// Central moment (nth moment)
$second_central_moment = RandomVariable::centralMoment($X, 2);
$third_central_moment  = RandomVariable::centralMoment($X, 3);

// Skewness (population and sample)
$skewness = RandomVariable::populationSkewness($X);  // Similar to Excel's SKEW.P
$skewness = RandomVariable::sampleSkewness($X);      // Similar to Excel's SKEW
// Alernative method of calculating skewness
$skewness = RandomVariable::skewness($X);

// Kurtosis (excess)
$kurtosis    = RandomVariable::kurtosis($X);
$platykurtic = RandomVariable::isPlatykurtic($X); // true if kurtosis is less than zero
$leptokurtic = RandomVariable::isLeptokurtic($X); // true if kurtosis is greater than zero
$mesokurtic  = RandomVariable::isMesokurtic($X);  // true if kurtosis is zero

// Standard error of the mean (SEM)
$sem = RandomVariable::standardErrorOfTheMean($X); // same as sem
$sem = RandomVariable::sem($X);                    // same as standardErrorOfTheMean

// Error function (Gauss error function)
$error = RandomVariable::errorFunction(2); // same as erf
$error = RandomVariable::erf(2);           // same as errorFunction

$error = RandomVariable::complementaryErrorFunction(2); // same as erfc
$error = RandomVariable::erfc(2);                       // same as complementaryErrorFunction
```

### Statistics - Regressions
```php
use Math\Statistics\Regression

$points = [[1,2], [2,3], [4,5], [5,7], [6,8]];

// Simple linear regression (least squares method)
$linear_regression = Regression::linear($points);
print_r($linear_regression);
/*
Array (
    [regression equation]          => y = 0.6046511627907 + 1.2209302325581x
    [slope]                        => 1.2209302325581
    [y intercept]                  => 0.6046511627907
    [correlation coefficient]      => 0.99304378406301
    [coefficient of determination] => 0.98613595706619
    [sample size]                  => 5
    [mean x]                       => 3.6
    [mean y]                       => 5
)
*/

// Evaluate for y for any x using linear regression slope and y intercept
$x           = 5;
$slope       = $linear_regression['slope'];
$y_intercept = $linear_regression['y intercept'];
$y           = Regression::linearEvaluate($x, $slope, $y_intercept);

// Power law regression - power curve (least squares fitting)
$power_regression = Regression::powerLaw($points);
print_r($power_regression);
/*
Array (
    [regression equation]          => y = 56.483375436574 * x^0.26415375648621
    [a]                            => 56.483375436574
    [b]                            => 0.26415375648621
    [mean x]                       => 68.642857142857
    [mean y]                       => 172.35714285714
    [sample size]                  => 14
    [correlation coefficient]      => 0.78831908026071
    [coefficient of determination] => 0.62144697230309
)
*/

// Evaluate for y for any x using power law regression a and b
$x = 83;
$a = $power_regression['a'];
$b = $power_regression['b'];
$y = Regression::powerLawEvaluate($x, $a, $b);

// R - correlation coefficient
$R = Regression::r($points);                      // same as correlationCoefficient
$R = Regression::correlationCoefficient($points); // same as r

// R² - coefficient of determination
$R² = Regression::r2($points);                        // same as coefficientOfDetermination
$R² = Regression::coefficientOfDetermination($points) // same as r2
```

### Algebra
```php
use Math\Algebra;

// Greatest common divisor (GCD)
$gcd = Algebra::gcd(8, 12);

// Least common multiple (LCM)
$lcm = Algebra::lcm(5, 2);

// Factors of an integer
$factors = Algebra::factors(12); // returns [1, 2, 3, 4, 6, 12]
```

### Special Functions
```php
use Math\Functions\Special;

// Gamma function Γ(z)
$z = 4;
$Γ = Special::gamma($z);          // Lanczos approximation - same as gammaLanczos
$Γ = Special::gammaLanczos($z);   // Lanczos approximation - same as gamma
$Γ = Special::gammaStirling($z);  // Stirling approximation

// Sign function (also known as signum or sgn)
$x = 4;
$sign = Special::signum($x); // same as sgn
$sign = Special::sgn($x);    // same as signum
```

Unit Tests
----------

```bash
$ cd tests
$ phpunit
```

Standards
---------

Math PHP conforms to the following standards:

 * PSR-1 - Basic coding standard (http://www.php-fig.org/psr/psr-1/)
 * PSR-2 - Coding style guide (http://www.php-fig.org/psr/psr-2/)
 * PSR-4 - Autoloader (http://www.php-fig.org/psr/psr-4/)

License
-------

Math PHP is licensed under the MIT License.

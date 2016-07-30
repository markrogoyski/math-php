Math PHP
=====================

A modern math library for PHP.

Math PHP is a self-contained mathematics library in pure PHP with no external dependencies. It is actively under development and should be considered a work in progress.

Features
--------
 * Algebra
 * Functions
   - Map
   - Special Functions
 * Linear Algebra
   - Matrix
   - Vector
 * Numerical Analysis
 * Probability
     * Combinatorics
     * Distributions
         - Continuous
         - Discrete
     * Standard Normal Table (Z Table)
     * t Distribution Table
 * Statistics
     * Averages
     * Descriptive
     * Distributions
     * Random Variables
     * Regressions

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

### Functions - Map - Single Array
```php
use Math\Functions\Map\Single

$x = [1, 2, 3, 4];

$sums        = Single::add($x, 2);      // [3, 4, 5, 6]
$differences = Single::subtract($x, 1); // [0, 1, 2, 3]
$products    = Single::multiply($x, 5); // [5, 10, 15, 20]
$quotients   = Single::divide($x, 2);   // [0.5, 1, 1.5, 2]
$x²          = Single::square($x);      // [1, 4, 9, 16]
$x³          = Single::cube($x);        // [1, 8, 27, 64]
$x⁴          = Single::pow($x, 4);      // [1, 16, 81, 256]
$√x          = Single::sqrt($x);        // [1, 1.414, 1.732, 2]
$∣x∣         = Single::abs($x);
```

### Functions - Map - Multiple Arrays
```php
use Math\Functions\Map\Multi

$x = [10, 10, 10, 10];
$y = [1,   2,  5, 10];

// Map function against elements of two or more arrays, item by item (by item ...)
$sums        = Multi::add($x, $y);      // [11, 12, 15, 20]
$differences = Multi::subtract($x, $y); // [9, 8, 5, 0]
$products    = Multi::multiply($x, $y); // [10, 20, 50, 100]
$quotients   = Multi::divide($x, $y);   // [10, 5, 2, 1]
$maxes       = Multi::max($x, $y);      // [10, 10, 10, 10]
$mins        = Multi::mins($x, $y);     // [1, 2, 5, 10]

// All functions work on multiple arrays; not limited to just two
$x    = [10, 10, 10, 10];
$y    = [1,   2,  5, 10];
$z    = [4,   5,  6,  7];
$sums = Multi::add($x, $y, $z); // [15, 17, 21, 27]
```

### Functions - Special Functions
```php
use Math\Functions\Special;

// Gamma function Γ(z)
$z = 4;
$Γ = Special::gamma($z);          // Uses gamma definition for integers and half integers; uses Lanczos approximation for real numbers
$Γ = Special::gammaLanczos($z);   // Lanczos approximation
$Γ = Special::gammaStirling($z);  // Stirling approximation

// Incomplete gamma functions - γ(s,t), Γ(s,x)
list($x, $s) = [1, 2];
$γ = Special::lowerIncompleteGamma($x, $s); // same as γ
$γ = Special::γ($x, $s);                    // same as lowerIncompleteGamma
$Γ = Special::upperIncompleteGamma($x, $s);

// Beta function
list($x, $y) = [1, 2];
$β = Special::beta($x, $y);

// Incomplete beta functions
list($x, $a, $b) = [0.4, 2, 3];
$B  = Special::incompleteBeta($x, $a, $b);
$Iₓ = Special::regularizedIncompleteBeta($x, $a, $b);

// Error function (Gauss error function)
$error = Special::errorFunction(2);              // same as erf
$error = Special::erf(2);                        // same as errorFunction
$error = Special::complementaryErrorFunction(2); // same as erfc
$error = Special::erfc(2);                       // same as complementaryErrorFunction

// Sign function (also known as signum or sgn)
$x    = 4;
$sign = Special::signum($x); // same as sgn
$sign = Special::sgn($x);    // same as signum

// Logistic function (logistic sigmoid function)
$x₀ = 2; // x-value of the sigmoid's midpoint
$L  = 3; // the curve's maximum value
$k  = 4; // the steepness of the curve
$x  = 5;
$logistic = Special::logistic($x₀, $L, $k, $x);

// Double factorial
$n  = 6;
$n‼︎ = Special::doubleFactorial($n);

// Sigmoid function
$t = 2;
$sigmoid = Special::sigmoid($t);
```


### Linear Algebra - Matrix
```php
use Math\LinearAlgebra\Matrix;

$matrix = [
    [1, 2, 3],
    [4, 5, 6],
    [7, 8, 9],
];

// Matrix
$A = new Matrix($matrix);
$B = new Matrix($matrix);

// Basic matrix data
$array = $A->getMatrix();
$rows  = $A->getM();      // number of rows
$cols  = $A->getN();      // number of columns

// Basic matrix elements (zero-based indexing)
$row  = $A->getRow(2);
$col  = $A->getColumn(2);
$item = $A->get(2, 2);

// Row operations
list($mᵢ, $mⱼ, $k) = [1, 2, 5];
$R = $A->rowInterchange($mᵢ, $mⱼ);
$R = $A->rowMultiply($mᵢ, $k);     // Multiply row mᵢ by k
$R = $A->rowAdd($mᵢ, $mⱼ, $k);     // Add k * row mᵢ to row mⱼ
$R = $A->rowExclude($mᵢ);          // Exclude row $mᵢ

// Column operations
list($nᵢ, $nⱼ, $k) = [1, 2, 5];
$R = $A->columnInterchange($nᵢ, $nⱼ);
$R = $A->columnMultiply($nᵢ, $k);     // Multiply column nᵢ by k
$R = $A->columnAdd($nᵢ, $nⱼ, $k);     // Add k * column nᵢ to column nⱼ;
$R = $A->columnExclude($nᵢ);          // Exclude column $nᵢ

// Matrix operations - return a new Matrix
$A＋B = $A->add($B);
$A⊕B  = $A->directSum($B);
$A−B  = $A->subtract($B);
$AB   = $A->multiply($B);
$２A  = $A->scalarMultiply(2);
$A∘B  = $A->hadamardProduct($B);
$Aᵀ 　= $A->transpose();
$D  　= $A->diagonal();
$⟮A∣B⟯ = $A->augment($B);
$⟮A∣I⟯ = $A->augmentIdentity();  // Augment with the identity matrix

// Matrix operations - return a value
$tr⟮A⟯ = $A->trace();
$bool = $A->isSquare();
$‖A‖₁ = $A->oneNorm();
$‖A‖F = $A->frobeniusNorm();  // Hilbert–Schmidt norm
$‖A‖∞ = $A->infinityNorm();
$max  = $A->maxNorm();


// Map a function over each element of the Matrix
$func = function($x) {
    return $x * 2;
};
$R = $A->map($func);

// Print a matrix
print($A);
/*
 [1, 2, 3]
 [2, 3, 4]
 [3, 4, 5]
 */

// Static Matrix operations
list($m, $n)     = [4, 4];
$identity_matrix = Matrix::identity($n);
$zero_matrix     = Matrix::zero($m, $n);
$ones_matrix     = Matrix::one($m, $n);
```

### Linear Algebra - Vector
```php
use Math\LinearAlgebra\Vector;

$vector = [1, 2, 3];

// Vector
$A = new Vector($vector);
$B = new Vector($vector);

// Basic vector data
$array = $A->getVector();
$n     = $A->getN();      // number of elements

// Basic vector elements (zero-based indexing)
$item = $A->get(2);

// Vector operations - return a value
$sum    = $A->sum();
$A⋅B    = $A->dotProduct($B);    // same as innerProduct
$A⋅B    = $A->innerProduct($B);  // same as dotProduct
$l₁norm = $A->l1Norm();
$l²norm = $A->l2Norm();
$pnorm  = $A->pNorm();
$max    = $A->maxNorm();

// Vector operations - return a Matrix
$matrix = $A->outerProduct(new Vector([1, 2]));

// Print a vector
print($A); // [1, 2, 3]
```

### Numerical Analysis
```php
use Math\NumericalAnalysis

// Use Newton's Method to solve for a root of a polynomial
// f(x) = x⁴ + 8x³ -13x² -92x + 96
$f⟮x⟯ = function($x) {
    return $x**4 + 8 * $x**3 - 13 * $x**2 - 92 * $x + 96;
};
$args   = ['x'];   // Parameters to pass to callback function
$target = 0;       // Value of f(x) we a trying to solve for
$guess  = -4.1;    // Starting point
$tol    = 0.00001; // Tolerance; how close to the actual solution we would like
$x      = NewtonsMethod::solve($f⟮x⟯, $args, $target, $guess, $tol); // Solve for x where f(x) = $target

```

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

### Probability - Continuous Distributions
```php
use Math\Probability\Distribution\Continuous;

// Beta distribution
$α = 1; // shape parameter
$β = 1; // shape parameter
$x = 2;
$pdf = Beta::PDF($α, $β, $x);

// χ²-distribution (Chi-Squared)
$x = 1;
$k = 2; // degrees of freedom
$pdf = ChiSquared::PDF($x, $k);
$cdf = ChiSquared::CDF($x, $k);

// Exponential distribution 
$λ   = 1; // rate parameter
$x   = 2; // random variable
$pdf = Exponential::PDF($λ, $x);
$cdf = Exponential::CDF($λ, $x);

// F-distribution
$x  = 2;
$d₁ = 3; // degree of freedom v1
$d₂ = 4; // degree of freedom v2
$pdf = F::PDF($x, $d₁, $d₂);
$cdf = F::CDF($x, $d₁, $d₂);

// Laplace distribution
$μ = 1;   // location parameter
$b = 1.5; // scale parameter (diversity)
$x = 1;
$pdf = Laplace::PDF($μ, $b, $x);
$cdf = Laplace::CDF($μ, $b, $x);

// Logistic distribution
$μ = 2;   // location parameter
$s = 1.5; // scale parameter
$x = 3;
$pdf = Logistic::PDF($μ, $s, $x);
$cdf = Logistic::CDF($μ, $s, $x);

// Log-logistic distribution (Fisk distribution)
$α = 1; // scale parameter
$β = 1; // shape parameter
$x = 2;
$pdf = LogLogistic::PDF($α, $β, $x);
$cdf = LogLogistic::CDF($α, $β, $x);

// Log-normal distribution
list($μ, $σ, $x) = [6, 2, 4.3];
$pdf = LogNormal::PDF($x, $μ, $σ);
$cdf = LogNormal::CDF($x, $μ, $σ);

// Normal distribution
list($μ, $σ, $x) = [0, 1, 2];
$pdf = Normal::PDF($x, $μ, $σ);
$cdf = Normal::CDF($x₁, $μ, $σ);

// Pareto distribution
$a = 1; // shape parameter
$b = 1; // scale parameter
$x = 2;
$pdf = Pareto::PDF($a, $b, $x);
$cdf = Pareto::CDF($a, $b, $x);

// Standard normal distribution
$z = 2;
$pdf = StandardNormal::PDF($z);
$cdf = StandardNormal::CDF($z);

// Student's t-distribution
$x = 2;
$ν = 3; // degrees of freedom
$pdf = StudentT::PDF($x, $ν);
$cdf = StudentT::CDF($x, $ν);

// Uniform distribution
$a = 1; // lower boundary of the distribution
$b = 4; // upper boundary of the distribution
$x = 2;
$pdf = Uniform::PDF($a, $b, $x);
$cdf = Uniform::CDF($a, $b, $x);

// Weibull distribution
$k = 1; // shape parameter
$λ = 2; // scale parameter
$x = 2;
$pdf = Weibull::PDF($k, $λ, $x);
$cdf = Weibull::CDF($k, $λ, $x);
```

### Probability - Discrete Distributions
```php
use Math\Probability\Distribution\Discrete;

// Binomial distribution
$n = 2;   // number of events
$r = 1;   // number of successful events
$P = 0.5; // probability of success
$pmf = Binomial::PMF($n, $r, $P);
$cdf = Binomial::CDF($n, $r, $P);

// Bernoulli distribution (special case of binomial where n = 1)
$pmf = Bernoulli::PMF($r, $P);
$cdf = Bernoulli::CDF($r, $P);

// Geometric distribution (failures before the first success)
$k = 2;   // number of trials
$p = 0.5; // success probability
$pmf = Geometric::PMF($k, $p);
$cdf = Geometric::CDF($k, $p);

// Multinomial distribution
$frequencies   = [7, 2, 3];
$probabilities = [0.40, 0.35, 0.25];
$pmf = Multinomial::PMF($frequencies, $probabilities);

// Negative binomial distribution (Pascal)
$x = 2;   // number of trials required to produce r successes
$r = 1;   // number of successful events
$P = 0.5; // probability of success on an individual trial
$pmf = NegativeBinomial::PMF($x, $r, $P);  // same as Pascal::PMF
$pmf = Pascal::PMF($x, $r, $P);            // same as NegativeBinomial::PMF

// Poisson distribution
$k = 3; // events in the interval
$λ = 2; // average number of successful events per interval
$pmf = Poisson::PMF($k, $λ);
$cdf = Poisson::CDF($k, $λ);

// Shifted geometric distribution (probability to get one success)
$pmf = ShiftedGeometric::PMF($k, $p);
$cdf = ShiftedGeometric::CDF($k, $p);
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

// Get Z-score for confidence interval
$cl = 99; // confidence level
$z  = StandardNormalTable::getZScoreForConfidenceInterval($cl);
```

### Probability - t Distribution Table
```php
use Math\Probability\TDistributionTable;

// Get t critical value from degrees of freedom (ν) and confidence level (cl)
$ν       = 5;  // degrees of freedom
$cl      = 99; // confidence level
$t_value = TDistributionTable::getOneSidedTValueFromConfidenceLevel($ν, $cl);
$t_value = TDistributionTable::getTwoSidedTValueFromConfidenceLevel($ν, $cl);

// Get t critical value from degrees of freedom (ν) and alpha value (α)
$ν       = 5;     // degrees of freedom
$α       = 0.001; // alpha value
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
$averages = Average::getAverages($numbers);
print_r($averages);
/* Array (
    [mean]                => 15
    [median]              => 14
    [mode]                => Array ( [0] => 13 )
    [geometric_mean]      => 14.789726414533
    [harmonic_mean]       => 14.605077399381
    [contraharmonic_mean] => 15.474074074074
    [quadratic_mean]      => 15.235193176035
    [trimean]             => 14.5
    [iqm]                 => 14
    [cubic_mean]          => 15.492307432707
) */
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
$df = 5;                                         // degrees of freedom
$S² = Descriptive::variance($numbers, $df);      // can specify custom degrees of freedom

// Standard deviation
$σ = Descriptive::sd($numbers);                // same as standardDeviation; has optional parameter to set population or sample variance
$σ = Descriptive::standardDeviation($numbers); // same as sd; has optional parameter to set population or sample variance

// MAD - mean/median absolute deviations
$mean_mad   = Descriptive::meanAbsoluteDeviation($numbers);
$median_mad = Descriptive::medianAbsoluteDeviation($numbers);

// Quartiles (inclusive and exclusive methods)
// [0% => 13, Q1 => 13, Q2 => 14, Q3 => 17, 100% => 21, IQR => 4]
$quartiles = Descriptive::quartiles($numbers);          // Has optional parameter to specify method. Default is Exclusive
$quartiles = Descriptive::quartilesExclusive($numbers);
$quartiles = Descriptive::quartilesInclusive($numbers); 

// IQR - Interquartile range
$IQR = Descriptive::interquartileRange($numbers); // Same as IQR; has optional parameter to specify quartile method.
$IQR = Descriptive::IQR($numbers);                // Same as interquartileRange; has optional parameter to specify quartile method.

// Percentiles
$twentieth_percentile    = Descriptive::percentile($numbers, 20);
$ninety_fifth_percentile = Descriptive::percentile($numbers, 95);

// Midhinge
$midhinge = Descriptive::midhinge($numbers);

// Descriptive stats report
$stats = Descriptive::describe($numbers); // Has optional parameter to set population or sample calculations
print_r($stats);
/* Array (
    [n]          => 9
    [mean]       => 15
    [median]     => 14
    [mode]       => Array ( [0] => 13 )
    [range]      => 8
    [midrange]   => 17
    [variance]   => 8
    [sd]         => 2.8284271247462
    [mean_mad]   => 2.2222222222222
    [median_mad] => 1
    [quartiles]  => Array (
            [0%]   => 13
            [Q1]   => 13
            [Q2]   => 14
            [Q3]   => 17
            [100%] => 21
            [IQR]  => 4
        )
    [midhinge]   => 15
    [skewness]   => 1.4915533665654
    [kurtosis]   => 0.1728515625
    [sem]        => 0.94280904158206
    [ci_95]      => Array (
            [ci]          => 1.8478680091392
            [lower_bound] => 13.152131990861
            [upper_bound] => 16.847868009139
        )
    [ci_99]      => Array (
            [ci]          => 2.4285158135783
            [lower_bound] => 12.571484186422
            [upper_bound] => 17.428515813578
        )
) */
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

$X = [1, 2, 3, 4];
$Y = [2, 3, 4, 5];

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
$skewness = RandomVariable::skewness($X);            // general method of calculating skewness
$skewness = RandomVariable::populationSkewness($X);  // similar to Excel's SKEW.P
$skewness = RandomVariable::sampleSkewness($X);      // similar to Excel's SKEW
$SES      = RandomVariable::SES(count($X));          // standard error of skewness

// Kurtosis (excess)
$kurtosis    = RandomVariable::kurtosis($X);
$platykurtic = RandomVariable::isPlatykurtic($X); // true if kurtosis is less than zero
$leptokurtic = RandomVariable::isLeptokurtic($X); // true if kurtosis is greater than zero
$mesokurtic  = RandomVariable::isMesokurtic($X);  // true if kurtosis is zero
$SEK         = RandomVariable::SEK(count($X));    // standard error of kurtosis

// Standard error of the mean (SEM)
$sem = RandomVariable::standardErrorOfTheMean($X); // same as sem
$sem = RandomVariable::sem($X);                    // same as standardErrorOfTheMean

// Confidence interval
$μ  = 90; // sample mean
$n  = 9;  // sample size
$σ  = 36; // standard deviation
$cl = 99; // confidence level
$ci = RandomVariable::confidenceInterval($μ, $n, $σ, $cl); // Array( [ci] => 30.91, [lower_bound] => 59.09, [upper_bound] => 120.91 )

// Z score
$μ = 8; // mean
$σ = 1; // standard deviation
$x = 7;
$z = RandomVariable::zScore($μ, $σ, $x); 
```

### Statistics - Regressions
```php
use Math\Statistics\Regression

$points = [[1,2], [2,3], [4,5], [5,7], [6,8]];

// Simple linear regression (least squares method)
$regression = new Linear($points);
$parameters = $regression->getParameters();          // [m => 1.2209302325581, b => 0.6046511627907]
$equation   = $regression->getEquation();            // y = 1.2209302325581x + 0.6046511627907
$y          = $regression->evaluate(5);              // Evaluate for y at x = 5 using regression equation
$Ŷ          = $regression->yHat();
$SSreg      = $regression->sumOfSquaresRegression();
$SSres      = $regression->sumOfSquaresResidual();
$r          = $regression->r();                      // same as correlationCoefficient
$r²         = $regression->r2();                     // same as coefficientOfDetermination
$n          = $regression->getSampleSize();          // 5
$points     = $regression->getPoints();              // [[1,2], [2,3], [4,5], [5,7], [6,8]]
$xs         = $regression->getXs();                  // [1, 2, 4, 5, 6]
$yx         = $regression->getYs();                  // [2, 3, 5, 7, 8]

// Linear regression through a fixed point (least squares method)
$force_point = [0,0];
$regression  = new LinearThroughPoint($points, $force_point);
$parameters  = $regression->getParameters();
$equation    = $regression->getEquation();
$y           = $regression->evaluate(5);
$Ŷ           = $regression->yHat();
$SSreg       = $regression->sumOfSquaresRegression();
$SSres       = $regression->sumOfSquaresResidual();
$r           = $regression->r();
$r²          = $regression->r2();
$n           = $regression->getSampleSize();
$points      = $regression->getPoints();
$xs          = $regression->getXs();
$yx          = $regression->getYs();

// Power law regression - power curve (least squares fitting)
$regression = new PowerLaw($points);
$parameters = $regression->getParameters();          // [a => 56.483375436574, b => 0.26415375648621]
$equation   = $regression->getEquation();            // y = 56.483375436574x^0.26415375648621
$y          = $regression->evaluate(5);
$Ŷ          = $regression->yHat();
$SSreg      = $regression->sumOfSquaresRegression();
$SSres      = $regression->sumOfSquaresResidual();
$R          = $regression->r();
$R²         = $regression->r2();
$n          = $regression->getSampleSize();
$points     = $regression->getPoints();
$xs         = $regression->getXs();
$yx         = $regression->getYs();
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

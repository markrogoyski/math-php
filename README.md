Math PHP
=====================

### Powerful Modern Math Library for PHP

Math PHP is the only library you need to integrate mathematical functions into your applications. It is a self-contained library in pure PHP with no external dependencies.

It is actively under development with development (0.y.z) releases.

Features
--------
 * [Algebra](#algebra)
 * [Finance](#finance)
 * Functions
   - [Map](#functions---map---single-array)
   - [Special Functions](#functions---special-functions)
 * Information Theory
   - [Entropy](#information-theory---entropy)
 * Linear Algebra
   - [Matrix](#linear-algebra---matrix)
   - [Vector](#linear-algebra---vector)
 * Numerical Analysis
   - [Interpolation](#numerical-analysis---interpolation)
   - [Numerical Differentiation](#numerical-analysis---numerical-differentiation)
   - [Numerical Integration](#numerical-analysis---numerical-integration)
   - [Root Finding](#numerical-analysis---root-finding)
 * Probability
     - [Combinatorics](#probability---combinatorics)
     - Distributions
         * [Continuous](#probability---continuous-distributions)
         * [Discrete](#probability---discrete-distributions)
         * [Tables](#probability---distribution-tables)
 * Sequences
     - [Basic](#sequences---basic)
     - [Advanced](#sequences---advanced)
 * [Set Theory](#set-theory)
 * Statistics
     - [ANOVA](#statistics---anova)
     - [Averages](#statistics---averages)
     - [Correlation](#statistics---correlation)
     - [Descriptive](#statistics---descriptive)
     - [Distance and Divergence](#statistics---distance-and-divergence)
     - [Distributions](#statistics---distributions)
     - [Effect Size](#statistics---effect-size)
     - [Experiments](#statistics---experiments)
     - [Random Variables](#statistics---random-variables)
     - [Regressions](#statistics---regressions)
     - [Significance Testing](#statistics---significance-testing)

Setup
-----

 Add the library to your `composer.json` file in your project:

```javascript
{
  "require": {
      "markrogoyski/math-php": "0.*"
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
use MathPHP\Algebra;

// Greatest common divisor (GCD)
$gcd = Algebra::gcd(8, 12);

// Extended greatest common divisor - gcd(a, b) = a*a' + b*b'
$gcd = Algebra::extendedGCD(12, 8); // returns array [gcd, a', b']

// Least common multiple (LCM)
$lcm = Algebra::lcm(5, 2);

// Factors of an integer
$factors = Algebra::factors(12); // returns [1, 2, 3, 4, 6, 12]
```

### Finance
```php
use MathPHP\Finance;

// Financial payment for a loan or anuity with compound interest
$rate          = 0.035 / 12; // 3.5% interest paid at the end of every month
$periods       = 30 * 12;    // 30-year mortgage
$present_value = 265000;     // Mortgage note of $265,000.00
$future_value  = 0;
$beginning     = false;      // Adjust the payment to the beginning or end of the period
$pmt           = Finance::pmt($rate, $periods, $present_value, $future_value, $beginning);

// Annual Equivalent Rate (AER) of an annual percentage rate (APR)
$nominal = 0.035; // APR 3.5% interest
$periods = 12;    // Compounded monthly
$aer     = Finance::aer($nominal, $periods);

// Future value for a loan or anuity with compound interest
$payment = 1189.97;
$fv      = Finance::fv($rate, $periods, $payment, $present_value, $beginning)

// Present value for a loan or anuity with compound interest
$pv = Finance::pv($rate, $periods, $payment, $future_value, $beginning)
```

### Functions - Map - Single Array
```php
use MathPHP\Functions\Map\Single;

$x = [1, 2, 3, 4];

$sums        = Single::add($x, 2);      // [3, 4, 5, 6]
$differences = Single::subtract($x, 1); // [0, 1, 2, 3]
$products    = Single::multiply($x, 5); // [5, 10, 15, 20]
$quotients   = Single::divide($x, 2);   // [0.5, 1, 1.5, 2]
$x²          = Single::square($x);      // [1, 4, 9, 16]
$x³          = Single::cube($x);        // [1, 8, 27, 64]
$x⁴          = Single::pow($x, 4);      // [1, 16, 81, 256]
$√x          = Single::sqrt($x);        // [1, 1.414, 1.732, 2]
$∣x∣         = Single::abs($x);         // [1, 2, 3, 4]
$maxes       = Single::max($x, 3);      // [3, 3, 3, 4]
$mins        = Single::min($x, 3);      // [1, 2, 3, 3]
```

### Functions - Map - Multiple Arrays
```php
use MathPHP\Functions\Map\Multi;

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
use MathPHP\Functions\Special;

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

// Hypergeometric functions
$pFq = Special::generalizedHypergeometric($p, $q, $a, $b, $c, $z);
$₁F₁ = Special::confluentHypergeometric($a, $b, $z);
$₂F₁ = Special::hypergeometric($a, $b, $c, $z);

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

// Sigmoid function
$t = 2;
$sigmoid = Special::sigmoid($t);

// Softmax function
$𝐳    = [1, 2, 3, 4, 1, 2, 3];
$σ⟮𝐳⟯ⱼ = Special::softmax($𝐳);
```

### Information Theory - Entropy
```php
use MathPHP\InformationTheory\Entropy;

// Probability distributions
$p = [0.2, 0.5, 0.3];
$q = [0.1, 0.4, 0.5];

// Shannon entropy
$bits  = Entropy::shannonEntropy($p);         // log₂
$nats  = Entropy::shannonNatEntropy($p);      // ln
$harts = Entropy::shannonHartleyEntropy($p);  // log₁₀

// Cross entropy
$H⟮p、q⟯ = Entropy::crossEntropy($p, $q);       // log₂

// Joint entropy
$P⟮x、y⟯ = [1/2, 1/4, 1/4, 0];
H⟮x、y⟯ = Entropy::jointEntropy($P⟮x、y⟯);        // log₂

// Rényi entropy
$α    = 0.5;
$Hₐ⟮X⟯ = Entropy::renyiEntropy($p, $α);         // log₂

// Perplexity
$perplexity = Entropy::perplexity($p);         // log₂
```

### Linear Algebra - Matrix
```php
use MathPHP\LinearAlgebra\Matrix;

$matrix = [
    [1, 2, 3],
    [4, 5, 6],
    [7, 8, 9],
];

// Matrix factory creates most appropriate matrix
$A = MatrixFactory::create($matrix);
$B = MatrixFactory::create($matrix);

// Matrix factory can create a matrix from an array of column vectors
use MathPHP\LinearAlgebra\Vector;
$X₁ = new Vector([1, 4, 7]);
$X₂ = new Vector([2, 5, 8]);
$X₃ = new Vector([3, 6, 9]);
$C  = MatrixFactory::create([$X₁, $X₂, $X₃]);

// Can also directly instantiate desired matrix class
$A = new Matrix($matrix);
$B = new SquareMatrix($matrix);

// Basic matrix data
$array = $A->getMatrix();
$rows  = $A->getM();      // number of rows
$cols  = $A->getN();      // number of columns

// Basic matrix elements (zero-based indexing)
$row  = $A->getRow(2);
$col  = $A->getColumn(2);
$item = $A->get(2, 2);

// Other representations of matrix data
$vectors = $A->asVectors();           // array of column vectors
$D       = $A->getDiagonalElements(); // array of the diagonal elements

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
$R = $A->columnAdd($nᵢ, $nⱼ, $k);     // Add k * column nᵢ to column nⱼ
$R = $A->columnExclude($nᵢ);          // Exclude column $nᵢ

// Matrix operations - return a new Matrix
$A＋B = $A->add($B);
$A⊕B  = $A->directSum($B);
$A−B  = $A->subtract($B);
$AB   = $A->multiply($B);
$２A  = $A->scalarMultiply(2);
$A／2 = $A->scalarDivide(2);
$A∘B  = $A->hadamardProduct($B);
$A⊗B  = $A->kroneckerProduct($B);
$Aᵀ 　= $A->transpose();
$D  　= $A->diagonal();
$⟮A∣B⟯ = $A->augment($B);
$⟮A∣I⟯ = $A->augmentIdentity();  // Augment with the identity matrix
$⟮A∣B⟯ = $A->augmentBelow($B);
$rref = $A->rref();             // Reduced row echelon form
$A⁻¹  = $A->inverse();
$Mᵢⱼ  = $A->minorMatrix($mᵢ, $nⱼ); // Square matrix with row mᵢ and column nⱼ removed
$CM   = $A->cofactorMatrix();
$B    = $A->meanDeviation();
$S    = $A->covarianceMatrix();

// Matrix operations - return a new Vector
$AB = $A->vectorMultiply($X₁);
$M  = $A->sampleMean();

// Matrix operations - return a value
$tr⟮A⟯ = $A->trace();
$|A|  = $a->det();              // Determinant
$Mᵢⱼ  = $A->minor($mᵢ, $nⱼ);    // First minor
$Cᵢⱼ  = $A->cofactor($mᵢ, $nⱼ);

// Matrix norms - return a value
$‖A‖₁ = $A->oneNorm();
$‖A‖F = $A->frobeniusNorm(); // Hilbert–Schmidt norm
$‖A‖∞ = $A->infinityNorm();
$max  = $A->maxNorm();

// Matrix properties - return a bool
$bool = $A->isSquare();
$bool = $A->isSymmetric();

// Matrix decomposition
$PLU = $A->LUDecomposition(); // returns array of Matrices [L, U, P, A]; P is permutation matrix

// Solve a linear system of equations: Ax = b
$b = new Vector(1, 2, 3);
$x = $A->solve($b);

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

// Specialized matrices
list($m, $n, $k)     = [4, 4, 2];
$identity_matrix = MatrixFactory::identity($n);    // Ones on the main diagonal
$zero_matrix     = MatrixFactory::zero($m, $n);    // All zeros
$ones_matrix     = MatrixFactory::one($m, $n);     // All ones
$eye_matrix      = MatrixFactory::eye($m, $n, $k); // Ones (or other value) on the k-th diagonal

// Vandermonde matrix
$V = MatrixFactory::create([1, 2, 3], 4); // 4 x 3 Vandermonde matrix
$V = new VandermondeMatrix([1, 2, 3], 4); // Same as using MatrixFactory

// Diagonal matrix
$D = MatrixFactory::create([1, 2, 3]); // 3 x 3 diagonal matrix with zeros above and below the diagonal
$D = new DiagonalMatrix([1, 2, 3]);    // Same as using MatrixFactory

// PHP Predefined Interfaces
$json = json_encode($A); // JsonSerializable
$Aᵢⱼ  = $A[$mᵢ][$nⱼ];    // ArrayAccess
```

### Linear Algebra - Vector
```php
use MathPHP\LinearAlgebra\Vector;

// Vector
$A = new Vector([1, 2]);
$B = new Vector([2, 4]);

// Basic vector data
$array = $A->getVector();
$n     = $A->getN();           // number of elements
$M     = $A->asColumnMatrix(); // Vector as an nx1 matrix
$M     = $A->asRowMatrix();    // Vector as a 1xn matrix

// Basic vector elements (zero-based indexing)
$item = $A->get(1);

// Vector operations - return a value
$sum  = $A->sum();
$│A│  = $A->length();           // same as l2Norm
$A⋅B  = $A->dotProduct($B);     // same as innerProduct
$A⋅B  = $A->innerProduct($B);   // same as dotProduct
$A⊥⋅B = $A->perpDotProduct($B);

// Vector operations - return a Vector or Matrix
$kA    = $A->scalarMultiply($k);
$A＋B  = $A->add($B);
$A−B   = $A->subtract($B);
$A／k  = $A->scalarDivide($k);
$A⨂B  = $A->outerProduct($B);
$AxB   = $A->crossProduct($B);
$AB    = $A->directProduct($B);
$Â     = $A->normalize();
$A⊥    = $A->perpendicular();
$projᵇA = $A->projection($B);   // projection of A onto B
$perpᵇA = $A->perp($B);         // perpendicular of A on B

// Vector norms - return a value
$l₁norm = $A->l1Norm();
$l²norm = $A->l2Norm();
$pnorm  = $A->pNorm();
$max    = $A->maxNorm();

// Print a vector
print($A); // [1, 2]

// PHP Predefined Interfaces
$n    = count($A);       // Countable
$json = json_encode($A); // JsonSerializable
$Aᵢ   = $A[$i];          // ArrayAccess
```

### Numerical Analysis - Interpolation
```php
use MathPHP\NumericalAnalysis\Interpolation;

// Interpolation is a method of constructing new data points with the range
// of a discrete set of known data points.
// Each integration method can take input in two ways:
//  1) As a set of points (inputs and outputs of a function)
//  2) As a callback function, and the number of function evaluations to
//     perform on an interval between a start and end point.

// Input as a set of points
$points = [[0, 1], [1, 4], [2, 9], [3, 16]];

// Input as a callback function
$f⟮x⟯ = function ($x) {
    return $x**2 + 2 * $x + 1;
};
list($start, $end, $n) = [0, 3, 4];

// Lagrange Polynomial
// Returns a function p(x) of x
$p = Interpolation\LagrangePolynomial::interpolate($points);                // input as a set of points
$p = Interpolation\LagrangePolynomial::interpolate($f⟮x⟯, $start, $end, $n); // input as a callback function

$p(0) // 1
$p(3) // 16

// Nevilles Method
// More accurate than Lagrange Polynomial Interpolation given the same input
// Returns the evaluation of the interpolating polynomial at the $target point
$target = 2;
$result = Interpolation\NevillesMethod::interpolate($target, $points);                // input as a set of points
$result = Interpolation\NevillesMethod::interpolate($target, $f⟮x⟯, $start, $end, $n); // input as a callback function

// Newton Polynomial (Forward)
// Returns a function p(x) of x
$p = Interpolation\NewtonPolynomialForward::interpolate($points);                // input as a set of points
$p = Interpolation\NewtonPolynomialForward::interpolate($f⟮x⟯, $start, $end, $n); // input as a callback function

$p(0) // 1
$p(3) // 16

// Natural Cubic Spline
// Returns a piecewise polynomial p(x)
$p = Interpolation\NaturalCubicSpline::interpolate($points);                // input as a set of points
$p = Interpolation\NaturalCubicSpline::interpolate($f⟮x⟯, $start, $end, $n); // input as a callback function

$p(0) // 1
$p(3) // 16

// Clamped Cubic Spline
// Returns a piecewise polynomial p(x)

// Input as a set of points
$points = [[0, 1, 0], [1, 4, -1], [2, 9, 4], [3, 16, 0]];

// Input as a callback function
$f⟮x⟯ = function ($x) {
    return $x**2 + 2 * $x + 1;
};
$f’⟮x⟯ = function ($x) {
    return 2*$x + 2;
};
list($start, $end, $n) = [0, 3, 4];

$p = Interpolation\ClampedCubicSpline::interpolate($points);                // input as a set of points
$p = Interpolation\ClampedCubicSpline::interpolate($f⟮x⟯, $f’⟮x⟯, $start, $end, $n); // input as a callback function

$p(0) // 1
$p(3) // 16
```

### Numerical Analysis - Numerical Differentiation
```php
use MathPHP\NumericalAnalysis\NumericalDifferentiation;

// Numerical Differentiation approximates the derivative of a function.
// Each Differentiation method can take input in two ways:
//  1) As a set of points (inputs and outputs of a function)
//  2) As a callback function, and the number of function evaluations to
//     perform on an interval between a start and end point.

// Input as a callback function
$f⟮x⟯ = function ($x) {
    return $x**2 + 2 * $x + 1;
};

// Three Point Formula
// Returns an approximation for the derivative of our input at our target

// Input as a set of points
$points = [[0, 1], [1, 4], [2, 9]];

$target = 0;
list($start, $end, $n) = [0, 2, 3];
$derivative = NumericalDifferentiation\ThreePointFormula::differentiate($target, $points);                // input as a set of points
$derivative = NumericalDifferentiation\ThreePointFormula::differentiate($target, $f⟮x⟯, $start, $end, $n); // input as a callback function

// Five Point Formula
// Returns an approximation for the derivative of our input at our target

// Input as a set of points
$points = [[0, 1], [1, 4], [2, 9], [3, 16], [4, 25]];

$target = 0;
list($start, $end, $n) = [0, 4, 5];
$derivative = NumericalDifferentiation\FivePointFormula::differentiate($target, $points);                // input as a set of points
$derivative = NumericalDifferentiation\FivePointFormula::differentiate($target, $f⟮x⟯, $start, $end, $n); // input as a callback function

// Second Derivative Midpoint Formula
// Returns an approximation for the second derivative of our input at our target

// Input as a set of points
$points = [[0, 1], [1, 4], [2, 9];

$target = 1;
list($start, $end, $n) = [0, 2, 3];
$derivative = NumericalDifferentiation\SecondDerivativeMidpointFormula::differentiate($target, $points);                // input as a set of points
$derivative = NumericalDifferentiation\SecondDerivativeMidpointFormula::differentiate($target, $f⟮x⟯, $start, $end, $n); // input as a callback function
```

### Numerical Analysis - Numerical Integration
```php
use MathPHP\NumericalAnalysis\NumericalIntegration;

// Numerical integration approximates the definite integral of a function.
// Each integration method can take input in two ways:
//  1) As a set of points (inputs and outputs of a function)
//  2) As a callback function, and the number of function evaluations to
//     perform on an interval between a start and end point.

// Trapezoidal Rule (closed Newton-Cotes formula)
$points = [[0, 1], [1, 4], [2, 9], [3, 16]];
$∫f⟮x⟯dx = NumericalIntegration\TrapezoidalRule::approximate($points); // input as a set of points

$f⟮x⟯ = function ($x) {
    return $x**2 + 2 * $x + 1;
};
list($start, $end, $n) = [0, 3, 4];
$∫f⟮x⟯dx = NumericalIntegration\TrapezoidalRule::approximate($f⟮x⟯, $start, $end, $n); // input as a callback function

// Simpsons Rule (closed Newton-Cotes formula)
$points = [[0, 1], [1, 4], [2, 9], [3, 16], [4,3]];
$∫f⟮x⟯dx = NumericalIntegration\SimpsonsRule::approximate($points); // input as a set of points

$f⟮x⟯ = function ($x) {
    return $x**2 + 2 * $x + 1;
};
list($start, $end, $n) = [0, 3, 5];
$∫f⟮x⟯dx = NumericalIntegration\SimpsonsRule::approximate($f⟮x⟯, $start, $end, $n); // input as a callback function

// Simpsons 3/8 Rule (closed Newton-Cotes formula)
$points = [[0, 1], [1, 4], [2, 9], [3, 16]];
$∫f⟮x⟯dx = NumericalIntegration\SimpsonsThreeEighthsRule::approximate($points); // input as a set of points

$f⟮x⟯ = function ($x) {
    return $x**2 + 2 * $x + 1;
};
list($start, $end, $n) = [0, 3, 5];
$∫f⟮x⟯dx = NumericalIntegration\SimpsonsThreeEighthsRule::approximate($f⟮x⟯, $start, $end, $n); // input as a callback function

// Booles Rule (closed Newton-Cotes formula)
$points = [[0, 1], [1, 4], [2, 9], [3, 16], [4, 25]];
$∫f⟮x⟯dx = NumericalIntegration\BoolesRule::approximate($points); // input as a set of points

$f⟮x⟯ = function ($x) {
    return $x**3 + 2 * $x + 1;
};
list($start, $end, $n) = [0, 4, 5];
$∫f⟮x⟯dx = NumericalIntegration\BoolesRuleRule::approximate($f⟮x⟯, $start, $end, $n); // input as a callback function

// Rectangle Method (open Newton-Cotes formula)
$points = [[0, 1], [1, 4], [2, 9], [3, 16]];
$∫f⟮x⟯dx = NumericalIntegration\RectangleMethod::approximate($points); // input as a set of points

$f⟮x⟯ = function ($x) {
    return $x**2 + 2 * $x + 1;
};
list($start, $end, $n) = [0, 3, 4];
$∫f⟮x⟯dx = NumericalIntegration\RectangleMethod::approximate($f⟮x⟯, $start, $end, $n); // input as a callback function

// Midpoint Rule (open Newton-Cotes formula)
$points = [[0, 1], [1, 4], [2, 9], [3, 16]];
$∫f⟮x⟯dx = NumericalIntegration\MidpointRule::approximate($points); // input as a set of points

$f⟮x⟯ = function ($x) {
    return $x**2 + 2 * $x + 1;
};
list($start, $end, $n) = [0, 3, 4];
$∫f⟮x⟯dx = NumericalIntegration\MidpointRule::approximate($f⟮x⟯, $start, $end, $n); // input as a callback function
```

### Numerical Analysis - Root Finding
```php
use MathPHP\NumericalAnalysis\RootFinding;

// Root-finding methods solve for a root of a polynomial.

// f(x) = x⁴ + 8x³ -13x² -92x + 96
$f⟮x⟯ = function($x) {
    return $x**4 + 8 * $x**3 - 13 * $x**2 - 92 * $x + 96;
};

// Newton's Method
$args     = [-4.1];  // Parameters to pass to callback function (initial guess, other parameters)
$target   = 0;       // Value of f(x) we a trying to solve for
$tol      = 0.00001; // Tolerance; how close to the actual solution we would like
$position = 0;       // Which element in the $args array will be changed; also serves as initial guess. Defaults to 0.
$x        = RootFinding\NewtonsMethod::solve($f⟮x⟯, $args, $target, $tol, $position); // Solve for x where f(x) = $target

// Secant Method
$p₀  = -1;      // First initial approximation
$p₁  = 2;       // Second initial approximation
$tol = 0.00001; // Tolerance; how close to the actual solution we would like
$x   = RootFinding\SecantMethod::solve($f⟮x⟯, $p₀, $p₁, $tol); // Solve for x where f(x) = 0

// Bisection Method
$a   = 2;       // The start of the interval which contains a root
$b   = 5;       // The end of the interval which contains a root
$tol = 0.00001; // Tolerance; how close to the actual solution we would like
$x   = RootFinding\BisectionMethod::solve($f⟮x⟯, $a, $b, $tol); // Solve for x where f(x) = 0

// Fixed-Point Iteration
// f(x) = x⁴ + 8x³ -13x² -92x + 96
// Rewrite f(x) = 0 as (x⁴ + 8x³ -13x² + 96)/92 = x
// Thus, g(x) = (x⁴ + 8x³ -13x² + 96)/92
$g⟮x⟯ = function($x) {
    return ($x**4 + 8 * $x**3 - 13 * $x**2 + 96)/92;
};
$a   = 0;       // The start of the interval which contains a root
$b   = 2;       // The end of the interval which contains a root
$p   = 0;       // The initial guess for our root
$tol = 0.00001; // Tolerance; how close to the actual solution we would like
$x   = RootFinding\FixedPointIteration::solve($g⟮x⟯, $a, $b, $p, $tol); // Solve for x where f(x) = 0
```

### Probability - Combinatorics
```php
use MathPHP\Probability\Combinatorics;

list($n, $x, $k) = [10, 3, 4];

// Factorials
$n！  = Combinatorics::factorial($n);
$n‼︎   = Combinatorics::doubleFactorial($n);
$x⁽ⁿ⁾ = Combinatorics::risingFactorial($x, $n);
$x₍ᵢ₎ = Combinatorics::fallingFactorial($x, $n);
$！n  = Combinatorics::subfactorial($n);

// Permutations
$nPn = Combinatorics::permutations($n);     // Permutations of n things, taken n at a time (same as factorial)
$nPk = Combinatorics::permutations($n, $k); // Permutations of n things, taking only k of them

// Combinations
$nCk  = Combinatorics::combinations($n, $k);                            // n choose k without repetition
$nC′k = Combinatorics::combinations($n, $k, Combinatorics::REPETITION); // n choose k with repetition (REPETITION const = true)

// Central binomial coefficient
$cbc = Combinatorics::centralBinomialCoefficient($n);

// Catalan number
$Cn = Combinatorics::catalanNumber($n);

// Lah number
$L⟮n、k⟯ = Combinatorics::lahNumber($n, $k)

// Multinomial coefficient
$groups    = [5, 2, 3];
$divisions = Combinatorics::multinomial($groups);
```

### Probability - Continuous Distributions
```php
use MathPHP\Probability\Distribution\Continuous;

// Beta distribution
$α   = 1; // shape parameter
$β   = 1; // shape parameter
$x   = 2;
$pdf = Beta::PDF($α, $β, $x);
$cdf = Beta::CDF($α, $β, $x);
$μ   = Beta::mean($α, $β);

// Cauchy distribution
$x   = 1;
$x₀  = 2; // location parameter
$γ   = 3; // scale parameter
$pdf = Cauchy::PDF(x, x₀, γ);
$cdf = Cauchy::CDF(x, x₀, γ);

// χ²-distribution (Chi-Squared)
$x   = 1;
$k   = 2; // degrees of freedom
$pdf = ChiSquared::PDF($x, $k);
$cdf = ChiSquared::CDF($x, $k);

// Exponential distribution
$x   = 2; // random variable
$λ   = 1; // rate parameter
$pdf = Exponential::PDF($x, $λ);
$cdf = Exponential::CDF($x, $λ);
$μ   = Exponential::mean($λ);

// F-distribution
$x   = 2;
$d₁  = 3; // degree of freedom v1
$d₂  = 4; // degree of freedom v2
$pdf = F::PDF($x, $d₁, $d₂);
$cdf = F::CDF($x, $d₁, $d₂);
$μ   = F::mean($d₁, $d₂);

// Laplace distribution
$x   = 1;
$μ   = 1;   // location parameter
$b   = 1.5; // scale parameter (diversity)
$pdf = Laplace::PDF($x, $μ, $b);
$cdf = Laplace::CDF($x, $μ, $b);

// Logistic distribution
$x   = 3;
$μ   = 2;   // location parameter
$s   = 1.5; // scale parameter
$pdf = Logistic::PDF($x, $μ, $s);
$cdf = Logistic::CDF($x, $μ, $s);

// Log-logistic distribution (Fisk distribution)
$x   = 2;
$α   = 1; // scale parameter
$β   = 1; // shape parameter
$pdf = LogLogistic::PDF($x, $α, $β);
$cdf = LogLogistic::CDF($x, $α, $β);
$μ   = LogLogistic::mean($α, $β);

// Log-normal distribution
$x = 4.3;
$μ = 6;   // scale parameter
$σ = 2;   // location parameter
$pdf  = LogNormal::PDF($x, $μ, $σ);
$cdf  = LogNormal::CDF($x, $μ, $σ);
$mean = LogNormal::mean($μ, $σ);

// Normal distribution
list($x, $σ, $μ) = [2, 1, 0];
$pdf = Normal::PDF($x, $μ, $σ);
$cdf = Normal::CDF($x, $μ, $σ);

// Noncentral T distribution
list($x, $ν, $μ) = [8, 50, 10];
$pdf  = NoncentralT::PDF($x, $ν, $μ);
$cdf  = NoncentralT::CDF($x, $ν, $μ);
$mean = NoncentralT::mean($ν, $μ);

// Pareto distribution
$x   = 2;
$a   = 1; // shape parameter
$b   = 1; // scale parameter
$pdf = Pareto::PDF($x, $a, $b);
$cdf = Pareto::CDF($x, $a, $b);
$μ   = Pareto::mean($a, $b);

// Standard normal distribution
$z   = 2;
$pdf = StandardNormal::PDF($z);
$cdf = StandardNormal::CDF($z);

// Student's t-distribution
$x   = 2;
$ν   = 3;   // degrees of freedom
$p   = 0.4; // proportion of area
$pdf = StudentT::PDF($x, $ν);
$cdf = StudentT::CDF($x, $ν);
$t   = StudentT::inverse2Tails($p, $ν);  // t such that the area greater than t and the area beneath -t is p

// Uniform distribution
$a   = 1; // lower boundary of the distribution
$b   = 4; // upper boundary of the distribution
$x   = 2;
$pdf = Uniform::PDF($a, $b, $x);
$cdf = Uniform::CDF($a, $b, $x);
$μ   = Uniform::mean($a, $b);

// Weibull distribution
$x   = 2;
$k   = 1; // shape parameter
$λ   = 2; // scale parameter
$pdf = Weibull::PDF($x, $k, $λ);
$cdf = Weibull::CDF($x, $k, $λ);
$μ   = Weibull::mean($k, $λ);

// Other CDFs - All continuous distributions (...params will be distribution-specific)
// Replace 'DistributionName' with desired distribution.
$inv_cdf = DistributionName::inverse($target, ...$params);   // Inverse CDF of the distribution
$between = DistributionName::between($x₁, $x₂, ...$params);  // Probability of being between two points, x₁ and x₂
$outside = DistributionName::outside($x₁, $x₂, ...$params);  // Probability of being between below x₁ and above x₂
$above   = DistributionName::above($x, ...$params);          // Probability of being above x to ∞

// Random Number Generator
$random  = DistributionName::rand(...$params);               // A random number with a given distribution
```

### Probability - Discrete Distributions
```php
use MathPHP\Probability\Distribution\Discrete;

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
$k = 2;   // number of trials
$p = 0.5; // success probability
$pmf = ShiftedGeometric::PMF($k, $p);
$cdf = ShiftedGeometric::CDF($k, $p);
```

### Probability - Distribution Tables
```php
use MathPHP\Probability\Distribution\Table;

// Provided solely for completeness' sake.
// It is statistics tradition to provide these tables.
// Math PHP has dynamic distribution CDF functions you can use instead.

// Standard Normal Table (Z Table)
$table       = Table\StandardNormal::Z_SCORES;
$probability = $table[1.5][0];                 // Value for Z of 1.50

// t Distribution Tables
$table   = Table\TDistribution::ONE_SIDED_CONFIDENCE_LEVEL;
$table   = Table\TDistribution::TWO_SIDED_CONFIDENCE_LEVEL;
$ν       = 5;  // degrees of freedom
$cl      = 99; // confidence level
$t       = $table[$ν][$cl];

// t Distribution Tables
$table = Table\TDistribution::ONE_SIDED_ALPHA;
$table = Table\TDistribution::TWO_SIDED_ALPHA;
$ν     = 5;     // degrees of freedom
$α     = 0.001; // alpha value
$t     = $table[$ν][$α];

// χ² Distribution Table
$table = Table\ChiSquared::CHI_SQUARED_SCORES;
$df    = 2;    // degrees of freedom
$p     = 0.05; // P value
$χ²    = $table[$df][$p];
```

### Sequences - Basic
```php
use MathPHP\Sequence\Basic;

$n = 5; // Number of elements in the sequence

// Arithmetic progression
$d           = 2;  // Difference between the elements of the sequence
$a₁          = 1;  // Starting number for the sequence
$progression = Basic::arithmeticProgression($n, $d, $a₁);
// [1, 3, 5, 7, 9] - Indexed from 1

// Geometric progression (arⁿ⁻¹)
$a           = 2; // Scalar value
$r           = 3; // Common ratio
$progression = Basic::geometricProgression($n, $a, $r);
// [2(3)⁰, 2(3)¹, 2(3)², 2(3)³] = [2, 6, 18, 54] - Indexed from 1

// Square numbers (n²)
$squares = Basic::squareNumber($n);
// [0², 1², 2², 3², 4²] = [0, 1, 4, 9, 16] - Indexed from 0

// Cubic numbers (n³)
$cubes = Basic::cubicNumber($n);
// [0³, 1³, 2³, 3³, 4³] = [0, 1, 8, 27, 64] - Indexed from 0

// Powers of 2 (2ⁿ)
$po2 = Basic::powersOfTwo($n);
// [2⁰, 2¹, 2², 2³, 2⁴] = [1,  2,  4,  8,  16] - Indexed from 0

// Powers of 10 (10ⁿ)
$po10 = Basic::powersOfTen($n);
// [10⁰, 10¹, 10², 10³,  10⁴] = [1, 10, 100, 1000, 10000] - Indexed from 0

// Factorial (n!)
$fact = Basic::factorial($n);
// [0!, 1!, 2!, 3!, 4!] = [1,  1,  2,  6,  24] - Indexed from 0
```

### Sequences - Advanced
```php
use MathPHP\Sequence\Advanced;

$n = 6; // Number of elements in the sequence

// Fibonacci (Fᵢ = Fᵢ₋₁ + Fᵢ₋₂)
$fib = Advanced::fibonacci($n);
// [0, 1, 1, 2, 3, 5] - Indexed from 0

// Lucas numbers
$lucas = Advanced::lucasNumber($n);
// [2, 1, 3, 4, 7, 11] - Indexed from 0

// Pell numbers
$pell = Advanced::pellNumber($n);
// [0, 1, 2, 5, 12, 29] - Indexed from 0

// Triangular numbers (figurate number)
$triangles = Advanced::triangularNumber($n);
// [1, 3, 6, 10, 15, 21] - Indexed from 1

// Pentagonal numbers (figurate number)
$pentagons = Advanced::pentagonalNumber($n);
// [1, 5, 12, 22, 35, 51] - Indexed from 1

// Hexagonal numbers (figurate number)
$hexagons = Advanced::hexagonalNumber($n);
// [1, 6, 15, 28, 45, 66] - Indexed from 1

// Heptagonal numbers (figurate number)
$hexagons = Advanced::heptagonalNumber($n);
// [1, 4, 7, 13, 18, 27] - Indexed from 1

// Look-and-say sequence (describe the previous term!)
$look_and_say = Advanced::lookAndSay($n);
// ['1', '11', '21', '1211', '111221', '312211'] - Indexed from 1

// Lazy caterer's sequence (central polygonal numbers)
$lazy_caterer = Advanced::lazyCaterers($n);
// [1, 2, 4, 7, 11, 16] - Indexed from 0

// Magic squares series (magic constants; magic sums)
$magic_squares = Advanced::magicSquares($n);
// [0, 1, 5, 15, 34, 65] - Indexed from 0
```

### Set Theory
```php
use MathPHP\SetTheory\Set;
use MathPHP\SetTheory\ImmutableSet;

// Sets and immutable sets
$A = new Set([1, 2, 3]);          // Can add and remove members
$B = new ImmutableSet([3, 4, 5]); // Cannot modify set once created

// Basic set data
$set         = $A->asArray();
$cardinality = $A->length();
$bool        = $A->isEmpty();

// Set membership
$true = $A->isMember(2);
$true = $A->isNotMember(8);

// Add and remove members
$A->add(4);
$A->add(new Set(['a', 'b']));
$A->addMulti([5, 6, 7]);
$A->remove(7);
$A->removeMulti([5, 6]);
$A->clear();

// Set properties against other sets - return boolean
$bool = $A->isDisjoint($B);
$bool = $A->isSubset($B);         // A ⊆ B
$bool = $A->isProperSubset($B);   // A ⊆ B & A ≠ B
$bool = $A->isSuperset($B);       // A ⊇ B
$bool = $A->isProperSuperset($B); // A ⊇ B & A ≠ B

// Set operations with other sets - return a new Set
$A∪B  = $A->union($B);
$A∩B  = $A->intersect($B);
$A＼B = $A->difference($B);          // relative complement
$AΔB  = $A->symmetricDifference($B);
$A×B  = $A->cartesianProduct($B);

// Other set operations
$P⟮A⟯ = $A->powerSet();
$C   = $A->copy();

// Print a set
print($A); // Set{1, 2, 3, 4, Set{a, b}}

// PHP Interfaces
$n = count($A);                 // Countable
foreach ($A as $member) { ... } // Iterator

// Fluent interface
$A->add(5)->add(6)->remove(4)->addMulti([7, 8, 9]);
```

### Statistics - ANOVA
```php
use MathPHP\Statistics\ANOVA;

// One-way ANOVA
$sample1 = [1, 2, 3];
$sample2 = [3, 4, 5];
$sample3 = [5, 6, 7];
   ⋮            ⋮

$anova = ANOVA::oneWay($sample1, $sample2, $sample3);
print_r($anova);
/* Array (
    [ANOVA] => Array (             // ANOVA hypothesis test summary data
            [treatment] => Array (
                    [SS] => 24     // Sum of squares (between)
                    [df] => 2      // Degrees of freedom
                    [MS] => 12     // Mean squares
                    [F]  => 12     // Test statistic
                    [P]  => 0.008  // P value
                )
            [error] => Array (
                    [SS] => 6      // Sum of squares (within)
                    [df] => 6      // Degrees of freedom
                    [MS] => 1      // Mean squares
                )
            [total] => Array (
                    [SS] => 30     // Sum of squares (total)
                    [df] => 8      // Degrees of freedom
                )
        )
    [total_summary] => Array (     // Total summary data
            [n]        => 9
            [sum]      => 36
            [mean]     => 4
            [SS]       => 174
            [variance] => 3.75
            [sd]       => 1.9364916731037
            [sem]      => 0.6454972243679
        )
    [data_summary] => Array (      // Data summary (each input sample)
            [0] => Array ([n] => 3 [sum] => 6  [mean] => 2 [SS] => 14  [variance] => 1 [sd] => 1 [sem] => 0.57735026918963)
            [1] => Array ([n] => 3 [sum] => 12 [mean] => 4 [SS] => 50  [variance] => 1 [sd] => 1 [sem] => 0.57735026918963)
            [2] => Array ([n] => 3 [sum] => 18 [mean] => 6 [SS] => 110 [variance] => 1 [sd] => 1 [sem] => 0.57735026918963)
        )
) */

// Two-way ANOVA
/*        | Factor B₁ | Factor B₂ | Factor B₃ | ⋯
Factor A₁ |  4, 6, 8  |  6, 6, 9  |  8, 9, 13 | ⋯
Factor A₂ |  4, 8, 9  | 7, 10, 13 | 12, 14, 16| ⋯
    ⋮           ⋮           ⋮           ⋮         */
$factorA₁ = [
  [4, 6, 8],    // Factor B₁
  [6, 6, 9],    // Factor B₂
  [8, 9, 13],   // Factor B₃
];
$factorA₂ = [
  [4, 8, 9],    // Factor B₁
  [7, 10, 13],  // Factor B₂
  [12, 14, 16], // Factor B₃
];
       ⋮

$anova = ANOVA::twoWay($factorA₁, $factorA₂);
print_r($anova);
/* Array (
    [ANOVA] => Array (          // ANOVA hypothesis test summary data
            [factorA] => Array (
                    [SS] => 32                 // Sum of squares
                    [df] => 1                  // Degrees of freedom
                    [MS] => 32                 // Mean squares
                    [F]  => 5.6470588235294    // Test statistic
                    [P]  => 0.034994350619895  // P value
                )
            [factorB] => Array (
                    [SS] => 93                 // Sum of squares
                    [df] => 2                  // Degrees of freedom
                    [MS] => 46.5               // Mean squares
                    [F]  => 8.2058823529412    // Test statistic
                    [P]  => 0.0056767297582031 // P value
                )
            [interaction] => Array (
                    [SS] => 7                  // Sum of squares
                    [df] => 2                  // Degrees of freedom
                    [MS] => 3.5                // Mean squares
                    [F]  => 0.61764705882353   // Test statistic
                    [P]  => 0.5555023440712    // P value
                )
            [error] => Array (
                    [SS] => 68                 // Sum of squares (within)
                    [df] => 12                 // Degrees of freedom
                    [MS] => 5.6666666666667    // Mean squares
                )
            [total] => Array (
                    [SS] => 200                // Sum of squares (total)
                    [df] => 17                 // Degrees of freedom
                )
        )
    [total_summary] => Array (    // Total summary data
            [n]        => 18
            [sum]      => 162
            [mean]     => 9
            [SS]       => 1658
            [variance] => 11.764705882353
            [sd]       => 3.4299717028502
            [sem]      => 0.80845208345444
        )
    [summary_factorA]     => Array ( ... )   // Summary data of factor A
    [summary_factorB]     => Array ( ... )   // Summary data of factor B
    [summary_interaction] => Array ( ... )   // Summary data of interactions of factors A and B
) */
```

### Statistics - Averages
```php
use MathPHP\Statistics\Average;

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

// Moving averages
$n       = 3;
$weights = [3, 2, 1];
$SMA     = Average::simpleMovingAverage($numbers, $n);             // 3 n-point moving average
$CMA     = Average::cumulativeMovingAverage($numbers);
$WMA     = Average::weightedMovingAverage($numbers, $n, $weights);
$EPA     = Average::exponentialMovingAverage($numbers, $n);

// Means of two numbers
list($x, $y) = [24, 6];
$agm           = Average::arithmeticGeometricMean($x, $y); // same as agm
$agm           = Average::agm($x, $y);                     // same as arithmeticGeometricMean
$log_mean      = Average::logarithmicMean($x, $y);
$heronian_mean = Average::heronianMean($x, $y);
$identric_mean = Average::identricMean($x, $y);

// Averages report
$averages = Average::describe($numbers);
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

### Statistics - Correlation
```php
use MathPHP\Statistics\Correlation;

$X = [1, 2, 3, 4, 5];
$Y = [2, 3, 4, 4, 6];

// Covariance
$σxy = Correlation::covariance($X, $Y);  // Has optional parameter to set population (defaults to sample covariance)

// r - Pearson product-moment correlation coefficient (Pearson's r)
$r = Correlation::r($X, $Y);  // Has optional parameter to set population (defaults to sample correlation coefficient)

// R² - Coefficient of determination
$R² = Correlation::R2($X, $Y);  // Has optional parameter to set population (defaults to sample coefficient of determination)

// τ - Kendall rank correlation coefficient (Kendall's tau)
$τ = Correlation::kendallsTau($X, $Y);

// ρ - Spearman's rank correlation coefficient (Spearman's rho)
$ρ = Correlation::spearmansRho($X, $Y);

// Descriptive correlation report
$stats = Correlation::describe($X, $Y);
print_r($stats);
/* Array (
    [cov] => 2.25
    [r]   => 0.95940322360025
    [R2]  => 0.92045454545455
    [tau] => 0.94868329805051
    [rho] => 0.975
) */
```

### Statistics - Descriptive
```php
use MathPHP\Statistics\Descriptive;

$numbers = [13, 18, 13, 14, 13, 16, 14, 21, 13];

// Range and midrange
$range    = Descriptive::range($numbers);
$midrange = Descriptive::midrange($numbers);

// Variance (population and sample)
$σ² = Descriptive::populationVariance($numbers); // n degrees of freedom
$S² = Descriptive::sampleVariance($numbers);     // n - 1 degrees of freedom

// Variance (Custom degrees of freedom)
$df = 5;                                    // degrees of freedom
$S² = Descriptive::variance($numbers, $df); // can specify custom degrees of freedom

// Standard deviation (Uses population variance)
$σ = Descriptive::sd($numbers);                // same as standardDeviation;
$σ = Descriptive::standardDeviation($numbers); // same as sd;

// SD+ (Standard deviation for a sample; uses sample variance)
$SD＋ = Descriptive::sd($numbers, Descriptive::SAMPLE); // SAMPLE constant = true
$SD＋ = Descriptive::standardDeviation($numbers, true); // same as sd with SAMPLE constant

// Coefficient of variation (cᵥ)
$cᵥ = Descriptive::coefficientOfVariation($numbers);

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

// Describe a list of numbers - descriptive stats report
$stats = Descriptive::describe($numbers); // Has optional parameter to set population or sample calculations
print_r($stats);
/* Array (
    [n]          => 9
    [min]        => 13
    [max]        => 21
    [mean]       => 15
    [median]     => 14
    [mode]       => Array ( [0] => 13 )
    [range]      => 8
    [midrange]   => 17
    [variance]   => 8
    [sd]         => 2.8284271247462
    [cv]         => 0.18856180831641
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
    [ses]        => 0.71713716560064
    [kurtosis]   => 0.1728515625
    [sek]        => 1.3997084244475
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

// Five number summary - five most important sample percentiles
$summary = Descriptive::fiveNumberSummary($numbers);
// [min, Q1, median, Q3, max]
```

### Statistics - Distance and Divergence
```php
use MathPHP\Statistics\Distance;

// Probability distributions
$p = [0.2, 0.5, 0.3];
$q = [0.1, 0.4, 0.5];

// Distances
$DB⟮p、q⟯ = Distance::bhattacharyyaDistance($p, $q);
$H⟮p、q⟯  = Distance::hellingerDistance($p, $q)

// Divergences
$Dkl⟮P‖Q⟯ = Distance::kullbackLeiblerDivergence($p, $q);
$JSD⟮P‖Q⟯ = Distance::jensenShannonDivergence($p, $q);
```

### Statistics - Distributions
```php
use MathPHP\Statistics\Distribution;

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

### Statistics - Effect Size
```php
use MathPHP\Statistics\EffectSize;

$SSt = 24;  // Sum of squares treatment
$SSE = 300; // Sum of squares error
$SST = 600; // Sum of squares total
$dft = 1;   // Degrees of freedom treatment
$MSE = 18;  // Mean squares error

// η² - Eta-squared
$η²  = EffectSize::etaSquared($SSt, $SST);
$η²p = EffectSize::partialEtaSquared($SSt, $SSE);

// ω² - Omega-squared
$ω² = EffectSize::omegaSquared($SSt, $dft, $SST, $MSE);

// Cohen's ƒ²
$ƒ² = EffectSize::cohensF($η²);
$ƒ² = EffectSize::cohensF($ω²);
$ƒ² = EffectSize::cohensF($R²);

// Cohen's q
list($r₁, $r₂) = [0.1, 0.2];
$q = EffectSize::cohensQ($r₁, $r₂);

// Cohen's d
list($μ₁, $σ₁) = [6.7, 1.2];
list($μ₂, $σ₂) = [6, 1];
$d = EffectSize::cohensD($μ₁, $μ₂, $σ₁, $σ₂);

// Hedges' g
list($μ₁, $σ₁, $n₁) = [6.7, 1.2, 15];
list($μ₂, $σ₂, $n₂) = [6, 1, 15];
$g = EffectSize::hedgesG($μ₁, $μ₂, $σ₁, $σ₂, $n₁, $n₂);

// Glass' Δ
$Δ = EffectSize::glassDelta($μ₁, $μ₂, $σ₂);
```

### Statistics - Experiments
```php
use MathPHP\Statistics\Experiment;

$a = 28;   // Exposed and event present
$b = 129;  // Exposed and event absent
$c = 4;    // Non-exposed and event present
$d = 133;  // Non-exposed and event absent

// Risk ratio (relative risk) - RR
$RR = Experiment::riskRatio($a, $b, $c, $d);
// ['RR' => 6.1083, 'ci_lower_bound' => 2.1976, 'ci_upper_bound' => 16.9784, 'p' => 0.0005]

// Odds ratio (OR)
$OR = Experiment::oddsRatio($a, $b, $c, $d);
// ['OR' => 7.2171, 'ci_lower_bound' => 2.4624, 'ci_upper_bound' => 21.1522, 'p' => 0.0003]

// Likelihood ratios (positive and negative)
$LL = Experiment::likelihoodRatio($a, $b, $c, $d);
// ['LL+' => 7.4444, 'LL-' => 0.3626]

$sensitivity = 0.67;
$specificity = 0.91;
$LL          = Experiment::likelihoodRatioSS($sensitivity, $specificity);

```

### Statistics - Random Variables
```php
use MathPHP\Statistics\RandomVariable;

$X = [1, 2, 3, 4];
$Y = [2, 3, 4, 5];

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
```

### Statistics - Regressions
```php
use MathPHP\Statistics\Regression;

$points = [[1,2], [2,3], [4,5], [5,7], [6,8]];

// Simple linear regression (least squares method)
$regression = new Regresion\Linear($points);
$parameters = $regression->getParameters();          // [m => 1.2209302325581, b => 0.6046511627907]
$equation   = $regression->getEquation();            // y = 1.2209302325581x + 0.6046511627907
$y          = $regression->evaluate(5);              // Evaluate for y at x = 5 using regression equation
$ci         = $regression->CI(5, 0.5);               // Confidence interval for x = 5 with p-value of 0.5
$pi         = $regression->PI(5, 0.5);               // Prediction interval for x = 5 with p-value of 0.5; Optional number of trials parameter.
$Ŷ          = $regression->yHat();
$r          = $regression->r();                      // same as correlationCoefficient
$r²         = $regression->r2();                     // same as coefficientOfDetermination
$se         = $regression->standardErrors();         // [m => se(m), b => se(b)]
$t          = $regression->tValues();                // [m => t, b => t]
$p          = $regression->tProbability();           // [m => p, b => p]
$F          = $regression->FStatistic();
$p          = $regression->FProbability();
$h          = $regression->leverages();
$e          = $regression->residuals();
$D          = $regression->cooksD();
$DFFITS     = $regression->DFFITS();
$SStot      = $regression->sumOfSquaresTotal();
$SSreg      = $regression->sumOfSquaresRegression();
$SSres      = $regression->sumOfSquaresResidual();
$MSR        = $regression->meanSquareRegression();
$MSE        = $regression->meanSquareResidual();
$MSTO       = $regression->meanSquareTotal();
$error      = $regression->errorSD();                // Standard error of the residuals
$V          = $regression->regressionVariance();
$n          = $regression->getSampleSize();          // 5
$points     = $regression->getPoints();              // [[1,2], [2,3], [4,5], [5,7], [6,8]]
$xs         = $regression->getXs();                  // [1, 2, 4, 5, 6]
$ys         = $regression->getYs();                  // [2, 3, 5, 7, 8]
$ν          = $regression->degreesOfFreedom();

// Linear regression through a fixed point (least squares method)
$force_point = [0,0];
$regression  = new Regresion\LinearThroughPoint($points, $force_point);
$parameters  = $regression->getParameters();
$equation    = $regression->getEquation();
$y           = $regression->evaluate(5);
$Ŷ           = $regression->yHat();
$r           = $regression->r();
$r²          = $regression->r2();
 ⋮                     ⋮

// Theil–Sen estimator (Sen's slope estimator, Kendall–Theil robust line)
$regression  = new Regresion\TheilSen($points);
$parameters  = $regression->getParameters();
$equation    = $regression->getEquation();
$y           = $regression->evaluate(5);
 ⋮                     ⋮

// Use Lineweaver-Burk linearization to fit data to the Michaelis–Menten model: y = (V * x) / (K + x)
$regression  = new Regresion\LineweaverBurk($points);
$parameters  = $regression->getParameters();  // [V, K]
$equation    = $regression->getEquation();    // y = Vx / (K + x)
$y           = $regression->evaluate(5);
 ⋮                     ⋮

// Use Hanes-Woolf linearization to fit data to the Michaelis–Menten model: y = (V * x) / (K + x)
$regression  = new Regresion\HanesWoolf($points);
$parameters  = $regression->getParameters();  // [V, K]
$equation    = $regression->getEquation();    // y = Vx / (K + x)
$y           = $regression->evaluate(5);
 ⋮                     ⋮

// Power law regression - power curve (least squares fitting)
$regression = new Regresion\PowerLaw($points);
$parameters = $regression->getParameters();   // [a => 56.483375436574, b => 0.26415375648621]
$equation   = $regression->getEquation();     // y = 56.483375436574x^0.26415375648621
$y          = $regression->evaluate(5);
 ⋮                     ⋮

// LOESS - Locally Weighted Scatterplot Smoothing (Local regression)
$α          = 1/3;                         // Smoothness parameter
$λ          = 1;                           // Order of the polynomial fit
$regression = new Regresion\LOESS($points, $α, $λ);
$y          = $regression->evaluate(5);
$Ŷ          = $regression->yHat();
 ⋮                     ⋮
```

### Statistics - Significance Testing
```php
use MathPHP\Statistics\Significance;

// Z test - One sample (z and p values)
$Hₐ = 20;   // Alternate hypothesis (M Sample mean)
$n  = 200;  // Sample size
$H₀ = 19.2; // Null hypothesis (μ Population mean)
$σ  = 6;    // SD of population (Standard error of the mean)
$z  = Significance:zTest($Hₐ, $n, $H₀, $σ);           // Same as zTestOneSample
$z  = Significance:zTestOneSample($Hₐ, $n, $H₀, $σ);  // Same as zTest
/* [
  'z'  => 1.88562, // Z score
  'p1' => 0.02938, // one-tailed p value
  'p2' => 0.0593,  // two-tailed p value
] */

// Z test - Two samples (z and p values)
$μ₁ = 27;   // Sample mean of population 1
$μ₂ = 33;   // Sample mean of population 2
$n₁ = 75;   // Sample size of population 1
$n₂ = 50;   // Sample size of population 2
$σ₁ = 14.1; // Standard deviation of sample mean 1
$σ₂ = 9.5;  // Standard deviation of sample mean 2
$z  = Significance::zTestTwoSample($μ₁, $μ₂, $n₁, $n₂, $σ₁, $σ₂);
/* [
  'z'  => -2.36868418147285,  // z score
  'p1' => 0.00893,            // one-tailed p value
  'p2' => 0.0179,             // two-tailed p value
] */

// Z score
$M = 8; // Sample mean
$μ = 7; // Population mean
$σ = 1; // Population SD
$z = Significance::zScore($μ, $σ, $x);

// T test - One sample (t and p values)
$Hₐ = 280; // Alternate hypothesis (M Sample mean)
$s  = 50;  // SD of sample
$n  = 15;  // Sample size
$H₀ = 300; // Null hypothesis (μ₀ Population mean)
$t  = Significance::tTestOneSample($Hₐ, $s, $n, $H₀);
/* [
  't'  => -1.549, // t score
  'p1' => 0.0718, // one-tailed p value
  'p2' => 0.1437, // two-tailed p value
] */

// T test - Two samples (t and p values)
$μ₁ = 42.14; // Sample mean of population 1
$μ₂ = 43.23; // Sample mean of population 2
$n₁ = 10;    // Sample size of population 1
$n₂ = 10;    // Sample size of population 2
$σ₁ = 0.683; // Standard deviation of sample mean 1
$σ₂ = 0.750; // Standard deviation of sample mean 2
$t  = Significance::tTestTwoSample($μ₁, $μ₂, $n₁, $n₂, $σ₁, $σ₂);
/* [
  't'  => -3.3978,  // t score
  'p1' => 0.001604, // one-tailed p value
  'p2' => 0.181947, // two-tailed p value
] */

// T score
$Hₐ = 280; //Alternate hypothesis (M Sample mean)
$s  = 50;  // SD of sample
$n  = 15;  // Sample size
$H₀ = 300; // Null hypothesis (μ₀ Population mean)
$t  = Significance::tScore($Hₐ, $s, $n, $H);

// χ² test (chi-squared goodness of fit test)
$observed = [4, 6, 17, 16, 8, 9];
$expected = [10, 10, 10, 10, 10, 10];
$χ²       = Significance::chiSquaredTest($observed, $expected);
// ['chi-square' => 14.2, 'p' => 0.014388]
```

Unit Tests
----------

```bash
$ cd tests
$ phpunit
```

[![Coverage Status](https://coveralls.io/repos/github/markrogoyski/math-php/badge.svg?branch=master)](https://coveralls.io/github/markrogoyski/math-php?branch=master)
[![Build Status](https://travis-ci.org/markrogoyski/math-php.svg?branch=master)](https://travis-ci.org/markrogoyski/math-php)

Standards
---------

Math PHP conforms to the following standards:

 * PSR-1 - Basic coding standard (http://www.php-fig.org/psr/psr-1/)
 * PSR-2 - Coding style guide (http://www.php-fig.org/psr/psr-2/)
 * PSR-4 - Autoloader (http://www.php-fig.org/psr/psr-4/)

License
-------

Math PHP is licensed under the MIT License.

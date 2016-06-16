<?php
namespace Math\Probability\Distribution;

class DiscreteTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @dataProvider dataProviderForBinomialPMF
     */
    public function testBinomialPMF(int $n, int $r, float $p, float $binomial_distribution)
    {
        $this->assertEquals($binomial_distribution, Discrete::binomialPMF($n, $r, $p), '', 0.001);
    }

    /**
     * Data provider for binomial
     * Data: [ n, r, p, binomial distribution ]
     */
    public function dataProviderForBinomialPMF()
    {
        return [
        [ 2, 1, 0.5, 0.5 ],
        [ 2, 1, 0.4, 0.48 ],
        [ 6, 2, 0.7, 0.0595350 ],
        [ 8, 7, 0.83, 0.3690503 ],
        [ 10, 5, 0.85, 0.0084909 ],
        [ 50, 48, 0.97, 0.2555182 ],
        [ 5, 4, 1, 0.0 ],
        [ 12, 4, 0.2, 0.1329 ]
        ];
    }

    public function testBinomialPMFProbabilityLowerBoundException()
    {
        $this->setExpectedException('\Exception');
        Discrete::binomialPMF(6, 2, -0.1);
    }

    public function testBinomialPMFProbabilityUpperBoundException()
    {
        $this->setExpectedException('\Exception');
        Discrete::binomialPMF(6, 2, 1.1);
    }

    /**
     * @dataProvider dataProviderForBinomialCDF
     */
    public function testBinomialCDF(int $n, int $r, float $p, float $cumulative_binomial_distribution)
    {
        $this->assertEquals($cumulative_binomial_distribution, Discrete::binomialCDF($n, $r, $p), '', 0.001);
    }

    /**
     * Data provider for cumulative binomial
     * Data: [ n, r, p, cumulative binomial distribution ]
     */
    public function dataProviderForBinomialCDF()
    {
        return [
            [ 2, 1, 0.5, 0.75 ],
            [ 2, 1, 0.4, 0.84 ],
            [ 6, 2, 0.7, 0.07047 ],
            [ 8, 7, 0.83, 0.7747708 ],
            [ 10, 5, 0.85, 0.009874091 ],
            [ 50, 48, 0.97, 0.4447201 ],
            [ 5, 4, 1, 0.0 ],
            [ 12, 4, 0.2, 0.92744 ],
        ];
    }

    /**
     * @dataProvider dataProviderForNegativeBinomial
     */
    public function testNegativeBinomial(int $x, int $r, float $P, float $neagative_binomial_distribution)
    {
        $this->assertEquals($neagative_binomial_distribution, Discrete::negativeBinomial($x, $r, $P), '', 0.001);
    }

    /**
     * Data provider for neagative binomial
     * Data: [ x, r, P, negative binomial distribution ]
     */
    public function dataProviderForNegativeBinomial()
    {
        return [
            [ 2, 1, 0.5, 0.25 ],
            [ 2, 1, 0.4, 0.24 ],
            [ 6, 2, 0.7, 0.019845 ],
            [ 8, 7, 0.83, 0.322919006776561 ],
            [ 10, 5, 0.85, 0.00424542789316406 ],
            [ 50, 48, 0.97, 0.245297473979909 ],
            [ 5, 4, 1, 0.0 ],
            [ 2, 2, 0.5, 0.25 ],
        ];
    }

    public function testNegativeBinomialProbabilityLowerBoundException()
    {
        $this->setExpectedException('\Exception');
        Discrete::negativeBinomial(6, 2, -0.1);
    }

    public function testNegativeBinomialProbabilityUpperBoundException()
    {
        $this->setExpectedException('\Exception');
        Discrete::negativeBinomial(6, 2, 1.1);
    }

    /**
     * @dataProvider dataProviderForPascal
     */
    public function testPascal(int $x, int $r, float $P, float $neagative_binomial_distribution)
    {
        $this->assertEquals($neagative_binomial_distribution, Discrete::pascal($x, $r, $P), '', 0.001);
    }

    /**
     * Data provider for Pascal
     * Data: [ x, r, P, negative binomial distribution ]
     */
    public function dataProviderForPascal()
    {
        return [
            [ 2, 1, 0.5, 0.25 ],
            [ 2, 1, 0.4, 0.24 ],
            [ 6, 2, 0.7, 0.019845 ],
            [ 8, 7, 0.83, 0.322919006776561 ],
            [ 10, 5, 0.85, 0.00424542789316406 ],
            [ 50, 48, 0.97, 0.245297473979909 ],
            [ 5, 4, 1, 0.0 ],
            [ 2, 2, 0.5, 0.25 ],
        ];
    }

    public function testPascalProbabilityLowerBoundException()
    {
        $this->setExpectedException('\Exception');
        Discrete::pascal(6, 2, -0.1);
    }

    public function testPascalProbabilityUpperBoundException()
    {
        $this->setExpectedException('\Exception');
        Discrete::pascal(6, 2, 1.1);
    }

    /**
     * @dataProvider dataProviderForPoissonPMF
     */
    public function testPoissonPMF(int $k, float $λ, float $probability)
    {
        $this->assertEquals($probability, Discrete::poissonPMF($k, $λ), '', 0.001);
    }

    /**
     * Data provider for poisson
     * Data: [ k, λ, poisson distribution ]
     */
    public function dataProviderForPoissonPMF()
    {
        return [
            [ 3, 2, 0.180 ],
            [ 3, 5, 0.140373895814280564513 ],
            [ 8, 6, 0.103257733530844 ],
            [ 2, 0.45, 0.065 ],
            [ 16, 12, 0.0542933401099791 ],
        ];
    }

    /**
     * @dataProvider dataProviderForPoissonCDF
     */
    public function testPoissonCDF(int $k, float $λ, float $probability)
    {
        $this->assertEquals($probability, Discrete::poissonCDF($k, $λ), '', 0.001);
    }

    /**
     * Data provider for cumulative poisson
     * Data: [ k, λ, culmulative poisson distribution ]
     */
    public function dataProviderForPoissonCDF()
    {
        return [
            [ 3, 2, 0.857123460498547048662 ],
            [ 3, 5, 0.2650 ],
            [ 8, 6, 0.8472374939845613089968 ],
            [ 2, 0.45, 0.99 ],
            [ 16, 12, 0.898708992560164 ],
        ];
    }

    public function testPoissonExceptionWhenKLessThanZero()
    {
        $this->setExpectedException('\Exception');
        Discrete::poissonPMF(-1, 2);
    }

    public function testPoissonExceptionWhenλLessThanZero()
    {
        $this->setExpectedException('\Exception');
        Discrete::poissonPMF(2, -1);
    }

    /**
     * @dataProvider dataProviderForMultinomialPMF
     */
    public function testMultinomialPMF(array $frequencies, array $probabilities, $pmf)
    {
        $this->assertEquals($pmf, Discrete::multinomialPMF($frequencies, $probabilities), '', 0.001);
    }

    public function dataProviderForMultinomialPMF()
    {
        return [
            [ [1, 1], [0.5, 0.5], 0.5 ],
            [ [1, 1], [0.4, 0.6], 0.48 ],
            [ [7, 2, 3], [0.40, 0.35, 0.25], 0.0248 ],
            [ [1, 2, 3], [0.2, 0.3, 0.5], 0.135 ],
            [ [2, 3, 3, 2], [0.25, 0.25, 0.25, 0.25], 0.024 ],
            [ [5, 2], [0.4, 0.6], 0.07741440000000005 ],
        ];
    }

    public function testMultinomialPMFExceptionCountFrequenciesAndProbabilitiesDoNotMatch()
    {
        $this->setExpectedException('\Exception');
        Discrete::multinomialPMF([1, 2,3], [0.3, 0.4, 0.2, 0.1]);
    }

    public function testMultinomialPMFExceptionProbabilitiesDoNotAddUpToOne()
    {
        $this->setExpectedException('\Exception');
        Discrete::multinomialPMF([1, 2,3], [0.3, 0.2, 0.1]);
    }

    /**
     * @dataProvider dataProviderForGeometricShiftedPMF
     */
    public function testGeometricShiftedPMF(int $k, float $p, float $pmf)
    {
        $this->assertEquals($pmf, Discrete::geometricShiftedPMF($k, $p), '', 0.001);
    }

    public function dataProviderForGeometricShiftedPMF()
    {
        return [
            [ 5, 0.1, 0.065610 ],
            [ 5, 0.2, 0.081920 ],
            [ 1, 0.4, 0.400000 ],
            [ 2, 0.4, 0.240000 ],
            [ 3, 0.4, 0.144 ],
            [ 5, 0.5, 0.031512 ],
            [ 5, 0.09, 0.061717 ],
            [ 1, 1, 1 ],
            [ 2, 1, 0 ],
        ];
    }

    public function testGeometricShiftedPMFExceptionKLessThanOne()
    {
        $this->setExpectedException('\Exception');
        Discrete::geometricShiftedPMF(0, 0.4);
    }

    public function testGeometricShiftedPMFExceptionPLessThanEqualZero()
    {
        $this->setExpectedException('\Exception');
        Discrete::geometricShiftedPMF(2, 0);  
    }

    public function testGeometricShiftedPMFExceptionPGreaterThanOne()
    {
        $this->setExpectedException('\Exception');
        Discrete::geometricShiftedPMF(2, 1.2);  
    }

    /**
     * @dataProvider dataProviderForGeometricShiftedCDF
     */
    public function testGeometricShiftedCDF(int $k, float $p, float $cdf)
    {
        $this->assertEquals($cdf, Discrete::geometricShiftedCDF($k, $p), '', 0.001);
    }

    public function dataProviderForGeometricShiftedCDF()
    {
        return [
            [ 5, 0.1, 0.40951 ],
            [ 5, 0.2, 0.67232 ],
            [ 1, 0.4, 0.4  ],
            [ 2, 0.4, 0.64 ],
            [ 3, 0.4, 0.784 ],
            [ 5, 0.5, 0.9688 ],
            [ 5, 0.09, 0.3759678549 ],
            [ 1, 1, 1 ],
            [ 2, 1, 1 ],
        ];
    }

    public function testGeometricShiftedCDFExceptionKLessThanZero()
    {
        $this->setExpectedException('\Exception');
        Discrete::geometricShiftedCDF(-1, 0.4);
    }

    public function testGeometricShiftedCDFExceptionPLessThanEqualZero()
    {
        $this->setExpectedException('\Exception');
        Discrete::geometricShiftedCDF(2, 0);  
    }

    public function testGeometricShiftedCDFExceptionPGreaterThanOne()
    {
        $this->setExpectedException('\Exception');
        Discrete::geometricShiftedCDF(2, 1.2);  
    }

    /**
     * @dataProvider dataProviderForGeometricKFailuresPMF
     */
    public function testGeometricKFailuresPMF(int $k, float $p, float $pmf)
    {
        $this->assertEquals($pmf, Discrete::geometricKFailuresPMF($k, $p), '', 0.001);
    }

    public function dataProviderForGeometricKFailuresPMF()
    {
        return [
            [ 5, 0.1, 0.059049 ],
            [ 5, 0.2, 0.065536 ],
            [ 1, 0.4, 0.24 ],
            [ 2, 0.4, 0.144 ],
            [ 3, 0.4, 0.0864 ],
            [ 5, 0.5, 0.015625 ],
            [ 5, 0.09, 0.056162893059 ],
            [ 1, 1, 0 ],
            [ 2, 1, 0 ],
        ];
    }

    public function testGeometricKFailuresPMFExceptionKLessThanOne()
    {
        $this->setExpectedException('\Exception');
        Discrete::geometricKFailuresPMF(0, 0.4);
    }

    public function testGeometricKFailuresPMFExceptionPLessThanEqualZero()
    {
        $this->setExpectedException('\Exception');
        Discrete::geometricKFailuresPMF(2, 0);  
    }

    public function testGeometricKFailuresPMFExceptionPGreaterThanOne()
    {
        $this->setExpectedException('\Exception');
        Discrete::geometricKFailuresPMF(2, 1.2);  
    }

    /**
     * @dataProvider dataProviderForGeometricKFailuresCDF
     */
    public function testGeometricKFailuresCDF(int $k, float $p, float $cdf)
    {
        $this->assertEquals($cdf, Discrete::geometricKFailuresCDF($k, $p), '', 0.001);
    }

    public function dataProviderForGeometricKFailuresCDF()
    {
        return [
            [ 5, 0.1, 0.468559 ],
            [ 5, 0.2, 0.737856 ],
            [ 1, 0.4, 0.64  ],
            [ 2, 0.4, 0.784 ],
            [ 3, 0.4, 0.8704 ],
            [ 5, 0.5, 0.984375 ],
            [ 5, 0.09, 0.432130747959 ],
            [ 1, 1, 1 ],
            [ 2, 1, 1 ],
        ];
    }

    public function testGeometricKFailuresCDFExceptionKLessThanZero()
    {
        $this->setExpectedException('\Exception');
        Discrete::geometricKFailuresCDF(-1, 0.4);
    }

    public function testGeometricKFailuresCDFExceptionPLessThanEqualZero()
    {
        $this->setExpectedException('\Exception');
        Discrete::geometricKFailuresCDF(2, 0);  
    }

    public function testGeometricKFailuresCDFExceptionPGreaterThanOne()
    {
        $this->setExpectedException('\Exception');
        Discrete::geometricKFailuresCDF(2, 1.2);  
    }
}

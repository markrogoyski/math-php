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
}

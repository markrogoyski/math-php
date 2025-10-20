<?php

namespace MathPHP\Tests\Probability\Distribution\Discrete;

use MathPHP\Probability\Distribution\Discrete\Hypergeometric;

class HypergeometricTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @test         pmf returns expected probability
     * @dataProvider dataProviderForPmf
     * @param  int   $N population size
     * @param  int   $K number of success states in the population
     * @param  int   $n number of draws
     * @param  int   $k number of observed successes
     * @param  float $expectedPmf
     */
    public function testPmf(int $N, int $K, int $n, int $k, float $expectedPmf)
    {
        // Given
        $hypergeometric = new Hypergeometric($N, $K, $n);

        // When
        $pmf = $hypergeometric->pmf($k);

        // Then
        $this->assertEqualsWithDelta($expectedPmf, $pmf, 0.0000001);
    }

    /**
     * Test data made with: http://stattrek.com/m/online-calculator/hypergeometric.aspx
     * @return array
     */
    public function dataProviderForPmf(): array
    {
        return [
            [50, 5, 10, 4, 0.00396458305801507],
            [50, 5, 10, 5, 0.000118937491740452],
            [100, 80, 50, 40, 0.196871217706549],
            [100, 80, 50, 35, 0.00889760379503624],
            [48, 6, 15, 2, 0.350128003786331],
            [48, 6, 15, 0, 0.0902552187538097],
            [48, 6, 15, 6, 0.000407855201543217],
            [100, 30, 20, 5, 0.19182559242904654583],
        ];
    }

    /**
     * @test         cdf returns expected probability
     * @dataProvider dataProviderForCdf
     * @param  int   $N population size
     * @param  int   $K number of success states in the population
     * @param  int   $n number of draws
     * @param  int   $k number of observed successes
     * @param  float $expectedCdf
     */
    public function testCdf(int $N, int $K, int $n, int $k, float $expectedCdf)
    {
        // Given
        $hypergeometric = new Hypergeometric($N, $K, $n);

        // When
        $cdf = $hypergeometric->cdf($k);

        // Then
        $this->assertEqualsWithDelta($expectedCdf, $cdf, 0.0000001);
    }

    /**
     * Test data generated with SciPy (scipy.stats.hypergeom.cdf)
     * Comprehensive test coverage including:
     * - Small, medium, and large populations
     * - Various K/N and n/N ratios
     * - Edge cases (K=0, K=N, n=N)
     * - Extreme K values
     * - Support boundaries (min and max k values)
     * @return array
     */
    public function dataProviderForCdf(): array
    {
        return [
            [10, 3, 5, 0, 0.08333333333333333],
            [10, 3, 5, 1, 0.5],
            [10, 3, 5, 3, 1.0],
            [10, 5, 5, 0, 0.003968253968253969],
            [10, 5, 5, 2, 0.5000000000000001],
            [10, 5, 5, 3, 0.8968253968253967],
            [10, 5, 5, 5, 1.0],
            [15, 5, 8, 0, 0.006993006993006992],
            [15, 5, 8, 2, 0.42657342657342656],
            [15, 5, 8, 3, 0.8181818181818181],
            [15, 5, 8, 5, 1.0],
            [20, 0, 5, 0, 1.0],
            [20, 8, 10, 0, 0.00035722791140747797],
            [20, 8, 10, 4, 0.6750416765896641],
            [20, 8, 10, 6, 0.9901166944510598],
            [20, 8, 10, 8, 1.0],
            [20, 10, 20, 10, 1.0],
            [20, 20, 5, 5, 1.0],
            [25, 10, 12, 0, 8.749495221429533e-05],
            [25, 10, 12, 5, 0.7158836990173644],
            [25, 10, 12, 7, 0.9872930407861085],
            [25, 10, 12, 10, 1.0],
            [30, 12, 15, 0, 5.26052763092138e-06],
            [30, 12, 15, 6, 0.6448091743601885],
            [30, 12, 15, 9, 0.9961124700807491],
            [30, 12, 15, 12, 1.0],
            [40, 15, 20, 0, 3.85428639043767e-07],
            [40, 15, 20, 7, 0.5],
            [40, 15, 20, 11, 0.9960441531631743],
            [40, 15, 20, 15, 1.0],
            [50, 0, 10, 0, 1.0],
            [50, 5, 10, 0, 0.3105627820045686],
            [50, 5, 10, 2, 0.9517396968037909],
            [50, 5, 10, 3, 0.9959164794502445],
            [50, 5, 10, 5, 1.0],
            [50, 10, 15, 0, 0.01787134197126206],
            [50, 10, 15, 5, 0.9699982931829035],
            [50, 10, 15, 7, 0.9996099207075893],
            [50, 10, 15, 10, 1.0],
            [50, 20, 25, 0, 1.1273262902205426e-09],
            [50, 20, 25, 10, 0.6133563604064838],
            [50, 20, 25, 15, 0.9993957050091867],
            [50, 20, 25, 20, 1.0],
            [50, 25, 20, 0, 1.1273262902205426e-09],
            [50, 25, 20, 10, 0.6133563604064838],
            [50, 25, 20, 15, 0.9993957050091867],
            [50, 25, 20, 20, 1.0],
            [50, 25, 50, 25, 1.0],
            [50, 45, 25, 20, 0.02507598784194529],
            [50, 45, 25, 22, 0.5],
            [50, 45, 25, 23, 0.8256621797655233],
            [50, 45, 25, 25, 1.0],
            [50, 50, 10, 10, 1.0],
            [100, 0, 20, 0, 1.0],
            [100, 1, 25, 0, 0.75],
            [100, 1, 25, 1, 1.0],
            [100, 2, 50, 0, 0.2474747474747475],
            [100, 2, 50, 1, 0.7525252525252526],
            [100, 2, 50, 2, 1.0],
            [100, 10, 20, 0, 0.09511627243078839],
            [100, 10, 20, 5, 0.9960669235332087],
            [100, 10, 20, 7, 0.9999762172503596],
            [100, 10, 20, 10, 1.0],
            [100, 30, 25, 0, 2.6619582756245685e-05],
            [100, 30, 25, 12, 0.993150980754778],
            [100, 30, 25, 18, 0.9999999689113735],
            [100, 30, 25, 25, 1.0],
            [100, 50, 40, 0, 7.472794411214413e-19],
            [100, 50, 40, 20, 0.5807916780398517],
            [100, 50, 40, 30, 0.999993675397855],
            [100, 50, 40, 40, 1.0],
            [100, 50, 100, 50, 1.0],
            [100, 80, 50, 30, 8.79303628551999e-08],
            [100, 80, 50, 40, 0.5984356088532748],
            [100, 80, 50, 45, 0.9974801132893285],
            [100, 80, 50, 50, 1.0],
            [100, 98, 50, 48, 0.24747474747474746],
            [100, 98, 50, 49, 0.7525252525252526],
            [100, 98, 50, 50, 1.0],
            [100, 99, 25, 24, 0.25],
            [100, 99, 25, 25, 1.0],
            [100, 100, 25, 25, 1.0],
            [200, 50, 40, 0, 2.150519409839511e-06],
            [200, 50, 40, 20, 0.9999776768090545],
            [200, 50, 40, 30, 0.9999999999999988],
            [200, 50, 40, 40, 1.0],
            [200, 100, 60, 0, 1.9524500643203445e-24],
            [200, 100, 60, 30, 0.5612693524872567],
            [200, 100, 60, 45, 0.9999994374617821],
            [200, 100, 60, 60, 1.0],
            [300, 75, 50, 0, 1.1830240925557346e-07],
            [300, 75, 50, 25, 0.9999947326380135],
            [300, 75, 50, 37, 1.0],
            [300, 75, 50, 50, 1.0],
            [400, 100, 80, 0, 4.4284954017132875e-12],
            [400, 100, 80, 40, 0.9999999910113719],
            [400, 100, 80, 60, 1.0],
            [400, 100, 80, 80, 1.0],
            [500, 100, 80, 0, 2.926099467436453e-09],
            [500, 100, 80, 40, 0.999999999996426],
            [500, 100, 80, 60, 1.0],
            [500, 100, 80, 80, 1.0],
            [500, 250, 100, 0, 2.9696045989948152e-36],
            [500, 250, 100, 50, 0.5444861751634522],
            [500, 250, 100, 75, 0.9999999968746173],
            [500, 250, 100, 100, 1.0],
            [1000, 100, 50, 0, 0.004475790977886088],
            [1000, 100, 50, 25, 0.9999999999999925],
            [1000, 100, 50, 37, 1.0],
            [1000, 100, 50, 50, 1.0],
            [1000, 300, 100, 0, 3.2116360860175614e-17],
            [1000, 300, 100, 50, 0.9999972126895079],
            [1000, 300, 100, 75, 1.0],
            [1000, 300, 100, 100, 1.0],
        ];
    }

    /**
     * @test         mean returns expected average
     * @dataProvider dataProviderForMean
     * @param  int   $N population size
     * @param  int   $K number of success states in the population
     * @param  int   $n number of draws
     * @param  float $μ
     */
    public function testMean(int $N, int $K, int $n, float $μ)
    {
        // Given
        $hypergeometric = new Hypergeometric($N, $K, $n);

        // When
        $mean = $hypergeometric->mean();

        // Then
        $this->assertEqualsWithDelta($μ, $mean, 0.0000001);
    }

    /**
     * Test data made with: http://keisan.casio.com/exec/system/1180573201
     * @return array
     */
    public function dataProviderForMean(): array
    {
        return [
            [50, 5, 10, 1],
            [100, 80, 50, 40],
            [48, 6, 15, 1.875],
            [100, 30, 20, 6],
        ];
    }

    /**
     * @test         mode
     * @dataProvider dataProviderForMode
     * @param  int   $N population size
     * @param  int   $K number of success states in the population
     * @param  int   $n number of draws
     * @param  array $expectedMode
     */
    public function testMode(int $N, int $K, int $n, array $expectedMode)
    {
        // Given
        $hypergeometric = new Hypergeometric($N, $K, $n);

        // When
        $mode = $hypergeometric->mode();

        // Then
        $this->assertEqualsWithDelta($expectedMode, $mode, 0.0000001);
    }

    /**
     * @return array [N, K, n, mode]
     */
    public function dataProviderForMode(): array
    {
        return [
            [50, 5, 10, [1, 1]],
            [100, 80, 50, [40, 40]],
            [48, 6, 15, [2, 2]],
            [100, 30, 20, [6, 6]],
        ];
    }

    /**
     * @test         variance
     * @dataProvider dataProviderForVariance
     * @param  int   $N population size
     * @param  int   $K number of success states in the population
     * @param  int   $n number of draws
     * @param  float $σ²
     */
    public function testVariance(int $N, int $K, int $n, float $σ²)
    {
        // Given
        $hypergeometric = new Hypergeometric($N, $K, $n);

        // When
        $variance = $hypergeometric->variance();

        // Then
        $this->assertEqualsWithDelta($σ², $variance, 0.0000001);
    }

    /**
     * @return array [N, K, n, σ²]
     */
    public function dataProviderForVariance(): array
    {
        return [
            [50, 5, 10, 0.73469387755102],
            [100, 80, 50, 4.040404040404],
        ];
    }
}

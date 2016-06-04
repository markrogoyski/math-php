<?php
namespace Math\Statistics;

class AverageTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @dataProvider dataProviderForMean
     */
    public function testMean(array $numbers, $mean)
    {
        $this->assertEquals($mean, Average::mean($numbers), '', 0.01);
    }

    /**
     * Data provider for mean test
     * Data: [ [ numbers ], mean ]
     */
    public function dataProviderForMean()
    {
        return [
            [ [ 1, 1, 1 ], 1 ],
            [ [ 1, 2, 3 ], 2 ],
            [ [ 2, 3, 4 ], 3 ],
            [ [ 5, 5, 6 ], 5.33 ],
            [ [ 13, 18, 13, 14, 13, 16, 14, 21, 13 ], 15 ],
            [ [ 1, 2, 4, 7 ], 3.5 ],
            [ [ 8, 9, 10, 10, 10, 11, 11, 11, 12, 13 ], 10.5 ],
            [ [ 6, 7, 8, 10, 12, 14, 14, 15, 16, 20 ], 12.2 ],
            [ [ 9, 10, 11, 13, 15, 17, 17, 18, 19, 23 ], 15.2 ],
            [ [ 12, 14, 16, 20, 24, 28, 28, 30, 32, 40 ], 24.4 ],
        ];
    }

    public function testMeanNullWhenEmptyArray()
    {
        $this->assertNull(Average::mean(array()));
    }

    /**
     * @dataProvider dataProviderForMedian
     */
    public function testMedian(array $numbers, $median)
    {
        $this->assertEquals($median, Average::median($numbers), '', 0.01);
    }

    /**
     * Data provider for median test
     * Data: [ [ numbers ], median ]
     */
    public function dataProviderForMedian()
    {
        return [
            [ [ 1, 1, 1 ], 1 ],
            [ [ 1, 2, 3 ], 2 ],
            [ [ 2, 3, 4 ], 3 ],
            [ [ 5, 5, 6 ], 5 ],
            [ [ 1, 2, 3, 4, 5 ], 3 ],
            [ [ 1, 2, 3, 4, 5, 6 ], 3.5 ],
            [ [ 13, 18, 13, 14, 13, 16, 14, 21, 13 ], 14 ],
            [ [ 1, 2, 4, 7 ], 3 ],
            [ [ 8, 9, 10, 10, 10, 11, 11, 11, 12, 13 ], 10.5 ],
            [ [ 6, 7, 8, 10, 12, 14, 14, 15, 16, 20 ], 13 ],
            [ [ 9, 10, 11, 13, 15, 17, 17, 18, 19, 23 ], 16 ],
            [ [ 12, 14, 16, 20, 24, 28, 28, 30, 32, 40 ], 26 ],
        ];
    }

    public function testMedianNullWhenEmptyArray()
    {
        $this->assertNull(Average::median(array()));
    }

    /**
     * @dataProvider dataProviderForMode
     */
    public function testMode(array $numbers, $modes)
    {
        $computed_modes = Average::mode($numbers);
        sort($modes);
        sort($computed_modes);
        $this->assertEquals($modes, $computed_modes);
    }

    /**
     * Data provider for mode test
     * Data: [ [ numbers ], mode ]
     */
    public function dataProviderForMode()
    {
        return [
            [ [ 1, 1, 1 ], [1] ],
            [ [ 1, 1, 2 ], [1] ],
            [ [ 1, 2, 1 ], [1] ],
            [ [ 2, 1, 1 ], [1] ],
            [ [ 1, 2, 2 ], [2] ],
            [ [ 1, 1, 1, 1 ], [1] ],
            [ [ 1, 1, 1, 2 ], [1] ],
            [ [ 1, 1, 2, 1 ], [1] ],
            [ [ 1, 2, 1, 1 ], [1] ],
            [ [ 2, 1, 1, 1 ], [1] ],
            [ [ 1, 1, 2, 2 ], [ 1, 2 ] ],
            [ [ 1, 2, 2, 1 ], [ 1, 2 ] ],
            [ [ 2, 2, 1, 1 ], [ 1, 2 ] ],
            [ [ 2, 1, 2, 1 ], [ 1, 2 ] ],
            [ [ 2, 1, 1, 2 ], [ 1, 2 ] ],
            [ [ 1, 1, 2, 2, 3, 3 ], [ 1, 2, 3 ] ],
            [ [ 1, 2, 1, 2, 3, 3 ], [ 1, 2, 3 ] ],
            [ [ 1, 2, 3, 1, 2, 3 ], [ 1, 2, 3 ] ],
            [ [ 3, 1, 2, 3, 2, 1 ], [ 1, 2, 3 ] ],
            [ [ 3, 3, 2, 2, 1, 1 ], [ 1, 2, 3 ] ],
            [ [ 1, 1, 1, 2, 2, 3 ], [1] ],
            [ [ 1, 2, 2, 2, 2, 3 ], [2] ],
            [ [ 1, 2, 2, 3, 3, 4 ], [ 2, 3 ] ],
            [ [ 13, 18, 13, 14, 13, 16, 14, 21, 13 ], [13] ],
            [ [ 1, 2, 4, 7 ], [ 1, 2, 4, 7 ] ],
            [ [ 8, 9, 10, 10, 10, 11, 11, 11, 12, 13 ], [ 10, 11 ] ],
            [ [ 6, 7, 8, 10, 12, 14, 14, 15, 16, 20 ], [14] ],
            [ [ 9, 10, 11, 13, 15, 17, 17, 18, 19, 23 ], [17] ],
            [ [ 12, 14, 16, 20, 24, 28, 28, 30, 32, 40 ], [28] ],
        ];
    }

    /**
     * @dataProvider dataProviderForGeometricMean
     */
    public function testGeometricMean(array $numbers, $mean)
    {
        $this->assertEquals($mean, Average::geometricMean($numbers), '', 0.01);
    }

    /**
     * Data provider for geometric mean test
     * Data: [ [ numbers ], mean ]
     */
    public function dataProviderForGeometricMean()
    {
        return [
            [ [ 1, 1, 1 ], 1 ],
            [ [ 1, 2, 3 ], 1.81712 ],
            [ [ 2, 3, 4 ], 2.8845 ],
            [ [ 5, 5, 6 ], 5.31329 ],
            [ [ 13, 18, 13, 14, 13, 16, 14, 21, 13 ], 14.78973 ],
            [ [ 1, 2, 4, 7 ], 2.73556 ],
            [ [ 8, 9, 10, 10, 10, 11, 11, 11, 12, 13 ], 10.41031 ],
            [ [ 6, 7, 8, 10, 12, 14, 14, 15, 16, 20 ], 11.4262 ],
            [ [ 9, 10, 11, 13, 15, 17, 17, 18, 19, 23 ], 14.59594 ],
            [ [ 12, 14, 16, 20, 24, 28, 28, 30, 32, 40 ], 22.8524 ],
            [ [ 1, 3, 5, 7, 10 ], 4.02011 ],
        ];
    }

    public function testGeomoetricMeanNullWhenEmptyArray()
    {
        $this->assertNull(Average::geometricMean(array()));
    }

    public function testModeEmtyArrayWhenEmptyArray()
    {
        $this->assertEmpty(Average::mode(array()));
    }

    /**
     * @dataProvider dataProviderForHarmonicMean
     */
    public function testHamonicMean(array $numbers, $mean)
    {
        $this->assertEquals($mean, Average::harmonicMean($numbers), '', 0.01);
    }

    /**
     * Data provider for hamonic mean test
     * Data: [ [ numbers ], mean ]
     */
    public function dataProviderForHarmonicMean()
    {
        return [
            [ [ 1, 2, 4, ], 1.71429 ],
            [ [ 1, 1, 1 ], 1 ],
            [ [ 1, 2, 3 ], 1.63636 ],
            [ [ 2, 3, 4 ], 2.76923 ],
            [ [ 5, 5, 6 ], 5.29412 ],
            [ [ 13, 18, 13, 14, 13, 16, 14, 21, 13 ], 14.60508 ],
            [ [ 1, 2, 4, 7 ], 2.11321 ],
            [ [ 8, 9, 10, 10, 10, 11, 11, 11, 12, 13 ], 10.31891 ],
            [ [ 6, 7, 8, 10, 12, 14, 14, 15, 16, 20 ], 10.63965 ],
            [ [ 9, 10, 11, 13, 15, 17, 17, 18, 19, 23 ], 13.98753 ],
            [ [ 12, 14, 16, 20, 24, 28, 28, 30, 32, 40 ], 21.27929 ],
            [ [ 1, 3, 5, 7, 10 ], 2.81501 ],
        ];
    }

    public function testHarmonicMeanNullWhenEmptyArray()
    {
        $this->assertNull(Average::harmonicMean(array()));
    }

    public function testHarmonicMeanExceptionNegativeValues()
    {
        $this->setExpectedException('\Exception');
        Average::harmonicMean([ 1, 2, 3, -4, 5, -6, 7 ]);
    }

    /**
     * @dataProvider dataProviderForArithmeticGeometricMean
     */
    public function testArithmeticGeometricMean($x, $y, $mean)
    {
        $this->assertEquals($mean, Average::arithmeticGeometricMean($x, $y), '', 0.00001);
    }

    /**
     * @dataProvider dataProviderForArithmeticGeometricMean
     */
    public function testAGM($x, $y, $mean)
    {
        $this->assertEquals($mean, Average::agm($x, $y), '', 0.00001);
    }

    public function dataProviderForArithmeticGeometricMean() {
        return [
            [ 24, 6, 13.4581714817256154207668131569743992430538388544 ],
            [ 2, 4, 2.913582062093814 ],
            [ 1, 1, 1 ],
            [ 43.6, 7765.332, 1856.949564100313 ],
            [ 0, 3434, 0 ],
            [ 3432, 0, 0 ],
        ];
    }

    public function testArithmeticGeometricMeanNegativeNAN() {
        $this->assertNan(Average::arithmeticGeometricMean(-32, 45));
        $this->assertNan(Average::arithmeticGeometricMean(32, -45));
        $this->assertNan(Average::agm(-32, 45));
        $this->assertNan(Average::agm(32, -45));
    }

    public function testGetAverages()
    {
        $averages = Average::getAverages([ 13, 18, 13, 14, 13, 16, 14, 21, 13 ]);
        $this->assertTrue(is_array($averages));
        $this->assertArrayHasKey('mean', $averages);
        $this->assertArrayHasKey('median', $averages);
        $this->assertArrayHasKey('mode', $averages);
        $this->assertArrayHasKey('geometric_mean', $averages);
        $this->assertTrue(is_numeric($averages['mean']));
        $this->assertTrue(is_numeric($averages['median']));
        $this->assertTrue(is_array($averages['mode']));
        $this->assertTrue(is_numeric($averages['geometric_mean']));
    }
}

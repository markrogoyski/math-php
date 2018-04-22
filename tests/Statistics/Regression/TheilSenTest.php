<?php
namespace MathPHP\Tests\Statistics\Regression;

use MathPHP\Statistics\Regression\TheilSen;

class TheilSenTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testCase constructor
     */
    public function testConstructor()
    {
        $points = [ [1,2], [2,3], [4,5], [5,7], [6,8] ];
        $regression = new TheilSen($points);
        $this->assertInstanceOf(\MathPHP\Statistics\Regression\Regression::class, $regression);
        $this->assertInstanceOf(\MathPHP\Statistics\Regression\TheilSen::class, $regression);
    }

    /**
     * @testCase getPoints
     */
    public function testGetPoints()
    {
        $points = [ [1,2], [2,3], [4,5], [5,7], [6,8] ];
        $regression = new TheilSen($points);
        $this->assertEquals($points, $regression->getPoints());
    }

    /**
     * @testCase getXs
     */
    public function testGetXs()
    {
        $points = [ [1,2], [2,3], [4,5], [5,7], [6,8] ];
        $regression = new TheilSen($points);
        $this->assertEquals([1,2,4,5,6], $regression->getXs());
    }

    /**
     * @testCase getYs
     */
    public function testGetYs()
    {
        $points = [ [1,2], [2,3], [4,5], [5,7], [6,8] ];
        $regression = new TheilSen($points);
        $this->assertEquals([2,3,5,7,8], $regression->getYs());
    }

    /**
     * @testCase     getEquation - Equation matches pattern y = mx + b
     * @dataProvider dataProviderForEquation
     * @param        array $points
     */
    public function testGetEquation(array $points)
    {
        $regression = new TheilSen($points);
        $this->assertRegExp('/^y = \d+[.]\d+x [+] \d+[.]\d+$/', $regression->getEquation());
    }

    /**
     * @return array [points]
     */
    public function dataProviderForEquation(): array
    {
        return [
            [ [ [0,0], [1,1], [2,2], [3,3], [4,4] ] ],
        ];
    }

    /**
     * @testCase     getParameters
     * @dataProvider dataProviderForParameters
     * @param        array $points
     * @param        float $m
     * @param        float $b
     */
    public function testGetParameters(array $points, float $m, float $b)
    {
        $regression = new TheilSen($points);
        $parameters = $regression->getParameters();
        $this->assertEquals($m, $parameters['m'], '', 0.0001);
        $this->assertEquals($b, $parameters['b'], '', 0.0001);
    }

    /**
     * @return array [points, m, b]
     */
    public function dataProviderForParameters(): array
    {
        return [
            [
                [ [1,2], [2,3], [4,5], [5,7], [6,8] ],
                1.225, 0.1
            ],
        ];
    }

    /**
     * @testCase     getSampleSize
     * @dataProvider dataProviderForSampleSize
     * @param        array $points
     * @param        int   $n
     */
    public function testGetSampleSize(array $points, int $n)
    {
        $regression = new TheilSen($points);
        $this->assertEquals($n, $regression->getSampleSize());
    }

    /**
     * @return array [points, n]
     */
    public function dataProviderForSampleSize()
    {
        return [
            [
                [ [1,2], [2,3], [4,5], [5,7], [6,8] ], 5
            ],
        ];
    }

    /**
     * @testCase     evaluate
     * @dataProvider dataProviderForEvaluate
     * @param        array $points
     * @param        float $x
     * @param        float $y
     */
    public function testEvaluate(array $points, float $x, float $y)
    {
        $regression = new TheilSen($points);
        $this->assertEquals($y, $regression->evaluate($x));
    }

    /**
     * @return array [points, x, y]
     */
    public function dataProviderForEvaluate(): array
    {
        return [
            [
                [ [0,0], [1,1], [2,2], [3,3], [4,4] ], // y = x + 0
                5, 5,
            ],
            [
                [ [0,0], [1,1], [2,2], [3,3], [4,4] ], // y = x + 0
                18, 18,
            ],
            [
                [ [0,0], [1,2], [2,4], [3,6] ], // y = 2x + 0
                4, 8,
            ],
            [
                [ [0,1], [1,3.5], [2,6] ], // y = 2.5x + 1
                5, 13.5
            ],
            [
                [ [0,2], [1,1], [2,0], [3,-1] ], // y = -x - 2
                4, -2
            ],
        ];
    }
}

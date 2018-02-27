<?php
namespace MathPHP\Tests\Probability\Distribution\Discrete;

use MathPHP\Probability\Distribution\Discrete\Uniform;
use MathPHP\Exception;

class UniformTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testCase     pmf returns the expectd probability
     * @dataProvider dataProviderForPmf
     * @param        int   $a
     * @param        int   $b
     * @param        float $pmf
     */
    public function testPmf(int $a, int $b, float $pmf)
    {
        $uniform = new Uniform($a, $b);
        $this->assertEquals($pmf, $uniform->pmf(), '', 0.001);
    }

    public function dataProviderForPmf(): array
    {
        return [
            [1, 2, 0.5],
            [1, 3, 0.33333],
            [1, 4, 0.25],
            [1, 5, 0.2],
        ];
    }

    /**
     * @testCase constructor throws a BadDataException if b is < a
     */
    public function testConstructorException()
    {
        $this->expectException(Exception\BadDataException::class);
        $a   = 4;
        $b   = 1;
        $uniform = new Uniform($a, $b);
    }

    /**
     * @testCase     cdf returns the expected culmulative probability
     * @dataProvider dataProviderForCdf
     * @param        int   $a
     * @param        int   $b
     * @param        float $pmf
     */
    public function testCdf(int $a, int $b, $k, float $pmf)
    {
        $uniform = new Uniform($a, $b);
        $this->assertEquals($pmf, $uniform->cdf($k), '', 0.001);
    }

    public function dataProviderForCdf(): array
    {
        return [
            [1, 4, 0, 0],
            [1, 4, 1, 1/4],
            [1, 4, 2, 2/4],
            [1, 4, 3, 3/4],
            [1, 4, 4, 4/4],
            [1, 4, 5, 1],
        ];
    }

    /**
     * @testCase     mean returns the expected average
     * @dataProvider dataProviderForAverage
     * @param        int   $a
     * @param        int   $b
     * @param        float $mean
     */
    public function testMean(int $a, int $b, float $mean)
    {
        $uniform = new Uniform($a, $b);
        $this->assertEquals($mean, $uniform->mean(), '', 0.0001);
    }

    /**
     * @testCase     median returns the expected average
     * @dataProvider dataProviderForAverage
     * @param        int   $a
     * @param        int   $b
     * @param        float $mean
     */
    public function testMedian(int $a, int $b, float $median)
    {
        $uniform = new Uniform($a, $b);
        $this->assertEquals($median, $uniform->median(), '', 0.0001);
    }

    public function dataProviderForAverage(): array
    {
        return [
            [1, 2, 3/2],
            [1, 3, 4/2],
            [1, 4, 5/2],
        ];
    }
}

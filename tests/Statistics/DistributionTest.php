<?php

namespace MathPHP\Tests\Statistics;

use MathPHP\Statistics\Distribution;

class DistributionTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @test         frequency
     * @dataProvider dataProviderForFrequency
     * @param        array $values
     * @param        array $expected
     */
    public function testFrequency(array $values, array $expected)
    {
        // When
        $frequencies = Distribution::frequency($values);

        // Then
        $this->assertEquals($expected, $frequencies);
    }

    /**
     * @return array [values, frequencies]
     */
    public function dataProviderForFrequency(): array
    {
        return [
            [
                [ 'A', 'A', 'B', 'B', 'B', 'B', 'C', 'C', 'D', 'F' ],
                [ 'A' => 2, 'B' => 4, 'C' => 2, 'D' => 1, 'F' => 1 ],
            ],
            [
                [ 1, 1, 1, 1, 1, 2, 2, 2, 3, 3, 3, 3, 3, 3, 3, 3, 3, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4],
                [ 1 => 5, 2 => 3, 3 => 9, 4 => 14 ],
            ],
            [
                [ 'yes', 'yes', 'no', 'yes', 'no', 'no', 'yes', 'yes', 'yes', 'no' ],
                [ 'yes' => 6, 'no' => 4 ],
            ],
            [
                [ 'agree', 'disagree', 'agree', 'agree', 'no opinion', 'agree', 'disagree' ],
                [ 'agree' => 4, 'disagree' => 2, 'no opinion' => 1 ],
            ],
        ];
    }

    /**
     * @test         relativeFrequency
     * @dataProvider dataProviderForRelativeFrequency
     * @param        array $values
     * @param        array $expected
     */
    public function testRelativeFrequency(array $values, array $expected)
    {
        // When
        $frequencies = Distribution::relativeFrequency($values);

        // Then
        $this->assertEquals($expected, $frequencies, '', 0.0001);
    }

    /**
     * @return array [values, frequencies]
     */
    public function dataProviderForRelativeFrequency(): array
    {
        return [
            [
                [ 'A', 'A', 'B', 'B', 'B', 'B', 'C', 'C', 'D', 'F' ],
                [ 'A' => 0.2, 'B' => 0.4, 'C' => 0.2, 'D' => 0.1, 'F' => 0.1 ],
            ],
            [
                [ 1, 1, 1, 1, 1, 2, 2, 2, 3, 3, 3, 3, 3, 3, 3, 3, 3, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4],
                [ 1 => 0.16129032, 2 => 0.09677419, 3 => 0.29032258, 4 => 0.45161290 ],
            ],
            [
                [ 'yes', 'yes', 'no', 'yes', 'no', 'no', 'yes', 'yes', 'yes', 'no' ],
                [ 'yes' => 0.6, 'no' => 0.4 ],
            ],
            [
                [ 'agree', 'disagree', 'agree', 'agree', 'no opinion', 'agree', 'disagree' ],
                [ 'agree' => 0.57142857, 'disagree' => 0.28571429, 'no opinion' => 0.14285714 ],
            ],
        ];
    }

    /**
     * @test         cumulativeFrequency
     * @dataProvider dataProviderForCumulativeFrequency
     * @param        array $values
     * @param        array $expected
     */
    public function testCumulativeFrequency(array $values, array $expected)
    {
        // When
        $frequencies = Distribution::cumulativeFrequency($values);

        // Then
        $this->assertEquals($expected, $frequencies, '', 0.0001);
    }

    /**
     * @return array [values, frequencies]
     */
    public function dataProviderForCumulativeFrequency(): array
    {
        return [
            [
                [ 'A', 'A', 'B', 'B', 'B', 'B', 'C', 'C', 'D', 'F' ],
                [ 'A' => 2, 'B' => 6, 'C' => 8, 'D' => 9, 'F' => 10 ],
            ],
            [
                [ 1, 1, 1, 1, 1, 2, 2, 2, 3, 3, 3, 3, 3, 3, 3, 3, 3, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4],
                [ 1 => 5, 2 => 8, 3 => 17, 4 => 31 ],
            ],
            [
                [ 'yes', 'yes', 'no', 'yes', 'no', 'no', 'yes', 'yes', 'yes', 'no' ],
                [ 'yes' => 6, 'no' => 10 ],
            ],
            [
                [ 'agree', 'disagree', 'agree', 'agree', 'no opinion', 'agree', 'disagree' ],
                [ 'agree' => 4, 'disagree' => 6, 'no opinion' => 7 ],
            ],
        ];
    }

    /**
     * @test         cumulativeRelativeFrequency
     * @dataProvider dataProviderForCumulativeRelativeFrequency
     * @param        array $values
     * @param        array $expected
     */
    public function testCumulativeRelativeFrequency(array $values, array $expected)
    {
        // When
        $frequencies = Distribution::cumulativeRelativeFrequency($values);

        // Then
        $this->assertEquals($expected, $frequencies, '', 0.0001);
    }

    /**
     * @return array [values, frequencies]
     */
    public function dataProviderForCumulativeRelativeFrequency(): array
    {
        return [
            [
                [ 'A', 'A', 'B', 'B', 'B', 'B', 'C', 'C', 'D', 'F' ],
                [ 'A' => 0.2, 'B' => 0.6, 'C' => 0.8, 'D' => 0.9, 'F' => 1 ],
            ],
            [
                [ 1, 1, 1, 1, 1, 2, 2, 2, 3, 3, 3, 3, 3, 3, 3, 3, 3, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4],
                [ 1 => 0.16129032, 2 => 0.25806452, 3 => 0.5483871, 4 => 1 ],
            ],
            [
                [ 'yes', 'yes', 'no', 'yes', 'no', 'no', 'yes', 'yes', 'yes', 'no' ],
                [ 'yes' => 0.6, 'no' => 1 ],
            ],
            [
                [ 'agree', 'disagree', 'agree', 'agree', 'no opinion', 'agree', 'disagree' ],
                [ 'agree' => 0.57142857, 'disagree' => 0.85714286, 'no opinion' => 1 ],
            ],
        ];
    }

    /**
     * @test         stemAndLeafPlot
     * @dataProvider dataProviderForStemAndLeafPlot
     * @param        array $values
     * @param        array $expected
     */
    public function testStemAndLeafPlot(array $values, array $expected)
    {
        // When
        $plot = Distribution::stemAndLeafPlot($values);

        // Then
        $this->assertEquals($expected, $plot);
    }

    /**
     * @return array [values, plot]
     */
    public function dataProviderForStemAndLeafPlot(): array
    {
        return [
            [
                [44, 46, 47, 49, 63, 64, 66, 68, 68, 72, 72, 75, 76, 81, 84, 88, 106, ],
                [ 4 => [4, 6, 7, 9], 5 => [], 6 => [3, 4, 6, 8, 8], 7 => [2, 2, 5, 6], 8 => [1, 4, 8], 9 => [], 10 => [6] ],
            ],
        ];
    }

    /**
     * @test stemAndLeafPlot printed to standard output
     */
    public function testStemAndLeafPlotPrint()
    {
        // Given
        $print = true;

        // Then
        $this->expectOutputString('0 | 1 2 3' . \PHP_EOL);

        // When
        Distribution::stemAndLeafPlot([1, 2, 3], $print);
    }
}

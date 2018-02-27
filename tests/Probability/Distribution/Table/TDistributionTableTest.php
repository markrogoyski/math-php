<?php
namespace MathPHP\Tests\Probability\Distribution\Table;

use MathPHP\Probability\Distribution\Table\TDistribution;
use MathPHP\Exception;

class TDistributionTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @dataProvider dataProviderForOneSidedCL
     */
    public function testGetOneSidedTValueFromConfidenceLevel(string $ν, string $cl, $t)
    {
        $this->assertEquals($t, TDistribution::getOneSidedTValueFromConfidenceLevel($ν, $cl));
    }

    public function dataProviderForOneSidedCL()
    {
        return [
            [ 1, 0, 0 ],
            [ 1, 75, 1.000 ],
            [ 1, 80, 1.376 ],
            [ 1, 97.5, 12.71 ],
            [ 1, 99.9, 318.3 ],
            [ 1, 99.95, 636.6 ],
            [ 10, 0, 0 ],
            [ 10, 75, 0.700 ],
            [ 10, 80, 0.879 ],
            [ 10, 95, 1.812 ],
            [ 10, 99.5, 3.169 ],
            [ 10, 99.95, 4.587 ],
            [ 'infinity', 0, 0 ],
            [ 'infinity', 75, 0.674 ],
            [ 'infinity', 97.5, 1.960 ],
            [ 'infinity', 99.95, 3.291 ],
        ];
    }

    /**
     * @dataProvider dataProviderForTwoSidedCL
     */
    public function testGetTwoSidedTValueFromConfidenceLevel(string $ν, string $cl, $t)
    {
        $this->assertEquals($t, TDistribution::getTwoSidedTValueFromConfidenceLevel($ν, $cl));
    }

    public function dataProviderForTwoSidedCL()
    {
        return [
            [ 1, 0, 0 ],
            [ 1, 50, 1.000 ],
            [ 1, 60, 1.376 ],
            [ 1, 95, 12.71 ],
            [ 1, 99.8, 318.3 ],
            [ 1, 99.9, 636.6 ],
            [ 10, 0, 0 ],
            [ 10, 50, 0.700 ],
            [ 10, 60, 0.879 ],
            [ 10, 90, 1.812 ],
            [ 10, 99, 3.169 ],
            [ 10, 99.9, 4.587 ],
            [ 'infinity', 0, 0 ],
            [ 'infinity', 50, 0.674 ],
            [ 'infinity', 95, 1.960 ],
            [ 'infinity', 99.9, 3.291 ],
        ];
    }

    /**
     * @dataProvider dataProviderForOneSidedAlpha
     */
    public function testGetOneSidedTValueFromAlpha(string $ν, string $α, $t)
    {
        $this->assertEquals($t, TDistribution::getOneSidedTValueFromAlpha($ν, $α));
    }

    public function dataProviderForOneSidedAlpha()
    {
        return [
            [ 1, '0.50', 0 ],
            [ 1, 0.25, 1.000 ],
            [ 1, '0.20', 1.376 ],
            [ 1, 0.025, 12.71 ],
            [ 1, 0.001, 318.3 ],
            [ 1, 0.0005, 636.6 ],
            [ 10, '0.50', 0 ],
            [ 10, 0.25, 0.700 ],
            [ 10, '0.20', 0.879 ],
            [ 10, 0.05, 1.812 ],
            [ 10, 0.005, 3.169 ],
            [ 10, 0.0005, 4.587 ],
            [ 'infinity', '0.50', 0 ],
            [ 'infinity', 0.25, 0.674 ],
            [ 'infinity', 0.025, 1.960 ],
            [ 'infinity', 0.0005, 3.291 ],
        ];
    }

    /**
     * @dataProvider dataProviderForTwoSidedAlpha
     */
    public function testGetTwoSidedTValueFromAlpha(string $ν, string $α, $t)
    {
        $this->assertEquals($t, TDistribution::getTwoSidedTValueFromAlpha($ν, $α));
    }

    public function dataProviderForTwoSidedAlpha()
    {
        return [
            [ 1, '1.00', 0 ],
            [ 1, '0.50', 1.000 ],
            [ 1, '0.40', 1.376 ],
            [ 1, 0.05, 12.71 ],
            [ 1, 0.002, 318.3 ],
            [ 1, 0.001, 636.6 ],
            [ 10, '1.00', 0 ],
            [ 10, '0.50', 0.700 ],
            [ 10, '0.40', 0.879 ],
            [ 10, '0.10', 1.812 ],
            [ 10, 0.01, 3.169 ],
            [ 10, 0.001, 4.587 ],
            [ 'infinity', '1.00', 0 ],
            [ 'infinity', '0.50', 0.674 ],
            [ 'infinity', 0.05, 1.960 ],
            [ 'infinity', 0.001, 3.291 ],
        ];
    }

    public function testGetOneSidedTValueFromConfidenceLevelExceptionBadDF()
    {
        $this->expectException(Exception\BadDataException::class);
        TDistribution::getOneSidedTValueFromConfidenceLevel(1234, 99);
    }

    public function testGetTwoSidedTValueFromConfidenceLevelExceptionBadDF()
    {
        $this->expectException(Exception\BadDataException::class);
        TDistribution::getTwoSidedTValueFromConfidenceLevel(1234, 99);
    }

    public function testGetOneSidedTValueFromAlphaExceptionBadDF()
    {
        $this->expectException(Exception\BadDataException::class);
        TDistribution::getOneSidedTValueFromAlpha(1234, 0.05);
    }

    public function testGetTwoSidedTValueFromAlphaExceptionBadDF()
    {
        $this->expectException(Exception\BadDataException::class);
        TDistribution::getTwoSidedTValueFromAlpha(1234, 0.05);
    }

    public function testGetOneSidedTValueFromConfidenceLevelExceptionBadCL()
    {
        $this->expectException(Exception\BadDataException::class);
        TDistribution::getOneSidedTValueFromConfidenceLevel(1, 155);
    }

    public function testGetTwoSidedTValueFromConfidenceLevelExceptionBadCL()
    {
        $this->expectException(Exception\BadDataException::class);
        TDistribution::getTwoSidedTValueFromConfidenceLevel(1, 155);
    }

    public function testGetOneSidedTValueFromAlphaExceptionBadAlpha()
    {
        $this->expectException(Exception\BadDataException::class);
        TDistribution::getOneSidedTValueFromAlpha(1, 999);
    }

    public function testGetTwoSidedTValueFromAlphaExceptionBadAlpha()
    {
        $this->expectException(Exception\BadDataException::class);
        TDistribution::getTwoSidedTValueFromAlpha(1, 999);
    }
}

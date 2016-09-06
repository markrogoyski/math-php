<?php
namespace Math\Probability\Distribution\Table;

class ChiSquaredlTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider dataProviderForTable
     */
    public function testChiSquaredValuesFromConstant(int $df, float $p, float $χ²)
    {
        $p = sprintf('%1.3f', $p);
        $this->assertEquals($χ², ChiSquared::CHI_SQUARED_SCORES[$df][$p]);
    }

    /**
     * @dataProvider dataProviderForTable
     */
    public function testChiSquaredValuesFromFunction(int $df, float $p, float $χ²)
    {
        $this->assertEquals($χ², ChiSquared::getChiSquareValue($df, $p));
    }

    public function dataProviderForTable()
    {
        return [
            [1, 0.995, 0.0000393],
            [1, 0.05, 3.841],
            [1, 0.050, 3.841],
            [1, 0.01, 6.635],
            [5, 0.05, 11.070],
            [5, 0.01, 15.086],
        ];
    }

    public function testChiSquaredException()
    {
        $this->setExpectedException('\Exception');

        $df = 88474;
        $p  = 0.44;
        ChiSquared::getChiSquareValue($df, $p);
    }
}

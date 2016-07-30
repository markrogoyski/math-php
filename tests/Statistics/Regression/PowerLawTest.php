<?php
namespace Math\Statistics\Regression;

class PowerLawTest extends \PHPUnit_Framework_TestCase
{

    public function testConstructor()
    {
        $points = [ [ 83, 183 ], [ 71, 168 ], [ 64, 171 ], [ 69, 178 ], [ 69, 176 ], [ 64, 172 ], [ 68, 165 ], [ 59, 158 ], [ 81, 183 ], [ 91, 182 ], [ 57, 163 ], [ 65, 175 ], [ 58, 164 ], [ 62, 175 ] ];
        $regression = new PowerLaw($points);
        $this->assertInstanceOf('Math\Statistics\Regression\Regression', $regression);
        $this->assertInstanceOf('Math\Statistics\Regression\PowerLaw', $regression);
    }

    /**
     * @dataProvider dataProviderForEquation
     * Equation matches pattern y = axᵇ
     */
    public function testGetEquation(array $points)
    {
        $regression = new PowerLaw($points);
        $this->assertRegExp('/^y = \d+[.]\d+x\^\d+[.]\d+$/', $regression->getEquation());
    }

    public function dataProviderForEquation()
    {
        return [
            [
                [ [ 83, 183 ], [ 71, 168 ], [ 64, 171 ], [ 69, 178 ], [ 69, 176 ], [ 64, 172 ], [ 68, 165 ], [ 59, 158 ], [ 81, 183 ], [ 91, 182 ], [ 57, 163 ], [ 65, 175 ], [ 58, 164 ], [ 62, 175 ] ],
            ]
        ];
    }

    /**
     * @dataProvider dataProviderForParameters
     */
    public function testGetParameters(array $points, $a, $b)
    {
        $regression = new PowerLaw($points);
        $parameters = $regression->getParameters();
        $this->assertEquals($a, $parameters['a'], '', 0.0001);
        $this->assertEquals($b, $parameters['b'], '', 0.0001);
    }

    public function dataProviderForParameters()
    {
        return [
            [
                [ [ 83, 183 ], [ 71, 168 ], [ 64, 171 ], [ 69, 178 ], [ 69, 176 ], [ 64, 172 ], [ 68, 165 ], [ 59, 158 ], [ 81, 183 ], [ 91, 182 ], [ 57, 163 ], [ 65, 175 ], [ 58, 164 ], [ 62, 175 ] ],
                56.48338, 0.2641538,
            ],
        ];
    }

    /**
     * @dataProvider dataProviderForEvaluate
     */
    public function testEvaluate(array $points, $x, $y)
    {
        $regression = new PowerLaw($points);
        $this->assertEquals($y, $regression->evaluate($x), '', 0.0001);
    }

    public function dataProviderForEvaluate()
    {
        // y = 56.48338x^0.2641538
        return [
            [
                [ [ 83, 183 ], [ 71, 168 ], [ 64, 171 ], [ 69, 178 ], [ 69, 176 ], [ 64, 172 ], [ 68, 165 ], [ 59, 158 ], [ 81, 183 ], [ 91, 182 ], [ 57, 163 ], [ 65, 175 ], [ 58, 164 ], [ 62, 175 ] ],
                83, 181.4898448,
            ],
            [
                [ [ 83, 183 ], [ 71, 168 ], [ 64, 171 ], [ 69, 178 ], [ 69, 176 ], [ 64, 172 ], [ 68, 165 ], [ 59, 158 ], [ 81, 183 ], [ 91, 182 ], [ 57, 163 ], [ 65, 175 ], [ 58, 164 ], [ 62, 175 ] ],
                71, 174.1556182,
            ],
            [
                [ [ 83, 183 ], [ 71, 168 ], [ 64, 171 ], [ 69, 178 ], [ 69, 176 ], [ 64, 172 ], [ 68, 165 ], [ 59, 158 ], [ 81, 183 ], [ 91, 182 ], [ 57, 163 ], [ 65, 175 ], [ 58, 164 ], [ 62, 175 ] ],
                64, 169.4454327,
            ],
            [
                [ [ 83, 183 ], [ 71, 168 ], [ 64, 171 ], [ 69, 178 ], [ 69, 176 ], [ 64, 172 ], [ 68, 165 ], [ 59, 158 ], [ 81, 183 ], [ 91, 182 ], [ 57, 163 ], [ 65, 175 ], [ 58, 164 ], [ 62, 175 ] ],
                57, 164.3393562,
            ],
            [
                [ [ 83, 183 ], [ 71, 168 ], [ 64, 171 ], [ 69, 178 ], [ 69, 176 ], [ 64, 172 ], [ 68, 165 ], [ 59, 158 ], [ 81, 183 ], [ 91, 182 ], [ 57, 163 ], [ 65, 175 ], [ 58, 164 ], [ 62, 175 ] ],
                91, 185.955396,
            ],
        ];
    }
}

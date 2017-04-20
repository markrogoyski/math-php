<?php
namespace MathPHP;

class TrigonometryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @testCase     method returns points on a unit circle.
     * @dataProvider dataProviderForUnitCircle
     */
    public function testUnitCircle(int $num, array $results)
    {
        $this->assertEquals($results, Trigonometry::unitCircle($num));
    }

    public function dataProviderForUnitCircle()
    {
        $sqrt = 1 / sqrt(2);
        return [
            [5, [[1, 0], [0, 1], [-1, 0], [0, -1], [1, 0]]],
            [9, [[1, 0], [$sqrt, $sqrt], [0, 1], [-$sqrt, $sqrt], [-1, 0], [-$sqrt, -$sqrt], [0, -1], [$sqrt, -$sqrt], [1, 0]]],
        ];
    }
}

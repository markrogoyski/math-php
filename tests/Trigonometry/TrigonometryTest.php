<?php

namespace MathPHP\Tests;

use MathPHP\Trigonometry;
use MathPHP\Exception\OutOfBoundsException;

class TrigonometryTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @test         unitCircle returns points on a unit circle.
     * @dataProvider dataProviderForUnitCircle
     * @param        int   $points
     * @param        array $expected
     */
    public function testUnitCircle(int $points, array $expected)
    {
        // When
        $unitCircle = Trigonometry::unitCircle($points);

        // Then
        $this->assertEqualsWithDelta($expected, $unitCircle, 0.00000001);
    }

    /**
     * @return array
     */
    public function dataProviderForUnitCircle(): array
    {
        return [
            [0, []],
            [1, [[1, 0]]],
            [2, [[1, 0], [1, 0]]],
            [3, [[1, 0], [-1, 0], [1, 0]]],
            [5, [[1, 0], [0, 1], [-1, 0], [0, -1], [1, 0]]],
            [9, [[1, 0], [\M_SQRT1_2, \M_SQRT1_2], [0, 1], [-\M_SQRT1_2, \M_SQRT1_2], [-1, 0], [-\M_SQRT1_2, -\M_SQRT1_2], [0, -1], [\M_SQRT1_2, -\M_SQRT1_2], [1, 0]]],
        ];
    }

    /**
     * @test unitCircle throws exception for negative points
     */
    public function testUnitCircleNegativePointsThrowsException()
    {
        // Then
        $this->expectException(OutOfBoundsException::class);

        // When
        Trigonometry::unitCircle(-1);
    }
}

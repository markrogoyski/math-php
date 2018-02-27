<?php
namespace MathPHP\Tests;

use MathPHP\Trigonometry;

class TrigonometryTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testCase     unitCircle returns points on a unit circle.
     * @dataProvider dataProviderForUnitCircle
     * @param        int $num
     * @param        array $results
     */
    public function testUnitCircle(int $num, array $results)
    {
        $this->assertEquals($results, Trigonometry::unitCircle($num));
    }

    public function dataProviderForUnitCircle(): array
    {
        return [
            [5, [[1, 0], [0, 1], [-1, 0], [0, -1], [1, 0]]],
            [9, [[1, 0], [\M_SQRT1_2, \M_SQRT1_2], [0, 1], [-\M_SQRT1_2, \M_SQRT1_2], [-1, 0], [-\M_SQRT1_2, -\M_SQRT1_2], [0, -1], [\M_SQRT1_2, -\M_SQRT1_2], [1, 0]]],
        ];
    }
}

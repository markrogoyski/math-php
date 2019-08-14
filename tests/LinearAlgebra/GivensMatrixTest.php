<?php
namespace MathPHP\Tests\LinearAlgebra;

use MathPHP\Exception\OutOfBoundsException;
use MathPHP\LinearAlgebra\MatrixFactory;

class GivensMatrixTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @test Test that the construction fails when parameters are out of bounds
     */
    public function testException()
    {
        $this->expectException(OutOfBoundsException::class);
        // When
        $matrix = MatrixFactory::givens(2, 3, \M_PI, 2);
    }

    /**
     * @test         Test that the function returns a properly formatted Matrix
     * @dataProvider dataProviderForTestGivensMatrix
     * @param        int $m
     * @param        int $n
     * @param        float $angle
     * @param        int $size
     * @param        array $expected
     */
    public function testGivensMatrix(int $m, int $n, float $angle, int $size, array $expected)
    {
        $G = MatrixFactory::givens($m, $n, $angle, $size);
        $this->assertEquals($expected, $G->getMatrix());
    }

    public function dataProviderForTestGivensMatrix()
    {
        return [
            [
                0, 1, \M_PI, 2,
                [[-1, 0],
                 [0, -1]]
            ],
            [
                0, 2, \M_PI / 4, 3,
                [[\M_SQRT1_2, 0,  -1 * \M_SQRT1_2],
                 [0,          1,  0],
                 [\M_SQRT1_2, 0, \M_SQRT1_2]],
            ],
        ];
    }
}

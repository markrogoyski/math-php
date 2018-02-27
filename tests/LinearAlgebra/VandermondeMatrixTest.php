<?php
namespace MathPHP\Tests\LinearAlgebra;

use MathPHP\LinearAlgebra\VandermondeMatrix;
use MathPHP\LinearAlgebra\Matrix;

class VandermondeMatrixTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @dataProvider dataProviderForTestConstructor
     */
    public function testConstructor($M, int $n, $V)
    {
        $M = new VandermondeMatrix($M, $n);
        $V = new Matrix($V);

        $this->assertInstanceOf(VandermondeMatrix::class, $M);
        $this->assertInstanceOf(Matrix::class, $M);
        
        $m = $V->getM();
        for ($i = 0; $i < $m; $i++) {
            $this->assertEquals($V[$i], $M[$i]);
        }
        $m = $M->getM();
        for ($i = 0; $i < $m; $i++) {
            $this->assertEquals($V[$i], $M[$i]);
        }
    }

    public function dataProviderForTestConstructor()
    {
        return [
            [
                [1, 2, 3, 4], 4,
                [
                    [1, 1, 1, 1],
                    [1, 2, 4, 8],
                    [1, 3, 9, 27],
                    [1, 4, 16, 64],
                ],
            ],
            [
                [10, 5, 4], 3,
                [
                    [1, 10, 100],
                    [1, 5, 25],
                    [1, 4, 16],
                ],
            ],
        ];
    }
}

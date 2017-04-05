<?php
namespace MathPHP\LinearAlgebra;

class EigenvalueTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider dataProviderForEigenvalues
     */
    public function testEigenvalues(array $A, array $S)
    {
        $A = MatrixFactory::create($A);
        $this->assertEquals($S, Eigenvalue::quadratic($A)->getMatrix(), '', 0.0001);
    }
    public function dataProviderForEigenvalues()
    {
        return [
            [
                [
                    [0, 1],
                    [-2, -3],
                ],
                [
                    [-1, 0],
                    [0, -2],
                ],
            ],
            [
                [
                    [-2, -4, 2],
                    [-2, 1, 2],
                    [4, 2, 5],
                ],
                [
                    [6, 0, 0],
                    [0, -5, 0],
                    [0, 0, 3],
                ],
            ],
        ];
    }
}

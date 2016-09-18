<?php
namespace Math\LinearAlgebra;

class VectorOperationsTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider dataProviderForDotProduct
     */
    public function testDotProduct(array $A, array $B, $dot_product)
    {
        $A  = new Vector($A);
        $B  = new Vector($B);
        $this->assertEquals($dot_product, $A->dotProduct($B));
    }

    /**
     * @dataProvider dataProviderForDotProduct
     */
    public function testInnerProduct(array $A, array $B, $dot_product)
    {
        $A  = new Vector($A);
        $B  = new Vector($B);
        $this->assertEquals($dot_product, $A->innerProduct($B));
    }

    public function dataProviderForDotProduct()
    {
        return [
            [ [ 1, 2, 3 ],  [ 4, -5, 6 ],  12 ],
            [ [ -4, -9],    [ -1, 2],     -14 ],
            [ [ 6, -1, 3 ], [ 4, 18, -2 ],  0 ],
        ];
    }

    public function testDotProductExceptionSizeDifference()
    {
        $A = new Vector([1, 2]);
        $B = new Vector([1, 2, 3]);

        $this->setExpectedException('\Exception');
        $A->dotProduct($B);
    }

    /**
     * @dataProvider dataProviderForCrossProduct
     */
    public function testCrossProduct(array $A, array $B, array $R)
    {
        $A = new Vector($A);
        $B = new Vector($B);
        $R = new Vector($R);
        $this->assertEquals($R, $A->crossProduct($B));
    }

    public function dataProviderForCrossProduct()
    {
        return [
            [
                [1, 2, 3],
                [4, -5, 6],
                [27,6,-13],
            ],
            [
                [-1, 2, -3],
                [4,-5,6],
                [-3,-6,-3],
            ],
            [
                [0,0,0],
                [0,0,0],
                [0,0,0],
            ],
            [
                [4, 5, 6],
                [7, 8, 9],
                [-3, 6, -3],
            ],
            [
                [4, 9, 3],
                [12, 11, 4],
                [3, 20, -64],
            ],
            [
                [-4, 9, 3],
                [12, 11, 4],
                [3, 52, -152],
            ],
            [
                [4, -9, 3],
                [12, 11, 4],
                [-69, 20, 152],
            ],
            [
                [4, 9, -3],
                [12, 11, 4],
                [69, -52, -64],
            ],
            [
                [4, 9, 3],
                [-12, 11, 4],
                [3, -52, 152],
            ],
            [
                [4, 9, 3],
                [12, -11, 4],
                [69, 20, -152],
            ],
            [
                [4, 9, 3],
                [12, 11, -4],
                [-69, 52, -64],
            ],
        ];
    }

    /**
     * @dataProvider dataProviderForCrossProductExceptionWrongSize
     */
    public function testCrossProductExceptionWrongSize(array $A, array $B)
    {
        $A = new Vector($A);
        $B = new Vector($B);

        $this->setExpectedException('\Exception');
        $A->crossProduct($B);
    }

    public function dataProviderForCrossProductExceptionWrongSize()
    {
        return [
            [
                [1, 2],
                [1, 2, 3],
            ],
            [
                [1, 2, 3],
                [],
            ],
        ];
    }

    /**
     * @dataProvider dataProviderForOuterProduct
     */
    public function testOuterProduct(array $A, array $B, array $R)
    {
        $A = new Vector($A);
        $B = new Vector($B);
        $R = new Matrix($R);
        $this->assertEquals($R, $A->outerProduct($B));
    }

    public function dataProviderForOuterProduct()
    {
        return [
            [
                [1, 2],
                [3, 4, 5],
                [
                    [3, 4, 5],
                    [6, 8, 10],
                ],
            ],
            [
                [3, 4, 5],
                [1, 2],
                [
                    [3, 6],
                    [4, 8],
                    [5, 10],
                ],
            ],
        ];
    }

    /**
     * @dataProvider dataProviderForL1Norm
     */
    public function testL1Norm(array $A, $l₁norm)
    {
        $A = new Vector($A);

        $this->assertEquals($l₁norm, $A->l1Norm(), '', 0.0001);
    }

    public function dataProviderForL1Norm()
    {
        return [
            [ [1, 2, 3], 6 ],
            [ [-7, 5, 5], 17 ],
        ];
    }

    /**
     * @dataProvider dataProviderForL2Norm
     */
    public function testL2Norm(array $A, $l²norm)
    {
        $A = new Vector($A);

        $this->assertEquals($l²norm, $A->l2Norm(), '', 0.0001);
    }

    public function dataProviderForL2Norm()
    {
        return [
            [ [1, 2, 3], 3.7416573867739413 ],
            [ [7, 5, 5], 9.9498743710662 ],
        ];
    }

    /**
     * @dataProvider dataProviderForPNorm
     */
    public function testPNorm(array $A, $p, $pnorm)
    {
        $A = new Vector($A);

        $this->assertEquals($pnorm, $A->pNorm($p), '', 0.0001);
    }

    public function dataProviderForPNorm()
    {
        return [
            [ [1, 2, 3], 2, 3.74165738677 ],
            [ [1, 2, 3], 3, 3.30192724889 ],
        ];
    }

    /**
     * @dataProvider dataProviderForMaxNorm
     */
    public function testMaxNorm(array $A, $maxnorm)
    {
        $A = new Vector($A);

        $this->assertEquals($maxnorm, $A->maxNorm(), '', 0.0001);
    }

    public function dataProviderForMaxNorm()
    {
        return [
            [ [1, 2, 3], 3 ],
            [ [7, -5, 5], 7 ],
            [ [-3, -7, 6, 3], 7],
        ];
    }

    /**
     * @dataProvider dataProviderForSum
     */
    public function testSum(array $A, $sum)
    {
        $A = new Vector($A);

        $this->assertEquals($sum, $A->sum(), '', 0.00001);
    }

    public function dataProviderForSum()
    {
        return [
            [ [1, 2, 3], 6 ],
            [ [2, 3, 4, 8, 8, 9], 34 ],
        ];
    }

    /**
     * @dataProvider dataProviderForScalarMultiply
     */
    public function testScalarMultiply(array $A, $k, array $R)
    {
        $A  = new Vector($A);
        $kA = $A->scalarMultiply($k);
        $R  = new Vector($R);

        $this->assertEquals($R, $kA);
        $this->assertEquals($R->getVector(), $kA->getVector());
    }

    public function dataProviderForScalarMultiply()
    {
        return [
            [
                [],
                2,
                [],
            ],
            [
                [1],
                2,
                [2],
            ],
            [
                [2, 3],
                2,
                [4, 6],
            ],
            [
                [1, 2, 3],
                2,
                [2, 4, 6],
            ],
            [
                [1, 2, 3, 4, 5],
                5,
                [5, 10, 15, 20, 25],
            ],
            [
                [1, 2, 3, 4, 5],
                0,
                [0, 0, 0, 0, 0],
            ],
            [
                [1, 2, 3, 4, 5],
                -2,
                [-2, -4, -6, -8, -10],
            ],
            [
                [1, 2, 3, 4, 5],
                0.2,
                [0.2, 0.4, 0.6, 0.8, 1],
            ],
        ];
    }

    /**
     * @dataProvider dataProviderForAdd
     */
    public function testAdd(array $A, array $B, array $R)
    {
        $A    = new Vector($A);
        $B    = new Vector($B);
        $A＋B = $A->add($B);
        $R    = new Vector($R);

        $this->assertEquals($R, $A＋B);
        $this->assertEquals($R->getVector(), $A＋B->getVector());
    }

    public function dataProviderForAdd()
    {
        return [
            [
                [1, 2, 3],
                [1, 2, 3],
                [2, 4, 6],
            ],
        ];
    }
}

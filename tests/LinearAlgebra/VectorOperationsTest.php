<?php
namespace MathPHP\Tests\LinearAlgebra;

use MathPHP\LinearAlgebra\Vector;
use MathPHP\LinearAlgebra\Matrix;
use MathPHP\Exception;

class VectorOperationsTest extends \PHPUnit\Framework\TestCase
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

        $this->expectException(Exception\VectorException::class);
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

        $this->expectException(Exception\VectorException::class);
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
        $this->assertEquals($R->getMatrix(), $A->outerProduct($B)->getMatrix());
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
            [
                [1],
                [2],
                [
                    [2],
                ],
            ],
            [
                [1, 2],
                [2, 3],
                [
                    [2, 3],
                    [4, 6],
                ],
            ],
            [
                [1, 2, 3],
                [2, 3, 4],
                [
                    [2, 3, 4],
                    [4, 6, 8],
                    [6, 9, 12],
                ],
            ],
            [
                [1, 2, 3, 4],
                [2, 3, 4, 5],
                [
                    [2, 3, 4, 5],
                    [4, 6, 8, 10],
                    [6, 9, 12, 15],
                    [8, 12, 16, 20],
                ],
            ],
            [
                [3, 2, 6, 4],
                [4, 5, 1, 7],
                [
                    [12, 15, 3, 21],
                    [8, 10, 2, 14],
                    [24, 30, 6, 42],
                    [16, 20, 4, 28],
                ],
            ],
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
     * @dataProvider dataProviderForScalarDivide
     */
    public function testScalarDivide(array $A, $k, array $R)
    {
        $A    = new Vector($A);
        $A／k = $A->scalarDivide($k);
        $R    = new Vector($R);

        $this->assertEquals($R, $A／k);
        $this->assertEquals($R->getVector(), $A／k->getVector());
    }

    public function dataProviderForScalarDivide()
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
                [1/2],
            ],
            [
                [2, 4],
                2,
                [1, 2],
            ],
            [
                [1, 2, 3],
                2,
                [1/2, 1, 3/2],
            ],
            [
                [5, 10, 15, 20, 25],
                5,
                [1, 2, 3, 4, 5],
            ],
            [
                [0, 0, 0, 0, 0],
                47,
                [0, 0, 0, 0, 0],
            ],
            [
                [-2, -4, -6, -8, -10],
                -2,
                [1, 2, 3, 4, 5],
            ],
            [
                [1, 2, 3, 4, 5],
                0.2,
                [5, 10, 15, 20, 25],
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
                [],
                [],
                [],
            ],
            [
                [1],
                [2],
                [3],
            ],
            [
                [1, 2, 3],
                [1, 2, 3],
                [2, 4, 6],
            ],
            [
                [1, 2, 3],
                [-2, -2, -4],
                [-1, 0, -1],
            ],
        ];
    }

    public function testAddExceptionSizeMisMatch()
    {
        $A = new Vector([1, 2, 3]);
        $B = new Vector([1, 2]);

        $this->expectException(Exception\VectorException::class);
        $A->add($B);
    }

    /**
     * @dataProvider dataProviderForSubtract
     */
    public function testSubtract(array $A, array $B, array $R)
    {
        $A    = new Vector($A);
        $B    = new Vector($B);
        $A−B = $A->subtract($B);
        $R    = new Vector($R);

        $this->assertEquals($R, $A−B);
        $this->assertEquals($R->getVector(), $A−B->getVector());
    }

    public function dataProviderForSubtract()
    {
        return [
            [
                [],
                [],
                [],
            ],
            [
                [3],
                [2],
                [1],
            ],
            [
                [2, 2, 2],
                [1, 2, 3],
                [1, 0, -1],
            ],
            [
                [2, 2, 2],
                [-1, -2, -3],
                [3, 4, 5],
            ],
        ];
    }

    public function testSubtractExceptionSizeMisMatch()
    {
        $A = new Vector([1, 2, 3]);
        $B = new Vector([1, 2]);

        $this->expectException(Exception\VectorException::class);
        $A->subtract($B);
    }

    /**
     * @dataProvider dataProviderForLength
     */
    public function testLength(array $A, $l²norm)
    {
        $A = new Vector($A);

        $this->assertEquals($l²norm, $A->length(), '', 0.0001);
    }

    public function dataProviderForLength()
    {
        return [
            [ [1, 2, 3], 3.7416573867739413 ],
            [ [7, 5, 5], 9.9498743710662 ],
            [ [3, 3, 3], 5.196152422706632 ],
            [ [2, 2, 2], 3.4641016151377544 ],
            [ [1, 1, 1], 1.7320508075688772 ],
            [ [0, 0, 0], 0 ],
            [ [1, 0, 0], 1 ],
            [ [1, 1, 0], 1.4142135623730951 ],
            [ [-1, 1, 0], 1.4142135623730951 ],
        ];
    }

    /**
     * @dataProvider dataProviderForNormalize
     */
    public function testNormalize(array $A, array $expected)
    {
        $A        = new Vector($A);
        $Â        = $A->normalize();
        $expected = new Vector($expected);

        $this->assertEquals($expected, $Â, '', 0.00000001);
        $this->assertEquals($expected->getVector(), $Â->getVector(), '', 0.00000001);
    }

    public function dataProviderForNormalize()
    {
        return [
            [
                [3, 5],
                [0.51449575542753, 0.85749292571254],
            ],
            [
                [3, 1, 2],
                [0.80178372573727, 0.26726124191242, 0.53452248382485],
            ],
        ];
    }

    /**
     * @dataProvider dataProviderForPerpendicular
     */
    public function testPerpendicular(array $A, array $expected)
    {
        $A        = new Vector($A);
        $A⊥       = $A->perpendicular();
        $expected = new Vector($expected);

        $this->assertEquals($expected, $A⊥);
        $this->assertEquals($expected->getVector(), $A⊥->getVector());
    }

    public function dataProviderForPerpendicular()
    {
        return [
            [
                [3, 5],
                [-5, 3],
            ],
            [
                [2, 3],
                [-3, 2],
            ],
        ];
    }

    public function testPerpendicularExceptionNGreaterThanTwo()
    {
        $A = new Vector([1, 2, 3]);

        $this->expectException(Exception\VectorException::class);
        $A->perpendicular();
    }

    /**
     * @dataProvider dataProviderForPerpDotProduct
     */
    public function testPerpDotProduct(array $A, array $B, $expected)
    {
        $A        = new Vector($A);
        $B        = new Vector($B);
        $A⊥⋅B     = $A->perpDotProduct($B);

        $this->assertEquals($expected, $A⊥⋅B);
    }

    public function dataProviderForPerpDotProduct()
    {
        return [
            [
                [3, -2],
                [1, 2],
                8,
            ],
            [
                [2, 0],
                [-1, 3],
                6
            ],
        ];
    }

    public function testPerpDotProductExceptionNNotBothTwo()
    {
        $A = new Vector([1, 2, 3]);
        $B = new Vector([1, 2, 3]);

        $this->expectException(Exception\VectorException::class);
        $A->perpDotProduct($B);
    }

    /**
     * @dataProvider dataProviderForProjection
     */
    public function testProjection(array $A, array $B, array $expected)
    {
        $A        = new Vector($A);
        $B        = new Vector($B);
        $projₐb   = $A->projection($B);
        $expected = new Vector($expected);

        $this->assertEquals($expected, $projₐb);
        $this->assertEquals($expected->getVector(), $projₐb->getVector());
    }

    public function dataProviderForProjection()
    {
        return [
            [
                [2, 4],
                [5, 3],
                [3.2352941176468, 1.94117647058808],
            ],
            [
                [1, 2, 3],
                [4, 5, 6],
                [128/77, 160/77, 192/77],
            ],
            [
                [4, 5, 6],
                [1, 2, 3],
                [16/7, 32/7, 48/7],
            ],
            [
                [2, 9, -4],
                [-1, 5, 5],
                [-23/51, 115/51, 115/51],
            ],
            [
                [1, 1, 1],
                [1, 1, 1],
                [1, 1, 1],
            ],
            [
                [1, 1, 1],
                [2, 2, 2],
                [1, 1, 1],
            ],
            [
                [2, 2, 2],
                [1, 1, 1],
                [2, 2, 2],
            ],
            [
                [1, 2, 1],
                [2, 1, 2],
                [4/3, 2/3, 4/3],
            ],
        ];
    }

    /**
     * @dataProvider dataProviderForPerp
     */
    public function testPerp(array $A, array $B, array $expected)
    {
        $A        = new Vector($A);
        $B        = new Vector($B);
        $perpₐb   = $A->perp($B);
        $expected = new Vector($expected);

        $this->assertEquals($expected, $perpₐb);
        $this->assertEquals($expected->getVector(), $perpₐb->getVector());
    }

    public function dataProviderForPerp()
    {
        return [
            [
                [2, 4],
                [5, 3],
                [-1.23529411764, 2.0588235294],
            ],
        ];
    }

    /**
     * @dataProvider dataProviderForDirectProduct
     */
    public function testDirectProduct(array $A, array $B, array $expected)
    {
        $A        = new Vector($A);
        $B        = new Vector($B);
        $AB       = $A->directProduct($B);
        $expected = new Matrix($expected);

        $this->assertEquals($expected->getMatrix(), $AB->getMatrix());
    }

    public function dataProviderForDirectProduct()
    {
        return [
            [
                [1],
                [2],
                [
                    [2],
                ],
            ],
            [
                [1, 2],
                [2, 3],
                [
                    [2, 3],
                    [4, 6],
                ],
            ],
            [
                [1, 2, 3],
                [2, 3, 4],
                [
                    [2, 3, 4],
                    [4, 6, 8],
                    [6, 9, 12],
                ],
            ],
            [
                [1, 2, 3, 4],
                [2, 3, 4, 5],
                [
                    [2, 3, 4, 5],
                    [4, 6, 8, 10],
                    [6, 9, 12, 15],
                    [8, 12, 16, 20],
                ],
            ],
            [
                [3, 2, 6, 4],
                [4, 5, 1, 7],
                [
                    [12, 15, 3, 21],
                    [8, 10, 2, 14],
                    [24, 30, 6, 42],
                    [16, 20, 4, 28],
                ],
            ],
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
     * @testCase     kroneckerProduct returns the expected Vector
     * @dataProvider dataProviderForKroneckerProduct
     * @param        array $A
     * @param        array $B
     * @param        array $expected
     */
    public function testKroneckerProduct(array $A, array $B, array $expected)
    {
        $A = new Vector($A);
        $B = new Vector($B);
        $R = new Vector($expected);

        $A⨂B = $A->kroneckerProduct($B);

        $this->assertEquals($R, $A⨂B);
    }

    public function dataProviderForKroneckerProduct(): array
    {
        return [
            [
                [1],
                [1],
                [1],
            ],
            [
                [2],
                [3],
                [6],
            ],
            [
                [1, 2],
                [3, 4],
                [3, 4, 6, 8],
            ],
            [
                [4, 6],
                [3, 9],
                [12, 36, 18, 54],
            ],
            [
                [1, 2, 3],
                [4, 5, 6],
                [4, 5, 6, 8, 10, 12, 12, 15, 18],
            ],
            [
                [5, 3, 9, 8],
                [1, 6, 5, 12],
                [5, 30, 25, 60, 3, 18, 15, 36, 9, 54, 45, 108, 8, 48, 40, 96],
            ],
        ];
    }
}

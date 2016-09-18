<?php
namespace Math\LinearAlgebra;

class VectorTest extends \PHPUnit_Framework_TestCase
{

    public function setUp()
    {
        $this->A = [1, 2, 3, 4, 5];
        $this->vector = new Vector($this->A);
    }

    public function testConstructor()
    {
        $this->assertInstanceOf('Math\LinearAlgebra\Vector', $this->vector);
    }

    public function testGetVector()
    {
        $this->assertEquals($this->A, $this->vector->getVector());
    }

    /**
     * @dataProvider dataProviderForGetN
     */
    public function testGetN(array $A, int $n)
    {
        $vector = new Vector($A);
        $this->assertEquals($n, $vector->getN());
    }

    public function dataProviderForGetN()
    {
        return [
            [[], 0],
            [[1], 1],
            [[1,2], 2],
            [[1,2,3], 3],
            [[1,2,3,4], 4],
        ];
    }

    public function testGet()
    {
        $this->assertEquals(1, $this->vector->get(0));
        $this->assertEquals(2, $this->vector->get(1));
        $this->assertEquals(3, $this->vector->get(2));
        $this->assertEquals(4, $this->vector->get(3));
        $this->assertEquals(5, $this->vector->get(4));
    }

    public function testGetException()
    {
        $this->setExpectedException('\Exception');
        $this->vector->get(100);
    }

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

    public function testArrayAccessInterfaceOffsetGet()
    {
        $this->assertEquals(1, $this->vector[0]);
        $this->assertEquals(2, $this->vector[1]);
        $this->assertEquals(3, $this->vector[2]);
        $this->assertEquals(4, $this->vector[3]);
        $this->assertEquals(5, $this->vector[4]);
    }

    public function testArrayAccessInterfaceOffsetSet()
    {
        $this->setExpectedException('\Exception');
        $this->vector[0] = 1;
    }

    public function testArrayAccessOffsetExists()
    {
        $this->assertTrue($this->vector->offsetExists(0));
    }

    public function testArrayAccessOffsetUnsetException()
    {
        $this->setExpectedException('\Exception');
        unset($this->vector[0]);
    }

    public function testToString()
    {
        $A      = new Vector([1, 2, 3]);
        $string = $A->__toString();
        $this->assertTrue(is_string($string));
        $this->assertEquals('[1, 2, 3]', $string);
    }

    /**
     * @dataProvider dataProviderForAsColumnMatrix
     */
    public function testAsColumnMatrix(array $A, array $R)
    {
        $A = new Vector($A);
        $R = new Matrix($R);
        $M = $A->asColumnMatrix();

        $this->assertEquals($R, $M);
    }

    public function dataProviderForAsColumnMatrix()
    {
        return [
            [
                [],
                [],
            ],
            [
                [1],
                [
                    [1],
                ],
            ],
            [
                [1, 2],
                [
                    [1],
                    [2],
                ],
            ],
            [
                [1, 2, 3],
                [
                    [1],
                    [2],
                    [3],
                ],
            ],
            [
                [1, 2, 3, 4],
                [
                    [1],
                    [2],
                    [3],
                    [4],
                ],
            ],
        ];
    }

    /**
     * @dataProvider dataProviderForCountable
     */
    public function testCountableInterface(array $A, $n)
    {
        $A = new Vector($A);

        $this->assertEquals($n, count($A));
    }

    public function dataProviderForCountable()
    {
        return [
            [[], 0],
            [[1], 1],
            [[1, 1], 2],
            [[1, 1, 1], 3],
            [[1, 1, 1, 1], 4],
            [[1, 1, 1, 1, 1], 5],
            [[1, 1, 1, 1, 1, 1], 6],
            [[1, 1, 1, 1, 1, 1, 1], 7],
            [[1, 1, 1, 1, 1, 1, 1, 1], 8],
            [[1, 1, 1, 1, 1, 1, 1, 1, 1], 9],
            [[1, 1, 1, 1, 1, 1, 1, 1, 1, 1], 10],
        ];
    }

    /**
     * @dataProvider dataProviderForJsonSerializable
     */
    public function testJsonSerializable(array $A, string $json)
    {
        $A    = new Vector($A);

        $this->assertEquals($json, json_encode($A));
    }

    public function dataProviderForJsonSerializable()
    {
        return [
            [
                [],
                '[]',
            ],
            [
                [1],
                '[1]',
            ],
            [
                [1, 2, 3],
                '[1,2,3]',
            ],
        ];
    }

    public function testInterfaces()
    {
        $interfaces = class_implements('\Math\LinearAlgebra\Vector');

        $this->assertContains('Countable', $interfaces);
        $this->assertContains('ArrayAccess', $interfaces);
        $this->assertContains('JsonSerializable', $interfaces);
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
}

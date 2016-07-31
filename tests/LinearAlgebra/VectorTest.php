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
}

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
}

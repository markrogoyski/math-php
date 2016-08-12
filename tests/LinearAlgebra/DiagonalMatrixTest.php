<?php
namespace Math\LinearAlgebra;
class DiagonalMatrixTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->A = [
            [1, 0, 0],
            [0, 2, 0],
            [0, 0, 3],
        ];
        $this->D = [1,2,3];
        $this->matrix = new DiagonalMatrix($this->D);
    }
   
    public function testGetMatrix()
    {
        $this->assertEquals($this->A, $this->matrix->getMatrix());
    }
}

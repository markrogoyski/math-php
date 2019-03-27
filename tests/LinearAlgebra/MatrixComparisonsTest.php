<?php
namespace MathPHP\Tests\LinearAlgebra;

use MathPHP\LinearAlgebra\MatrixFactory;

class MatrixComparisonsTest extends \PHPUnit\Framework\TestCase
{
    use MatrixDataProvider;

    /**
     * @test         isEqual finds two matrices to be equal
     * @dataProvider dataProviderForSquareMatrix
     * @dataProvider dataProviderForNotSquareMatrix
     * @dataProvider dataProviderForNonsingularMatrix
     * @param        array $A
     * @throws       \Exception
     */
    public function testIsEqual(array $A)
    {
        // Given
        $A = MatrixFactory::create($A);

        // Then
        $this->assertTrue($A->isEqual($A));
    }

    /**
     * @test         isEqual finds to matrices to not be equal
     * @dataProvider dataProviderForTwoSquareMatrices
     * @dataProvider dataProviderForTwoNonsingularMatrixes
     * @param        array $A
     * @param        array $B
     * @throws       \Exception
     */
    public function testIsNotEqual(array $A, array $B)
    {
        // Given
        $A = MatrixFactory::create($A);
        $B = MatrixFactory::create($B);

        // Then
        $this->assertFalse($A->isEqual($B));
        $this->assertFalse($B->isEqual($A));
    }
}

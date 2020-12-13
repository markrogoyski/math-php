<?php

namespace MathPHP\Tests\LinearAlgebra\Matrix;

use MathPHP\LinearAlgebra\MatrixFactory;
use MathPHP\LinearAlgebra\Matrix;
use MathPHP\LinearAlgebra\Vector;
use MathPHP\Exception;

class MatrixSolveTest extends \PHPUnit\Framework\TestCase
{
    use \MathPHP\Tests\LinearAlgebra\Fixture\MatrixDataProvider;

    /**
     * @test         Solve array
     * @dataProvider dataProviderForSolve
     * @param        array $A
     * @param        array $b
     * @param        array $expected
     * @throws       \Exception
     */
    public function testSolveArray(array $A, array $b, array $expected)
    {
        // Given
        $A        = MatrixFactory::create($A);
        $expected = new Vector($expected);

        // When
        $x = $A->solve($b);

        // Then
        $this->assertEqualsWithDelta($expected, $x, 0.00001);;
    }

    /**
     * @test         Solve vector
     * @dataProvider dataProviderForSolve
     * @param        array $A
     * @param        array $b
     * @param        array $expected
     * @throws       \Exception
     */
    public function testSolveVector(array $A, array $b, array $expected)
    {
        // Given
        $A        = MatrixFactory::create($A);
        $b        = new Vector($b);
        $expected = new Vector($expected);

        // When
        $x = $A->solve($b);

        // Then
        $this->assertEqualsWithDelta($expected, $x, 0.00001);;
    }

    /**
     * @test         Compute the inverse before trying to solve
     * @dataProvider dataProviderForSolve
     * @param        array $A
     * @param        array $b
     * @param        array $expected
     * @throws       \Exception
     */
    public function testSolveInverse(array $A, array $b, array $expected)
    {
        // Given
        $A        = MatrixFactory::create($A);
        $b        = new Vector($b);
        $expected = new Vector($expected);

        // When
        $A->inverse();
        $x = $A->solve($b);

        // Then
        $this->assertEqualsWithDelta($expected, $x, 0.00001);;
    }

    /**
     * @test         Compute the RREF before trying to solve.
     * @dataProvider dataProviderForSolve
     * @param        array $A
     * @param        array $b
     * @param        array $expected
     * @throws       \Exception
     */
    public function testSolveRref(array $A, array $b, array $expected)
    {
        // Given
        $A        = MatrixFactory::create($A);
        $b        = new Vector($b);
        $expected = new Vector($expected);

        // When
        $A->rref();
        $x = $A->solve($b);

        // Then
        $this->assertEqualsWithDelta($expected, $x, 0.00001);;
    }

    /**
     * @test         Solve forcing LU method
     * @dataProvider dataProviderForSolve
     * @param        array $A
     * @param        array $b
     * @param        array $expected
     * @throws       \Exception
     */
    public function testSolveForcingLuMethod(array $A, array $b, array $expected)
    {
        // Given
        $A        = MatrixFactory::create($A);
        $b        = new Vector($b);
        $expected = new Vector($expected);

        // When
        $x = $A->solve($b, Matrix::LU);

        // Then
        $this->assertEqualsWithDelta($expected, $x, 0.00001);;
    }

    /**
     * @test         Solve forcing QR method
     * @dataProvider dataProviderForSolve
     * @param        array $A
     * @param        array $b
     * @param        array $expected
     * @throws       \Exception
     */
    public function testSolveForcingQrMethod(array $A, array $b, array $expected)
    {
        // Given
        $A        = MatrixFactory::create($A);
        $b        = new Vector($b);
        $expected = new Vector($expected);

        // When
        $x = $A->solve($b, Matrix::QR);

        // Then
        $this->assertEqualsWithDelta($expected, $x, 0.00001);;
    }

    /**
     * @test         Solve forcing Inverse method
     * @dataProvider dataProviderForSolve
     * @param        array $A
     * @param        array $b
     * @param        array $expected
     * @throws       \Exception
     */
    public function testSolveForcingInverseMethod(array $A, array $b, array $expected)
    {
        // Given
        $A        = MatrixFactory::create($A);
        $b        = new Vector($b);
        $expected = new Vector($expected);

        // When
        $x = $A->solve($b, Matrix::INVERSE);

        // Then
        $this->assertEqualsWithDelta($expected, $x, 0.00001);;
    }

    /**
     * @test         Solve forcing RREF method
     * @dataProvider dataProviderForSolve
     * @param        array $A
     * @param        array $b
     * @param        array $expected
     * @throws       \Exception
     */
    public function testSolveForcingRrefMethod(array $A, array $b, array $expected)
    {
        // Given
        $A        = MatrixFactory::create($A);
        $b        = new Vector($b);
        $expected = new Vector($expected);

        // When
        $x = $A->solve($b, Matrix::RREF);

        // Then
        $this->assertEqualsWithDelta($expected, $x, 0.00001);;
    }

    /**
     * @test         solve exception
     * @dataProvider dataProviderForSolveExceptionNotVectorOrArray
     * @throws       \Exception
     */
    public function testSolveExceptionNotVectorOrArray($b)
    {
        // Given
        $A = new Matrix([
            [1, 2, 3],
            [2, 3, 4],
            [3, 4, 5],
        ]);

        // Then
        $this->expectException(Exception\IncorrectTypeException::class);

        // When
        $A->solve($b);
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function dataProviderForSolveExceptionNotVectorOrArray(): array
    {
        return [
            [new Matrix([[1], [2], [3]])],
            [25],
        ];
    }

    /**
     * @test         Test ref by solving the system of linear equations.
     *               There is no single row echelon form for a matrix (as opposed to reduced row echelon form).
     *               Therefore, instead of directly testing the REF obtained,
     *               use the REF to then solve for x using back substitution.
     *               The result should be the expected solution to the system of linear equations.
     * @dataProvider dataProviderForSolve
     * @param        array $A
     * @param        array $b
     * @param        array $expected_x
     * @throws       \Exception
     */
    public function testRefUsingSolve(array $A, array $b, array $expected_x)
    {
        // Given
        $m        = count($b);
        $A        = MatrixFactory::create($A);
        $b_matrix = MatrixFactory::createFromVectors([new Vector($b)]);
        $Ab       = $A->augment($b_matrix);
        $ref      = $Ab->ref();

        // When solve for x using back substitution on the REF matrix
        $x = [];
        for ($i = $m - 1; $i >= 0; $i--) {
            $x[$i] = $ref[$i][$m];
            for ($j = $i + 1; $j < $m; $j++) {
                $x[$i] -= $ref[$i][$j] * $x[$j];
            }
            $x[$i] /= $ref[$i][$i];
        }

        // Then
        $this->assertEqualsWithDelta($expected_x, $x, 0.00001);;

        // And as an extra check, solve the original matrix and compare the result.
        $solved_x = $A->solve($b);
        $this->assertEqualsWithDelta($x, $solved_x->getVector(), 0.00001);;
    }

    /**
     * @test         After solving, multiplying Ax = b
     *               In Python you could do numpy.dot(A, x) == b for this verification
     * @dataProvider dataProviderForSolve
     * @param        array $A
     * @param        array $b
     * @throws       \Exception
     */
    public function testAxEqualsBAfterSolving(array $A, array $b)
    {
        // Given
        $A = MatrixFactory::create($A);

        // And
        $x = $A->solve($b);

        // When Ax
        $Ax = $A->multiply($x);

        // Then Ax = b
        $this->assertEqualsWithDelta($b, $Ax->asVectors()[0]->getVector(), 0.00001);;
    }
}

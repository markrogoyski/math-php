<?php
namespace Math\LinearAlgebra;

/**
 * Matrix factory to create matrices of all types.
 * Use factory instead of instantiating individual Matrix classes.
 */
class MatrixFactory
{
    /**
     * Factory method
     *
     * @param  array    $A 1- or 2-dimensional array of Matrix data
     *                     1-dimensional array for Diagonal and Vandermonde matrices
     *                     2-dimensional array for Square, Function, and regular Matrices
     * @param  int|null $n Optional n for Vandermonde matrix
     *
     * @return Matrix
     */
    public static function create(array $A, int $n = null): Matrix
    {
        self::checkParams($A, $n);

        $matrix_type = self::determineMatrixType($A, $n);

        switch ($matrix_type) {
            case 'matrix':
                return new Matrix($A);
            case 'square':
                return new SquareMatrix($A);
            case 'diagonal':
                return new DiagonalMatrix($A);
            case 'vandermonde':
                return new VandermondeMatrix($A, $n);
            case 'function':
                return new FunctionMatrix($A);
            case 'vandermonde_square':
                return new VandermondeSquareMatrix($A, $n);
            case 'function_square':
                return new FunctionSquareMatrix($A);
        }
    }

    /* ************************************************************************
     * PRIVATE HELPER METHODS
     * ***********************************************************************/

    /**
     * Check input parameters
     *
     * @param  array    $A
     * @param  int|null $n
     *
     * @return bool
     */
    private static function checkParams(array $A, int $n = null): bool
    {
        if (empty($A)) {
            throw new \Exception('Array data not provided for Matrix creation');
        }

        if (isset($A[0]) && is_array($A[0])) {
            $column_count = count($A[0]);
            foreach ($A as $i => $row) {
                if (count($row) !== $column_count) {
                    throw new \Exception("Row $i has a different column count: " . count($row) . "; was expecting $column_count.");
                }
            }
        }

        return true;
    }

    /**
     * Determine what type of matrix to create
     *
     * @param  array    $A 1- or 2-dimensional array of Matrix data
     *                     1-dimensional array for Diagonal and Vandermonde matrices
     *                     2-dimensional array for Square, Function, and regular Matrices
     * @param  int|null $n Optional n for Vandermonde matrix
     *
     * @return string indicating what matrix type to create
     */
    private static function determineMatrixType(array $A, $vandermonde_n): string
    {
        $m = count($A);

        // 1-dimensional array is how we create diagonal and vandermonde matrices
        $one_dimensional = count(array_filter($A, 'is_array')) === 0;
        if ($one_dimensional) {
            if (is_null($vandermonde_n)) {
                return 'diagonal';
            }
            if ($m === $vandermonde_n) {
                return 'vandermonde_square';
            }
            return 'vandermonde';
        }

        // Square Matrices have the same number of rows (m) and columns (n)
        $n = count($A[0]);
        if ($m === $n) {
            if (is_callable($A[0][0])) {
                return 'function_square';
            }
            return 'square';
        }

        // Non square Matrices
        if (is_callable($A[0][0])) {
            return 'function';
        }
        return 'matrix';
    }
}

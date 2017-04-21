<?php
namespace MathPHP\LinearAlgebra;

use MathPHP\Exception;

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
            case 'from_vectors':
                return self::createFromVectors($A);
            case 'vandermonde':
                return new VandermondeMatrix($A, $n);
            case 'function':
                return new FunctionMatrix($A);
            case 'vandermonde_square':
                return new VandermondeSquareMatrix($A, $n);
            case 'function_square':
                return new FunctionSquareMatrix($A);
        }

        throw new Exception\IncorrectTypeException('Unknown matrix type');
    }

    /**************************************************************************
     * SPECIAL MATRICES - Not created from an Array
     *  - identity
     *  - zero
     *  - one
     *  - eye
     **************************************************************************/

    /**
     * Identity matrix - n x n matrix with ones in the diagonal
     * Option to set the diagonal to any number.
     *
     * Example:
     *  n = 3; x = 1
     *
     *      [1 0 0]
     *  A = [0 1 0]
     *      [0 0 1]
     *
     * @param int    $n size of matrix
     * @param number $x (optional; default 1)
     *
     * @return SquareMatrix
     *
     * @throws OutOfBoundsException if n < 0
     */
    public static function identity(int $n, $x = 1): SquareMatrix
    {
        if ($n < 0) {
            throw new Exception\OutOfBoundsException("n must be ≥ 0. n = $n");
        }
        $R = [];

        for ($i = 0; $i < $n; $i++) {
            for ($j = 0; $j < $n; $j++) {
                $R[$i][$j] = $i == $j ? $x : 0;
            }
        }

        return self::create($R);
    }

    /**
     * Echange matrix - n x n matrix with ones in the reverse diagonal
     * Row-reversed, or column-reversed version of the identity matrix.
     * https://en.wikipedia.org/wiki/Exchange_matrix
     *
     * Example:
     *  n = 3; x = 1
     *
     *      [0 0 1]
     *  A = [0 1 0]
     *      [1 0 0]
     *
     * @param int    $n size of matrix
     * @param number $x (Optional to set the diagonal to any number; default 1)
     *
     * @return SquareMatrix
     *
     * @throws OutOfBoundsException if n < 0
     */
    public static function exchange(int $n, $x = 1): SquareMatrix
    {
        if ($n < 0) {
            throw new Exception\OutOfBoundsException("n must be ≥ 0. n = $n");
        }
        $R = [];

        $one = $n - 1;
        for ($i = 0; $i < $n; $i++) {
            for ($j = 0; $j < $n; $j++) {
                $R[$i][$j] = $j == $one ? $x : 0;
            }
            $one--;
        }

        return self::create($R);
    }

    /**
     * Zero matrix - m x n matrix with all elements being zeros
     *
     * Example:
     *  m = 3; n = 3
     *
     *      [0 0 0]
     *  A = [0 0 0]
     *      [0 0 0]
     *
     * @param int $m rows
     * @param int $n columns
     *
     * @return Matrix
     *
     * @throws OutOfBoundsException if m < 1 or n < 1
     */
    public static function zero(int $m, int $n): Matrix
    {
        if ($m < 1 || $n < 1) {
            throw new Exception\OutOfBoundsException("m and n must be > 0. m = $m, n = $n");
        }

        $R = [];

        for ($i = 0; $i < $m; $i++) {
            for ($j = 0; $j < $n; $j++) {
                $R[$i][$j] = 0;
            }
        }

        return self::create($R);
    }

    /**
     * Ones matrix - m x n matrix with all elements being ones
     *
     * Example:
     *  m = 3; n = 3
     *
     *      [1 1 1]
     *  A = [1 1 1]
     *      [1 1 1]
     *
     * @param int $m rows
     * @param int $n columns
     *
     * @return Matrix
     *
     * @throws OutOfBoundsException if m or n < 1
     */
    public static function one(int $m, int $n): Matrix
    {
        if ($m < 1 || $n < 1) {
            throw new Exception\OutOfBoundsException("m and n must be > 0. m = $m, n = $n");
        }

        $R = [];

        for ($i = 0; $i < $m; $i++) {
            for ($j = 0; $j < $n; $j++) {
                $R[$i][$j] = 1;
            }
        }

        return self::create($R);
    }

    /**
     * Eye matrix - ones on the k diagonal and zeros everywhere else.
     * Diagonal can start at any column.
     * Option to set the diagonal to any number.
     *
     * Example:
     *  m = 3; n = 3; k = 1; x = 1 (3x3 matrix with 1s on the kth (1) diagonal)
     *
     *      [0 1 0]
     *  A = [0 0 1]
     *      [0 0 0]
     *
     * @param int    $m number of rows
     * @param int    $n number of columns
     * @param int    $k Diagonal to fill with xs
     * @param number $x (optional; default 1)
     *
     * @return Matrix
     *
     * @throws OutOfBoundsException if m, n, or k are < 0
     * @throws OutOfBoundsException if k >= n
     */
    public static function eye(int $m, int $n, int $k, $x = 1): Matrix
    {
        if ($n < 0 || $m < 0 || $k < 0) {
            throw new Exception\OutOfBoundsException("m, n and k must be ≥ 0. m = $m, n = $n, k = $k");
        }
        if ($k >= $n) {
            throw new Exception\OutOfBoundsException("k must be < n. k = $k, n = $n");
        }

        $R = (self::zero($m, $n))->getMatrix();

        for ($i = 0; $i < $m; $i++) {
            if (($k + $i) < $n) {
                $R[$i][$k + $i] = $x;
            }
        }

        return self::create($R);
    }

    /**
     * Hilbert matrix - a square matrix with entries being the unit fractions
     * https://en.wikipedia.org/wiki/Hilbert_matrix
     *
     *           1
     * Hij = ---------
     *       i + j - 1
     *
     * Example: n = 5
     *
     *     [1 ½ ⅓ ¼ ⅕]
     *     [½ ⅓ ¼ ⅕ ⅙]
     * H = [⅓ ¼ ⅕ ⅙ ⅐]
     *     [¼ ⅕ ⅙ ⅐ ⅛]
     *     [⅕ ⅙ ⅐ ⅛ ⅑]
     *
     * @return SquareMatrix
     */
    public static function hilbert(int $n): SquareMatrix
    {
        if ($n < 1) {
            throw new Exception\OutOfBoundsException("n must be > 0. m = $m");
        }

        $H = [];
        for ($i = 1; $i <= $n; $i++) {
            for ($j = 1; $j <= $n; $j++) {
                $H[$i-1][$j-1] = 1 / ($i + $j - 1);
            }
        }

        return self::create($H);
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
     *
     * @throws BadDataException if array data not provided for matrix creation
     * @throws MatrixException if any row has a different column count
     */
    private static function checkParams(array $A, int $n = null): bool
    {
        if (empty($A)) {
            throw new Exception\BadDataException('Array data not provided for Matrix creation');
        }

        if (isset($A[0]) && is_array($A[0])) {
            $column_count = count($A[0]);
            foreach ($A as $i => $row) {
                if (count($row) !== $column_count) {
                    throw new Exception\MatrixException("Row $i has a different column count: " . count($row) . "; was expecting $column_count.");
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

        // 1-dimensional array is how we create diagonal and vandermonde matrices,
        // as well as matrices from an array of vectors
        $one_dimensional = count(array_filter($A, 'is_array')) === 0;
        if ($one_dimensional) {
            $is_array_of_vectors = array_reduce(
                $A,
                function ($carry, $item) {
                    return $carry && ($item instanceof Vector);
                },
                true
            );
            if ($is_array_of_vectors) {
                return 'from_vectors';
            }
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
        // First check to make sure it isn't something strange
        if (is_array($A[0][0])) {
            return 'unknown';
        }
        // Then check remaining matrix types
        if (is_callable($A[0][0])) {
            return 'function';
        }
        return 'matrix';
    }

    /**
     * Create a matrix from an array of Vectors
     *
     * Example:
     *        [1]       [4]        [7]       [8]
     *   X₁ = [2]  X₂ = [2]   X₃ = [8]  X₄ = [4]
     *        [1]       [13]       [1]       [5]
     *
     *       [1  4 7 8]
     *   R = [2  2 8 4]
     *       [1 13 1 5]
     *
     * @param  array  $A array of Vectors
     *
     * @return Matrix
     *
     * @throws MatrixException if the Vectors are not all the same length
     */
    private static function createFromVectors(array $A): Matrix
    {
        // Check that all vectors are the same length
        $m = $A[0]->getN();
        $n = count($A);
        for ($j = 1; $j < $n; $j++) {
            if ($A[$j]->getN() !== $m) {
                throw new Exception\MatrixException('Vectors being combined into matrix have different lengths');
            }
        }

        // Concatenate all the vectors
        $R = [];
        foreach ($A as $V) {
            $R[] = $V->getVector();
        }

        // Transpose to create matrix from the vector columns
        return (new Matrix($R))->transpose();
    }
}

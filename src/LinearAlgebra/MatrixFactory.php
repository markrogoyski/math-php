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
     * @param  array[] $A 2-dimensional array of Matrix data
     *
     * @return Matrix
     *
     * @throws Exception\BadDataException
     * @throws Exception\IncorrectTypeException
     * @throws Exception\MathException
     * @throws Exception\MatrixException
     */
    public static function create(array $A): Matrix
    {
        self::checkParams($A);

        $matrix_type = self::determineMatrixType($A);

        switch ($matrix_type) {
            case 'matrix':
                return new Matrix($A);
            case 'square':
                return new SquareMatrix($A);
            case 'function':
                return new FunctionMatrix($A);
            case 'function_square':
                return new FunctionSquareMatrix($A);
            case 'object_square':
                return new ObjectSquareMatrix($A);
        }

        throw new Exception\IncorrectTypeException('Unknown matrix type');
    }

    /**
     * Factory method to create a matrix from an array of Vectors
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
     * @param  Vector[] $A array of Vectors
     *
     * @return Matrix
     *
     * @throws Exception\MatrixException if the Vectors are not all the same length
     * @throws Exception\IncorrectTypeException
     * @throws Exception\BadDataException
     */
    public static function createFromVectors(array $A): Matrix
    {
        // Check that all vectors are the same length
        $m = $A[0]->getN();
        $n = \count($A);
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

    /**************************************************************************
     * SPECIAL MATRICES - Not created from an array of arrays
     *  - identity
     *  - exchange
     *  - downshiftPermutation
     *  - upshiftPermutation
     *  - zero
     *  - one
     *  - eye
     *  - diagonal
     *  - hilbert
     *  - vandermonde
     *  - givens
     **************************************************************************/

    /**
     * Identity matrix - n x n matrix with ones in the diagonal
     *
     * Example:
     *  n = 3;
     *
     *      [1 0 0]
     *  A = [0 1 0]
     *      [0 0 1]
     *
     * @param int   $n size of matrix
     *
     * @return Matrix
     *
     * @throws Exception\BadDataException
     * @throws Exception\IncorrectTypeException
     * @throws Exception\MathException
     * @throws Exception\MatrixException
     * @throws Exception\OutOfBoundsException if n < 0
     */
    public static function identity(int $n): Matrix
    {
        if ($n < 0) {
            throw new Exception\OutOfBoundsException("n must be ≥ 0. n = $n");
        }
        $R = [];

        for ($i = 0; $i < $n; $i++) {
            for ($j = 0; $j < $n; $j++) {
                $R[$i][$j] = $i == $j ? 1 : 0;
            }
        }

        return self::create($R);
    }

    /**
     * Exchange matrix - n x n matrix with ones in the reverse diagonal
     * Row-reversed, or column-reversed version of the identity matrix.
     * https://en.wikipedia.org/wiki/Exchange_matrix
     *
     * Example:
     *  n = 3;
     *
     *      [0 0 1]
     *  A = [0 1 0]
     *      [1 0 0]
     *
     * @param int $n size of matrix
     *
     * @return Matrix
     *
     * @throws Exception\BadDataException
     * @throws Exception\IncorrectTypeException
     * @throws Exception\MathException
     * @throws Exception\MatrixException
     * @throws Exception\OutOfBoundsException if n < 0
     */
    public static function exchange(int $n): Matrix
    {
        if ($n < 0) {
            throw new Exception\OutOfBoundsException("n must be ≥ 0. n = $n");
        }
        $R = [];

        $one = $n - 1;
        for ($i = 0; $i < $n; $i++) {
            for ($j = 0; $j < $n; $j++) {
                $R[$i][$j] = $j == $one ? 1 : 0;
            }
            $one--;
        }

        return self::create($R);
    }

    /**
     * Downshift permutation matrix
     * Pushes the components of a vector down one notch with wraparound
     *
     *       [0, 0, 0, 1] [x₁]   [x₄]
     *       [1, 0, 0, 0] [x₂]   [x₁]
     * D₄x = [0, 1, 0, 0] [x₃] = [x₂]
     *       [0, 0, 1, 0] [x₄]   [x₃]
     *
     * @param  int $n
     *
     * @return Matrix
     *
     * @throws Exception\BadDataException
     * @throws Exception\IncorrectTypeException
     * @throws Exception\MathException
     * @throws Exception\MatrixException
     * @throws Exception\OutOfBoundsException if n < 0
     */
    public static function downshiftPermutation(int $n): Matrix
    {
        $I = self::identity($n)->getMatrix();

        $bottom_row = array_pop($I);
        array_unshift($I, $bottom_row);

        return self::create($I);
    }

    /**
     * Upshift permutation matrix - Dᵀ
     * Pushes the components of a vector up one notch with wraparound
     *
     * @param  int $n
     *
     * @return Matrix
     *
     * @throws Exception\BadDataException
     * @throws Exception\IncorrectTypeException
     * @throws Exception\MathException
     * @throws Exception\MatrixException
     * @throws Exception\OutOfBoundsException
     */
    public static function upshiftPermutation(int $n): Matrix
    {
        return self::downshiftPermutation($n)->transpose();
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
     * @throws Exception\BadDataException
     * @throws Exception\IncorrectTypeException
     * @throws Exception\MathException
     * @throws Exception\MatrixException
     * @throws Exception\OutOfBoundsException if m < 1 or n < 1
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
     * @throws Exception\BadDataException
     * @throws Exception\IncorrectTypeException
     * @throws Exception\MathException
     * @throws Exception\MatrixException
     * @throws Exception\OutOfBoundsException if m or n < 1
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
     * @param int   $m number of rows
     * @param int   $n number of columns
     * @param int   $k Diagonal to fill with xs
     * @param float $x (optional; default 1)
     *
     * @return Matrix
     *
     * @throws Exception\BadDataException
     * @throws Exception\IncorrectTypeException
     * @throws Exception\MathException
     * @throws Exception\MatrixException
     * @throws Exception\OutOfBoundsException if m, n, or k are < 0; if k >= n
     */
    public static function eye(int $m, int $n, int $k, float $x = null): Matrix
    {
        if ($n < 0 || $m < 0 || $k < 0) {
            throw new Exception\OutOfBoundsException("m, n and k must be ≥ 0. m = $m, n = $n, k = $k");
        }
        if ($k >= $n) {
            throw new Exception\OutOfBoundsException("k must be < n. k = $k, n = $n");
        }
        $x = $x ?? 1;

        $R = (self::zero($m, $n))->getMatrix();

        for ($i = 0; $i < $m; $i++) {
            if (($k + $i) < $n) {
                $R[$i][$k + $i] = $x;
            }
        }

        return self::create($R);
    }

    /**
     * A Diagonal Matrix is constructed from a single-row array.
     * The elements of this array are placed on the diagonal of a square matrix.
     *
     * Example:
     *  D = [1, 2, 3]
     *
     *     [1 0 0]
     * A = [0 2 0]
     *     [0 0 3]
     *
     * @param array $D elements of the diagonal
     *
     * @return DiagonalMatrix
     *
     * @throws Exception\MatrixException
     */
    public static function diagonal(array $D): DiagonalMatrix
    {
        $m = \count($D);

        $A = [];
        for ($i = 0; $i < $m; $i++) {
            for ($j = 0; $j < $m; $j++) {
                if ($i == $j) {
                    $A[$i][$j] = $D[$i];
                } else {
                    $A[$i][$j] = 0;
                }
            }
        }

        return new DiagonalMatrix($A);
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
     * @param int $n
     *
     * @return Matrix
     *
     * @throws Exception\BadDataException
     * @throws Exception\IncorrectTypeException
     * @throws Exception\MathException
     * @throws Exception\MatrixException
     * @throws Exception\OutOfBoundsException
     */
    public static function hilbert(int $n): Matrix
    {
        if ($n < 1) {
            throw new Exception\OutOfBoundsException("n must be > 0. m = $n");
        }

        $H = [];
        for ($i = 1; $i <= $n; $i++) {
            for ($j = 1; $j <= $n; $j++) {
                $H[$i - 1][$j - 1] = 1 / ($i + $j - 1);
            }
        }

        return self::create($H);
    }

    /**
     * Create the Vandermonde Matrix from a simple array.
     *
     * @param array $M (α₁, α₂, α₃ ⋯ αm)
     * @param int   $n
     *
     * @return Matrix
     *
     * @throws Exception\BadDataException
     * @throws Exception\IncorrectTypeException
     * @throws Exception\MathException
     * @throws Exception\MatrixException
     */
    public static function vandermonde(array $M, int $n): Matrix
    {
        $A = [];
        foreach ($M as $row => $α) {
            for ($i = 0; $i < $n; $i++) {
                $A[$row][$i] = $α ** $i;
            }
        }

        return self::create($A);
    }

   /**
    * Construct a Givens rotation matrix
    *
    *               [  1 ⋯ 0 ⋯ 0 ⋯ 0  ]
    *               [  ⋮ ⋱ ⋮   ⋮   ⋮   ]
    *               [  0 ⋯ c ⋯-s ⋯ 0  ]
    * G (𝒾,𝒿,θ) =   [  ⋮   ⋮  ⋱ ⋮  ⋮  ]
    *               [  0 ⋯ s ⋯ c ⋯ 0  ]
    *               [  ⋮    ⋮   ⋮ ⋱ ⋮ ]
    *               [  0 ⋯ 0 ⋯ 0 ⋯ 1 ]
    *
    * https://en.wikipedia.org/wiki/Givens_rotation
    *
    * @param int   $m The row in G in which the top of the rotation lies
    * @param int   $n The column in G in which the left of the rotation lies
    * @param float $angle The angle to use in the trigonometric functions
    * @param int   $size The total number of rows in G
    *
    * @return Matrix
    *
    * @throws Exception\BadDataException
    * @throws Exception\IncorrectTypeException
    * @throws Exception\MathException
    * @throws Exception\MatrixException
    * @throws Exception\OutOfBoundsException
    */
    public static function givens(int $m, int $n, float $angle, int $size): Matrix
    {
        if ($m >= $size || $n >= $size || $m < 0 || $n < 0) {
            throw new Exception\OutOfBoundsException("m and n must be within the matrix");
        }

        $G         = MatrixFactory::identity($size)->getMatrix();
        $G[$m][$m] = cos($angle);
        $G[$n][$n] = cos($angle);
        $G[$m][$n] = -1 * sin($angle);
        $G[$n][$m] = sin($angle);

        return MatrixFactory::create($G);
    }

    /**
     * Create a Matrix of random numbers
     *
     * @param int $m   number of rows
     * @param int $n   number of columns
     * @param int $min lower bound for the random number (optional - default: 0)
     * @param int $max upper bound for the random number (optional - default: 20)
     *
     * @return Matrix
     *
     * @throws Exception\BadDataException
     * @throws Exception\IncorrectTypeException
     * @throws Exception\MathException
     * @throws Exception\MatrixException
     */
    public static function random(int $m, int $n, int $min = 0, int $max = 20): Matrix
    {
        $A = [];
        for ($i = 0; $i < $m; $i++) {
            for ($j = 0; $j < $n; $j++) {
                $A[$i][$j] = rand($min, $max);
            }
        }
        return self::create($A);
    }

    /* ************************************************************************
     * PRIVATE HELPER METHODS
     * ***********************************************************************/

    /**
     * Check input parameters
     *
     * @param  array    $A
     *
     * @return bool
     *
     * @throws Exception\BadDataException if array data not provided for matrix creation
     * @throws Exception\MatrixException if any row has a different column count
     */
    private static function checkParams(array $A): bool
    {
        if (empty($A)) {
            throw new Exception\BadDataException('Array data not provided for Matrix creation');
        }

        if (isset($A[0]) && is_array($A[0])) {
            $column_count = \count($A[0]);
            foreach ($A as $i => $row) {
                if (\count($row) !== $column_count) {
                    throw new Exception\MatrixException("Row $i has a different column count: " . \count($row) . "; was expecting $column_count.");
                }
            }
        }

        return true;
    }

    /**
     * Determine what type of matrix to create
     *
     * @param  array[] $A 2-dimensional array of Matrix data
     *
     * @return string indicating what matrix type to create
     */
    private static function determineMatrixType(array $A): string
    {
        $m = \count($A);
        $n = \count($A[0]);

        // Square Matrices have the same number of rows (m) and columns (n)
        if ($m === $n) {
            // closures are objects, so we need to separate them out.
            if (is_object($A[0][0])) {
                if ($A[0][0] instanceof \Closure) {
                    return 'function_square';
                } else {
                    return 'object_square';
                }
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

        // Numeric matrix
        return 'matrix';
    }
}

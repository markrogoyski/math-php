<?php

namespace MathPHP\LinearAlgebra\Decomposition;

use MathPHP\Exception;
use MathPHP\Functions\Support;
use MathPHP\LinearAlgebra\Householder;
use MathPHP\LinearAlgebra\NumericMatrix;
use MathPHP\LinearAlgebra\MatrixFactory;

/**
 * Hessenberg Decomposition
 *
 * A = QHQ*
 *
 * Where:
 * - Q is an orthogonal matrix
 * - H is an upper Hessenberg matrix (zeros below the first subdiagonal)
 * - Q* is the conjugate transpose of Q
 *
 * The Hessenberg decomposition is useful as a preprocessing step for eigenvalue algorithms,
 * as it preserves eigenvalues while reducing the matrix to a form that is easier to work with.
 *
 * @property-read NumericMatrix $Q Orthogonal transformation matrix
 * @property-read NumericMatrix $H Upper Hessenberg matrix
 */
class Hessenberg extends Decomposition
{
    /** @var NumericMatrix Orthogonal transformation matrix */
    private $Q;

    /** @var NumericMatrix Upper Hessenberg matrix */
    private $H;

    /**
     * Hessenberg constructor
     *
     * @param NumericMatrix $Q Orthogonal transformation matrix
     * @param NumericMatrix $H Upper Hessenberg matrix
     */
    private function __construct(NumericMatrix $Q, NumericMatrix $H)
    {
        $this->Q = $Q;
        $this->H = $H;
    }

    /**
     * Decompose a matrix into Hessenberg form using Householder reflections
     * Factory method to create Hessenberg objects.
     *
     * A = QHQ*
     *
     * Where:
     * - Q is an orthogonal matrix
     * - H is an upper Hessenberg matrix (zeros below the first subdiagonal)
     * - Q* is the conjugate transpose of Q
     *
     * Algorithm:
     * - Uses Householder reflections to systematically zero out elements below the first subdiagonal
     * - Each reflection preserves eigenvalues through similarity transformations
     * - Requires n-2 Householder transformations for an n×n matrix
     *
     * Numerical Stability:
     * - Uses consistent error tolerance throughout the decomposition process
     * - Householder transformations use the input matrix's error tolerance for consistent behavior
     * - The algorithm maintains numerical stability through orthogonal similarity transformations
     *
     * @param NumericMatrix $A Matrix to decompose
     *
     * @return Hessenberg
     *
     * @throws Exception\BadDataException
     * @throws Exception\IncorrectTypeException
     * @throws Exception\MathException
     * @throws Exception\MatrixException
     * @throws Exception\OutOfBoundsException
     * @throws Exception\VectorException
     */
    public static function decompose(NumericMatrix $A): Hessenberg
    {
        if (!$A->isSquare()) {
            throw new Exception\MatrixException('Hessenberg decomposition only works on square matrices');
        }

        $n = $A->getN();
        $ε = $A->getError();

        // For 1x1 matrices, return trivial decomposition
        if ($n < 2) {
            $Q = MatrixFactory::identity(1);
            $H = clone $A;
            $Q->setError($ε);
            $H->setError($ε);
            return new Hessenberg($Q, $H);
        }

        // Start with copies of the input matrix and identity matrix
        $H = MatrixFactory::createNumeric($A->getMatrix());
        $Q = MatrixFactory::identity($n);

        // Apply Householder transformations to columns 0 through n-3
        // (we need n-2 transformations total for an n×n matrix)
        for ($k = 0; $k < $n - 2; $k++) {
            // Extract the submatrix starting from row k+1, column k
            $subH = $H->submatrix($k + 1, $k, $n - 1, $k);

            // Skip if the column below the diagonal is already effectively zero
            if (Support::isZero($subH->frobeniusNorm(), $ε)) {
                continue;
            }

            // Create Householder transformation for this column
            $reflector = Householder::transform($subH, $ε);

            // Embed the Householder matrix in a full-size identity matrix
            $fullReflector = self::embedHouseholderMatrix($reflector, $n, $k + 1);

            // Apply the transformation: H = QHQ'
            $H = $fullReflector->multiply($H)->multiply($fullReflector->transpose());

            // Accumulate the orthogonal transformations: Q = QQ'
            $Q = $Q->multiply($fullReflector->transpose());
        }

        // Propagate error tolerance to result matrices
        $Q->setError($ε);
        $H->setError($ε);

        return new Hessenberg($Q, $H);
    }

    /**
     * Embed a Householder transformation matrix into a full-size identity matrix
     *
     * The Householder matrix is placed starting at the specified offset in both row and column dimensions.
     *
     * The implementation used is not the most efficient,
     * but it helps us visualize what it is, using matrix operations.
     * We prefer clarity and simplicity over performance.
     *
     * An alternative implementation would copy Householder matrix elements
     * to the appropriate position in identity matrix in a nested loop.
     * It is more efficient, but harder to visualize what the transformation is.
     *
     * $I = MatrixFactory::identity($n)->getMatrix();
     *
     * for ($i = 0; $i < $householder->getM(); $i++) {
     *     for ($j = 0; $j < $householder->getN(); $j++) {
     *         $I[$offset + $i][$offset + $j] = $householder[$i][$j];
     *.    }
     * }
     * return MatrixFactory::createNumeric($I);
     *
     * @param NumericMatrix $householder The Householder transformation matrix
     * @param int $n The size of the full identity matrix
     * @param int $offset The starting position to embed the Householder matrix
     *
     * @return NumericMatrix Full-size matrix with embedded Householder transformation
     *
     * @throws Exception\MatrixException
     * @throws Exception\IncorrectTypeException
     */
    private static function embedHouseholderMatrix(NumericMatrix $householder, int $n, int $offset): NumericMatrix
    {
        $I = MatrixFactory::identity($offset);

        // Create the top block: [ I | 0 ]
        $topBlock = $I->augment(MatrixFactory::zero($offset, $n - $offset));

        // Create the bottom block: [ 0 | H ]
        $bottomBlock = MatrixFactory::zero($n - $offset, $offset)->augment($householder);

        // Stack them vertically: [ topBlock ]
        //                        [ bottomBlock ]
        return $topBlock->augmentBelow($bottomBlock);
    }

    /**
     * Get Q or H matrix
     *
     * @param string $name
     *
     * @return NumericMatrix
     *
     * @throws Exception\MatrixException
     */
    public function __get(string $name): NumericMatrix
    {
        switch ($name) {
            case 'Q':
            case 'H':
                return $this->$name;

            default:
                throw new Exception\MatrixException("Hessenberg class does not have a gettable property: $name");
        }
    }
}

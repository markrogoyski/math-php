<?php

namespace MathPHP\Statistics\Multivariate;

use MathPHP\Exception;
use MathPHP\Exception\BadDataException;
use MathPHP\Exception\IncorrectTypeException;
use MathPHP\Exception\MathException;
use MathPHP\Exception\MatrixException;
use MathPHP\Exception\OutOfBoundsException;
use MathPHP\LinearAlgebra\Matrix;
use MathPHP\LinearAlgebra\MatrixFactory;
use MathPHP\LinearAlgebra\NumericMatrix;
use MathPHP\LinearAlgebra\ObjectMatrix;
use MathPHP\LinearAlgebra\Vector;
use MathPHP\Statistics\Descriptive;

/**
 * Partial Least Squares Regression
 *
 * Using the NIPALS PLS2 algorithm
 *
 * https://en.wikipedia.org/wiki/Partial_least_squares_regression
 */
class PLS
{
    /** @var Vector X Means */
    private $Xcenter;

    /** @var Vector Y Means */
    private $Ycenter;

    /** @var Vector X Scale */
    private $Xscale;

    /** @var Vector Y Scale */
    private $Yscale;

    /** @var NumericMatrix $B Regression Coefficients */
    private $B;

    /** @var NumericMatrix $C  Y Loadings */
    private $C;

    /** @var NumericMatrix $P Projection of X to X scores */
    private $P;

    /** @var NumericMatrix $T X Scores */
    private $T;

    /** @var NumericMatrix $U Y Scores */
    private $U;

    /** @var NumericMatrix $W X Weights */
    private $W;

    /**
     * @param NumericMatrix $X each row is a sample, each column is a variable
     * @param NumericMatrix $Y each row is a sample, each column is a variable
     * @param int    $ncomp number of components to use in the model
     * @param bool   $scale standardize each column?
     *
     * @throws Exception\BadDataException if any rows have a different column count
     *
     * FIXME: cannot use Matrix, only NumericMatrix (because of using `columnMeans()` function).
     */
    public function __construct(Matrix $X, Matrix $Y, int $ncomp, bool $scale = false)
    {
        // Check that X and Y have the same amount of data.
        if ($X->getM() !== $Y->getM()) {
            throw new Exception\BadDataException('X and Y must have the same number of rows.');
        }

        // Standardize X and Y
        $this->Xcenter = $X->columnMeans();
        $this->Ycenter = $Y->columnMeans();
        if ($scale === true) {
            $this->Xscale = self::columnStdevs($X);
            $this->Yscale = self::columnStdevs($Y);
        } else {
            /** @var array<int> $xFill */
            $xFill = array_fill(0, $X->getN(), 1);
            $this->Xscale = new Vector($xFill);
            /** @var array<int> $yFill */
            $yFill = array_fill(0, $Y->getN(), 1);
            $this->Yscale = new Vector($yFill);
        }

        $E = $this->standardizeData($X, $this->Xcenter, $this->Xscale);
        $F = $this->standardizeData($Y, $this->Ycenter, $this->Yscale);

        $tol = 1E-8;
        for ($i = 0; $i < $ncomp; $i++) {
            $iterations = 0;
            // Several sources suggest using a random initial u. This can lead to inconsistent
            // results due to some columns then being multiplyed by -1 some of the time.
            // $new_u = MatrixFactory::random($X->getM(), 1, -20000, 20000)->scalarDivide(20000);
            $u = $F->asVectors()[0]->asColumnMatrix();
            $end = false;
            while (!$end) {
                ++$iterations;

                // $w is a unit vector
                $w = $E->transpose()->multiply($u);
                $w = $w->scalarDivide($w->frobeniusNorm());

                $t = $E->multiply($w);
                $c = $F->transpose()->multiply($t)->scalarDivide($t->frobeniusNorm() ** 2);
                $new_u = $F->multiply($c);
                $diff = $new_u->subtract($u)->frobeniusNorm();

                if ($diff < $tol || $iterations > 50) {
                    $end = true;
                }
                $u = $new_u;
            }

            // Least squares regression on a slope-only model: 𝜷ᵢ = Σ(xᵢyᵢ) / Σ(xᵢ²)
            // $q = $F->transpose()->multiply($u)->scalarDivide($u->frobeniusNorm() ** 2);
            $p = $E->transpose()->multiply($t)->scalarDivide($t->frobeniusNorm() ** 2);
            $d = $u->transpose()->multiply($t)->scalarDivide($t->frobeniusNorm() ** 2)->get(0, 0);

            // Deflate the data matrices
            $E = $E->subtract($t->multiply($p->transpose()));
            $F = $F->subtract($t->multiply($c->transpose())->scalarMultiply($d));

            // Add each of these columns to the overall matrices
            // @phpstan-ignore-next-line (Call to function is_null() with MathPHP\LinearAlgebra\NumericMatrix will always evaluate to false.)
            $this->C = is_null($this->C) ? $c : $this->C->augment($c);
            // @phpstan-ignore-next-line (Call to function is_null() with MathPHP\LinearAlgebra\NumericMatrix will always evaluate to false.)
            $this->P = is_null($this->P) ? $p : $this->P->augment($p);
            // @phpstan-ignore-next-line (Call to function is_null() with MathPHP\LinearAlgebra\NumericMatrix will always evaluate to false.)
            $this->T = is_null($this->T) ? $t : $this->T->augment($t);
            // @phpstan-ignore-next-line (Call to function is_null() with MathPHP\LinearAlgebra\NumericMatrix will always evaluate to false.)
            $this->U = is_null($this->U) ? $u : $this->U->augment($u);
            // @phpstan-ignore-next-line (Call to function is_null() with MathPHP\LinearAlgebra\NumericMatrix will always evaluate to false.)
            $this->W = is_null($this->W) ? $w : $this->W->augment($w);
        }

        // Calculate R (or W*)
        $R = $this->W->multiply($this->P->transpose()->multiply($this->W)->inverse());
        $this->B = $R->multiply($this->C->transpose());
    }

    /**************************************************************************
     * BASIC GETTERS
     *  - getCoefficients
     *  - getYLoadings
     *  - getProjection
     *  - getXScores
     *  - getYScores
     *  - getXLoadings
     **************************************************************************/

    /**
     * Get the regression coefficients
     *
     * The matrix that best transforms E into F
     *
     * @return NumericMatrix
     */
    public function getCoefficients(): Matrix
    {
        return $this->B;
    }

    /**
     * Get the loadings for Y
     *
     * Each loading column transforms F to U
     *
     * @return NumericMatrix
     */
    public function getYLoadings(): Matrix
    {
        return $this->C;
    }

    /**
     * Get the projection matrix
     *
     * Each projection column transforms T into Ê
     *
     * @return NumericMatrix
     */
    public function getProjection(): Matrix
    {
        return $this->P;
    }

    /**
     * Get the scores for the X values
     *
     * The latent variables of X
     *
     * @return NumericMatrix
     */
    public function getXScores(): Matrix
    {
        return $this->T;
    }

    /**
     * Get the scores for the Y values
     *
     * The latent variables of Y
     *
     * @return NumericMatrix
     */
    public function getYScores(): Matrix
    {
        return $this->U;
    }

    /**
     * Get the loadings for the X values
     *
     * Each loading column transforms E into T
     *
     * @return NumericMatrix
     */
    public function getXLoadings(): Matrix
    {
        return $this->W;
    }

    /**
     * Predict Values
     *
     * Use the regression model to predict new values of Y given values for X.
     * Y = (X - μₓ) ∗ σₓ⁻¹ ∗ B ∗ σ + μ
     *
     * @param NumericMatrix $X
     *
     * @return NumericMatrix
     *
     * @throws BadDataException
     */
    public function predict(Matrix $X): Matrix
    {
        if ($X->getN() !== $this->Xcenter->getN()) {
            throw new Exception\BadDataException('Data does not have the correct number of columns.');
        }

        // Create a matrix the same dimensions as $X, each element is the average of that column in the original data.
        $ones_column = MatrixFactory::one($X->getM(), 1);
        $Ycenter_matrix = $ones_column->multiply(MatrixFactory::createNumeric([$this->Ycenter->getVector()]));

        // Create a diagonal matrix of column standard deviations.
        $Yscale_matrix = MatrixFactory::diagonal($this->Yscale->getVector());

        $E = $this->standardizeData($X, $this->Xcenter, $this->Xscale);
        $F = $E->multiply($this->B);
        // Y = F ∗ σ + μ
        return $F->multiply($Yscale_matrix)->add($Ycenter_matrix);
    }

    /**
     * Standardize the data
     * Use provided $center and $scale Vectors to transform the provided data
     *
     * @param NumericMatrix $new_data - A Matrix of new data which is standardized against the original data
     * @param Vector        $center   - A list of values to center the data against
     * @param Vector        $scale    - A list of standard deviations to scale the data with.
     *
     * @return NumericMatrix
     *
     * @throws Exception\MathException
     */
    private function standardizeData(Matrix $new_data, Vector $center, Vector $scale): Matrix
    {
        // Create a matrix the same dimensions as $new_data, each element is the average of that column in the original data.
        $ones_column = MatrixFactory::one($new_data->getM(), 1);
        $center_matrix = $ones_column->multiply(MatrixFactory::createNumeric([$center->getVector()]));

        // Create a diagonal matrix of the inverse of each column standard deviation.
        $scale_matrix = MatrixFactory::diagonal($scale->getVector())->inverse();

        // scaled data: ($X - μ) ∗ σ⁻¹
        return $new_data->subtract($center_matrix)->multiply($scale_matrix);
    }

    /**
     * Column Standard Deviations
     * Create a Vector with the standard deviations of each column of the supplied matrix
     *
     * @param NumericMatrix $M - A Matrix of which to calculate the standard deviations.
     *
     * @return Vector
     */
    private static function columnStdevs(Matrix $M): Vector
    {
        $scaleArray = [];
        for ($i = 0; $i < $M->getN(); $i++) {
            $scaleArray[] = Descriptive::standardDeviation($M->getColumn($i));
        }
        return new Vector($scaleArray);
    }
}

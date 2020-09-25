<?php

namespace MathPHP\Statistics\Multivariate;

use MathPHP\Exception;
use MathPHP\LinearAlgebra\Matrix;
use MathPHP\LinearAlgebra\MatrixFactory;
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
    /** @var Vector Means */
    private $Xcenter;

    /** @var Vector Means */
    private $Ycenter;
 
    /** @var Vector Scale */
    private $Xscale;

    /** @var Vector Scale */
    private $Yscale;

    /** @var Matrix $W X Weights*/
    private $W = null;

    /** @var Matrix $T */
    private $T = null;
    
    /** @var Matrix $C */
    private $C = null;

    /** @var Matrix $U */
    private $U = null;

    /** @var Matrix $P */
    private $P = null;

    /** @var Matrix $Q */
    private $Q = null;

    /** @var Matrix $B Regression Coefficients*/
    private $B = null;

    /** @var array $D */
    private $D = [];
    /**
     * Constructor
     *
     * @param Matrix  $X each row is a sample, each column is a variable
     * @param Matrix  $Y each row is a sample, each column is a variable
     * @param int     $ncomp number of components to use in the model
     * @param boolean $scale standardize each column?
     *
     * @throws Exception\BadDataException if any rows have a different column count
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
            $this->Xscale = new Vector(array_fill(0, $X->getN(), 1));
            $this->Yscale = new Vector(array_fill(0, $Y->getN(), 1));
        }
        
        $E = $this->standardizeData($X, $this->Xcenter, $this->Xscale);
        $F = $this->standardizeData($Y, $this->Ycenter, $this->Yscale);

        $tol = 1E-8;
        for ($i = 0; $i < $ncomp; $i++) {
            // Several sources suggest using a random initial u. This can lead to inconsistent
            // results due to some columns then being multiplyed by -1 some of the time.
            // $new_u = MatrixFactory::random($X->getM(), 1, -20000, 20000)->scalarDivide(20000);
            $new_u = $F->submatrix(0, 0, $F->getM() - 1, 0);
            do {
                $u = $new_u;

                // $w is a unit vector
                $w = $E->transpose()->multiply($u);
                $w = $w->scalarDivide($w->frobeniusNorm());

                $t = $E->multiply($w);
                $c = $F->transpose()->multiply($t)->scalarDivide($t->frobeniusNorm() ** 2);
                $new_u = $F->multiply($c);
                $diff = $new_u->subtract($u)->frobeniusNorm();
            } while ($diff > $tol);
            $u = $new_u;

            // Least squares regression on a slope-only model
            $p = $E->transpose()->multiply($t)->scalarDivide($t->frobeniusNorm() ** 2);
            $q = $F->transpose()->multiply($u)->scalarDivide($u->frobeniusNorm() ** 2);
            $d = $u->transpose()->multiply($t)->scalarDivide($t->frobeniusNorm() ** 2)->get(0, 0);

            // Deflate the data matrices
            $E = $E->subtract($t->multiply($p->transpose()));
            $F = $F->subtract($t->multiply($c->transpose())->scalarMultiply($d));

            // Add each of these columns to the overall matrices
            $this->W = is_null($this->W) ? $w : $this->W->augment($w);
            $this->T = is_null($this->T) ? $t : $this->T->augment($t);
            $this->U = is_null($this->U) ? $u : $this->U->augment($u);
            $this->C = is_null($this->C) ? $c : $this->C->augment($c);
            $this->P = is_null($this->P) ? $p : $this->P->augment($p);
            $this->D[] = $d;
        }
       
        // Calculate R (or W*)
        $R = $this->W->multiply($this->P->transpose()->multiply($this->W)->inverse());
        $this->B = $R->multiply($this->C->transpose());
    }

    /**************************************************************************
     * BASIC GETTERS
     *  - getB
     *  - getC
     *  - getD
     *  - getP
     *  - getQ
     *  - getT
     *  - getU
     *  - getW
     **************************************************************************/
    public function getB()
    {
        return $this->B;
    }

    public function getC()
    {
        return $this->C;
    }

    public function getP()
    {
        return $this->P;
    }

    public function getQ()
    {
        return $this->Q;
    }

    public function getT()
    {
        return $this->T;
    }

    public function getU()
    {
        return $this->U;
    }

    public function getW()
    {
        return $this->W;
    }

    /**
     * Predict Values
     *
     * Use the regression model to predict new values of Y given values for X.
     * Y = (X - μ) ∗ σ⁻¹ ∗ B ∗ σ + μ
     */
    public function predict(Matrix $X)
    {
        if ($X->getN() !== $this->Xcenter->getN()) {
            throw new Exception\BadDataException("Data does not have the same number of columns. Expecting {$expecting}, given $given");
        }
        
        // Create a matrix the same dimensions as $new_data, each element is the average of that column in the original data.
        $ones_column = MatrixFactory::one($Y->getM(), 1);
        $Ycenter_matrix = $ones_column->multiply(MatrixFactory::create([$this->Ycenter->getVector()]));

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
     * @param Matrix $new_data - A Matrix of new data which is standardized against the original data
     * @param Vector $center -  A list of values to center the data against
     * @param Vector $scale  - A list of standard deviations to scale the data with.
     *
     * @return Matrix
     *
     * @throws Exception\MathException
     */
    private function standardizeData(Matrix $new_data, Vector $center, Vector $scale): Matrix
    {
        // Create a matrix the same dimensions as $new_data, each element is the average of that column in the original data.
        $ones_column = MatrixFactory::one($new_data->getM(), 1);
        $center_matrix = $center_matrix ?? $ones_column->multiply(MatrixFactory::create([$center->getVector()]));

        // Create a diagonal matrix of the inverse of each column standard deviation.
        $scale_matrix = MatrixFactory::diagonal($scale->getVector())->inverse();

        // scaled data: ($X - μ) ∗ σ⁻¹
        return $new_data->subtract($center_matrix)->multiply($scale_matrix);
    }

    private static function columnStdevs(Matrix $M)
    {
        $scaleArray = [];
        for ($i = 0; $i < $M->getN(); $i++) {
            $scaleArray[] = Descriptive::standardDeviation($M->getColumn($i));
        }
        return new Vector($scaleArray);
    }
}

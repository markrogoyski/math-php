<?php

namespace MathPHP\Statistics\Multivariate;

use MathPHP\Exception;
use MathPHP\Functions\Map\Single;
use MathPHP\LinearAlgebra\Eigenvalue;
use MathPHP\LinearAlgebra\Matrix;
use MathPHP\LinearAlgebra\MatrixFactory;
use MathPHP\LinearAlgebra\Vector;
use MathPHP\Probability\Distribution\Continuous\F;
use MathPHP\Probability\Distribution\Continuous\StandardNormal;
use MathPHP\Statistics\Descriptive;

/**
 * Partial Least Squares Regression
 *
 * Using the NIPALS PLS1 or PLS2 algorithms
 *  PLS1 for univariate Y regression
 *  PLS2 for multivariate Y regression
 *
 * https://en.wikipedia.org/wiki/Partial_least_squares_regression
 */
class PLS
{
    /** @var Matrix Dataset */
    private $X;
 
    /** @var Matrix Dataset */
    private $Y;

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

    /** @var Matrix $T X Scores*/
    private $T = null;
    
    /** @var Matrix $C Y Loadings*/
    private $C = null;

    /** @var Matrix $U Y Scores*/
    private $U = null;

    /** @var Matrix $P X Loadings*/
    private $P = null;

    /** @var Matrix $Q */
    private $Q = null;

    /** @var Matrix $B */
    private $B = null;
    /**
     * Constructor
     *
     * @param Matrix  $X each row is a sample, each column is a variable
     * @param Matrix  $Y each row is a sample, each column is a variable
     * @param boolean $scale standardize each column?
     *
     * @throws Exception\BadDataException if any rows have a different column count
     */
    public function __construct(Matrix $X, Matrix $Y, bool $scale = false)
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

        $new_u = MatrixFactory::random($X->getM(), 1, -2, 2)->scalarDivide(2);
        $tol = 1E-8;
        for ($i = 0; $i < $Y->getN(); $i++) {
            do {
                $u = $new_u;

                // $w is a unit vector
                $w = $E->transpose()->multiply($u);
                $abs_w = $w->frobeniusNorm();
                $w = $w->scalarDivide($abs_w);

                $t = $E->multiply($w);
                $c = self::RTO($F, $t);
                $new_u = $F->multiply($c);
                $diff = $new_u->subtract($u)->frobeniusNorm();
            } while ($diff > $tol);
            $u = $new_u;
            $p = self::RTO($E, $t);
            $q = self::RTO($F, $u);
            $d = self::RTO($u, $t)->get(0, 0);
            $E = $E->subtract($t->multiply($p->transpose()));
            $F = $F->subtract($t->multiply($c->transpose())->scalarMultiply($d));

            // Add each of these columns to the overall matrices
            $this->W = is_null($this->W) ? $w : $this->W->augment($w);
            $this->T = is_null($this->T) ? $t : $this->T->augment($t);
            $this->U = is_null($this->U) ? $u : $this->U->augment($u);
            $this->C = is_null($this->C) ? $c : $this->C->augment($c);
            $this->P = is_null($this->P) ? $p : $this->P->augment($p);
        }
       
        // Calculate R or Wstar
        $R = $this->W->multiply($this->P->transpose()->multiply($this->W)->inverse());
        $this->B = $R->multiply($this->C->transpose());
    }

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

    public function getW()
    {
        return $this->W;
    }

    public function predict(Matrix $X)
    {
        $E = $this->standardizeData($X, $this->Xcenter, $this->Xscale);
        $F = $E->multiply($this->B);
        return $this->unstandardizeData($F);
    }

    /**
     * Verify that the matrix has the same number of columns as the original data
     *
     * @param Matrix $newData
     *
     * @throws Exception\BadDataException if the matrix is not square
     */
    private function checkNewData(Matrix $newData)
    {
        $given = $newData->getN();
        $expecting = $this->Xcenter->getN();
        if ($given !== $expecting) {
            throw new Exception\BadDataException("Data does not have the same number of columns. Expecting {$expecting}, given $given");
        }
    }

    /**
     * Standardize the data
     * Use the object $Xcenter and $Xscale Vectors to transform the provided data
     *
     * @param Matrix $new_data - An optional Matrix of new data which is standardized against the original data
     *
     * @return Matrix
     *
     * @throws Exception\MathException
     */
    private function standardizeData(Matrix $new_data, Vector $center, Vector $scale): Matrix
    {
        $X = $new_data;

        $ones_column = MatrixFactory::one($X->getM(), 1);
        
        // Create a matrix the same dimensions as $new_data, each element is the average of that column in the original data.
        $center_matrix = $center_matrix ?? $ones_column->multiply(MatrixFactory::create([$center->getVector()]));
        $scale_matrix = MatrixFactory::diagonal($scale->getVector())->inverse();

        // scaled data: ($X - μ) / σ
        return $X->subtract($center_matrix)->multiply($scale_matrix);
    }

    /**
     * Standardize the data
     * Use the object $Xcenter and $Xscale Vectors to transform the provided data
     *
     * @param Matrix $new_data - An optional Matrix of new data which is standardized against the original data
     *
     * @return Matrix
     *
     * @throws Exception\MathException
     */
    private function unstandardizeData(Matrix $new_data): Matrix
    {
        $this->checkNewData($new_data);
        $Y = $new_data;

        $ones_column = MatrixFactory::one($Y->getM(), 1);
        
        // Create a matrix the same dimensions as $new_data, each element is the average of that column in the original data.
        $center_matrix = $ones_column->multiply(MatrixFactory::create([$this->Ycenter->getVector()]));
        $scale_matrix = MatrixFactory::diagonal($this->Yscale->getVector());

        // unscaled data: $Y * σ + μ
        return $X->multiply($scale_matrix)->add($center_matrix);
    }

    private static function columnStdevs(Matrix $M)
    {
        $scaleArray = [];
        for ($i = 0; $i < $M->getN(); $i++) {
            $scaleArray[] = Descriptive::standardDeviation($M->getColumn($i));
        }
        return new Vector($scaleArray);
    }

    /**
     * Regression Through the Origin
     */
    private static function RTO(Matrix $X, Matrix $Y)
    {
        return $X->transpose()->multiply($Y)->scalarDivide($Y->transpose()->multiply($Y)->get(0, 0));
    }
}

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
 * Principal component analysis
 *
 * PCA uses the correlation between data vectors to find a transformation that minimizes variability.
 *
 * https://en.wikipedia.org/wiki/Principal_component_analysis
 */
class PCA
{
    /** @var Matrix Dataset */
    protected $data;
 
    /** @var Vector Means */
    protected $center;
 
    /** @var Vector Scale */
    protected $scale;

    /**
     * @var Vector $EVal
     * The EigenValues of the correlation Matrix
     * Also the Loading Matrix for the PCA
     */
    protected $EVal = null;

    /**
     * @var Matrix $EVec
     * The Eigenvectors of the correlation matrix
     */
    protected $EVec = null;

    /**
     * @var number $inertia
     * The trace of the correlation matrix
     * Also the sum of the eigenvalues
     */
    protected $inertia;
    
    /**
     * Constructor
     *
     * @param Matrix $A each row is a sample, each column is a variable
     * @param bool $center - Sets if the columns are to be centered to μ = 0
     * @param bool $scale - Sets if the columns are to be scaled to σ  = 1
     *
     * @throws Exception\BadDataException if any rows have a different column count
     */
    public function __construct(Matrix $M, bool $center = true, bool $scale = true)
    {
        // Check that there is enough data: at least two columns and rows
        if (!($M->getM() > 1) || !($M->getN() > 1)) {
            throw new Exception\BadDataException('Data matrix must be at least 2x2.');
        }
        if ($center === true) {
            $this->center = $M->columnMeans();
        } else {
            $this->center = new Vector(array_fill(0, $this->data->getN()));
        }
        if ($scale === true) {
            $scalearray = [];
            for ($i = 0; $i < $M->getN(); $i++) {
                $scalearray[] = Descriptive::standardDeviation($M->getColumn($i));
            }
            $this->scale = new Vector($scalearray);
        } else {
            $this->scale = new Vector(array_fill(1, $this->data->getN()));
        }
        // Save the source data to the class
        $this->data = $M;

        // Center and scale the data as needed
        $this->data = $this->normalizeData();
        
        // Create the correlation / variance-covarience Matrix
        $samples = $M->getM();
        $corrCovMatrix = $this->data->transpose()->multiply($this->data)->scalarDivide($samples - 1);
        
        $this->inertia = $corrCovMatrix->trace();
        $this->EVal = new Vector($corrCovMatrix->eigenvalues(Eigenvalue::JACOBI_METHOD));
        $this->EVec = $corrCovMatrix->eigenvectors(Eigenvalue::JACOBI_METHOD);
    }

    /**
     * Verify that the matrix has the same number of columns as the original data
     *
     * @param Matrix $A
     *
     * @throws Exception\BadDataException if the matrix is not square
     */
    private function checkNewData(Matrix $new_data)
    {
        if ($new_data->getN() !== $this->data->getN()) {
            throw new Exception\BadDataException('Data does not have the same number of columns');
        }
    }

    /**
     * NormalizeData
     * Use the object $center and $scale Vectors to transform the provided data
     *
     * @param Matrix $new_data - An optional Matrix of new data which is Normalized against the original data
     *
     * @return Matrix
     */
    public function normalizeData(Matrix $new_data = null): Matrix
    {
        if ($new_data === null) {
            $X = $this->data;
        } else {
            $this->checkNewData($new_data);
            $X = $new_data;
        }
        $ones_column = MatrixFactory::one($X->getM(), 1);
        
        // Create a matrix the same dimentions as $new_data, each element is the average of that column in the original data.
        $center_matrix = $ones_column->multiply(MatrixFactory::create([$this->center->getVector()]));
        $scale_matrix = MatrixFactory::diagonal($this->scale->getVector())->inverse();

        // $scaled_data = ($X - μ) / σ
        $scaled_data = $X->subtract($center_matrix)->multiply($scale_matrix);
        return $scaled_data;
    }
    
    /**
     * The loadings are the unit eigenvectors of the correlation matrix
     * @return Matrix
     */
    public function getLoadings(): Matrix
    {
        return $this->EVec;
    }

    /**
     * The eigenvalues of the correlation matrix
     * @return Vector
     */
    public function getEigenvalues(): Vector
    {
        return $this->EVal;
    }
 
    /**
     * Get Scores
     *
     * Transform the normalized data with the loadings matrix
     * @return Matrix
     */
    public function getScores(Matrix $new_data = null): Matrix
    {
        if ($new_data === null) {
            $scaled_data = $this->data;
        } else {
            $this->checkNewData($new_data);
            $scaled_data = $this->normalizeData($newdata);
        }
        return $scaled_data->multiply($this->EVec);
    }

    /**
     * Get R² Values
     *
     * R² for each component is eigenvalue divided by the sum of all eigenvalues
     * @return float[]
     */
    public function getRsq(): array
    {
        return $this->EVal->scalarDivide($this->inertia)->getVector();
    }

    /**
     * Get the cumulative R²
     * @return float[]
     */
    public function getCumRsq(): array
    {
        $array = $this->getRsq();
        $result = [];
        $sum = 0;
        foreach ($array as $value) {
            $sum += array_shift($array);
            $result[] = $sum;
        }
        return $result;
    }

    /**
     * Get the Q Residuals
     *
     * The Q redidual is the error in the model at a given model complexity.
     * For each row (i) in the data Matrix x, and retained componenets (j):
     * Qᵢ = eᵢ'eᵢ = xᵢ(I-PⱼPⱼ')xᵢ'
     *
     * @param Matrix $new_data - An optional Matrix of new data which is Normalized against the original data
     *
     * @return Matrix
     */
    public function getQResiduals(Matrix $new_data = null): Matrix
    {
        $vars = $this->data->getN();
        if ($new_data === null) {
            $X = $this->data;
        } else {
            $this->checkNewData($new_data);
            $X = normalizeData($new_data);
        }
        
        $Xprime = $X->transpose();
        $initialized = false;
        $I = MatrixFactory::identity($vars);
        for ($i = 0; $i < $vars; $i++) {
            // Get the first $i+1 columns of the loading matrix
            $P = $this->EVec->submatrix(0, 0, $vars - 1, $i);
            $Pprime = $P->transpose();
            $new_column = MatrixFactory::create([$X->multiply($I->subtract($P->multiply($Pprime)))->multiply($Xprime)->getDiagonalElements()])->transpose();
            if (!$initialized) {
                $result_matrix = $new_column;
                $initialized = true;
            } else {
                $result_matrix = $result_matrix->augment($new_column);
            }
        }
        return $result_matrix;
    }
    
    /**
     * Get the T² Distance
     *
     * Get the distance from the score to the center of the model plane.
     * For each row (i) in the data matrix, and retained componenets (j)
     * Tᵢ² = XᵢPⱼΛⱼ⁻¹Pⱼ'Xᵢ'
     *
     * @param Matrix $new_data - An optional Matrix of new data which is Normalized against the original data
     *
     * @return Matrix
     */
    public function getT²Distances(Matrix $new_data = null): Matrix
    {
        $vars = $this->data->getN();
        if ($new_data === null) {
            $X = $this->data;
        } else {
            $this->checkNewData($new_data);
            $X = normalizeData($new_data);
        }
        $Xprime = $X->transpose();
        $initialized = false;
        for ($i = 0; $i < $this->data->getN(); $i++) {
            // Get the first $i+1 columns of the loading matrix
            $P = $this->EVec->submatrix(0, 0, $vars - 1, $i);
            $inverse_lambda = MatrixFactory::diagonal(array_slice($this->EVal->getVector(), 0, $i + 1))->inverse();
            $Pprime = $P->transpose();
            $new_column = MatrixFactory::create([$X->multiply($P)->multiply($inverse_lambda)->multiply($Pprime)->multiply($Xprime)->getDiagonalElements()])->transpose();
            if (!$initialized) {
                $result_matrix = $new_column;
                $initialized = true;
            } else {
                $result_matrix = $result_matrix->augment($new_column);
            }
        }
        return $result_matrix;
    }

    /**
     * Calculate the critical limits of T²
     *
     * @param float $alpha the probability limit of the critical value
     * @return float[] Critical values for each model complexity
     */
    public function getCriticalT²(float $alpha = .05): array
    {
        $samp = $this->data->getM();
        $vars = $this->data->getN();
        for ($i = 1; $i <= $vars; $i++) {
            $F = new F($i, $samp - $i);
            $T = $i * ($samp - 1) * $F->inverse(1 - $alpha) / ($samp - $i);
            $T_array[] = $T;
        }
        return $T_array;
    }

    /**
     * Calculate the critical limits of Q
     *
     * @param float $alpha the probability limit of the critical value
     * @return float[] Critical values for each model complexity
     */
    public function getCriticalQ(float $alpha = .05): array
    {
        $vars = $this->data->getN();
        $Qcrit = [];
        for ($i = 0; $i < $vars - 1; $i++) {
            $evals = array_slice($this->EVal->getVector(), $i + 1);
            $t1 = array_sum($evals);
            $t2 = array_sum(Single::square($evals));
            $t3 = array_sum(Single::pow($evals, 3));
            $h0 = 1 -2 * $t1 * $t3 / 3 / $t2 ** 2;
            if ($h0 < .001) {
                $h0 = .001;
            }
            $normal = new StandardNormal();
            $ca = $normal->inverse(1 - $alpha);
            $h1 = $ca * sqrt(2 * $t2 * $h0 ** 2) / $t1;
            $h2 = $t2 * $h0 * ($h0 - 1) / $t1 ** 2;
            $Qcrit[] = $t1 * (1 + $h1 + $h2) ** (1 / $h0);
        }
        // The final value is always zero since the model is perfectly fit.
        $Qcrit[] = 0;
        return $Qcrit;
    }
}

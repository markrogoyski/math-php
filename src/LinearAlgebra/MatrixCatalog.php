<?php

namespace MathPHP\LinearAlgebra;

class MatrixCatalog
{
    /** @var Matrix transpose */
    private $Aᵀ;

    /** @var Matrix inverse */
    private $A⁻¹;

    /** @var Reduction\RowEchelonForm */
    private $REF;

    /** @var Reduction\ReducedRowEchelonForm */
    private $RREF;

    /** @var Decomposition\LU */
    private $LU;

    /** @var Decomposition\QR */
    private $QR;

    /** @var Decomposition\Cholesky */
    private $cholesky;

    /** @var Decomposition\Crout */
    private $crout;

    /** @var float determinant */
    private $det;

    /**************************************************************************
     * DERIVED MATRICES
     *  - transpose
     *  - inverse
     **************************************************************************/

    // TRANSPOSE

    /**
     * @param Matrix $Aᵀ
     */
    public function addTranspose(Matrix $Aᵀ)
    {
        $this->Aᵀ = $Aᵀ;
    }

    /**
     * @return bool
     */
    public function hasTranspose()
    {
        return isset($this->Aᵀ);
    }

    /**
     * @return Matrix
     */
    public function getTranspose(): Matrix
    {
        return $this->Aᵀ;
    }

    // INVERSE

    /**
     * @param Matrix $A⁻¹
     */
    public function addInverse(Matrix $A⁻¹)
    {
        $this->A⁻¹ = $A⁻¹;
    }

    /**
     * @return bool
     */
    public function hasInverse()
    {
        return isset($this->A⁻¹);
    }

    /**
     * @return Matrix
     */
    public function getInverse(): Matrix
    {
        return $this->A⁻¹;
    }

    /**************************************************************************
     * MATRIX REDUCTIONS
     *  - ref (row echelon form)
     *  - rref (reduced row echelon form)
     **************************************************************************/

    // ROW ECHELON FORM

    /**
     * @param Reduction\RowEchelonForm $REF
     */
    public function addRowEchelonForm(Reduction\RowEchelonForm $REF)
    {
        $this->REF = $REF;
    }

    /**
     * @return bool
     */
    public function hasRowEchelonForm()
    {
        return isset($this->REF);
    }

    /**
     * @return Reduction\RowEchelonForm
     */
    public function getRowEchelonForm(): Reduction\RowEchelonForm
    {
        return $this->REF;
    }

    // REDUCED ROW ECHELON FORM

    /**
     * @param Reduction\ReducedRowEchelonForm $RREF
     */
    public function addReducedRowEchelonForm(Reduction\ReducedRowEchelonForm $RREF)
    {
        $this->RREF = $RREF;
    }

    /**
     * @return bool
     */
    public function hasReducedRowEchelonForm()
    {
        return isset($this->RREF);
    }

    /**
     * @return Reduction\ReducedRowEchelonForm
     */
    public function getReducedRowEchelonForm(): Reduction\ReducedRowEchelonForm
    {
        return $this->RREF;
    }

    /**************************************************************************
     * MATRIX DECOMPOSITIONS
     *  - LU decomposition
     *  - QR decomposition
     *  - Cholesky decomposition
     *  - Crout decomposition
     **************************************************************************/


    // LU DECOMPOSITION

    /**
     * @param Decomposition\LU $LU
     */
    public function addLuDecomposition(Decomposition\LU $LU)
    {
        $this->LU = $LU;
    }

    /**
     * @return bool
     */
    public function hasLuDecomposition()
    {
        return isset($this->LU);
    }

    /**
     * @return Decomposition\LU
     */
    public function getLuDecomposition(): Decomposition\LU
    {
        return $this->LU;
    }

    // QR DECOMPOSITION

    /**
     * @param Decomposition\LU $QR
     */
    public function addQrDecomposition(Decomposition\QR $QR)
    {
        $this->QR = $QR;
    }

    /**
     * @return bool
     */
    public function hasQrDecomposition()
    {
        return isset($this->QR);
    }

    /**
     * @return Decomposition\QR
     */
    public function getQrDecomposition(): Decomposition\QR
    {
        return $this->QR;
    }

    // CHOLESKY DECOMPOSITION

    /**
     * @param Decomposition\Cholesky $cholesky
     */
    public function addCholeskyDecomposition(Decomposition\Cholesky $cholesky)
    {
        $this->cholesky = $cholesky;
    }

    /**
     * @return bool
     */
    public function hasCholeskyDecomposition()
    {
        return isset($this->cholesky);
    }

    /**
     * @return Decomposition\Cholesky
     */
    public function getCholeskyDecomposition(): Decomposition\Cholesky
    {
        return $this->cholesky;
    }

    // CROUT DECOMPOSITION

    /**
     * @param Decomposition\Crout $crout
     */
    public function addCroutDecomposition(Decomposition\Crout $crout)
    {
        $this->crout = $crout;
    }

    /**
     * @return bool
     */
    public function hasCroutDecomposition()
    {
        return isset($this->crout);
    }

    /**
     * @return Decomposition\Crout
     */
    public function getCroutDecomposition(): Decomposition\Crout
    {
        return $this->crout;
    }

    /**************************************************************************
     * DERIVED DATA
     *  - determinant
     **************************************************************************/

    // DETERMINANT

    /**
     * @param number $det
     */
    public function addDeterminant($det)
    {
        $this->det = $det;
    }

    /**
     * @return bool
     */
    public function hasDeterminant()
    {
        return isset($this->det);
    }

    /**
     * @return number
     */
    public function getDeterminant()
    {
        return $this->det;
    }
}

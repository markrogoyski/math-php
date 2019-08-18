<?php
namespace MathPHP\LinearAlgebra;

class MatrixCatalog
{
    /** @var Decomposition\LU */
    private $LU;

    /** @var Decomposition\QR */
    private $QR;

    /** @var Decomposition\Cholesky */
    private $cholesky;

    /** @var Decomposition\Crout */
    private $crout;

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
}

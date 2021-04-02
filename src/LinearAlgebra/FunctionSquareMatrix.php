<?php

namespace MathPHP\LinearAlgebra;

use MathPHP\Exception;

class FunctionSquareMatrix
{
    /**
     * FunctionSquareMatrix constructor.
     *
     * @param array $A
     *
     * @throws Exception\BadDataException
     * @throws Exception\MathException
     */
    public function __construct(array $A)
    {
        $n = \count($A);
        $m = \count($A[0]);

        if ($m !== $n) {
            throw new Exception\BadDataException("n must equal m for square Function Matrix. n = $n, m = $m");
        }

        $this->validateMatrixDimensions();
    }

    /**
     * Validate the matrix is entirely m x n
     *
     * @throws Exception\BadDataException
     */
    protected function validateMatrixDimensions()
    {
        foreach ($this->A as $i => $row) {
            if (\count($row) !== $this->n) {
                throw new Exception\BadDataException("Row $i has a different column count: " . \count($row) . "; was expecting {$this->n}.");
            }
        }
    }

    /**
     * Evaluate
     *
     * @param array $params
     *
     * @return NumericMatrix
     *
     * @throws Exception\BadDataException
     * @throws Exception\IncorrectTypeException
     * @throws Exception\MathException
     * @throws Exception\MatrixException
     */
    public function evaluate(array $params): NumericMatrix
    {
        $m = $this->m;
        $n = $this->n;
        $R = [];
        for ($i = 0; $i < $m; $i++) {
            for ($j = 0; $j < $n; $j++) {
                $func = $this->A[$i][$j];
                $R[$i][$j] = $func($params);
            }
        }
        return MatrixFactory::create($R);
    }
}

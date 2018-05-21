<?php
namespace MathPHP\LinearAlgebra;

use MathPHP\Exception;

class FunctionSquareMatrix extends SquareMatrix
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
        $n = count($A);
        $m = count($A[0]);

        if ($m !== $n) {
            throw new Exception\BadDataException("n must equal m for square Function Matrix. n = $n, m = $m");
        }

        parent::__construct($A);
    }

    /**
     * Evaluate
     *
     * @param array $params
     *
     * @return Matrix
     *
     * @throws Exception\BadDataException
     * @throws Exception\IncorrectTypeException
     * @throws Exception\MathException
     * @throws Exception\MatrixException
     */
    public function evaluate(array $params): Matrix
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

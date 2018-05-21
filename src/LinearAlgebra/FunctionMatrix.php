<?php
namespace MathPHP\LinearAlgebra;

use MathPHP\Exception;

class FunctionMatrix extends Matrix
{
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

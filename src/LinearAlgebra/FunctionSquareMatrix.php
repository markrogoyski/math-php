<?php
namespace Math\LinearAlgebra;

class FunctionSquareMatrix extends SquareMatrix
{
    public function __construct(array $A)
    {
        $n = count($A);
        $m = count($A[0]);

        if ($m !== $n) {
            throw new \Exception('n must equal m for square Function Matrix');
        }

        parent::__construct($A);
    }

    public function evaluate(array $params)
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

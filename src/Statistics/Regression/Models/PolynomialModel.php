<?php

namespace MathPHP\Statistics\Regression\Models;

use MathPHP\Exception\BadDataException;
use MathPHP\Exception\IncorrectTypeException;
use MathPHP\Exception\MathException;
use MathPHP\Exception\MatrixException;
use MathPHP\LinearAlgebra\MatrixFactory;
use MathPHP\Polynomials\MonomialExponentGenerator;
use MathPHP\Util\Script;

trait PolynomialModel
{
    /**
     * Order of the polynomial
     * @var int
     */
    private $order = 1;

    /**
     * Whether the model should fit a constant
     * @var int
     */
    private $fit_constant = 1;

    /**
     * Evaluate the model given all the model parameters
     *
     * @param list<float> $vector
     * @param non-empty-list<float> $params
     *
     * @return float y evaluated
     *
     * @throws BadDataException
     * @throws IncorrectTypeException
     * @throws MathException
     * @throws MatrixException
     */
    public function evaluateModel(array $vector, array $params): float
    {
        $M = [$vector];
        $X = MatrixFactory::vandermonde($M, $this->order + 1);
        if ($this->fit_constant == 0) {
            $X = $X->columnExclude(0);
        }

        $row = $X->getRow(0);
        $y = 0;
        foreach ($row as $i => $x_val) {
            $y += $x_val * $params[$i];
        }
        return $y;
    }

    /**
     * Get regression parameters (coefficients).
     *
     * Use array_values() on the result to get a list<float> of coefficients.
     *
     * @param array<int, float> $params
     *
     * @return array<string, float> [β₀ + β₁ + β₂ + … + βₖ]
     */
    public function getModelParameters(array $params): array
    {
        $result = [];
        for ($i = 0, $len = \count($params); $i < $len; $i++) {
            $result['β' . Script::getSubscript($i)] = $params[$i];
        }
        return $result;
    }

    /**
     * Get regression equation
     *
     * @param non-empty-list<float> $params
     *
     * @return string
     */
    public function getModelEquation(array $params): string
    {
        $result = '';
        $exponentTuples = $this->getExponentTuples(\count($params) - 1);
        if ($this->fit_constant == 0) {
            \array_shift($exponentTuples);
        }
        foreach ($exponentTuples as $i => $exponents) {
            $result .= $result === '' ? 'y = ' : ' + ';
            $result .= \sprintf('%f', $params[$i]);
            foreach ($exponents as $j => $exponent) {
                if ($exponent === 0) {
                    continue;
                }
                $superscript = $exponent === 1 ? '' : Script::getSuperscript($exponent);
                $result .= \sprintf(' * x%s%s', Script::getSubscript($j + 1), $superscript);
            }
        }
        return $result;
    }

    /**
     * @param int $dimension
     * @return list<list<int>>
     */
    public function getExponentTuples(int $dimension): array
    {
        return MonomialExponentGenerator::all($dimension, $this->order, true);
    }
}

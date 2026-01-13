<?php

namespace MathPHP\Statistics\Regression\Models;

trait MultilinearModel
{
    private static $subscripts = ['₀', '₁', '₂', '₃', '₄', '₅', '₆', '₇', '₈', '₉'];

    /**
     * @param int $n
     *
     * @return string
     */
    public static function getSubscript(int $n): string
    {
        return \implode(
            '',
            \array_map(
                function ($c) {
                    return self::$subscripts[$c];
                },
                \str_split((string)$n)
            )
        );
    }

    /**
     * Evaluate the model given all the model parameters
     * y = x₁β₁ + x₂β₂ + … + xₖβₖ + ε
     *
     * @param list<float> $vector
     * @param non-empty-list<float> $params
     *
     * @return float y evaluated
     */
    public static function evaluateModel(array $vector, array $params): float
    {
        $y = 0;
        for ($i = 1, $len = \count($params); $i < $len; $i++) {
            $y += $vector[$i - 1] * $params[$i];
        }
        $y += $params[0];
        return $y;
    }

    /**
     * Get regression parameters (coefficients)
     *
     * @param non-empty-list<float> $params
     *
     * @return array<string, float>
     */
    public function getModelParameters(array $params): array
    {
        $result = [];
        for ($i = 1, $len = \count($params); $i < $len; $i++) {
            $result['β' . self::getSubscript($i)] = $params[$i];
        }
        $result['ε'] = $params[0];
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
        $result = 'y = ';
        for ($i = 1, $len = \count($params); $i < $len; $i++) {
            $result .= \sprintf('%fx%s + ', $params[$i], self::getSubscript($i));
        }
        $result .= \sprintf('%f', $params[0]);
        return $result;
    }
}

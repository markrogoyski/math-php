<?php

namespace MathPHP\Statistics\Regression\Models;

trait MultilinearModel
{
    /**
     * @var list<string>
     */
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
     * y = β₀ + x₁β₁ + x₂β₂ + … + xₖβₖ
     *
     * @param list<float> $vector
     * @param non-empty-list<float> $params
     *
     * @return float y evaluated
     */
    public static function evaluateModel(array $vector, array $params): float
    {
        $y = $params[0];
        for ($i = 1, $len = \count($params); $i < $len; $i++) {
            $y += $vector[$i - 1] * $params[$i];
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
            $result['β' . self::getSubscript($i)] = $params[$i];
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
        $result = \sprintf('y = %f', $params[0]);
        for ($i = 1, $len = \count($params); $i < $len; $i++) {
            $result .= \sprintf(' + %fx%s', $params[$i], self::getSubscript($i));
        }
        return $result;
    }
}

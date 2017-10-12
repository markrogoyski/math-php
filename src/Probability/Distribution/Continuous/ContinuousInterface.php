<?php

namespace MathPHP\Probability\Distribution\Continuous;

interface ContinuousInterface
{
    public function pdf(float $x);
    public function cdf(float $x);
    public function mean();
}

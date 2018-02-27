<?php
namespace MathPHP\Tests\Probability\Distribution\Discrete;

use MathPHP\Probability\Distribution\Discrete\Pascal;

class PascalTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @dataProvider dataProviderForPMF
     */
    public function testPMF(int $x, int $r, float $P, float $neagative_binomial_distribution)
    {
        $pascal = new Pascal($r, $P);
        $this->assertEquals($neagative_binomial_distribution, $pascal->pmf($x), '', 0.001);
    }

    /**
     * Data provider for  PMF
     * Data: [ x, r, P, negative binomial distribution ]
     */
    public function dataProviderForPMF()
    {
        return [
            [ 2, 1, 0.5, 0.25 ],
            [ 2, 1, 0.4, 0.24 ],
            [ 6, 2, 0.7, 0.019845 ],
            [ 8, 7, 0.83, 0.322919006776561 ],
            [ 10, 5, 0.85, 0.00424542789316406 ],
            [ 50, 48, 0.97, 0.245297473979909 ],
            [ 5, 4, 1, 0.0 ],
            [ 2, 2, 0.5, 0.25 ],
        ];
    }
}

<?php
namespace MathPHP\Tests\Probability\Distribution\Discrete;

use MathPHP\Probability\Distribution\Discrete\NegativeBinomial;

class NegativeBinomialTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @dataProvider dataProviderForPMF
     */
    public function testPMF(int $x, int $r, float $P, float $pmf)
    {
        $negativeBinomial = new NegativeBinomial($r, $P);
        $this->assertEquals($pmf, $negativeBinomial->pmf($x), '', 0.001);
    }

    /**
     * Data provider for negative binomial PMF
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
            [ 3, 1, 0.20, 0.128 ],
            [ 7, 3, 0.20, 0.049152 ],
        ];
    }
}

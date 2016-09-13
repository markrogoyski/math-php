<?php

namespace Math\Functions;

class PiecewiseTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider dataProviderForEval
     */
    public function testEval(array $coefficients, $x, $expected)
    {
        $polynomial = new Polynomial($coefficients);
        $evaluated  = $polynomial($x);
        $this->assertEquals($expected, $evaluated);
    }

    public function dataProviderForEval()
    {
        return [
            [
                [
                  [-100, -2],                   // f interval: [-100, -2]
                  [-2, 2],                      // g interval: [-2, 2]
                  [2, 100]                      // h interval: [2, 100]
                ], [
                  function ($x) { return -$x }, // f(x) = -x
                  function ($x) { return 2 },   // g(x) = 2
                  function ($x) { return $x }   // h(x) = x
                ],
                -27, 27       // p(-27) = f(-27) = -(-27) = 27
            ],
            [
                [
                  [-100, -2],                   // f interval: [-100, -2]
                  [-2, 2],                      // g interval: [-2, 2]
                  [2, 100]                      // h interval: [2, 100]
                ], [
                  function ($x) { return -$x }, // f(x) = -x
                  function ($x) { return 2 },   // g(x) = 2
                  function ($x) { return $x }   // h(x) = x
                ],
                1, 2       // p(1) = g(1) = 2
            ],
            [
                [
                  [-100, -2],                   // f interval: [-100, -2]
                  [-2, 2],                      // g interval: [-2, 2]
                  [2, 100]                      // h interval: [2, 100]
                ], [
                  function ($x) { return -$x }, // f(x) = -x
                  function ($x) { return 2 },   // g(x) = 2
                  function ($x) { return $x }   // h(x) = x
                ],
                20, 20       // p(20) = h(20) = 20
            ],
        ];
    }
}

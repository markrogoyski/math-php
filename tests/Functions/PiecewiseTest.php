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
            // Test evaluation given a single interval, function
            [
                [
                  [-100, 100],                  // f interval: [-100, 100]
                ], [
                  new Polynomial([1, 0])        // f(x) = x
                ],
                25, 25       // p(25) = f(25) = 25
            ],
            // Test eveluation in 1st piece given 3 intervals, functions
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
            // Test eveluation in 2nd piece given 3 intervals, functions
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
            // Test eveluation in 3rd piece given 3 intervals, functions
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
            // Test eveluation at "jump" located at a single point
            [
                [
                  [-100, -2],                   // f interval: [-100, -2]
                  [-2, 2, false, true],         // g interval: [-2, 2)
                  [2, 2],                       // z interval: [2, 2]
                  [2, 100, true, false]         // h interval: (2, 100]
                ], [
                  function ($x) { return -$x }, // f(x) = -x
                  function ($x) { return 2 },   // g(x) = 2
                  function ($x) { return 0 },   // z(x) = 0
                  function ($x) { return $x }   // h(x) = x
                ],
                2, 0       // p(2) = z(2) = 0
            ],
        ];
    }
}

<?php

namespace Math\Functions;

class PiecewiseTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider dataProviderForEval
     */
    public function testEval(array $intervals, array $functions, $input, $expected)
    {
        $piecewise = new Piecewise($intervals, $functions);
        $evaluated = $piecewise($input);
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
                  [-100, -2, false, true],      // f interval: [-100, -2)
                  [-2, 2],                      // g interval: [-2, 2]
                  [2, 100, true, false]         // h interval: (2, 100]
                ], [
                  new Polynomial([-1, 0]),      // f(x) = -x
                  new Polynomial([2]),          // g(x) = 2
                  new Polynomial([1, 0])        // h(x) = x
                ],
                -27, 27       // p(-27) = f(-27) = -(-27) = 27
            ],
            // Test eveluation in 2nd piece given 3 intervals, functions
            [
                [
                  [-100, -2, false, true],      // f interval: [-100, -2)
                  [-2, 2],                      // g interval: [-2, 2]
                  [2, 100, true, false]         // h interval: (2, 100]
                ], [
                  new Polynomial([-1, 0]),      // f(x) = -x
                  new Polynomial([2]),          // g(x) = 2
                  new Polynomial([1, 0])        // h(x) = x
                ],
                1, 2       // p(1) = g(1) = 2
            ],
            // Test eveluation in 3rd piece given 3 intervals, functions
            [
                [
                  [-100, -2, false, true],      // f interval: [-100, -2)
                  [-2, 2],                      // g interval: [-2, 2]
                  [2, 100, true, false]         // h interval: (2, 100]
                ], [
                  new Polynomial([-1, 0]),      // f(x) = -x
                  new Polynomial([2]),          // g(x) = 2
                  new Polynomial([1, 0])        // h(x) = x
                ],
                20, 20       // p(20) = h(20) = 20
            ],
            // Test eveluation at discountinuous, intermediate point
            [
                [
                  [-100, -2, false, true],      // f interval: [-100, -2)
                  [-2, 2],                      // g interval: [-2, 2]
                  [2, 100, true, false]         // h interval: (2, 100]
                ], [
                  new Polynomial([-1, 0]),      // f(x) = -x
                  new Polynomial([100]),        // g(x) = 100
                  new Polynomial([1, 0])        // h(x) = x
                ],
                2, 100       // p(2) = h(2) = 100
            ],
            // Test eveluation when intervals are given out of order
            [
                [
                  [-2, 2],                      // g interval: [-2, 2]
                  [-100, -2, false, true],      // f interval: [-100, -2)
                  [2, 100, true, false]         // h interval: (2, 100]
                ], [
                  new Polynomial([2]),          // g(x) = 2
                  new Polynomial([-1, 0]),      // f(x) = -x
                  new Polynomial([1, 0])        // h(x) = x
                ],
                0, 2       // p(0) = g(0) = 2
            ],
            // Test eveluation at "jump" located at a single point
            [
                [
                  [-100, -2],                   // f interval: [-100, -2]
                  [-2, 2, true, true],          // g interval: (-2, 2)
                  [2, 2],                       // z interval: [2, 2]
                  [2, 100, true, false]         // h interval: (2, 100]
                ], [
                  new Polynomial([-1, 0]),      // f(x) = -x
                  new Polynomial([2]),          // g(x) = 2
                  new Polynomial([0]),          // z(x) = 0
                  new Polynomial([1, 0])        // h(x) = x
                ],
                2, 0       // p(2) = z(2) = 0
            ],
        ];
    }

    public function testSubintervalsShareClosedPointException()
    {
        $intervals = [
          [-100, -2],                    // f interval: [-100, -2]
          [-2, 2],                       // g interval: [-2, 2]
          [2, 100]                       // h interval: [2, 100]
        ];
        $functions = [
          new Polynomial([-1, 0]),      // f(x) = -x
          new Polynomial([2]),          // g(x) = 2
          new Polynomial([1, 0])        // h(x) = x
        ];

        $this->setExpectedException('\Exception');
        $piecewise = new Piecewise($intervals, $functions);
    }

    public function testSubintervalsOverlapException()
    {
        $intervals = [
          [-100, -2],                    // f interval: [-100, -2]
          [-5, 1],                       // g interval: [-2, 1]
          [2, 100]                       // h interval: [2, 100]
        ];
        $functions = [
          new Polynomial([-1, 0]),      // f(x) = -x
          new Polynomial([2]),          // g(x) = 2
          new Polynomial([1, 0])        // h(x) = x
        ];

        $this->setExpectedException('\Exception');
        $piecewise = new Piecewise($intervals, $functions);
    }

    public function testSubintervalDecreasingException()
    {
        $intervals = [
          [-100, -2],                    // f interval: [-100, -2]
          [2, -2, true, true],           // g interval: (-2, 2)
          [2, 100]                       // h interval: [2, 100]
        ];
        $functions = [
          new Polynomial([-1, 0]),      // f(x) = -x
          new Polynomial([2]),          // g(x) = 2
          new Polynomial([1, 0])        // h(x) = x
        ];

        $this->setExpectedException('\Exception');
        $piecewise = new Piecewise($intervals, $functions);
    }

    public function testSubintervalContainsMoreThanTwoPoints()
    {
        $intervals = [
          [-100, -2, false, true],      // f interval: [-100, -2)
          [0, 2, 3],                    // g interval: [0, 3]
          [3, 100, true, false]         // h interval: (3, 100]
        ];
        $functions = [
          new Polynomial([-1, 0]),      // f(x) = -x
          new Polynomial([2]),          // g(x) = 2
          new Polynomial([1, 0])        // h(x) = x
        ];

        $this->setExpectedException('\Exception');
        $piecewise = new Piecewise($intervals, $functions);
    }

    public function testSubintervalContainsOnePoints()
    {
        $intervals = [
          [-100, -2, false, true],      // f interval: [-100, -2)
          [-2],                         // g interval: [-2, -2]
          [3, 100, true, false]         // h interval: (3, 100]
        ];
        $functions = [
          new Polynomial([-1, 0]),      // f(x) = -x
          new Polynomial([2]),          // g(x) = 2
          new Polynomial([1, 0])        // h(x) = x
        ];

        $this->setExpectedException('\Exception');
        $piecewise = new Piecewise($intervals, $functions);
    }

    public function testSubintervalContainsOpenPoint()
    {
        $intervals = [
          [-100, -2, false, true],      // f interval: [-100, -2)
          [-2, -2, true, true],         // g interval: (-2, 2)
          [3, 100, true, false]         // h interval: (3, 100]
        ];
        $functions = [
          new Polynomial([-1, 0]),      // f(x) = -x
          new Polynomial([2]),          // g(x) = 2
          new Polynomial([1, 0])        // h(x) = x
        ];

        $this->setExpectedException('\Exception');
        $piecewise = new Piecewise($intervals, $functions);
    }

    public function testInputFunctionsAreNotCallableException()
    {
        $intervals = [
          [-100, -2, false, true],          // f interval: [-100, -2)
          [-2, 2],                          // g interval: [-2, 2]
          [2, 100, true, false]             // h interval: (2, 100]
        ];
        $functions = [
          new Polynomial([-1, 0]),      // f(x) = -x
          2,                            // g(x) = 2
          new Polynomial([1, 0])        // h(x) = x
        ];

        $this->setExpectedException('\Exception');
        $piecewise = new Piecewise($intervals, $functions);
    }

    public function testNumberOfIntervalsAndFunctionsUnequalException()
    {
        $intervals = [
          [-100, -2, false, true],      // f interval: [-100, -2)
          [0, 2],                       // g interval: [0, 2]
          [2, 100, true, false]         // h interval: (2, 100]
        ];
        $functions = [
          new Polynomial([-1, 0]),      // f(x) = -x
          new Polynomial([2]),          // g(x) = 2
        ];

        $this->setExpectedException('\Exception');
        $piecewise = new Piecewise($intervals, $functions);
    }

    public function testEvaluationNotInDomainException()
    {
        $intervals = [
          [-100, -2, false, true],      // f interval: [-100, -2)
          [0, 2],                       // g interval: [0, 2]
          [2, 100, true, false]         // h interval: (2, 100]
        ];
        $functions = [
          new Polynomial([-1, 0]),      // f(x) = -x
          new Polynomial([2]),          // g(x) = 2
          new Polynomial([1, 0])        // h(x) = x
        ];

        $this->setExpectedException('\Exception');
        $piecewise = new Piecewise($intervals, $functions);
        $evaluation = $piecewise(-1);
    }

    public function testEvaluatedAtOpenPointException()
    {
        $intervals = [
          [-100, -2, true, true],      // f interval: (-100, -2)
          [-2, 2, true, true],         // g interval: (0, 2)
          [2, 100, true, true]         // h interval: (2, 100)
        ];
        $functions = [
          new Polynomial([-1, 0]),      // f(x) = -x
          new Polynomial([2]),          // g(x) = 2
          new Polynomial([1, 0])        // h(x) = x
        ];

        $this->setExpectedException('\Exception');
        $piecewise = new Piecewise($intervals, $functions);
        $evaluation = $piecewise(2);
    }

    public function testDuplicatedIntervalException()
    {
        $intervals = [
          [-100, -2, true, true],      // f interval: (-100, -2)
          [-100, -2, true, true],      // g interval: [-100, -2)
          [2, 100]        // h interval: [2, 100]
        ];
        $functions = [
          new Polynomial([-1, 0]),      // f(x) = -x
          new Polynomial([2]),          // g(x) = 2
          new Polynomial([1, 0])        // h(x) = x
        ];

        $this->setExpectedException('\Exception');
        $piecewise = new Piecewise($intervals, $functions);
    }
}

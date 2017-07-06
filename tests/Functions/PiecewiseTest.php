<?php
namespace MathPHP\Tests\Functions;

use MathPHP\Functions\Piecewise;
use MathPHP\Functions\Polynomial;
use MathPHP\Exception;

class PiecewiseTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider dataProviderForEval
     */
    public function testEval(array $intervals, array $polynomial_args, array $inputs, array $expected)
    {
        if (count($inputs) !== count($expected)) {
            $this->fail('Number of inputs and expected outputs must match');
        }

        $functions = array_map(
            function ($args) {
                return new Polynomial($args);
            },
            $polynomial_args
        );
        $piecewise = new Piecewise($intervals, $functions);

        $n = count($inputs);
        for ($i = 0; $i < $n; $i++) {
            $this->assertEquals($expected[$i], $piecewise($inputs[$i]));
        }
    }

    public function dataProviderForEval()
    {
        return [
            // Test evaluation given a single interval, function
            [
                [
                    [-100, 100],  // f interval: [-100, 100]
                ],
                [
                    [1, 0],       // new Polynomial([1, 0])  // f(x) = x
                ],
                [
                    -100, // p(-100) = f(-100) = -100
                    0,    // p(0) = f(0) = 0
                    1,    // p(1) = f(1) = 1
                    25,   // p(25) = f(25) = 25
                    100,  // p(100) = f(100) = 100
                ],
                [
                    -100, // p(-100) = f(-100) = -100
                    0,    // p(0) = f(0) = 0
                    1,    // p(1) = f(1) = 1
                    25,   // p(25) = f(25) = 25
                    100,  // p(100) = f(100) = 100
                ],
            ],
            // Test evaluation in 3 intervals, functions
            [
                [
                    [-100, -2, false, true], // f interval: [-100, -2)
                    [-2, 2],                 // g interval: [-2, 2]
                    [2, 100, true, false]    // h interval: (2, 100]
                ],
                [
                    [-1, 0], // new Polynomial([-1, 0]),      // f(x) = -x
                    [2],     // new Polynomial([2]),          // g(x) = 2
                    [1, 0],  // new Polynomial([1, 0])        // h(x) = x
                ],
                [
                    -27,   // p(-27) = f(-27) = -(-27) = 27
                    -3,    // p(-3) = f(-3) = -(-3) = 3
                    -2,    // p(-2) = g(-2) = 2
                    -1,    // p(-1) = g(-1) = 2
                    0,     // p(0) = g(0) = 2
                    1,     // p(1) = g(1) = 2
                    2,     // p(2) = g(2) = 2
                    3,     // p(3) = h(3) = 3
                    20,    // p(20) = h(20) = 20
                    100,   // p(100) = h(100) = 100
                ],
                [
                    27,    // p(-27) = f(-27) = -(-27) = 27
                    3,     // p(-3) = f(-3) = -(-3) = 3
                    2,     // p(-2) = g(-2) = 2
                    2,     // p(-1) = g(-1) = 2
                    2,     // p(0) = g(0) = 2
                    2,     // p(1) = g(1) = 2
                    2,     // p(2) = g(2) = 2
                    3,     // p(3) = h(3) = 3
                    20,    // p(20) = h(20) = 20
                    100,   // p(100) = h(100) = 100
                ]
            ],
            // Test evaluation of 3 intervals, and at discountinuous, intermediate point
            [
                [
                    [-100, -2, false, true], // f interval: [-100, -2)
                    [-2, 2],                 // g interval: [-2, 2]
                    [2, 100, true, false]    // h interval: (2, 100]
                ],
                [
                    [-1, 0], // new Polynomial([-1, 0]),      // f(x) = -x
                    [100],   // new Polynomial([2]),          // g(x) = 100
                    [1, 0],  // new Polynomial([1, 0])        // h(x) = x
                ],
                [
                    -27,   // p(-27) = f(-27) = -(-27) = 27
                    -3,    // p(-3) = f(-3) = -(-3) = 3
                    -2,    // p(-2) = g(-2) = 100
                    -1,    // p(-1) = g(-1) = 100
                    0,     // p(0) = g(0) = 100
                    1,     // p(1) = g(1) = 100
                    2,     // p(2) = g(2) = 100
                    3,     // p(3) = h(3) = 3
                    20,    // p(20) = h(20) = 20
                    100,   // p(100) = h(100) = 100
                ],
                [
                    27,    // p(-27) = f(-27) = -(-27) = 27
                    3,     // p(-3) = f(-3) = -(-3) = 3
                    100,   // p(-2) = g(-2) = 2
                    100,   // p(-1) = g(-1) = 2
                    100,   // p(0) = g(0) = 2
                    100,   // p(1) = g(1) = 2
                    100,   // p(2) = g(2) = 100
                    3,     // p(3) = h(3) = 3
                    20,    // p(20) = h(20) = 20
                    100,   // p(100) = h(100) = 100
                ]
            ],
            // Test evaluation when intervals are given out of order
            [
                [
                    [-2, 2],                 // g interval: [-2, 2]
                    [-100, -2, false, true], // f interval: [-100, -2)
                    [2, 100, true, false]    // h interval: (2, 100]
                ],
                [
                    [2],     // new Polynomial([2]),          // g(x) = 2
                    [-1, 0], // new Polynomial([-1, 0]),      // f(x) = -x
                    [1, 0],  // new Polynomial([1, 0])        // h(x) = x
                ],
                [0], [2]       // p(0) = g(0) = 2
            ],
            // Test eveluation at "jump" located at a single point
            [
                [
                    [-100, -2],           // f interval: [-100, -2]
                    [-2, 2, true, true],  // g interval: (-2, 2)
                    [2, 2],               // z interval: [2, 2]
                    [2, 100, true, false] // h interval: (2, 100]
                ],
                [
                    [-1, 0], // new Polynomial([-1, 0]),      // f(x) = -x
                    [2],     // new Polynomial([2]),          // g(x) = 2
                    [0],     // new Polynomial([0]),          // z(x) = 0
                    [1, 0],  // new Polynomial([1, 0])        // h(x) = x
                ],
                [2], [0]         // p(2) = z(2) = 0
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

        $this->expectException(Exception\BadDataException::class);
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

        $this->expectException(Exception\BadDataException::class);
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

        $this->expectException(Exception\BadDataException::class);
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

        $this->expectException(Exception\BadDataException::class);
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

        $this->expectException(Exception\BadDataException::class);
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

        $this->expectException(Exception\BadDataException::class);
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

        $this->expectException(Exception\BadDataException::class);
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

        $this->expectException(Exception\BadDataException::class);
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

        $this->expectException(Exception\BadDataException::class);
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

        $this->expectException(Exception\BadDataException::class);
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

        $this->expectException(Exception\BadDataException::class);
        $piecewise = new Piecewise($intervals, $functions);
    }
}

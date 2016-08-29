<?php

namespace Math\NumericalAnalysis;

class TrapezoidalRuleTest extends \PHPUnit_Framework_TestCase
{
    public function testSolvePolynomial()
    {
        // f(x)                            = x² + 2x + 1
        // Antiderivative F(x)             = (1/3)x³ + x² + x
        // Indefinite integral over [0, 3] = F(3) - F(0) = 21

        $expected = 21;

        // h₁, h₂, ... denotes the size on interval 1, 2, ...
        // ζ₁, ζ₂, ... denotes the max of the second derivative of f(x) on
        //             interval 1, 2, ...
        // f'(x)  = 2x + 2
        // f''(x) = 2
        // ζ      = f''(x) = 2
        // Error  = Sum(ζ₁h₁³ + ζ₂h₂³ + ...) = 2 * Sum(h₁³ + h₂³ + ...)

        // Approximate with endpoints: (0, 1) and (3, 16)
        // Error = 2 * ((3 - 0)²) = 18
        $tol = 18;
        $x = TrapezoidalRule::solve([0, 1], [3, 16]);
        $this->assertEquals($expected, $x, '', $tol);

        // Approximate with endpoints and one interior point: (0, 1), (1, 4),
        // and (3, 16)
        // Error = 2 * ((1 - 0)² + (3 - 1)²) = 10
        $tol = 10;
        $x = TrapezoidalRule::solve([0, 1], [1, 4], [3, 16]);
        $this->assertEquals($expected, $x, '', $tol);

        // Approximate with endpoints and two interior points: (0, 1), (1, 4),
        // (2, 9), and (3, 16)
        // Error = 2 * ((1 - 0)² + (2 - 1)² + (3 - 2)²) = 6
        $tol = 6;
        $x = TrapezoidalRule::solve([0, 1], [1, 4], [2, 9], [3, 16]);
        $this->assertEquals($expected, $x, '', $tol);
    }

    public function testNotCoordinatesException()
    {
        // An array doesn't have precisely two numbers (coordinates)
        $this->setExpectedException('\Exception');
        TrapezoidalRule::solve([0,0], [1,2,3]);
    }

    public function testNotEnoughArraysException()
    {
        // There are not enough arrays in the input
        $this->setExpectedException('\Exception');
        TrapezoidalRule::solve([0,0]);
    }

    public function testNotAFunctionException()
    {
        // Two arrays share the same first number (x-component)
        $this->setExpectedException('\Exception');
        TrapezoidalRule::solve([0,0], [0,5], [1,1]);
    }
}

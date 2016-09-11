<?php
namespace Math\NumericalAnalysis\NumericalDifferentiation;

class NumericalDifferentiationTest extends \PHPUnit_Framework_TestCase
{
    public function testInstantiateAbstractClassException()
    {
        // Instantiating NumericalDifferentiation (an abstract class)
        $this->setExpectedException('\Error');
        new NumericalDifferentiation;
    }

    public function testIncorrectInput()
    {
        // The input $source is neither a callback or a set of arrays
        $this->setExpectedException('\Exception');
        $x                 = 10;
        $incorrectFunction = $x**2 + 2 * $x + 1;
        NumericalDifferentiation::getPoints($incorrectFunction, [0,4,5]);
    }

    public function testNotCoordinatesException()
    {
        // An array doesn't have precisely two numbers (coordinates)
        $this->setExpectedException('\Exception');
        NumericalDifferentiation::validate([[0,0], [1,2,3], [2,2]], $degree = 3);
    }

    public function testNotEnoughArraysException()
    {
        // There are not enough arrays in the input
        $this->setExpectedException('\Exception');
        NumericalDifferentiation::validate([[0,0]], $degree = 3);
    }

    public function testNotAFunctionException()
    {
        // Two arrays share the same first number (x-component)
        $this->setExpectedException('\Exception');
        NumericalDifferentiation::validate([[0,0], [0,5], [1,1]], $degree = 3);
    }

    public function testSpacingNonConstant()
    {
        // There is not constant spacing between points
        $this->setExpectedException('\Exception');
        NumericalDifferentiation::isSpacingConstant([[0,0], [3,3], [2,2]]);
    }

    public function testTargetNotInPoints()
    {
        // Our target is not the x-component of one of our points
        $this->setExpectedException('\Exception');
        NumericalDifferentiation::isTargetInPoints(1, [[0,0], [3,3], [2,2]]);
    }
}

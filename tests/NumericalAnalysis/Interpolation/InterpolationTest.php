<?php
namespace Math\NumericalAnalysis\Interpolation;

class InterpolationTest extends \PHPUnit_Framework_TestCase
{
    public function testInstantiateAbstractClassException()
    {
        // Instantiating Interpolation (an abstract class)
        $this->setExpectedException('\Error');
        new Interpolation;
    }

    public function testIncorrectInput()
    {
        // The input $source is neither a callback or a set of arrays
        $this->setExpectedException('\Exception');
        $x                 = 10;
        $incorrectFunction = $x**2 + 2 * $x + 1;
        Interpolation::getPoints($incorrectFunction, [0,4,5]);
    }

    public function testNotCoordinatesException()
    {
        // An array doesn't have precisely two numbers (coordinates)
        $this->setExpectedException('\Exception');
        Interpolation::validate([[0,0], [1,2,3], [2,2]]);
    }

    public function testNotEnoughArraysException()
    {
        // There are not enough arrays in the input
        $this->setExpectedException('\Exception');
        Interpolation::validate([[0,0]]);
    }

    public function testNotAFunctionException()
    {
        // Two arrays share the same first number (x-component)
        $this->setExpectedException('\Exception');
        Interpolation::validate([[0,0], [0,5], [1,1]]);
    }
}

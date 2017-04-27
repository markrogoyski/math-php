<?php
namespace MathPHP\Tests\NumericalAnalysis\NumericalDifferentiation;

use MathPHP\NumericalAnalysis\NumericalDifferentiation\NumericalDifferentiation;

class NumericalDifferentiationTest extends \PHPUnit_Framework_TestCase
{
    public function testInstantiateAbstractClassException()
    {
        // Instantiating NumericalDifferentiation (an abstract class)
        $this->expectException('\Error');
        new NumericalDifferentiation();
    }

    public function testIncorrectInput()
    {
        // The input $source is neither a callback or a set of arrays
        $this->expectException('MathPHP\Exception\BadDataException');
        $x                 = 10;
        $incorrectFunction = $x**2 + 2 * $x + 1;
        NumericalDifferentiation::getPoints($incorrectFunction, [0,4,5]);
    }

    public function testNotCoordinatesException()
    {
        // An array doesn't have precisely two numbers (coordinates)
        $this->expectException('MathPHP\Exception\BadDataException');
        NumericalDifferentiation::validate([[0,0], [1,2,3], [2,2]], $degree = 3);
    }

    public function testNotEnoughArraysException()
    {
        // There are not enough arrays in the input
        $this->expectException('MathPHP\Exception\BadDataException');
        NumericalDifferentiation::validate([[0,0]], $degree = 3);
    }

    public function testNotAFunctionException()
    {
        // Two arrays share the same first number (x-component)
        $this->expectException('MathPHP\Exception\BadDataException');
        NumericalDifferentiation::validate([[0,0], [0,5], [1,1]], $degree = 3);
    }

    public function testSpacingNonConstant()
    {
        // There is not constant spacing between points
        $this->expectException('MathPHP\Exception\BadDataException');
        NumericalDifferentiation::isSpacingConstant([[0,0], [3,3], [2,2]]);
    }

    public function testTargetNotInPoints()
    {
        // Our target is not the x-component of one of our points
        $this->expectException('MathPHP\Exception\BadDataException');
        NumericalDifferentiation::isTargetInPoints(1, [[0,0], [3,3], [2,2]]);
    }
}

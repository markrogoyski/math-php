<?php
namespace MathPHP\Tests\NumericalAnalysis\Interpolation;

use MathPHP\NumericalAnalysis\Interpolation\Interpolation;
use MathPHP\Exception;

class InterpolationTest extends \PHPUnit\Framework\TestCase
{
    public function testInstantiateAbstractClassException()
    {
        // Instantiating Interpolation (an abstract class)
        $this->expectException(\Error::class);
        new Interpolation();
    }

    public function testIncorrectInput()
    {
        // The input $source is neither a callback or a set of arrays
        $this->expectException(Exception\BadDataException::class);
        $x                 = 10;
        $incorrectFunction = $x**2 + 2 * $x + 1;
        Interpolation::getPoints($incorrectFunction, [0,4,5]);
    }

    public function testNotCoordinatesException()
    {
        // An array doesn't have precisely two numbers (coordinates)
        $this->expectException(Exception\BadDataException::class);
        Interpolation::validate([[0,0], [1,2,3], [2,2]]);
    }

    public function testNotEnoughArraysException()
    {
        // There are not enough arrays in the input
        $this->expectException(Exception\BadDataException::class);
        Interpolation::validate([[0,0]]);
    }

    public function testNotAFunctionException()
    {
        // Two arrays share the same first number (x-component)
        $this->expectException(Exception\BadDataException::class);
        Interpolation::validate([[0,0], [0,5], [1,1]]);
    }
}

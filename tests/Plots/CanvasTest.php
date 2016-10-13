<?php

namespace MathPHP\Plots;

class CanvasTest extends \PHPUnit_Framework_TestCase
{
    public function testValidateSizeException()
    {
        // Giving a negative size for canvas dimensions
        $this->setExpectedException('\Exception');
        new Canvas(-100, 500);

        // Adjust to a negative size for canvas dimensions
        $this->setExpectedException('\Exception');
        $canvas = new Canvas();
        $canvas->size(-100, 500);
    }
}

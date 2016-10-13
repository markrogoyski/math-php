<?php

namespace MathPHP\Plots;

class CanvasTest extends \PHPUnit_Framework_TestCase
{
    public function testValidateSizeExceptionSet()
    {
        // Giving a negative size for canvas dimensions
        $this->setExpectedException('\Exception');
        new Canvas(-100, 500);
    }

    public function testValidateSizeExceptionUpdate()
    {
        // Adjust to a negative size for canvas dimensions
        $this->setExpectedException('\Exception');
        $canvas = new Canvas();
        $canvas->size(-100, 500);
    }

    public function testValidateIntervalSet()
    {
        // The input interval is set to single point (start = end)
        $this->setExpectedException('\Exception');
        $canvas = new Canvas();
        $canvas->addPlot(function ($x) { return 1; }, 0, 0);
    }

    public function testValidateIntervalUpdate()
    {
        // The plot interval is adjusted to single point (start = end)
        $this->setExpectedException('\Exception');
        $canvas = new Canvas();
        $plot = $canvas->addPlot(function ($x) { return 1; }, 0, 10);
        $plot->xRange(10, 10);
    }
}

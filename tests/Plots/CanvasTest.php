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

    public function testValidateInterval()
    {
        // The input interval is set to single point (start = end)
        $this->setExpectedException('\Exception');
        $canvas = new Canvas();
        $canvas->addPlot(function ($x) { return 1; }, 0, 0);

        // The plot interval is adjusted to single point (start = end)
        $this->setExpectedException('\Exception');
        $canvas = new Canvas();
        $plot = $canvas->addPlot(function ($x) { return 1; }, 0, 10);
        $plot->xRange(10, 10);
    }
}

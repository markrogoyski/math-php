<?php

namespace MathPHP\Plots;

class PlotTest extends \PHPUnit_Framework_TestCase
{
    public function testGridException()
    {
        // Giving a negative number
        $this->setExpectedException('\Exception');
        $canvas = new Canvas();
        $plot = $canvas->addPlot(function ($x) { return 1; }, 0, 10);
        $plot->grid(true, -10, 5);
    }

    public function testThicknessException()
    {
        // Giving a negative number
        $this->setExpectedException('\Exception');
        $canvas = new Canvas();
        $plot = $canvas->addPlot(function ($x) { return 1; }, 0, 10);
        $plot->thickness(-10);
    }
}

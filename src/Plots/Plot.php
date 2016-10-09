<?php

namespace MathPHP\Plots;

class Plot extends Canvas
{
    private $padding;

    public function __construct($padding)
    {
        $this->padding = $padding;
    }

    public function setPadding($padding)
    {
        $this->padding = $padding;
    }

    public function draw($canvas, $width, $height)
    {
        // Build convenience variables for canvas/plot measures
        $padding = $this->padding;

        // Create axes
        $black = imagecolorallocate($canvas, 0, 0, 0);
        imagerectangle($canvas, $padding, $padding, $width - $padding, $height - $padding, $black);

        // Define input function

        // calculate canvas step size and function step size

        // Calculate function values, min, and max

        // Draw y-axis values, dashes

        // Draw x-axis values, dashes

        // Draw title, x-axis title, y-axis title

        // Draw graph

        return $canvas;
    }
}

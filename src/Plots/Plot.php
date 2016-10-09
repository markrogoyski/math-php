<?php

namespace MathPHP\Plots;

class Plot extends Canvas
{
    public function __construct()
    {
        parent::__construct();
        $this->x_label = "x-label";
        $this->y_label = "y-label";
    }

    public function draw($canvas)
    {
        // Build convenience variables for canvas/plot measures
        $width = $this->width;
        $height = $this->height;
        $padding = 50;
        list($x_shift, $y_shift) = [
            isset($this->y_label) ? 1 : 0,
            isset($this->x_label) ? 1 : 0,
        ];
        list($graph_start_x, $graph_start_y, $graph_end_x, $graph_end_y) = [
            (1 + $x_shift)*$padding,
            imagesy($canvas) - (1 + $y_shift)*$padding,
            imagesx($canvas) - $padding,
            $padding
        ];
        list($graph_width, $graph_height) = [
            imagesx($canvas) - (2 + $x_shift)*$padding,
            imagesy($canvas) - (2 + $y_shift)*$padding
        ];

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

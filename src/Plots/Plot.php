<?php

namespace MathPHP\Plots;

class Plot extends Canvas
{
    public function __construct()
    {
        parent::__construct();
        $this->x_label = "x-label";
        $this->y_label = "y-label";
        $this->function = function ($x) { return $x; };
        $this->start = 0;
        $this->end = 10;
    }

    public function draw($canvas)
    {
        // Build convenience variables for graph measures
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
        imagerectangle($canvas, $graph_start_x, $graph_end_y, $graph_end_x, $graph_start_y, $black);

        // Define input function and function domain
        $function = $this->function;
        $start    = $this->start;
        $end      = $this->end;

        // Calculate graph step size and function step size
        $n             = 1000;
        $graph_step_x  = $graph_width/$n;
        $graph_step_y  = $graph_height/$n;
        $function_step = ($end - $start)/$n;

        // Calculate function values, min, and max
        $image = [];
        for ($i = 0; $i <= $n; $i++) {
            $image[] = $function($start + $i*$function_step);
        }
        $min = min($image);
        $max = max($image);
        $function_scale = $graph_height/($max - $min);

        // Draw y-axis values, dashes

        // Draw x-axis values, dashes

        // Draw title, x-axis title, y-axis title

        // Draw graph

        return $canvas;
    }
}

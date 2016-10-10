<?php

namespace MathPHP\Plots;

class Plot extends Canvas
{
    public function __construct(callable $function, $start, $end)
    {
        parent::__construct();
        //$this->x_label = "x-label";
        //$this->y_label = "y-label";
        //$this->title = "Working!";
        $this->function = $function;
        $this->start = $start;
        $this->end = $end;
    }

    public function grid(bool $switch) {
        $this->grid = $switch;
    }

    public function yLabel(string $label) {
        $this->y_label = $label;
    }

    public function draw($canvas)
    {
        // Grab parameters
        $width   = $this->width;
        $height  = $this->height;
        $padding = 50;
        $title   = $this->title ?? null;
        $x_label = $this->x_label ?? null;
        $y_label = $this->y_label ?? null;
        $weight  = $this->weight ?? 3;
        $grid    = $this->grid ?? false;

        // Build convenience variables for graph measures
        list($x_shift, $y_shift) = [
            isset($y_label) ? 1 : 0,
            isset($x_label) ? 1 : 0,
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

        // Calculate function values, min, max, and function scale
        $image = [];
        for ($i = 0; $i <= $n; $i++) {
            $image[] = $function($start + $i*$function_step);
        }
        $min = min($image);
        $max = max($image);
        $function_scale = $graph_height/($max - $min);

        // Draw y-axis values and grid
        $fontpath = realpath('.'); //replace . with a different directory if needed
        putenv('GDFONTPATH='.$fontpath);
        $count = 9;
        $font = 'arial.ttf';
        $size = 10;
        $angle = 0;
        $length1 = 1;
        $length2 = 5;
        $white = imagecolorallocate($canvas, 255, 255, 255);
        $style = array_merge(array_fill(0, $length1, $black), array_fill(0, $length2, $white));
        imagesetstyle($canvas, $style);
        for ($i = 0; $i <= $count; $i++) {
            imagettftext($canvas, $size, $angle, $graph_start_x - $padding*0.75, $size*0.5 + $graph_start_y - $i*($graph_height/$count), $black, $font, round(($min + $i*($max - $min)/$count), 1));
            if ($i !== 0 and $i !== $count and $grid) {
                imageline($canvas, $graph_start_x, $graph_start_y - $i*($graph_height/$count), $graph_end_x, $graph_start_y - $i*($graph_height/$count), IMG_COLOR_STYLED);
            }
        }

        // Draw x-axis values and grid
        $newcount = 9;
        for ($i = 0; $i <= $newcount; $i++) {
            imagettftext($canvas, $size, $angle, $graph_start_x + $i*($graph_width/$newcount), $graph_start_y + $padding/2, $black, $font, round(($start + $i*($end - $start)/$newcount), 1));
            if ($i !== 0 and $i !== $newcount and $grid) {
                imageline($canvas, $graph_start_x + $i*($graph_width/$newcount), $graph_start_y, $graph_start_x + $i*($graph_width/$newcount), $graph_end_y, IMG_COLOR_STYLED);
            }
        }

        // Draw title, x-axis title, y-axis title
        $sizeTitle = 20;
        $sizeAxis = 16;
        if (isset($title)) {
            $p = imagettfbbox($sizeTitle, 0, $font, $title);
            $title_x = ($width - ($p[2] - $p[0]))/2;
            imagettftext($canvas, $sizeTitle, $angle, $title_x, 35, $black, $font, $title);
        }
        if (isset($x_label)) {
            $q = imagettfbbox($sizeAxis, 0, $font, $x_label);
            $x_label_width = ($width - ($q[2] - $q[0]))/2;
            imagettftext($canvas, $sizeAxis, $angle, $x_label_width, $height - 35, $black, $font, $x_label);
        }
        if (isset($y_label)) {
            $r = imagettfbbox($sizeAxis, 90, $font, $y_label);
            $y_label_height = ($height - ($r[3] - $r[1]))/2;
            imagettftext($canvas, $sizeAxis, 90, 40, $y_label_height, $black, $font, $y_label);
        }

        // Draw graph
        imagesetthickness($canvas, $weight);
        for ($i = 0; $i < $n; $i++) {
            imageline($canvas, $graph_start_x + $i*$graph_step_x, $graph_start_y - ($image[$i]-$min)*$function_scale, $graph_start_x + ($i+1)*$graph_step_x, $graph_start_y - ($image[$i+1]-$min)*$function_scale, $black);
        }

        return $canvas;
    }
}

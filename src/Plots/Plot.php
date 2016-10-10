<?php

namespace MathPHP\Plots;

class Plot extends Canvas
{
    public function __construct(callable $function, $start, $end)
    {
        parent::__construct();
        $this->function = $function;
        $this->start    = $start;
        $this->end      = $end;
    }

    public function grid(bool $switch)
    {
        $this->grid = $switch;
    }

    public function title(string $title)
    {
        $this->title = $title;
    }

    public function yLabel(string $label)
    {
        $this->yLabel = $label;
    }

    public function xLabel(string $label)
    {
        $this->xLabel = $label;
    }

    public function color(string $color)
    {
        switch($color) {
            case 'red':
                $color = [255, 0, 0];
                break;
            case 'green':
                $color = [0, 255, 0];
                break;
            case 'blue':
                $color = [0, 0, 255];
                break;
            default:
                $color = [0, 0, 0];
        }
        $this->color = $color;
    }

    public function draw($canvas)
    {
        // Set defaults
        $black   = imagecolorallocate($canvas, 0, 0, 0);
        $white   = imagecolorallocate($canvas, 255, 255, 255);
        $padding = 50;

        // Grab parameters
        $width    = $this->width;
        $height   = $this->height;
        $title    = $this->title ?? null;
        $xLabel   = $this->xLabel ?? null;
        $yLabel   = $this->yLabel ?? null;
        $weight   = $this->weight ?? 3;
        $grid     = $this->grid ?? false;
        $color    = isset($this->color) ? imagecolorallocate($canvas, ... $this->color) : $black;
        $function = $this->function;
        $start    = $this->start;
        $end      = $this->end;

        // Determine if we need to add padding to make room for axis labels
        $x_shift = isset($yLabel) ? 40 : 0;
        $y_shift = isset($xLabel) ? 10 : 0;

        // Measure start and end points of plot on canvas
        $graph_start_x = $padding + $x_shift;
        $graph_start_y = imagesy($canvas) - ($padding + $y_shift);
        $graph_end_x   = imagesx($canvas) - $padding;
        $graph_end_y   = $padding;

        // Measure height and width of plot on canvas
        $graph_width  = $graph_end_x - $graph_start_x;
        $graph_height = $graph_start_y - $graph_end_y;

        // Create axes
        imagerectangle($canvas, $graph_start_x, $graph_end_y, $graph_end_x, $graph_start_y, $black);

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
        $min            = min($image);
        $max            = max($image);
        $function_scale = $graph_height/($max - $min);

        // Draw y-axis values and grid
        $gridCountY = 10;
        $style = array_merge(array_fill(0, 1, $black), array_fill(0, 5, $white));
        imagesetstyle($canvas, $style);
        for ($i = 0; $i <= $gridCountY; $i++) {
            imagestring($canvas, 2, $graph_start_x - 50, $graph_start_y - 8 - $i*($graph_height/$gridCountY), round(($min + $i*($max - $min)/$gridCountY), 1), $black);
            if ($i !== 0 and $i !== $gridCountY and $grid) {
                imageline($canvas, $graph_start_x, $graph_start_y - $i*($graph_height/$gridCountY), $graph_end_x, $graph_start_y - $i*($graph_height/$gridCountY), IMG_COLOR_STYLED);
            }
        }

        // Draw x-axis values and grid
        $gridCountX = 10;
        for ($i = 0; $i <= $gridCountX; $i++) {
            imagestring($canvas, 2, $graph_start_x + $i*($graph_width/$gridCountX), $graph_start_y + 8, round(($start + $i*($end - $start)/$gridCountX), 1), $black);
            if ($i !== 0 and $i !== $gridCountX and $grid) {
                imageline($canvas, $graph_start_x + $i*($graph_width/$gridCountX), $graph_start_y, $graph_start_x + $i*($graph_width/$gridCountX), $graph_end_y, IMG_COLOR_STYLED);
            }
        }

        // Draw title, x-axis title, y-axis title
        if (isset($title)) {
            imagestring($canvas, 5, ($width - strlen($title)*9)/2, 18, $title, $black);
        }
        if (isset($xLabel)) {
            imagestring($canvas, 4, ($width - strlen($xLabel)*8)/2, $height - 30, $xLabel, $black);
        }
        if (isset($yLabel)) {
            imagestringup($canvas, 4, 10, ($height + strlen($yLabel)*8)/2, $yLabel, $black);
        }

        // Draw graph
        imagesetthickness($canvas, $weight);
        for ($i = 0; $i < $n; $i++) {
            imageline($canvas, $graph_start_x + $i*$graph_step_x, $graph_start_y - ($image[$i]-$min)*$function_scale, $graph_start_x + ($i+1)*$graph_step_x, $graph_start_y - ($image[$i+1]-$min)*$function_scale, $color);
        }

        return $canvas;
    }
}

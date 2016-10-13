<?php

namespace MathPHP\Plots;

/*
* Single Plot
*
* Contains paramters for a single plot to draw on a parent Canvas object.
*
* To create a plot, first start with a canvas object. Then, construct a child
* Plot object by using the addPlot() method in the Canvas class. This will
* ensure that our resulting Plot object is tied to the canvas object to which
* it corresponds. Then, we explicitly use Plot methods() for the resulting
* plot object.
*
* This class currently supports the following methods:
*   - grid(): Turn on or off the grid lines for our plot, and specify the
*             number of grid lines for each axis
*   - title(): Assign a title to our plot
*   - yLabel(): Assign a label to our y-axis
*   - xLabel(): Assign a label to our x-axis
*   - color(): Set the color of our plot line/curve
*   - thickness(): Set the thickness of our plot line/curve
*
* Example: Graph the function f(x) = x*sin(x) on [0, 20] with a grid, title,
*          y-axis label, x-axis label, a color of red, and thickness of 5
*     $canvas = new Canvas();
*     $plot = $canvas->addPlot(function ($x) { return $x*sin($x); }, 0, 20);
*     $plot->grid(true);
*     $plot->yLabel("This is a working y-label");
*     $plot->xLabel("Time (seconds)");
*     $plot->title("Sample Title");
*     $plot->color("red");
*     $plot->thickness(5);
*     $canvas->save();
*
* There are plans to add support for the following:
*   - A method to change the font for all text
*   - A method to change the font size for all text
*   - A method to change the font color for all text
*   - A method to add more graphs (functions) to the same plot
*   - A child class for each text field (title and axis labels) which contains
*     methods to adjust the color, size, and font of that specific text field
*/
class Plot extends Canvas
{
    private $function;
    private $start;
    private $end;


    public function __construct(callable $function, float $start, float $end, int $width, int $height)
    {
        parent::__construct($width, $height);
        $this->function = $function;
        $this->start    = $start;
        $this->end      = $end;
    }

    public function grid(bool $switch = true, int $gridCountX = 10, int $gridCountY = 10)
    {
        $this->grid       = $switch;
        $this->gridCountX = 10;
        $this->gridCountY = 10;
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

    public function thickness(int $thickness)
    {
        $this->thickness = $thickness;
    }

    public function draw($canvas)
    {
        if (get_resource_type($canvas) !== "gd") {
            throw new \Exception("The was an error constructing the canvas")
        }

        $black   = imagecolorallocate($canvas, 0, 0, 0);
        $white   = imagecolorallocate($canvas, 255, 255, 255);
        $padding = 50;

        // Grab parameters or assign defaults
        $width      = $this->width;
        $height     = $this->height;
        $title      = $this->title ?? null;
        $xLabel     = $this->xLabel ?? null;
        $yLabel     = $this->yLabel ?? null;
        $color      = isset($this->color) ? imagecolorallocate($canvas, ... $this->color) : $black;
        $thickness  = $this->thickness ?? 3;
        $grid       = $this->grid ?? false;
        $gridCountY = $this->gridCountY ?? 10;
        $gridCountX = $this->gridCountX ?? 10;
        $function   = $this->function;
        $start      = $this->start;
        $end        = $this->end;

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

        // Calculate function step size (h) and graph step size
        $n             = 1000;
        $h             = ($end - $start)/$n;
        $graph_step_x  = $graph_width/$n;
        $graph_step_y  = $graph_height/$n;

        // Calculate function values, min, max, and function scale
        $image = [];
        for ($i = 0; $i <= $n; $i++) {
            $image[] = $function($start + $i*$h);
        }
        $min            = min($image);
        $max            = max($image);
        $function_scale = $graph_height/($max - $min);

        // Draw y-axis values and grid
        $style = array_merge(array_fill(0, 1, $black), array_fill(0, 5, $white));
        imagesetstyle($canvas, $style);
        for ($i = 0; $i <= $gridCountY; $i++) {
            $value = round(($min + $i*($max - $min)/$gridCountY), 1);
            $X₀    = $graph_start_x;
            $Xₙ    = $graph_end_x;
            $Y₀    = $graph_start_y - $i*($graph_height/$gridCountY);
            imagestring($canvas, 2, $X₀ - 10 - 6*strlen($value), $Y₀ - 8, $value, $black);
            if ($i !== 0 && $i !== $gridCountY && $grid) {
                imageline($canvas, $X₀, $Y₀, $Xₙ, $Y₀, IMG_COLOR_STYLED);
            }
        }

        // Draw x-axis values and grid
        for ($i = 0; $i <= $gridCountX; $i++) {
            $value = round(($start + $i*($end - $start)/$gridCountX), 1);
            $X₀    = $graph_start_x + $i*($graph_width/$gridCountX);
            $Y₀    = $graph_start_y;
            $Yₙ    = $graph_end_y;
            imagestring($canvas, 2, $X₀ + 2 - strlen($value)*3, $Y₀ + 8, $value, $black);
            if ($i !== 0 && $i !== $gridCountX && $grid) {
                imageline($canvas, $X₀, $Y₀, $X₀, $Yₙ, IMG_COLOR_STYLED);
            }
        }

        // Draw title, x-axis title, y-axis title
        if (isset($title)) {
            imagestring($canvas, 5, ($width + $x_shift - strlen($title)*9)/2, 18, $title, $black);
        }
        if (isset($xLabel)) {
            imagestring($canvas, 4, ($width + $x_shift - strlen($xLabel)*8)/2, $height - 30, $xLabel, $black);
        }
        if (isset($yLabel)) {
            imagestringup($canvas, 4, 10, ($height - $y_shift + strlen($yLabel)*8)/2, $yLabel, $black);
        }

        // Draw graph
        imagesetthickness($canvas, $thickness);
        for ($i = 0; $i < $n; $i++) {
            $xᵢ     = $graph_start_x + $i*$graph_step_x;
            $xᵢ₊₁   = $graph_start_x + ($i+1)*$graph_step_x;
            $f⟮xᵢ⟯   = $graph_start_y - ($image[$i]-$min)*$function_scale;
            $f⟮xᵢ₊₁⟯ = $graph_start_y - ($image[$i+1]-$min)*$function_scale;
            imageline($canvas, $xᵢ, $f⟮xᵢ⟯, $xᵢ₊₁, $f⟮xᵢ₊₁⟯, $color);
        }

        return $canvas;
    }
}

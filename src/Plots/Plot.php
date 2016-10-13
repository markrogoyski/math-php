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
* Example: Plot the function f(x) = x*sin(x) on [0, 20] with a grid, title,
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
*   - A method to add more plots (functions) to the same plot
*   - A child class for each text field (title and axis labels) which contains
*     methods to adjust the color, size, and font of that specific text field
*   - A method to add a yRange to our final plot
*/
class Plot extends Canvas
{
    private $function;
    private $start;
    private $end;

    /**
    * Construct a Plot object
    *
    * The constructed Plot object should be a child of an existing Canvas object.
    * In the construction, we assign a callback function, and the start and end
    * points of the interval to which we will graph the function.
    *
    * We should not construct a Plot object explicity (e.g. using new Plot).
    * Rather, this constructor is accessed implicitly in the parent Canvas
    * class, as it needs to correspond to an instance of Canvas. This is
    * because the save() method draws our plot onto a specific instance of
    * a GD image, and this image is initially built in the parent Canvas class.
    *
    * @param callable $function The callback function we are plotting
    * @param number   $start    The start of our plotting interval
    * @param number   $end      The end of the plotting interval
    */
    public function __construct(callable $function, float $start, float $end)
    {
        parent::__construct();

        $this->function = $function;

        list($start, $end) = $this->validateInterval($start, $end);
        $this->start       = $start;
        $this->end         = $end;
    }

    /**
    * Ajust the start and endpoint of the interval to which we are plotting
    * a function.
    *
    * @param number $start    The start of our plotting interval
    * @param number $end      The end of the plotting interval
    */
    public function xRange(float $start, float $end)
    {
        $this->start = $start;
        $this->end   = $end;
    }

    /**
    * Turn the plot grid lines on or of, and specify the number of grids in
    * each direction.
    *
    * @param bool $switch     A boolean for if the grid is shown or not
    * @param int  $gridCountX The number of grid lines on the x-axis
    * @param int  $gridCountY The number of grid lines on the y-axis
    */
    public function grid(bool $switch = true, int $gridCountX = 10, int $gridCountY = 10)
    {
        $this->grid       = $switch;
        $this->gridCountX = 10;
        $this->gridCountY = 10;
    }

    /**
    * Add a title (or modify the existing one) to our plot.
    *
    * @param string $title The title of our plot
    */
    public function title(string $title)
    {
        $this->title = $title;
    }

    /**
    * Add a y-axis label (or modify the existing one) to our plot.
    *
    * @param string $label The y-axis of our plot
    */
    public function yLabel(string $label)
    {
        $this->yLabel = $label;
    }

    /**
    * Add a x-axis label (or modify the existing one) to our plot.
    *
    * @param string $label The x-axis of our plot
    */
    public function xLabel(string $label)
    {
        $this->xLabel = $label;
    }

    /**
    * Modify the color of our plot line/curve.
    *
    * Input is a string which can correspond to a number of preset colors.
    * Currently, only red, green, and blue are supported, although more colors
    * can easily be extended.
    *
    * If an input string does not match a supported color, the color will
    * default to black.
    *
    * @param string $color The color of our plot line/curve
    */
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

    /**
    * Modify the thickness of our plot line/curve.
    *
    * @param int $thickness The thickness of our plot line/curve
    */
    public function thickness(int $thickness)
    {
        $this->thickness = $thickness;
    }

    /**
    * Draw the plot to our input canvas.
    *
    * Draw aspects of a single plot: x- and y-axis, (optional) x- and y-labels,
    * x- and y-axis reference numbers, (optional) title, (optional) grid lines,
    * and the actual plot itself.
    *
    * This method should not be called explicitly. Rather, it is accessed
    * implicitly when you run the save() method on the parent canvas of
    * a plot object. This ensures that a property $canvas property is
    * created before it is passed to this method, which draws a plot onto
    * a GD canvas.
    *
    * @param resource $canvas A GD resource passed in via a Canvas parent object
    *
    * @throws Exception if $canvas is not a GD resource
    */
    public function draw($canvas)
    {
        // Verify the input is a GD resource
        if (get_resource_type($canvas) !== "gd") {
            throw new \Exception("The was an error constructing the canvas");
        }

        // Set convenience variables
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
        $gridCountY = $this->gridCountY ?? null;
        $gridCountX = $this->gridCountX ?? null;
        $function   = $this->function;
        $start      = $this->start;
        $end        = $this->end;

        // Determine if we need to add padding to make room for axis labels
        $x_shift = isset($yLabel) ? 40 : 0;
        $y_shift = isset($xLabel) ? 10 : 0;

        // Measure start and end points of plot on canvas
        $plot_start_x = $padding + $x_shift;
        $plot_start_y = imagesy($canvas) - ($padding + $y_shift);
        $plot_end_x   = imagesx($canvas) - $padding;
        $plot_end_y   = $padding;

        // Measure height and width of plot on canvas
        $plot_width  = $plot_end_x - $plot_start_x;
        $plot_height = $plot_start_y - $plot_end_y;

        // Create axes
        imagerectangle($canvas, $plot_start_x, $plot_end_y, $plot_end_x, $plot_start_y, $black);

        // Calculate function step size (h) and plot step size
        $n             = 1000;
        $h             = ($end - $start)/$n;
        $plot_step_x   = $plot_width/$n;
        $plot_step_y   = $plot_height/$n;

        // Calculate function values, min, max, and function scale
        $image = [];
        for ($i = 0; $i <= $n; $i++) {
            $image[] = $function($start + $i*$h);
        }
        $min            = min($image);
        $max            = max($image);
        $function_scale = $plot_height/($max - $min);

        // Draw y-axis values and grid
        $style = array_merge(array_fill(0, 1, $black), array_fill(0, 5, $white));
        imagesetstyle($canvas, $style);
        for ($i = 0; $i <= $gridCountY; $i++) {
            $value = round(($min + $i*($max - $min)/$gridCountY), 1);
            $X₀    = $plot_start_x;
            $Xₙ    = $plot_end_x;
            $Y₀    = $plot_start_y - $i*($plot_height/$gridCountY);
            imagestring($canvas, 2, $X₀ - 10 - 6*strlen($value), $Y₀ - 8, $value, $black);
            if ($i !== 0 && $i !== $gridCountY && $grid) {
                imageline($canvas, $X₀, $Y₀, $Xₙ, $Y₀, IMG_COLOR_STYLED);
            }
        }

        // Draw x-axis values and grid
        for ($i = 0; $i <= $gridCountX; $i++) {
            $value = round(($start + $i*($end - $start)/$gridCountX), 1);
            $X₀    = $plot_start_x + $i*($plot_width/$gridCountX);
            $Y₀    = $plot_start_y;
            $Yₙ    = $plot_end_y;
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

        // Draw plot
        imagesetthickness($canvas, $thickness);
        for ($i = 0; $i < $n; $i++) {
            $xᵢ     = $plot_start_x + $i*$plot_step_x;
            $xᵢ₊₁   = $plot_start_x + ($i+1)*$plot_step_x;
            $f⟮xᵢ⟯   = $plot_start_y - ($image[$i]-$min)*$function_scale;
            $f⟮xᵢ₊₁⟯ = $plot_start_y - ($image[$i+1]-$min)*$function_scale;
            imageline($canvas, $xᵢ, $f⟮xᵢ⟯, $xᵢ₊₁, $f⟮xᵢ₊₁⟯, $color);
        }

        return $canvas;
    }
}

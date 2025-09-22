<?php

namespace MathPHP\Plots;

/**
 * Plotting Canvas
 *
 * The base class for the plotting utility.
 *
 * This is the parent class for our plotting utility. To create a plot image,
 * you first start with this class to build the foundation for our plot. This
 * will create an environment for which you can add a single plot or, in the
 * future, multiple plots.
 *
 * This class currently supports the following methods:
 *   - addPlot(): add a single plot objects (graphs) to our canvas
 *   - size():    adjust the size of our canvas
 *   - save():    draw the current
 *
 * Example: Plot the graph f(x) = x over [0, 100]
 *     $canvas = new Canvas(1000, 500);
 *     $canvas->addPlot(function ($x) { return $x; }, 0, 100);
 *     $canvas->save();
 *
 */
class Canvas
{
    protected $width;
    protected $height;
    private $plot;

    /**
    * Construct a new plotting canvas.
    *
    * The input arguments refer to the size of the canvas in pixels. Thus,
    * when you run the save() method, the size of the resulting image file
    * will be determined by these parameters. For example, running
    * (new Canvas())->save() will produce an image with a default size of
    * 700px by 500px.
    *
    * @param int $width  The width of our canvas, in pixels
    * @param int $height The height of our canvas, in pixels
    */
    public function __construct(int $width = 700, int $height = 500)
    {
        if (!extension_loaded('gd')) {
            if (!dl('gd.so')) {
                echo "GD extension is not installed/loaded. Ensure it is setup
                      property and then try again";
                exit;
            }
        }

        $this->validateSize($width, $height);

        $this->width  = $width;
        $this->height = $height;
    }

    /**
    * Add a single plot to our canvas object.
    *
    * This method is used when we are including a single plot in a canvas
    * object. To add multiple plots to a single canvas, use the addSubplot()
    * method (to be added in a future release).
    *
    * The default interval of our plot is [0, 10].
    *
    * @param callable $function The callback function we are plotting
    * @param number   $start    The start of our plotting interval
    * @param number   $end      The end of the plotting interval
    *
    * @return object The resulting Plot object constructed from our inputs
    *                and the parameters of the parent canvas object
    */
    public function addPlot(callable $function, $start = 0, $end = 10): Plot
    {
        $width      = $this->width;
        $height     = $this->height;

        list($start, $end) = $this->validateInterval($start, $end);
        $this->plot        = new Plot($function, $start, $end, $width, $height);

        return $this->plot;
    }

    /**
    * Modify the size of our canvas.
    *
    * Refer to the __construct() method for for further understanding of
    * canvas sizes.
    *
    * @param int $width  The width of our canvas, in pixels
    * @param int $height The height of our canvas, in pixels
    */
    public function size(int $width, int $height)
    {
        $this->validateSize($width, $height);

        $this->width  = $width;
        $this->height = $height;

        // If we've already added a plot to the canvas, adjust it's size as well
        if (isset($this->plot)) {
            $this->plot->size($width, $height);
        }
    }

    /**
    * Draw plot(s) and output resulting canvas.
    *
    * Draw the plot object(s) stored within our canvas' plot parameter. Then,
    * output the resulting canvas in a certain format. Currently, only
    * JPG outputs are supported. More support should be added soon, such as
    * outputting directly to a webpage, different file formats, etc.
    *
    * By default, this gives our canvas a white background.
    */
    public function save()
    {
        header('Content-type: image/png');

        $canvas = imagecreate($this->width, $this->height);
        imagecolorallocate($canvas, 255, 255, 255);

        if (isset($this->plot)) {
            $canvas = $this->plot->draw($canvas);
        }

        imagejpeg($canvas, 'image-' . rand() . '.jpg');
    }

    /**
    * Validate the input size of our canvus
    *
    * @throws Exception if $width or $height is negative
    */
    public function validateSize(int $width, int $height)
    {
        if ($width < 0 || $height < 0) {
            throw new \Exception("Canvas dimensions cannot be negative");
        }
    }

    /**
    * Valide that our input is a proper interval (not just a point).
    *
    * If the start point is greater than the input, swap the variables.
    *
    * @throws Exception if $start = $end (not an interval, just a point)
    */
    public function validateInterval(int $start, int $end)
    {
        if ($start === $end) {
            throw new \Exception("Start and end points the interval of our
                                  graph cannot be the same. Your current input
                                  would produce a graph over the interval
                                  [{$start}, {$end}], which is just a single
                                  point");
        }

        // Swap variables if start point is greater than end point
        if ($start > $end) {
            list($start, $end) = [$end, $start];
        }

        return [$start, $end];
    }
}

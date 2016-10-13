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
    private   $plot;

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
    public function addPlot(callable $function, $start = 0, $end = 10)
    {
        $width      = $this->width;
        $height     = $this->height;
        $this->plot = new Plot($function, $start, $end, $width, $height);

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
        $this->width  = $width;
        $this->height = $height;
        if(isset($this->plot)) {
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
}

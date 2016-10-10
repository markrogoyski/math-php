<?php

namespace MathPHP\Plots;

/**
 * Plotting Canvas
 *
 * The base class for the plotting utility.
 */
class Canvas
{
    protected $width;
    protected $height;
    protected $canvas;
    protected $plot;

    public function __construct($width = 600, $height = 600)
    {
        $this->width  = $width;
        $this->height = $height;
        $this->canvas = imagecreate($width, $height);
    }

    public function addPlot(callable $function, $start = 0, $end = 10)
    {
        $this->plot = new Plot($function, $start, $end);
        return $this->plot;
    }

    public function size($width = 600, $height = 600)
    {
        $this->width  = $width;
        $this->height = $height;
    }

    public function save()
    {
        header('Content-type: image/png');
        $canvas = imagecreate($this->width, $this->height);
        imagecolorallocate($canvas, 255, 255, 255);
        $canvas = $this->plot->draw($canvas);
        imagejpeg($canvas, 'image-' . rand() . '.jpg');
    }
}

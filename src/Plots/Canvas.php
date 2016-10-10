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
    private   $plot;

    public function __construct($width = 700, $height = 500)
    {
        $this->width  = $width;
        $this->height = $height;
    }

    public function addPlot(callable $function, $start = 0, $end = 10)
    {
        $width      = $this->width;
        $height     = $this->height;
        $this->plot = new Plot($function, $start, $end, $width, $height);
        return $this->plot;
    }

    public function size($width, $height)
    {
        $this->width  = $width;
        $this->height = $height;
        if(isset($this->plot)) {
            $this->plot->size($width, $height);
        }
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

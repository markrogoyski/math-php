<?php

namespace MathPHP\Plots;

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

    public function addPlot($padding = 0)
    {
        $this->plot = new Plot($padding);
        return $this->plot;
    }

    public function save()
    {
        $width  = $this->width;
        $height = $this->height;
        $canvas = imagecreate($width, $height);
        imagecolorallocate($canvas, 255, 255, 255);
        $canvas = $this->plot->draw($canvas, $width, $height);
        imagejpeg($canvas, 'image-' . rand() . '.jpg');
    }
}

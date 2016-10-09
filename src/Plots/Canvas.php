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

    public function addPlot()
    {
        $this->plot = new Plot();
        return $this->plot;
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

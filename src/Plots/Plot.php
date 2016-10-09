<?php

namespace MathPHP\Plots;

class Plot extends Canvas
{
    private $padding;

    public function __construct($padding)
    {
        $this->padding = $padding;
    }

    public function setPadding($padding)
    {
        $this->padding = $padding;
    }

    public function draw($canvas, $width, $height)
    {
        $padding = $this->padding;
        $black = imagecolorallocate($canvas, 0, 0, 0);
        header('Content-type: image/png');
        imagerectangle($canvas, $padding, $padding, $width - $padding, $height - $padding, $black);
        return $canvas;
    }
}

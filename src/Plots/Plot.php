<?php

namespace MathPHP\Plots;

class Plot extends Canvas
{
    public function draw($canvas, $width, $height)
    {
        $black = imagecolorallocate($canvas, 0, 0, 0);
        header('Content-type: image/png');
        // Draw something here
        return $canvas;
    }
}

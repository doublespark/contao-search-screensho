<?php

namespace Doublespark\ContaoSearchScreenshot\Library;

class CaptureConfig {

    protected $width  = 800;
    protected $height = 600;

    public function setViewport($width,$height)
    {
        $this->width  = $width;
        $this->height = $height;
    }

    public function getViewportWidth()
    {
        return $this->width;
    }

    public function getViewportHeight()
    {
        return $this->height;
    }
}
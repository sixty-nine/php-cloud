<?php

namespace SixtyNine\Cloud\Color;

abstract class ColorGenerator implements ColorGeneratorInterface
{
    protected $palette;

    public function __construct($palette)
    {
        $this->palette = $palette;
    }

    public function getPalette()
    {
        return $this->palette;
    }

    public abstract function getNextColor();
}

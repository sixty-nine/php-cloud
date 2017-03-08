<?php

namespace SixtyNine\Cloud\Color;

class RotateColorGenerator extends ColorGenerator
{
    protected $current = 0;

    public function getNextColor()
    {
        $color = $this->palette[$this->current % count($this->palette)];
        $this->current++;
        return $color;
    }
}
<?php

namespace SixtyNine\Cloud\Color;

class RandomColorGenerator extends ColorGenerator
{
    public function getNextColor()
    {
        return $this->palette[rand(0, count($this->palette) - 1)];
    }
}
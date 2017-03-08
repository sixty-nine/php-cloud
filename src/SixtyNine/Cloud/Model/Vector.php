<?php

namespace SixtyNine\Cloud\Model;

class Vector
{
    /** @var int */
    public $x = 0;
    /** @var int */
    public $y = 0;

    public function __construct($x = 0, $y = 0)
    {
        $this->x = (int)$x;
        $this->y = (int)$y;
    }

    public function duplicate()
    {
        return new Vector($this->x, $this->y);
    }

    public function move($dx, $dy)
    {
        $v = $this->duplicate();
        $v->x += $dx;
        $v->y += $dy;
        return $v;
    }

    public function __toString()
    {
        return sprintf('[%s, %s]', $this->x, $this->y);
    }
}

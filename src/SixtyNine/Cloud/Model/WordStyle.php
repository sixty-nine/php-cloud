<?php

namespace SixtyNine\Cloud\Model;

use SixtyNine\Cloud\Color\Color;

class WordStyle
{
    /** @var int */
    protected $angle = 0;
    /** @var int */
    protected $size = 1;
    /** @var Color */
    protected $color;

    public function __construct()
    {
        $this->color = new Color();
    }

    /**
     * @param int $angle
     * @return WordStyle
     */
    public function setAngle($angle)
    {
        $this->angle = $angle;
        return $this;
    }

    /**
     * @return int
     */
    public function getAngle()
    {
        return $this->angle;
    }

    /**
     * @param Color $color
     * @return WordStyle
     */
    public function setColor(Color $color)
    {
        $this->color = $color;
        return $this;
    }

    /**
     * @return Color
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * @param int $size
     * @return WordStyle
     */
    public function setSize($size)
    {
        $this->size = $size;
        return $this;
    }

    /**
     * @return int
     */
    public function getSize()
    {
        return $this->size;
    }
}

<?php

namespace SixtyNine\CloudBundle\Cloud\Placer;

use Imagine\Image\Point;

abstract class AbstractPlacer implements PlacerInterface
{
    /** @var int */
    protected $imgWidth;
    /** @var int */
    protected $imgHeight;

    /**
     * @param int $imgWidth
     * @param int $imgHeight
     */
    public function __construct($imgWidth, $imgHeight)
    {
        $this->imgWidth = $imgWidth;
        $this->imgHeight = $imgHeight;
    }

    /** {@inheritdoc} */
    function getFirstPlaceToTry()
    {
        return new Point($this->imgWidth / 3, $this->imgHeight / 2);
    }
}

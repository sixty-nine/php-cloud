<?php

namespace SixtyNine\CloudBundle\Cloud\Placer;

use Imagine\Image\Point;

abstract class AbstractPlacer implements PlacerInterface
{
    function getFirstPlaceToTry($imgWidth, $imgHeight)
    {
        return new Point($imgWidth / 3, $imgHeight / 2);
    }
}

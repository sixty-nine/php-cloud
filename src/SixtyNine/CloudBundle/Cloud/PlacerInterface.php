<?php

namespace SixtyNine\CloudBundle\Cloud;

use Imagine\Image\PointInterface;

interface PlacerInterface
{
    /**
     * @param PointInterface $current
     * @return PointInterface
     */
    function getNextPlaceToTry(PointInterface $current);
}

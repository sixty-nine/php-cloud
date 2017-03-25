<?php

namespace SixtyNine\CloudBundle\Cloud\Placer;

use Imagine\Image\PointInterface;

interface PlacerInterface
{
    /**
     * @return PointInterface
     */
    function getFirstPlaceToTry();

    /**
     * @param PointInterface $current
     * @return PointInterface
     */
    function getNextPlaceToTry(PointInterface $current);
}

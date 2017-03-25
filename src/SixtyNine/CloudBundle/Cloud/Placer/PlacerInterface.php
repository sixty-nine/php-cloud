<?php

namespace SixtyNine\CloudBundle\Cloud\Placer;

use Imagine\Image\PointInterface;

interface PlacerInterface
{
    /**
     * @param int $imgWidth
     * @param int $imgHeight
     * @return PointInterface
     */
    function getFirstPlaceToTry($imgWidth, $imgHeight);

    /**
     * @param PointInterface $current
     * @param int $imgWidth
     * @param int $imgHeight
     * @return PointInterface
     */
    function getNextPlaceToTry(PointInterface $current, $imgWidth, $imgHeight);
}

<?php

namespace SixtyNine\CloudBundle\Cloud\Placer;

use Imagine\Image\Point;
use Imagine\Image\PointInterface;

class LinearPlacer extends AbstractPlacer
{
    public function getNextPlaceToTry(PointInterface $current, $imgWidth, $imgHeight)
    {
        $increment = 10;

        if ($current->getX() < $imgWidth) {
            return new Point($current->getX() + $increment, $current->getY());
        }

        if ($current->getY() < $imgHeight) {
            return new Point(0, $current->getY() + $increment);
        }

        return false;
    }

    function getFirstPlaceToTry($imgWidth, $imgHeight)
    {
        return new Point(0, 0);
    }

}

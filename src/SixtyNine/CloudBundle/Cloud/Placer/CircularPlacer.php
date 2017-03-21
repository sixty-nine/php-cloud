<?php

namespace SixtyNine\CloudBundle\Cloud\Placer;

use Imagine\Image\Point;
use Imagine\Image\PointInterface;

class CircularPlacer implements PlacerInterface
{
    protected $increment = 0;

    public function getNextPlaceToTry(PointInterface $current)
    {
        /**
         * Cool params:
         *
         *      booby: a = 0, b = 1, i = 0.1
         *      perfect spiral: a = 0, b = 2, i = 0.25
         *
         */

        $a = 0;
        $b = 1;
        $i = $this->increment;
        $this->increment += 0.01;
        $r = $a + $b * $i;

        $x = $current->getX() + cos($i) * $r;
        $y = $current->getY() + sin($i) * $r;

        if ($x < 0 || $y < 0) {
            return false;
        }

        return new Point($x, $y);
    }
}

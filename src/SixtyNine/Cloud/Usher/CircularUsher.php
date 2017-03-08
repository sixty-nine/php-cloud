<?php

namespace SixtyNine\Cloud\Usher;

use SixtyNine\Cloud\Model\Vector;

/**
 * Responsible to find a place for the word in the cloud
 */
class CircularUsher extends IterativeUsher
{
    protected $increment = 0;

    public function getFirstPlaceToTry()
    {
        $this->increment = 0;

        return new Vector(
            $this->imgWidth / 2,
            $this->imgHeight / 2
        );
    }

    public function getNextPlaceToTry(Vector $current)
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
        $this->increment += 0.1;
        $r = $a + $b * $i;
        return new Vector(
            $current->x + cos($i) * $r,
            $current->y + sin($i) * $r
        );
    }
}

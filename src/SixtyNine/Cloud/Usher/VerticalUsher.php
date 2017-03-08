<?php

namespace SixtyNine\Cloud\Usher;

use SixtyNine\Cloud\Model\Vector;

/**
 * Responsible to find a place for the word in the cloud
 */
class VerticalUsher extends IterativeUsher
{
    public function getFirstPlaceToTry()
    {
        return new Vector();
    }

    public function getNextPlaceToTry(Vector $current)
    {
        $increment = 10;

        if ($current->y < $this->imgHeight) {
            return $current->move(0, $increment);
        }

        if ($current->x < $this->imgWidth) {
            return $current->move($increment, -$current->y);
        }

        return false;
    }
}

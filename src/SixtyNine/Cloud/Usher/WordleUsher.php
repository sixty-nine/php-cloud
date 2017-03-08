<?php

namespace SixtyNine\Cloud\Usher;

use SixtyNine\Cloud\Model\Vector;

/**
 * Responsible to find a place for the word in the cloud
 */
class WordleUsher extends IterativeUsher
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
        $i = $this->increment;
        $this->increment += 1;
        return new Vector(
            $current->x + ($i / 2 * cos($i)),
            $current->y + ($i / 2 * sin($i))
        );
    }
}

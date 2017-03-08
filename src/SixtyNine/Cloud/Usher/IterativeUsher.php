<?php

namespace SixtyNine\Cloud\Usher;

use SixtyNine\Cloud\Model\Box;
use SixtyNine\Cloud\Model\Vector;
use SixtyNine\Cloud\Model\Text;
use SixtyNine\Cloud\Performance\StopwatchAware;

/**
 * Responsible to find a place for the word in the cloud
 */
abstract class IterativeUsher extends AbstractUsher
{
    use StopwatchAware;

    const DEFAULT_MAX_TRIES = 100000;

    /** @var int */
    protected $maxTries;

    public function __construct($imgWidth, $imgHeight, $maxTries = self::DEFAULT_MAX_TRIES)
    {
        parent::__construct($imgWidth, $imgHeight);
        $this->maxTries = $maxTries;
    }

    public function getPlace(Text $text)
    {
        $this->stopwatchStart('usher', 'usher');

        /** @var Box $box */
        $box = $text->getBox();

        /** @var Vector $place */
        $place = $this->searchPlace($box);

        $screen = new Box(new Vector(), new Vector($this->imgWidth, $this->imgHeight));
        $placedBox = new Box($place, $box->getSize());
        if (!$placedBox->isInside($screen)) {
//            $this->mask->add($placedBox);
            $place = false;
        }

        if ($place) {
            $this->mask->add($placedBox);
            $text->setPosition($place);
        }
        $text->setVisible((bool)$place);

        $this->stopwatchStop('usher');
    }

    /**
     * Search a free place for a new box.
     * @param box $box
     * @return bool|Vector
     */
    protected function searchPlace(Box $box)
    {
        $place_found = false;
        $current = $this->getFirstPlaceToTry();
        $curTry = 1;

        while (!$place_found) {

            if (!$current) {
                return false;
            }

            if ($curTry > $this->maxTries) {
                return false;
            }

            $new_box = new Box($current, $box->getSize());
            // TODO: Check if the new coord is in the clip area
            $place_found = !$this->mask->overlaps($new_box);

            if ($place_found) {
                break;
            }

            $current = $this->getNextPlaceToTry($current);
            $curTry++;
        }

        return $current;
    }
}

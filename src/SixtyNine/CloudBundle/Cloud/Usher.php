<?php

namespace SixtyNine\CloudBundle\Cloud;

use Imagine\Image\Box;
use Imagine\Image\BoxInterface;
use Imagine\Image\FontInterface;
use Imagine\Image\Point;
use Imagine\Image\PointInterface;

/**
 * Responsible to find a place for the word in the cloud
 */

class Usher
{
    const DEFAULT_MAX_TRIES = 100000;

    /** @var int */
    protected $maxTries;

    /** @var \SixtyNine\CloudBundle\Cloud\Mask */
    protected $mask;

    /** @var \SixtyNine\CloudBundle\Cloud\PlacerInterface */
    protected $placer;

    public function __construct($imgWidth, $imgHeight, PlacerInterface $placer, $maxTries = self::DEFAULT_MAX_TRIES)
    {
        $this->mask = new Mask();
        $this->imgHeight = $imgHeight;
        $this->imgWidth = $imgWidth;
        $this->maxTries = $maxTries;
        $this->placer = $placer;
    }

    public function getPlace($text, FontInterface $font, $angle = 0)
    {
        $box = $font->box($text, $angle);
        $bounds = new Box($this->imgWidth, $this->imgHeight);
        $firstPlace = new Point($this->imgWidth / 2, $this->imgHeight / 2);

        $place = $this->searchPlace($bounds, $firstPlace, $box);

        if ($place) {
            $this->mask->add($place, $box);
            return array(
                'pos' => $place,
                'box' => $box,
            );
        }

        return false;
    }

    /**
     * Search a free place for a new box.
     * @param \Imagine\Image\Box $bounds
     * @param \Imagine\Image\PointInterface $first
     * @param \Imagine\Image\Box|\Imagine\Image\BoxInterface $box $box
     * @return bool|PointInterface
     */
    protected function searchPlace(Box $bounds, PointInterface $first, BoxInterface $box)
    {
        $place_found = false;
        $current = $first;
        $curTry = 1;

        while (!$place_found) {

            if (!$current) {
                return false;
            }

            if ($curTry > $this->maxTries) {
                return false;
            }

            $place_found = !$this->mask->overlaps($current, $box);

            if ($place_found) {
                break;
            }

            $current = $this->placer->getNextPlaceToTry($current);
            $curTry++;
        }

        return $current->in($bounds) ? $current : false;
    }
}

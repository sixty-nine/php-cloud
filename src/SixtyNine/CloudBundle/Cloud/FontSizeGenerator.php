<?php

namespace SixtyNine\CloudBundle\Cloud;

/**
 * Contributed by @jaskra and @mrahmadt
 */
class FontSizeGenerator
{
    /** @var int */
    protected $minFontSize;
    /** @var int */
    protected $maxFontSize;
    /** @var int */
    protected $minCount = PHP_INT_MAX;
    /** @var int */
    protected $maxCount = 0;

    /**
     * @param int $minFontSize Minimal font size
     * @param int $maxFontSize Maximal font size
     */
    public function __construct($minFontSize, $maxFontSize)
    {
        $this->minFontSize = $minFontSize;
        $this->maxFontSize = $maxFontSize;
    }

    public function reset()
    {
        $this->minCount = PHP_INT_MAX;
        $this->maxCount = 0;
    }

    public function calculateFontSize($count)
    {
        if ($count > $this->maxCount) {
            $this->maxCount = $count;
        }
        if ($count < $this->minCount) {
            $this->minCount = $count;
        }
        $diffCount = ($this->maxCount - $this->minCount) != 0
            ? ($this->maxCount - $this->minCount)
            : 1
        ;
        $diffSize = ($this->maxFontSize - $this->minFontSize) != 0
            ? ($this->maxFontSize - $this->minFontSize)
            : 1
        ;
        $slope = $diffSize / $diffCount;
        $yIntercept = $this->maxFontSize - ($slope * $this->maxCount);

        $font_size = (integer)($slope * $count + $yIntercept);

        if ($font_size < $this->minFontSize) {
            $font_size = $this->minFontSize;
        } elseif ($font_size > $this->maxFontSize) {
            $font_size = $this->maxFontSize;
        }
        
        return $font_size;
    }
}

<?php

namespace SixtyNine\CloudBundle\Cloud\FontSize;

class DimFontSizeGenerator
{
    /** @var int */
    protected $minFontSize;
    /** @var int */
    protected $maxFontSize;
    /** @var int */
    protected $deltaFont;
    /** @var int */
    protected $maxCount;

    /**
     * @param int $minFontSize Minimal font size
     * @param int $maxFontSize Maximal font size
     * @param int $maxCount
     * @throws \InvalidArgumentException
     */
    public function __construct($minFontSize, $maxFontSize, $maxCount)
    {
        if (!$maxCount) {
            throw new \InvalidArgumentException('maxCount must be strictly positive');
        }

        $this->minFontSize = $minFontSize;
        $this->maxFontSize = $maxFontSize;
        $this->deltaFont = $maxFontSize - $minFontSize;
        $this->maxCount = $maxCount;
    }

    public function calculateFontSize($count)
    {
        return (int)($this->minFontSize + $this->deltaFont * pow($count / $this->maxCount, 2));
    }
}

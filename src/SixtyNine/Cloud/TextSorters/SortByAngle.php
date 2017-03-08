<?php


namespace SixtyNine\Cloud\TextSorters;


use SixtyNine\Cloud\Model\Text;

class SortByAngle implements SorterInterface
{
    /** @var string */
    protected $direction;

    public function __construct($keepDirection = Text::DIR_VERTICAL)
    {
        $this->direction = $keepDirection === Text::DIR_VERTICAL
            ? Text::DIR_HORIZONTAL
            : Text::DIR_VERTICAL
        ;
    }

    function compare(Text $a, Text $b)
    {
        if ($a->getDir() === $this->direction) {
            return 1;
        }
        if ($b->getDir() === $this->direction) {
            return -1;
        }
        return 0;
    }
}
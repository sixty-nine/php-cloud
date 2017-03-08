<?php


namespace SixtyNine\Cloud\TextSorters;


use SixtyNine\Cloud\Model\Text;

class SortByOccurrence implements SorterInterface
{
    function compare(Text $a, Text $b)
    {
        if ($a->getWord()->getCount() == $b->getWord()->getCount()) {
            return 0;
        }
        return ($a->getWord()->getCount() < $b->getWord()->getCount()) ? 1 : -1;
    }
}
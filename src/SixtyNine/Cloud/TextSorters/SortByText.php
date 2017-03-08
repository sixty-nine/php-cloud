<?php


namespace SixtyNine\Cloud\TextSorters;


use SixtyNine\Cloud\Model\Text;

class SortByText implements SorterInterface
{
    function compare(Text $a, Text $b)
    {
        return (bool)($a->getWord()->getText() > $b->getWord()->getText());
    }
}
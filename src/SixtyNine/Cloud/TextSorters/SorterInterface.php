<?php

namespace SixtyNine\Cloud\TextSorters;

use SixtyNine\Cloud\Model\Text;

interface SorterInterface
{
    function compare(Text $a, Text $b);
} 
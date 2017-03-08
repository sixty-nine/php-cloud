<?php


namespace SixtyNine\Cloud\TextListFilter;


use SixtyNine\Cloud\Model\TextList;

interface TextListVisitorInterface
{
    function visit(TextList $list);
}

<?php
/**
 * This file is part of the sixty-nine/word-cloud.
 * Copyright (c) 2010-2016 Daniele Barsotti<sixtynine.db@gmail.com>
 */

namespace SixtyNine\Cloud\Filters;

use SixtyNine\Cloud\Model\Word;

abstract class AbstractFilter implements FilterInterface
{
    /** {@inheritdoc} */
    public function keepWord(Word $word)
    {
        return true;
    }

    /** {@inheritdoc} */
    public function filterWord(Word $word)
    {
        return $word;
    }

    /** {@inheritdoc} */
    function getParamsArray()
    {
        return array();
    }

    /** {@inheritdoc} */
    static function fromParamsArray(array $params)
    {
        return new static;
    }
}

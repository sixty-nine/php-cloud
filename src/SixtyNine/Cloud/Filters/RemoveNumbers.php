<?php
/**
 * This file is part of the sixty-nine/word-cloud.
 * Copyright (c) 2010-2016 Daniele Barsotti<sixtynine.db@gmail.com>
 */

namespace SixtyNine\Cloud\Filters;

use SixtyNine\Cloud\Model\Word;

/**
 * Remove trailing punctuation from words.
 */
class RemoveNumbers extends AbstractFilter implements FilterInterface
{
    /** {@inheritdoc} */
    public function filterWord(Word $word)
    {
        $word->setText(
            str_replace(explode(',', '1,2,3,4,5,6,7,8,9,0'), '', $word->getText())
        );
        return $word;
    }
}
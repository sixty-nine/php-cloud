<?php
/**
 * This file is part of the sixty-nine/word-cloud.
 * Copyright (c) 2010-2016 Daniele Barsotti<sixtynine.db@gmail.com>
 */

namespace SixtyNine\Cloud\Filters;

use SixtyNine\Cloud\Model\Word;
use SixtyNine\Cloud\Storage\RecordableInterface;

interface FilterInterface extends RecordableInterface
{
    /**
     * @param Word $word The word to filter
     * @return bool
     */
    function keepWord(Word $word);

    /**
     * @param Word $text
     * @return Word
     */
    function filterWord(Word $text);

    /**
     * @return array
     */
    function getParamsArray();

    /**
     * @param array $params
     * @return FilterInterface
     */
    static function fromParamsArray(array $params);
}

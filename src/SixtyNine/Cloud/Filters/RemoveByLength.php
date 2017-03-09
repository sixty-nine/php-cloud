<?php
/**
 * This file is part of the sixty-nine/word-cloud.
 * Copyright (c) 2010-2016 Daniele Barsotti<sixtynine.db@gmail.com>
 */

namespace SixtyNine\Cloud\Filters;

use SixtyNine\Cloud\Model\Word;
use SixtyNine\Cloud\Storage\Recordable;

/**
 * Remove words too short or too long.
 */
class RemoveByLength extends AbstractFilter implements FilterInterface
{
    protected $maxLength;
    protected $minLength;

    /**
     * @param bool|int $minLength The minimal length or false
     * @param bool|int $maxLength The maximal length or false
     */
    public function __construct($minLength = false, $maxLength = false)
    {
        $this->minLength = $minLength ? (int)$minLength : false;
        $this->maxLength = $maxLength ? (int)$maxLength : false;
    }

    /** {@inheritdoc} */
    public function keepWord(Word $word)
    {
        $len = strlen($word->getText());

        if (false !== $this->minLength && $len <= $this->minLength) {
            return false;
        }

        if (false !== $this->maxLength && $len >= $this->maxLength) {
            return false;
        }

        return true;
    }

    /** {@inheritdoc} */
    function getParamsArray()
    {
        return array('min' => $this->minLength, 'max' => $this->maxLength);
    }

    /** {@inheritdoc} */
    static function fromParamsArray(array $params)
    {
        Recordable::requireArrayKeysExist(array('min', 'max'), $params);
        return new RemoveByLength($params['min'], $params['max']);
    }
}

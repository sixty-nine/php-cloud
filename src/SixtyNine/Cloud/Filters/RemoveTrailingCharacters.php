<?php
/**
 * This file is part of the sixty-nine/word-cloud.
 * Copyright (c) 2010-2016 Daniele Barsotti<sixtynine.db@gmail.com>
 */

namespace SixtyNine\Cloud\Filters;

use SixtyNine\Cloud\Model\Word;
use SixtyNine\Cloud\Storage\Recordable;

/**
 * Remove trailing punctuation from words.
 */
class RemoveTrailingCharacters extends AbstractFilter implements FilterInterface
{
    protected $punctuation;

    /**
     * @param array $punctuation Array of punctuation to be removed.
     */
    public function __construct($punctuation = array('.', ',', ';', '?', '!'))
    {
        $this->punctuation = $punctuation;
    }

    /** {@inheritdoc} */
    public function filterWord(Word $word)
    {
        $text = $word->getText();

        foreach($this->punctuation as $p) {
            if(substr($text, -1) == $p) {
                $text = substr($text, 0, -1);
            }
        }

        $word->setText($text);
        return $word;
    }

    /** {@inheritdoc} */
    function getParamsArray()
    {
        return array('characters' => $this->punctuation);
    }

    /** {@inheritdoc} */
    static function fromParamsArray(array $params)
    {
        Recordable::requireArrayKeysExist('characters', $params);
        return new RemoveTrailingCharacters($params['characters']);
    }
}
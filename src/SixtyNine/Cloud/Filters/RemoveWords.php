<?php
/**
 * This file is part of the sixty-nine/word-cloud.
 * Copyright (c) 2010-2016 Daniele Barsotti<sixtynine.db@gmail.com>
 */

namespace SixtyNine\Cloud\Filters;

use SixtyNine\Cloud\Model\Word;
use SixtyNine\Cloud\Storage\Recordable;

/**
 * Remove words in a blacklist.
 */
class RemoveWords extends AbstractFilter implements FilterInterface
{
    protected $unwantedWords;

    /**
     * @param array $unwantedWords Array of words to be removed.
     */
    public function __construct($unwantedWords = array(
        'and', 'our', 'your', 'their', 'his', 'her', 'the', 'you', 'them', 'yours',
        'with', 'such', 'even')
    )
    {
        $this->unwantedWords = $unwantedWords;
    }

    /**
     * {@inheritdoc}
     */
    public function filterWord(Word $word)
    {
        if (in_array($word, $this->unwantedWords))  {
            return false;
        }
        return $word;
    }

    /** {@inheritdoc} */
    public function keepWord(Word $word)
    {
        return !in_array($word->getText(), $this->unwantedWords);
    }

    /** {@inheritdoc} */
    function getParamsArray()
    {
        return array('words' => $this->unwantedWords);
    }

    /** {@inheritdoc} */
    static function fromParamsArray(array $params)
    {
        Recordable::requireArrayKeysExist('words', $params);
        return new RemoveWords($params['words']);
    }
}
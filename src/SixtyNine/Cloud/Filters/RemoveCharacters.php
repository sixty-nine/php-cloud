<?php
/**
 * This file is part of the sixty-nine/word-cloud.
 * Copyright (c) 2010-2016 Daniele Barsotti<sixtynine.db@gmail.com>
 */

namespace SixtyNine\Cloud\Filters;

use SixtyNine\Cloud\Model\Word;
use SixtyNine\Cloud\Storage\Recordable;

/**
 * Remove unwanted characters from words.
 */
class RemoveCharacters extends AbstractFilter implements FilterInterface
{
    protected $unwantedCharacters;

    /**
     * @param array $unwantedCharacters Array of characters to be removed.
     */
    public function __construct($unwantedCharacters = null)
    {
        if (!$unwantedCharacters) {
            $unwantedCharacters = array(
                ':', '?', '!', '\'', '"', '(', ')', '[', ']',
            );
        }
        $this->unwantedCharacters = $unwantedCharacters;
    }

    /** {@inheritdoc} */
    public function filterWord(Word $word)
    {
        $text = $word->getText();
        foreach($this->unwantedCharacters as $p) {
            $text = str_replace($p, '', $text);
        }
        $word->setText($text);
        return $word;
    }

    /** {@inheritdoc} */
    function getParamsArray()
    {
        return array('characters' => $this->unwantedCharacters);
    }

    /** {@inheritdoc} */
    static function fromParamsArray(array $params)
    {
        Recordable::requireArrayKeysExist('characters', $params);
        return new RemoveCharacters($params['characters']);
    }
}
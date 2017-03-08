<?php
/**
 * This file is part of the sixty-nine/word-cloud.
 * Copyright (c) 2010-2016 Daniele Barsotti<sixtynine.db@gmail.com>
 */
namespace SixtyNine\Cloud\Filters;

use SixtyNine\Cloud\Model\Word;
use SixtyNine\Cloud\Storage\Recordable;

/**
 * Change the case of the words.
 */
class ChangeCase extends AbstractFilter implements FilterInterface
{
    const UPPERCASE = 'uppercase';
    const LOWERCASE = 'lowercase';
    const UCFIRST = 'ucfirst';

    /** @var string */
    protected $case;

    /**
     * @param string $case
     */
    public function __construct($case)
    {
        $this->case = $case;
    }

    /** {@inheritdoc} */
    public function filterWord(Word $word)
    {
        $text = $word->getText();
        switch ($this->case) {
            case self::UPPERCASE:
                $text = strtoupper($text);
                break;
            case self::UCFIRST:
                $text = ucfirst($text);
                break;
            case self::LOWERCASE:
                $text = strtolower($text);
                break;
        }
        $word->setText($text);
        return $word;
    }

    /** {@inheritdoc} */
    function getParamsArray()
    {
        return array('case' => $this->case);
    }

    /** {@inheritdoc} */
    static function fromParamsArray(array $params)
    {
        Recordable::requireArrayKeysExist('case', $params);
        return new ChangeCase($params['case']);
    }
}
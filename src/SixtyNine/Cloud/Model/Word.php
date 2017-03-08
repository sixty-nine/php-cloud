<?php

namespace SixtyNine\Cloud\Model;

/**
 * A word is some text with its number of occurrences in the Words and a style for
 * display.
 */
class Word
{
    /** @var string */
    protected $text;

    /** @var int */
    protected $count = 0;

    /** @var WordStyle */
    protected $style;

    public function __construct()
    {
        $this->style = new WordStyle();
    }

    /**
     * @param int $count
     * @return Word
     */
    public function setCount($count)
    {
        $this->count = $count;
        return $this;
    }

    /** @return int */
    public function getCount()
    {
        return $this->count;
    }

    /**
     * @param int $count
     * @return Word
     */
    public function increaseCount($count = 1)
    {
        $this->count += $count;
        return $this;
    }

    /**
     * @param int $count
     * @return Word
     */
    public function decrease($count = 1)
    {
        $this->count -= $count;
        return $this;
    }

    /**
     * @param string $text
     * @return Word
     */
    public function setText($text)
    {
        $this->text = $text;
        return $this;
    }

    /** @return string */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param \SixtyNine\Cloud\Model\WordStyle $style
     * @return Word
     */
    public function setStyle($style)
    {
        $this->style = $style;
        return $this;
    }

    /**
     * @return \SixtyNine\Cloud\Model\WordStyle
     */
    public function getStyle()
    {
        return $this->style;
    }
}
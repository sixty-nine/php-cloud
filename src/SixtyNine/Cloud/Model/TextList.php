<?php

namespace SixtyNine\Cloud\Model;

use SixtyNine\Cloud\Font\FontInterface;
use SixtyNine\Cloud\Font\FontSizeGeneratorInterface;
use SixtyNine\Cloud\TextListFilter\TextListVisitorInterface;
use SixtyNine\Cloud\TextSorters\SorterInterface;
use SixtyNine\Cloud\Usher\UsherInterface;

class TextList implements \IteratorAggregate
{
    /** @var array */
    protected $list;

    /** @var FontInterface */
    protected $font;

    /** @var UsherInterface */
    protected $usher;

    /** @var FontSizeGeneratorInterface */
    protected $fontSizeGenerator;

    /** @var CloudStyle */
    protected $style;

    /** @var SorterInterface */
    protected $sorter;

    /**
     * @param CloudStyle $style
     * @param Words $words
     */
    public function __construct(CloudStyle $style, Words $words = null)
    {
        $this->style = $style;
        $this->font = $style->getFont();
        $this->list = array();

        if ($words) {
            $this->addWords($words);
        }
    }

    /**
     * @param Text $text
     */
    public function addText(Text $text)
    {
        $this->list[$text->getWord()->getText()] = $text;
        $this->reset();
    }

    /**
     * @param Words $words
     */
    public function addWords(Words $words)
    {
        foreach ($words->getWords() as $word) {
            $this->addWord($word);
        }
    }

    /**
     * @param Word $word
     * @return \SixtyNine\Cloud\Model\Text
     */
    public function addWord(Word $word)
    {
        $text = new Text($this->font, $word);
        $this->addText($text);
        return $text;
    }

    /**
     * @param array $texts
     */
    public function addTexts(array $texts)
    {
        foreach ($texts as $text) {
            if ($text instanceof Text) {
                $this->addText($text);
            }
        }
    }

    /**
     * @param Word $word
     * @return Text
     */
    public function getText(Word $word)
    {
        if (array_key_exists($word->getText(), $this->list)) {
            return $this->list[$word->getText()];
        }
        return false;
    }

    /**
     * @return \SixtyNine\Cloud\Model\CloudStyle
     */
    public function getStyle()
    {
        return $this->style;
    }

    /** {@inheritdoc} */
    public function getIterator()
    {
        return new \ArrayIterator($this->list);
    }

    public function applyVisitor(TextListVisitorInterface $visitor)
    {
        $visitor->visit($this);
        $this->reset();
    }

    public function reset()
    {
        $this->sort();
    }

    public function setSorter(SorterInterface $sorter)
    {
        $this->sorter = $sorter;
    }

    public function sort()
    {
        if ($this->sorter) {
            $callback = array($this->sorter, 'compare');
            $array = $this->list;
            uasort($array, $callback);
            $this->list  = $array;
        }
    }
}

<?php

namespace SixtyNine\Cloud\Model;

use SixtyNine\Cloud\Filters\Filters;

class Words
{
    /**
     * An array of Words
     * @var array
     */
    protected $words = array();

    /**
     * The total number of occurrences of words
     * @var int
     */
    protected $totalCount = 0;

    /** @var int */
    protected $minCount = 0;

    /**
     * An array of FilterInterface
     * @var Filters
     */
    protected $filters;

    /** @var bool */
    protected $useFilters = true;

    function __construct(Filters $filters, $useFilters = true)
    {
        $this->filters = $filters;
        $this->useFilters = $useFilters;
    }

    /**
     * @param string $text
     * @param int $count
     * @return bool|\SixtyNine\Cloud\Model\Word
     */
    public function addWord($text, $count = 1)
    {
        /** @var Word $word */
        $word = new Word();
        $word->setText($text);
        $word->setCount(0);

        if (!$word = $this->filters->filterWord($word)) {
            return false;
        }

        $text = $word->getText();
        if (array_key_exists($text, $this->words)) {
            $word = $this->words[$text];
        } else {
            $this->words[$text] = $word;
        }

        $word->increaseCount($count);
        $this->totalCount += $count;

        return true;
    }

    public function addWords($words, $count = 1)
    {
        foreach ($words as $word) {
            $this->addWord($word, $count);
        }
    }

    /**
     * Extract a list of words from a text and add them to the frequency table.
     * @param string $text The text to parse
     * @param int $maxWords
     */
    public function addText($text, $maxWords = 100)
    {
        $array = preg_split("/[\n\r\t ]+/", $text);
        $this->addWords(array_slice($array, 0, $maxWords));
    }

    /**
     * @param $html
     * @param int $maxWords
     * @return bool
     */
    public function addHtml($html, $maxWords = 100)
    {
        if (!$html) {
            return false;
        }

        $d = new \DOMDocument;
        $mock = new \DOMDocument;
        libxml_use_internal_errors(true);
        $d->loadHTML($html);
        libxml_use_internal_errors(false);
        $body = $d->getElementsByTagName('body')->item(0);
        if ($body) {
            foreach ($body->childNodes as $child) {
                $mock->appendChild($mock->importNode($child, true));
            }
        }
        $text = html_entity_decode(strip_tags($mock->saveHTML()));
        $this->addText($text, $maxWords);
        return true;
    }


    /** @return array */
    public function getWords()
    {
        return $this->words;
    }

    /**
     * @param $key
     * @return Word
     * @throws \InvalidArgumentException
     */
    public function getWord($key)
    {
        if (!array_key_exists($key, $this->words)) {
            throw new \InvalidArgumentException('Word not found: ' . $key);
        }
        return $this->words[$key];
    }

    /** @return int */
    public function getTotalCount()
    {
        return $this->totalCount;
    }
}

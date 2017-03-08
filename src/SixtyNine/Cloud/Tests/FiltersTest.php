<?php


namespace SixtyNine\Cloud\Tests;


use SixtyNine\Cloud\Filters\FilterInterface;
use SixtyNine\Cloud\Filters\Filters;
use SixtyNine\Cloud\Filters\RemoveNumbers;
use SixtyNine\Cloud\Filters\RemoveWords;
use SixtyNine\Cloud\Model\Word;

class FiltersTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @param $filter
     * @param $word
     * @param $expectedKeepWord
     * @param null $expectedWord
     * @dataProvider wordsProvider
     */
    public function testTest($filter, $word, $expectedKeepWord, $expectedWord = null)
    {
        $this->assertFilteredWord($filter, $word, $expectedKeepWord, $expectedWord);
    }

    public function wordsProvider()
    {
        return array(
            [
                new RemoveWords(array('and')),
                $this->getWord('foobar'),
                true
            ],
            [
                new RemoveWords(array('and')),
                $this->getWord('and'),
                false
            ],
            [
                new RemoveNumbers(),
                $this->getWord('1234abcd5678'),
                true,
                $this->getWord('abcd')
            ],
            [
                new RemoveNumbers(),
                $this->getWord('1234567890'),
                true,
                $this->getWord('')
            ],
        );
    }

    protected function assertFilteredWord(FilterInterface $filter, Word $word, $expectedKeepWord, Word $expectedFiltered = null)
    {
        $filters = new Filters(array($filter));
        $filtered = $filters->filterWord($word);
        $filteredWord = $filter->filterWord($word);

        if ($expectedKeepWord) {
            $this->assertTrue((bool)$filtered);
            $this->assertTrue((bool)$filteredWord);
        } else {
            $this->assertFalse($filtered, 'Expected the word to be filtered out');
            $this->assertFalse($filter->keepWord($word));
        }

        if ($expectedFiltered) {
            $this->assertEquals($expectedFiltered, $filtered, 'Unexpected filtered value');
            $this->assertEquals($expectedFiltered, $filteredWord, 'Unexpected filtered word');
        }
    }

    protected function getWord($text)
    {
        return (new Word())->setText($text);
    }
}
 
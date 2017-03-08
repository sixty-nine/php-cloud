<?php


namespace SixtyNine\Cloud\Tests;


use SixtyNine\Cloud\Filters\ChangeCase;
use SixtyNine\Cloud\Filters\Filters;
use SixtyNine\Cloud\Filters\RemoveCharacters;
use SixtyNine\Cloud\Filters\RemoveTrailingCharacters;
use SixtyNine\Cloud\Filters\RemoveWords;
use SixtyNine\Cloud\Model\Word;
use SixtyNine\Cloud\Model\Words;

class WordsTest extends \PHPUnit_Framework_TestCase
{
    public function testWords()
    {
        $filters =new Filters();
        $filters->addFilters(array(
            new RemoveTrailingCharacters(),
            new RemoveCharacters(),
            new RemoveWords(),
            new ChangeCase(ChangeCase::LOWERCASE)
        ));

        $words = new Words($filters);
        $words->addText("Lorem ipsum et du bla bla, lorem.");

        $extracted = $words->getWords();

        foreach ($extracted as $word) {
            $this->assertInstanceOf(Word::class, $word);
        }

        $this->assertcount(5, $extracted);
        $this->assertTrue(array_key_exists('lorem', $extracted));
        $this->assertTrue(array_key_exists('ipsum', $extracted));
        $this->assertTrue(array_key_exists('et', $extracted));
        $this->assertTrue(array_key_exists('du', $extracted));
        $this->assertTrue(array_key_exists('bla', $extracted));

        $this->assertEquals(2, $extracted['lorem']->getCount());
        $this->assertEquals(2, $extracted['bla']->getCount());
        $this->assertEquals(1, $extracted['ipsum']->getCount());
    }
}
 
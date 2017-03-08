<?php

namespace SixtyNine\Cloud\Tests;

use SixtyNine\Cloud\Font\Font;
use SixtyNine\Cloud\Model\Word;
use SixtyNine\Cloud\Model\Text;

class FontTest extends \PHPUnit_Framework_TestCase
{
    public function testFont()
    {
        $word = new Word();
        $word->setText('foobar');
        $word->getStyle()->setSize(10);
        $font = new Font(__DIR__ . '/fixtures/Arial.ttf');
        $text = new Text($font, $word);
        $box = $text->getBox();
        $this->assertEquals(40, $box->getWidth());
        $this->assertEquals(12, $box->getHeight());
    }
}
 
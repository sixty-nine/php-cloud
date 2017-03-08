<?php


namespace SixtyNine\Cloud\Tests;


use SixtyNine\Cloud\Color\Color;
use SixtyNine\Cloud\Filters\ChangeCase;
use SixtyNine\Cloud\Filters\Filters;
use SixtyNine\Cloud\Filters\RemoveCharacters;
use SixtyNine\Cloud\Filters\RemoveWords;
use SixtyNine\Cloud\Font\Font;
use SixtyNine\Cloud\Model\Vector;
use SixtyNine\Cloud\Model\CloudStyle;
use SixtyNine\Cloud\Model\Text;
use SixtyNine\Cloud\Model\TextList;
use SixtyNine\Cloud\Model\Words;
use SixtyNine\Cloud\Renderer\Renderer;

class RendererTest extends \PHPUnit_Framework_TestCase
{
    public function testRenderer()
    {
        $filters =new Filters();
        $filters->addFilters(array(
            new ChangeCase(ChangeCase::LOWERCASE),
            new RemoveCharacters(),
            new RemoveWords(),
        ));

        $words = new Words($filters);
        $words->addWords(array('Hello', 'WORLD', 'vertical'));

        $word1 = $words->getWord('hello');
        $word1->getStyle()->setSize(20);
        $word1->getStyle()->setColor((new Color())->setHex('FF0000'));

        $word2 = $words->getWord('world');
        $word2->getStyle()->setSize(40);

        $word3 = $words->getWord('vertical');
        $word3->getStyle()->setSize(12);
        $word3->getStyle()->setAngle(90);

        $font = new Font(__DIR__ . '/fixtures/Arial.ttf');
        $style = new CloudStyle($font);

        $list = new TextList($style);
        $text = new Text($font, $word1);
        $text->setPosition(new Vector(20, 10));
        $list->addText($text);
        $text = new Text($font, $word2);
        $text->setPosition(new Vector(20, 22));
        $list->addText($text);
        $text = new Text($font, $word3);
        $text->setPosition(new Vector(4, 10));
        $list->addText($text);

        $renderer = new Renderer();
        $image = $renderer->createImage(200, 100);
        $image = $renderer->render($image, $list);

        $file = __DIR__ . '/test.png';
        $image->saveToPngFile($file);
        //system('display ' . $file);
        unlink($file);

        $this->assertTrue(
            file_get_contents(__DIR__ . '/fixtures/test.png') == $image->getRawPngContent(),
            'First test image not equal'
        );
        $image->destroy();

        $image = $renderer->createImage(200, 100);
        $image = $renderer->render($image, $list, true);
        $this->assertTrue(
            file_get_contents(__DIR__ . '/fixtures/test-with-boxes.png') === $image->getRawPngContent(),
            'Second test image not equal'
        );
        $image->destroy();
    }

    public function testDrawWord()
    {
        $words = new Words(new Filters());

        $font = new Font(__DIR__ . '/fixtures/Arial.ttf');
        $list = new TextList(new CloudStyle($font));

        $words->addWord('FOOBAR');
        $word = $words->getWord('FOOBAR');
        $word->getStyle()->setSize(20);

        $text = new Text($font, $word);
        $text->setPosition(new Vector(0, 0));
        $list->addText($text);

        $words->addWord('vertical');
        $word = $words->getWord('vertical');
        $word->getStyle()->setSize(10);
        $word->getStyle()->setAngle(90);

        $text = new Text($font, $word);
        $text->setPosition(new Vector(0, 22));
        $list->addText($text);

        $renderer = new Renderer();
        $image = $renderer->createImage(100, 200);
        $renderer->render($image, $list, true);

        $file = __DIR__ . '/test.png';
        $image->saveToPngFile($file);
        //system('display ' . $file);
        unlink($file);
    }
}

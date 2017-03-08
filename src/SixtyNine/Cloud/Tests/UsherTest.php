<?php


namespace SixtyNine\Cloud\Tests;


use SixtyNine\Cloud\Color\Color;
use SixtyNine\Cloud\Filters\ChangeCase;
use SixtyNine\Cloud\Filters\Filters;
use SixtyNine\Cloud\Filters\RemoveCharacters;
use SixtyNine\Cloud\Filters\RemoveWords;
use SixtyNine\Cloud\Font\DefaultFontSizeGenerator;
use SixtyNine\Cloud\Font\Font;
use SixtyNine\Cloud\Model\TextList;
use SixtyNine\Cloud\Model\Word;
use SixtyNine\Cloud\Model\CloudStyle;
use SixtyNine\Cloud\Model\Words;
use SixtyNine\Cloud\Renderer\Renderer;
use SixtyNine\Cloud\TextListFilter\FontSizeVisitor;
use SixtyNine\Cloud\TextListFilter\UsherVisitor;
use SixtyNine\Cloud\Usher\LinearUsher;

class UsherTest extends \PHPUnit_Framework_TestCase
{
    public function testRenderer()
    {
        $filters =new Filters();
        $filters->addFilters(array(
            new ChangeCase(ChangeCase::LOWERCASE),
            new RemoveCharacters(),
            new RemoveWords(),
        ));

        $font = new Font(__DIR__ . '/fixtures/Arial.ttf');

        $usher = new LinearUsher(200, 1000);

        $list = new TextList(new CloudStyle($font));

        $words = new Words($filters);
        $words->addWords(array('Hello', 'WORLD', 'vertical', 'by'));

        $word = $words->getWord('hello');
        $word
            ->setCount(10)
            ->getStyle()
                ->setSize(20)
                ->setColor((new Color())->setHex('FF0000'))
        ;
        $list->addWord($word);

        $word = $words->getWord('vertical');
        $word->setCount(5)
            ->getStyle()
                ->setSize(30)
                ->setAngle(90)
        ;
        $list->addWord($word);

        $word = $words->getWord('world');
        $word->setCount(5)->getStyle()->setSize(40);
        $list->addWord($word);

        $word = new Word();
        $word->setCount(3)->setText('Hello out there!')
            ->getStyle()
                ->setSize(15)
                ->setAngle(90)
        ;
        $list->addWord($word);

        $list->applyVisitor(new FontSizeVisitor(
            new DefaultFontSizeGenerator(10, 40)
        ));
        $list->applyVisitor(new UsherVisitor($usher));

        $renderer = new Renderer();
        $image = $renderer->createImage(1000, 1000);
        $renderer->render($image, $list, true);

        $file = __DIR__ . '/test.png';
        $image->saveToPngFile($file);
        //system('display ' . $file);
        unlink($file);

        $image->destroy();
    }
}

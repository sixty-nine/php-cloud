<?php


namespace SixtyNine\Cloud\Tests;


use SixtyNine\Cloud\Color\Color;
use SixtyNine\Cloud\Color\PaletteFactory;
use SixtyNine\Cloud\Color\RandomColorGenerator;
use SixtyNine\Cloud\Color\RotateColorGenerator;
use SixtyNine\Cloud\Config\Config;
use SixtyNine\Cloud\Filters\ChangeCase;
use SixtyNine\Cloud\Filters\Filters;
use SixtyNine\Cloud\Filters\RemoveByLength;
use SixtyNine\Cloud\Filters\RemoveCharacters;
use SixtyNine\Cloud\Filters\RemoveTrailingCharacters;
use SixtyNine\Cloud\Filters\RemoveWords;
use SixtyNine\Cloud\Font\DefaultFontSizeGenerator;
use SixtyNine\Cloud\Font\Font;
use SixtyNine\Cloud\Model\Text;
use SixtyNine\Cloud\Model\TextList;
use SixtyNine\Cloud\Model\TextListBuilder;
use SixtyNine\Cloud\Model\Word;
use SixtyNine\Cloud\Model\CloudStyle;
use SixtyNine\Cloud\Model\Words;
use SixtyNine\Cloud\Renderer\Renderer;
use SixtyNine\Cloud\TextListFilter\ColorVisitor;
use SixtyNine\Cloud\TextListFilter\FontSizeVisitor;
use SixtyNine\Cloud\TextListFilter\OrientationVisitor;
use SixtyNine\Cloud\TextListFilter\UsherVisitor;
use SixtyNine\Cloud\TextSorters\SortByAngle;
use SixtyNine\Cloud\TextSorters\SortByOccurrence;
use SixtyNine\Cloud\TextSorters\SortByText;
use SixtyNine\Cloud\Usher\LinearUsher;
use SixtyNine\Cloud\Usher\VerticalUsher;
use SixtyNine\Cloud\Usher\WordleUsher;

class CloudTest extends \PHPUnit_Framework_TestCase
{
    public function testRenderer()
    {
        $config = new Config(array(__DIR__ . '/../Resources/config'));
        $blacklist = $config->get('words');
        $blacklist = $blacklist['blacklist'];

        $filters =new Filters();
        $filters->addFilters(array(
            new ChangeCase(ChangeCase::LOWERCASE),
            new RemoveCharacters(),
            new RemoveTrailingCharacters(),
            new RemoveWords($blacklist),
            new RemoveByLength(4),
        ));

        $words = new Words($filters);
        $words->addText(file_get_contents(__DIR__ . '/fixtures/text.txt'));

        $font = new Font(__DIR__ . '/fixtures/TestFont.ttf');
        $factory = new PaletteFactory($config);

        $style = new CloudStyle($font);
        $style->setBackgroundColor(new Color());

        $builder = new TextListBuilder($config);
        $list = $builder->getList(
            $style,
            $words,
            new LinearUsher(600, 1000),
//            new VerticalUsher(600, 1000),
//            new WordleUsher(600, 1000),
            OrientationVisitor::WORDS_MAINLY_HORIZONTAL,
            new SortByAngle(Text::DIR_VERTICAL),
            new RandomColorGenerator($factory->getNamedPalette('pastel')),
            new DefaultFontSizeGenerator(20, 100)
        );

        $renderer = new Renderer();
        $image = $renderer->createImage(600, 1000);
        $renderer->render($image, $list, true);

        $file = __DIR__ . '/test.png';
        $image->saveToPngFile($file);
        //system('display ' . $file);
        unlink($file);

        $image->destroy();
    }
}

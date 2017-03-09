<?php

namespace SixtyNine\CloudBundle\Builder;

use SixtyNine\Cloud\Color\Color;
use SixtyNine\Cloud\Color\PaletteFactory;
use SixtyNine\Cloud\Color\RandomColorGenerator;
use SixtyNine\Cloud\Color\RotateColorGenerator;
use SixtyNine\Cloud\Config\Config;
use SixtyNine\Cloud\Filters\ChangeCase;
use SixtyNine\Cloud\Filters\Filters;
use SixtyNine\Cloud\Filters\RemoveByLength;
use SixtyNine\Cloud\Filters\RemoveCharacters;
use SixtyNine\Cloud\Filters\RemoveNumbers;
use SixtyNine\Cloud\Filters\RemoveTrailingCharacters;
use SixtyNine\Cloud\Font\DefaultFontSizeGenerator;
use SixtyNine\Cloud\Font\Font;
use SixtyNine\Cloud\Model\CloudStyle;
use SixtyNine\Cloud\Model\Text;
use SixtyNine\Cloud\Model\TextListBuilder;
use SixtyNine\Cloud\Model\Words;
use SixtyNine\Cloud\Performance\StopwatchAware;
use SixtyNine\Cloud\Renderer\Renderer;
use SixtyNine\Cloud\TextSorters\SortByAngle;
use SixtyNine\Cloud\TextSorters\SortByOccurrence;
use SixtyNine\Cloud\TextSorters\SortByText;
use SixtyNine\Cloud\Usher\CircularUsher;
use SixtyNine\Cloud\Usher\LinearUsher;
use SixtyNine\Cloud\Usher\VerticalUsher;
use SixtyNine\Cloud\Usher\WordleUsher;
use SixtyNine\CloudBundle\Entity\WordsList;

class CloudBuilder
{
    /**
     * @param $text
     * @param Filters $filters
     * @return Words
     */
    public function createWords($text, Filters $filters = null)
    {
        $filters = $filters ? $filters : new Filters();
        $words = new Words($filters);
        $words->addText($text);
        return $words;
    }

    public function createWordsFromUrl($url, Filters $filters = null)
    {
        $filters = $filters ? $filters : new Filters();
        $words = new Words($filters);
        $html = file_get_contents($url);
        $words->addHtml($html);
        return $words;
    }

    public function buildWordsFromWordsList(WordsList $list)
    {
        $words = new Words(new Filters());
        /** @var \SixtyNine\CloudBundle\Entity\Word $word */
        foreach ($list->getWords() as $word) {
            $words->addWord($word->getText(), $word->getCount());
        }
        return $words;
    }

    public function createImage(Words $words, Config $config, $data)
    {
        $font = new Font(__DIR__ . '/../Resources/fonts/' . $data['font']);
        $factory = new PaletteFactory($config);

        $style = new CloudStyle($font);
        $style->setBackgroundColor(new Color());

        $imgWidth = 1000;
        $imgHeight = 800;

        $builder = new TextListBuilder(new Config());

        $usher = new LinearUsher($imgWidth, $imgHeight);
        if (isset($data['usher'])) {
            switch ($data['usher']) {
                case 'vert':
                    $usher = new VerticalUsher($imgWidth, $imgHeight);
                    break;
                case 'circular':
                    $usher = new CircularUsher($imgWidth, $imgHeight);
                    break;
                case 'wordle':
                    $usher = new WordleUsher($imgWidth, $imgHeight);
                    break;
            }
        }

        $sortBy = null;
        if (isset($data['sortby'])) {
            switch ($data['sortby']) {
                case 'angle-v':
                    $sortBy = new SortByAngle();
                    break;
                case 'angle-h':
                    $sortBy = new SortByAngle(Text::DIR_HORIZONTAL);
                    break;
                case 'count':
                    $sortBy = new SortByOccurrence();
                    break;
                case 'alpha':
                    $sortBy = new SortByText();
                    break;
            }
        }

        $colorGenerator = isset($data['randomColor']) && $data['randomColor']
            ? new RandomColorGenerator($factory->getNamedPalette($data['palette']))
            : new RotateColorGenerator($factory->getNamedPalette($data['palette']))
        ;

        if ($data['minSize'] > $data['maxSize']) {
            $data['minSize'] = $data['maxSize'];
        }

        $list = $builder->getList(
            $style,
            $words,
            $usher,
            $data['orientation'],
            $sortBy,
            $colorGenerator,
            new DefaultFontSizeGenerator($data['minSize'], $data['maxSize'])
        );

//        $storage = new WordsStorage();
//        $json = $storage->save($words);
//        echo '<pre>' . $json->toJson();
//        die;

        $renderer = new Renderer();
        $image = $renderer->createImage($imgWidth, $imgHeight);
        $renderer->render($image, $list, $data['frame']);

        if (isset($data['debugUsher']) && $data['debugUsher']) {
            $renderer->drawUsher($image, $usher, (new Color())->setHex('FF0000'), 250);
        }

        return $image;
    }

}
 
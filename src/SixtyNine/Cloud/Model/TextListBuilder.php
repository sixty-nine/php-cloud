<?php

namespace SixtyNine\Cloud\Model;

use SixtyNine\Cloud\Color\ColorGeneratorInterface;
use SixtyNine\Cloud\Config\Config;
use SixtyNine\Cloud\Font\DefaultFontSizeGenerator;
use SixtyNine\Cloud\Font\FontSizeGeneratorInterface;
use SixtyNine\Cloud\TextListFilter\ColorVisitor;
use SixtyNine\Cloud\TextListFilter\FontSizeVisitor;
use SixtyNine\Cloud\TextListFilter\OrientationVisitor;
use SixtyNine\Cloud\TextListFilter\UsherVisitor;
use SixtyNine\Cloud\TextSorters\SorterInterface;
use SixtyNine\Cloud\Usher\UsherInterface;

class TextListBuilder
{
    /** @var \SixtyNine\Cloud\Config\Config  */
    protected $config;

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    /**
     * @param CloudStyle $style
     * @param Words $words
     * @param UsherInterface $usher
     * @param int $orientation
     * @param SorterInterface $sortBy
     * @param ColorGeneratorInterface $colorGenerator
     * @param FontSizeGeneratorInterface $sizeGenerator
     * @return TextList
     */
    public function getList(
        CloudStyle $style,
        Words $words,
        UsherInterface $usher,
        $orientation = OrientationVisitor::WORDS_MAINLY_HORIZONTAL,
        SorterInterface $sortBy = null,
        ColorGeneratorInterface $colorGenerator = null,
        FontSizeGeneratorInterface $sizeGenerator = null
    )
    {
        $list = new TextList($style, $words);

        $list->applyVisitor(new OrientationVisitor($orientation));

        if ($sortBy) {
            $list->setSorter($sortBy);
        }

        if (!$sizeGenerator) {
            $sizeGenerator = new DefaultFontSizeGenerator(20, 100);
        }
        $list->applyVisitor(new FontSizeVisitor($sizeGenerator));

        if ($colorGenerator) {
            $list->applyVisitor(new ColorVisitor($colorGenerator));
        }

        $list->applyVisitor(new UsherVisitor($usher));

        return $list;
    }
}

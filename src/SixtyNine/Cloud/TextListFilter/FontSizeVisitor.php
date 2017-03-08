<?php

namespace SixtyNine\Cloud\TextListFilter;

use SixtyNine\Cloud\Model\Text;
use SixtyNine\Cloud\Model\TextList;
use SixtyNine\Cloud\Font\FontSizeGeneratorInterface;

class FontSizeVisitor implements TextListVisitorInterface
{
    /** @var FontSizeGeneratorInterface */
    protected $fontSizeGenerator;

    public function __construct(FontSizeGeneratorInterface $fontSizeGenerator)
    {
        $this->fontSizeGenerator = $fontSizeGenerator;
    }

    function visit(TextList $list)
    {
        /** @var Text $text */
        foreach ($list as $text) {
            $size = $this->fontSizeGenerator->calculateFontSize($text->getWord());
            $text->getWord()->getStyle()->setSize($size);
        }
    }
}
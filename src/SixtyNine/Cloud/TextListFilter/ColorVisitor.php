<?php

namespace SixtyNine\Cloud\TextListFilter;

use SixtyNine\Cloud\Color\ColorGeneratorInterface;
use SixtyNine\Cloud\Model\Text;
use SixtyNine\Cloud\Model\TextList;

class ColorVisitor implements TextListVisitorInterface
{
    /** @var ColorGeneratorInterface */
    protected $generator;

    public function __construct(ColorGeneratorInterface $generator)
    {
        $this->generator = $generator;
    }

    function visit(TextList $list)
    {
        /** @var Text $text */
        foreach ($list as $text) {
            $text->getWord()->getStyle()->setColor($this->generator->getNextColor());
        }
    }
}

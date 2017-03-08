<?php

namespace SixtyNine\Cloud\TextListFilter;

use SixtyNine\Cloud\Model\Text;
use SixtyNine\Cloud\Model\TextList;

class OrientationVisitor implements TextListVisitorInterface
{
    const WORDS_HORIZONTAL = 0;
    const WORDS_MAINLY_HORIZONTAL = 25;
    const WORDS_MIXED = 50;
    const WORDS_MAINLY_VERTICAL = 75;
    const WORDS_VERTICAL = 100;

    /** @var int */
    protected $verticalProb;

    public function __construct($verticalProb = self::WORDS_MAINLY_HORIZONTAL)
    {
        $this->verticalProb = $verticalProb;
    }

    function visit(TextList $list)
    {
        /** @var Text $text */
        foreach ($list as $text) {
            $orientation = $this->getNextOrientation();
            $text->getWord()->getStyle()->setAngle(
                $orientation === Text::DIR_HORIZONTAL ? 0 : 90
            );
            $text->setDir($orientation);
        }
    }

    protected function getNextOrientation()
    {
        if (random_int(0, 100) <= $this->verticalProb) {
            return Text::DIR_VERTICAL;
        }
        return Text::DIR_HORIZONTAL;
    }
}

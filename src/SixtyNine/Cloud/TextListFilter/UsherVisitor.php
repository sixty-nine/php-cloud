<?php

namespace SixtyNine\Cloud\TextListFilter;

use SixtyNine\Cloud\Model\Text;
use SixtyNine\Cloud\Model\TextList;
use SixtyNine\Cloud\Usher\UsherInterface;

class UsherVisitor implements TextListVisitorInterface
{
    /** @var UsherInterface */
    protected $usher;

    public function __construct(UsherInterface $usher)
    {
        $this->usher = $usher;
    }

    function visit(TextList $list)
    {
        $this->usher->resetMask();
        /** @var Text $text */
        foreach ($list as $text) {
            $text->calculateSize();
            $this->usher->getPlace($text);
        }
    }
}
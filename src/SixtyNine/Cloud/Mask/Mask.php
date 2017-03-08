<?php

namespace SixtyNine\Cloud\Mask;
use SixtyNine\Cloud\Model\Box;
use SixtyNine\Cloud\Model\Vector;

/**
 * List of already placed boxes used to search a free space for a new box.
 * Very ineffective implementation.
 */
class Mask implements MaskInterface
{
    private $drawnBoxes = array();

    /** {@inheritdoc} */
    public function add(Box $box)
    {
        $this->drawnBoxes[] = $box;
    }

    /** {@inheritdoc} */
    public function overlaps(Box $testBox)
    {
        /** @var Box $box */
        foreach ($this->drawnBoxes as $box) {
            if ($box->intersects($testBox)) {
                return true;
            }
        }
        return false;
    }

    /** {@inheritdoc} */
    public function getEnclosingBox()
    {
        $left = null;
        $right = null;
        $top = null;
        $bottom = null;
        /** @var Box $box */
        foreach ($this->drawnBoxes as $box) {
            if (($left == NULL) || ($box->getLeft() < $left)) $left = $box->getLeft();
            if (($right == NULL) || ($box->getRight() > $right)) $right = $box->getRight();
            if (($top == NULL) || ($box->getTop() < $top)) $top = $box->getTop();
            if (($bottom == NULL) || ($box->getBottom() > $bottom)) $bottom = $box->getBottom();
        }
        return (new Box(
            new Vector($left, $top),
            new Vector($right - $left, $bottom - $top)
        ));
    }
}

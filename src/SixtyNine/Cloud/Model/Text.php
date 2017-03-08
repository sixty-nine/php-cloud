<?php

namespace SixtyNine\Cloud\Model;

use SixtyNine\Cloud\Font\FontInterface;

/**
 * A Word along with a Font, has a position and a size inside the image.
 */
class Text
{
    const DIR_VERTICAL = 'vert';
    const DIR_HORIZONTAL = 'horiz';

    /** @var FontInterface */
    protected $font;

    /** @var Word */
    protected $word;

    /** @var Vector */
    protected $size;

    /** @var Vector */
    protected $position;

    /** @var string */
    protected $dir;

    /** @var boolean */
    protected $visible = true;

    public function __construct(FontInterface $font, Word $word)
    {
        $this->font = $font;
        $this->word = $word;
        $this->position = new Vector();
        $this->calculateSize();
        $this->dir =
            $word->getStyle()->getAngle() === 90
                ? self::DIR_VERTICAL
                : self::DIR_HORIZONTAL
        ;
    }

    public function calculateSize()
    {
        $box = $this->font->getBoundingBox($this);
        $this->size = $box->getSize();
    }

    /**
     * @param \SixtyNine\Cloud\Model\Vector $position
     * @return Word
     */
    public function setPosition(Vector $position)
    {
        $this->position = $position;
        return $this;
    }

    /**
     * @return Vector
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * @return \SixtyNine\Cloud\Model\Box
     */
    public function getBox()
    {
        return new Box($this->position, $this->size);
    }

    /**
     * @return \SixtyNine\Cloud\Model\Vector
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * @return \SixtyNine\Cloud\Model\Word
     */
    public function getWord()
    {
        return $this->word;
    }

    /**
     * @return string
     */
    public function getDir()
    {
        return $this->dir;
    }

    /**
     * @param string $dir
     */
    public function setDir($dir)
    {
        $this->dir = $dir;
    }

    /**
     * @param boolean $visible
     */
    public function setVisible($visible)
    {
        $this->visible = $visible;
    }

    /**
     * @return boolean
     */
    public function getVisible()
    {
        return $this->visible;
    }
}

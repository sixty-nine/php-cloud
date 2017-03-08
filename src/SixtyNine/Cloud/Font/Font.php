<?php

namespace SixtyNine\Cloud\Font;

use SixtyNine\Cloud\Model\Box;
use SixtyNine\Cloud\Model\Text;

/**
 * Embed a TTF font in the Cloud system.
 */
class Font implements FontInterface
{
    /** @var string */
    protected $file;

    /** @var float */
    protected $paddingSize;

    /** @var int */
    protected $paddingAngle;

    /**
     * Create a TTF font from the given $file.
     * @param string $file
     * @param int $paddingAngle
     * @param int $paddingSize
     * @throws \InvalidArgumentException
     */
    function __construct($file, $paddingAngle = 0, $paddingSize = 1)
    {
        if (!file_exists($file)) {
            throw new \InvalidArgumentException("File not found $file");
        }

        $this->file = $file;
        $this->paddingAngle = $paddingAngle;
        $this->paddingSize = $paddingSize;
    }

    /** {@inheritdoc} */
    function getBoundingBox(Text $text)
    {
        $word = $text->getWord();
        $box = new Box();
        $coords = imagettfbbox(
            $word->getStyle()->getSize() * $this->paddingSize,
            $word->getStyle()->getAngle() - $this->paddingAngle,
            $this->file,
            $word->getText()
        );

        $box->fromBoundingBox(-$coords[6], -$coords[7], $coords);

        if ($text->getDir() === Text::DIR_VERTICAL) {
            $box->move(
                $text->getPosition()->x - $box->getPosition()->x - $box->getWidth(),
                $text->getPosition()->y - $box->getPosition()->y - $box->getHeight()
            );
        } else {
            $box->move(
                $text->getPosition()->x - $box->getPosition()->x,
                $text->getPosition()->y - $box->getPosition()->y - $box->getHeight()
            );
        }

        return $box;
    }

    /** {@inheritdoc} */
    public function getFile()
    {
        return $this->file;
    }
}

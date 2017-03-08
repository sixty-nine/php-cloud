<?php

namespace SixtyNine\Cloud\Renderer;

use SixtyNine\Cloud\Color\Color;
use SixtyNine\Cloud\Font\FontInterface;
use SixtyNine\Cloud\Model\Box;
use SixtyNine\Cloud\Model\Text;
use SixtyNine\Cloud\Model\Vector;

class Image
{
    /** @var int */
    protected $width;
    /** @var int */
    protected $height;
    /** @var object */
    protected $resource;
    /** @var array */
    protected $colors;

    /**
     * @param int $height
     * @param int $width
     */
    public function __construct($width, $height)
    {
        $this->height = $height;
        $this->width = $width;
        $this->colors = array();
        $this->resource = imagecreatetruecolor($width, $height);
        imagealphablending($this->resource, false);
        imagesavealpha($this->resource, true);
    }

    public function fillBackground(Color $color)
    {
        imagefill($this->resource, 0, 0, $this->allocateColor($color));
    }

    public function drawText(Text $text, FontInterface $font, Color $color, $drawBB = false)
    {
        $word = $text->getWord();
        $bbColor = (new Color())->setHex('0000FF');
        $box = $text->getBox();

        if ($drawBB) {
            $this->drawBox($box, $bbColor);
        }

        $posX = $text->getDir() === Text::DIR_VERTICAL
            ? $text->getPosition()->x + $box->getWidth()
            : $text->getPosition()->x;
        $posY = $text->getPosition()->y + $box->getHeight();

        imagettftext(
            $this->resource,
            $word->getStyle()->getSize(),
            $word->getStyle()->getAngle(),
            $posX,
            $posY,
            $this->colors[$color->getHex()],
            $font->getFile(),
            $word->getText()
        );
    }

    public function drawBox(Box $box, Color $color)
    {
        $col = $this->allocateColor($color);

        imagerectangle(
            $this->resource,
            $box->getTopLeftPoint()->x,
            $box->getTopLeftPoint()->y,
            $box->getBottomRightPoint()->x,
            $box->getBottomRightPoint()->y,
            $col
        );
    }

    public function drawLine(Vector $a, Vector $b, Color $color)
    {
        $col = $this->allocateColor($color);

        imageline(
            $this->resource,
            $a->x,
            $a->y,
            $b->x,
            $b->y,
            $col
        );
    }

    /**
     * @return int
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * @return int
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * @return resource
     */
    public function getResource()
    {
        return $this->resource;
    }

    public function allocateColor(Color $color, $alpha = 0)
    {
        if (!array_key_exists($color->getHex(), $this->colors)) {
            $rgb = $color->getRgb();
            $resource = imagecolorallocatealpha($this->resource, $rgb[0],$rgb[1], $rgb[2], $alpha);
            $this->colors[$color->getHex()] = $resource;
        }

        return $this->colors[$color->getHex()];
    }

    public function destroy()
    {
        imagedestroy($this->resource);
    }

    public function saveToPngFile($file)
    {
        imagepng($this->resource, $file);
        return file_get_contents($file);
    }

    public function getRawPngContent()
    {
        $file = sprintf('%s/%s', sys_get_temp_dir(), uniqid());
        $content = $this->saveToPngFile($file);
        unlink($file);
        return $content;
    }
}

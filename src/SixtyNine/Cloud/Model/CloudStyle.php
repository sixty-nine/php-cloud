<?php

namespace SixtyNine\Cloud\Model;

use SixtyNine\Cloud\Color\Color;
use SixtyNine\Cloud\Color\ColorGeneratorInterface;
use SixtyNine\Cloud\Font\FontInterface;

class CloudStyle
{
    /** @var Color */
    protected $backgroundColor;

    /** @var Color */
    protected $foreColor;

    /** @var ColorGeneratorInterface */
    protected $colorGenerator;

    /** @var FontInterface */
    protected $font;

    /**
     * Constructor
     */
    public function __construct(FontInterface $font)
    {
        $this->backgroundColor = (new Color())->setHex('FFFFFF');
        $this->foreColor = new Color();
        $this->font = $font;
    }

    /**
     * @param \SixtyNine\Cloud\Color\Color $backgroundColor
     * @return CloudStyle
     */
    public function setBackgroundColor($backgroundColor)
    {
        $this->backgroundColor = $backgroundColor;
        return $this;
    }

    /**
     * @return \SixtyNine\Cloud\Color\Color
     */
    public function getBackgroundColor()
    {
        return $this->backgroundColor;
    }

    /**
     * @param \SixtyNine\Cloud\Color\ColorGeneratorInterface $colorGenerator
     * @return CloudStyle
     */
    public function setColorGenerator($colorGenerator)
    {
        $this->foreColor = null;
        $this->colorGenerator = $colorGenerator;
        return $this;
    }

    /**
     * @return \SixtyNine\Cloud\Color\ColorGeneratorInterface
     */
    public function getColorGenerator()
    {
        return $this->colorGenerator;
    }

    /**
     * @param \SixtyNine\Cloud\Color\Color $foreColor
     * @return CloudStyle
     */
    public function setForeColor($foreColor)
    {
        $this->foreColor = $foreColor;
        return $this;
    }

    /**
     * @return \SixtyNine\Cloud\Color\Color
     */
    public function getForeColor()
    {
        if (!$this->foreColor && $this->colorGenerator) {
            return $this->colorGenerator->getNextColor();
        }
        return $this->foreColor;
    }

    /**
     * @return \SixtyNine\Cloud\Font\FontInterface
     */
    public function getFont()
    {
        return $this->font;
    }
}

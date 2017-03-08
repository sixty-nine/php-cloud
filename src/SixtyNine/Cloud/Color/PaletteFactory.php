<?php

namespace SixtyNine\Cloud\Color;

use Colors\RandomColor;
use SixtyNine\Cloud\Config\Config;

/**
 * Generate color palettes (arrays of colors)
 */
class PaletteFactory
{
    /** @var array */
    protected $palettes = array();

    /**
     * @param Config $config
     */
    public function __construct(Config $config = null)
    {
        if ($config) {
            $this->palettes = $config->get('palettes');
        }
    }

    /**
     * Construct a random color palette
     * @param integer $count The number of colors in the palette
     * @return array
     */
    public function getFullRandomPalette($count = 5)
    {
        $palette = array();
        for ($i = 0; $i < $count; $i++) {
            $color = new Color();
            $color->setRgb(rand(0, 255), rand(0, 255), rand(0, 255));
            $palette[] = $color;
        }
        return $palette;
    }

    /**
     * Construct a color palette from a list of hexadecimal colors (RRGGBB)
     * @param array $hexArray An array of hexadecimal color strings
     * @throws \Exception
     * @return array
     */
    public function getPaletteFromHex($hexArray)
    {
        $palette = array();

        foreach ($hexArray as $hex) {
            $color = new Color();

            if (substr($hex, 0, 1) === '#') {
                $hex = substr($hex, 1);
            }

            $color->setHex($hex);

            $palette[] = $color;
        }

        return $palette;
    }

    public function getNamedPalette($name)
    {
        if (array_key_exists($name, $this->palettes)) {
            return self::getPaletteFromHex($this->palettes[$name]);
        }
        return self::getNamedPalette('grey');
    }

    public function listNamedPalettes()
    {
        return array_keys($this->palettes);
    }

    public function getRandomPalette($howMany, $config = array())
    {
        return $this->getPaletteFromHex(RandomColor::many($howMany, $config));
    }
}

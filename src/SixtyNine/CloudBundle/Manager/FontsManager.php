<?php


namespace SixtyNine\CloudBundle\Manager;

use Imagine\Gd\Imagine;
use Imagine\Image\Box;
use Imagine\Image\Color;
use Imagine\Image\Point;
use Imagine\Gd\Font as ImagineFont;
use SixtyNine\CloudBundle\Model\Font;

class FontsManager
{
    /** @var string */
    protected $basePath;

    /** @var array */
    protected $fonts = array();

    /**
     * Matches some font file names to known font names.
     * @var array
     */
    protected $knownFonts = array(
        'Airmole_Antique.ttf' => 'Airmole Antique',
        'Airmole_Shaded.ttf' => 'Airmole Shaded',
        'Alexis_3D.ttf' => 'Alexis 3D',
        'Almonte_Snow.ttf' => 'Almonte Snow',
        'Arial.ttf' => 'Arial',
        'Paper_Cut.ttf' => 'Paper Cut',
        'TheThreeStoogesFont.ttf' => 'The Three Stooges',
        'Marcsc___.ttf' => 'Marcelle',
        'SoulMission.ttf' => 'SoulMission',
        'FAIL.ttf' => 'FAIL',
        'laundromat_1967.ttf' => 'Laundromat 1967',
        'KILLEDDJ.ttf' => 'Killed DJ',
    );

    function __construct($basePath = null)
    {
        $this-> basePath = is_null($basePath)
            ? __DIR__ . '/../Resources/fonts'
            : $basePath
        ;

        foreach (glob($this->basePath . "/*.ttf") as $filename) {
            if (array_key_exists(basename($filename), $this->knownFonts)) {
                $name = $this->knownFonts[basename($filename)];
            } else {
                $name = basename($filename, '.ttf');
            }
            $this->fonts[$name] = new Font($name, realpath($filename));
        }
    }

    public function getFonts()
    {
        return $this->fonts;
    }

    public function getFontsByName()
    {
        $res = array();

        /** @var Font $font */
        foreach ($this->fonts as $font) {
            $res[$font->getName()] = basename($font->getFile());
        }

        return $res;
    }

    public function getFullFontPath($fontFile)
    {
        return $this->basePath . '/' . $fontFile;
    }

    public function preview($name)
    {
        if (!array_key_exists($name, $this->fonts)) {
            throw new \InvalidArgumentException();
        }

        $imagine = new Imagine();

        $image = $imagine->create(
            new Box(800, 75),
            new Color('#000000')
        );

        $image->draw()->text(
            $name,
            new ImagineFont($this->fonts['Arial']->getFile(), 12, new Color('#FF2222')),
            new Point(10, 8)
        );

        $image->draw()->text(
            'The quick brown fox jumps over the lazy dog',
            new ImagineFont($this->fonts[$name]->getFile(), 24, new Color('#FFFFFF')),
            new Point(10, 30)
        );

        return $image;
    }
}

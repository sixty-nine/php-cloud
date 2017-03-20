<?php


namespace SixtyNine\CloudBundle\Manager;


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
            $this->fonts[] = new Font($name, realpath($filename));
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
}

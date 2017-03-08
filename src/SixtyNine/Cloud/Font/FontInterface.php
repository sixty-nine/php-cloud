<?php

namespace SixtyNine\Cloud\Font;

use SixtyNine\Cloud\Model\Box;
use SixtyNine\Cloud\Model\Text;

/**
 * Embed a font in the Cloud system.
 */
interface FontInterface
{
    /**
     * Get the file to the font.
     * @return string
     */
    function getFile();

    /**
     * Calculate the size of the given $word depending on the font set.
     * @param Text $word
     * @return Box
     */
    function getBoundingBox(Text $word);
}

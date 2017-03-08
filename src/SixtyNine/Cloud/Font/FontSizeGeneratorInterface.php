<?php

namespace SixtyNine\Cloud\Font;

use SixtyNine\Cloud\Model\Word;
use SixtyNine\Cloud\Storage\RecordableInterface;

/**
 * Generate the size of words.
 */
interface FontSizeGeneratorInterface extends RecordableInterface
{
    /**
     * Generate the size of the given $word.
     * @param Word $word
     * @return int
     */
    function calculateFontSize(Word $word);
}

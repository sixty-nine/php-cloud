<?php
namespace SixtyNine\Cloud\Color;

use SixtyNine\Cloud\Storage\RecordableInterface;

interface ColorGeneratorInterface
{
    /**
     * @return Color
     */
    function getNextColor();

    /**
     * @return array
     */
    function getPalette();
}
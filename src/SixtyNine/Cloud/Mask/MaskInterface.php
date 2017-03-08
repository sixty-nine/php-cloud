<?php
namespace SixtyNine\Cloud\Mask;

use SixtyNine\Cloud\Model\Box;


/**
 * List of already placed boxes used to search a free space for a new box.
 */
interface MaskInterface
{
    /**
     * Add a new box to the mask.
     * @param Box $box The new box to add
     */
    function add(Box $box);

    /**
     * Test whether a box overlaps with the already drawn boxes.
     * @param Box $testBox The box to test
     * @return boolean True if the box overlaps with the already drawn boxes and false otherwise
     */
    function overlaps(Box $testBox);

    /**
     * Get the box enclosing all the rendered blocks.
     * @return Box
     */
    function getEnclosingBox();
}
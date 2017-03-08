<?php

namespace SixtyNine\Cloud\Usher;

use SixtyNine\Cloud\Model\Vector;
use SixtyNine\Cloud\Model\Text;
use SixtyNine\Cloud\Storage\RecordableInterface;

/**
 * Responsible to find a place for the word in the cloud
 */
interface UsherInterface extends RecordableInterface
{
    /**
     * @param \SixtyNine\Cloud\Model\Text $word
     * @return Vector
     */
    function getPlace(Text $word);

    /**
     * @return void
     */
    function resetMask();

    /**
     * @return Vector
     */
    function getFirstPlaceToTry();

    /**
     * @param Vector $current
     * @return Vector
     */
    function getNextPlaceToTry(Vector $current);
}

<?php

namespace SixtyNine\Cloud\Usher;

use SixtyNine\Cloud\Mask\Mask;
use SixtyNine\Cloud\Storage\Recordable;

/**
 * Responsible to find a place for the word in the cloud
 */
abstract class AbstractUsher implements UsherInterface
{
    /** @var \SixtyNine\Cloud\Mask\MaskInterface  */
    protected $mask;
    /** @var int */
    protected $imgWidth;
    /** @var int */
    protected $imgHeight;

    public function __construct($imgWidth, $imgHeight)
    {
        $this->resetMask();
        $this->imgHeight = $imgHeight;
        $this->imgWidth = $imgWidth;
    }

    public function resetMask()
    {
        $this->mask = new Mask();
    }

    /**
     * @return array
     */
    public function getParamsArray()
    {
        return array();
    }

    /**
     * @param array $params
     * @return UsherInterface
     */
    static public function fromParamsArray(array $params)
    {
        Recordable::requireArrayKeysExist(array('imgWidth', 'imgHeight'), $params);
        return new static($params['imgWidth'], $params['imgHeight']);
    }
}

<?php

namespace SixtyNine\Cloud\Font;

use SixtyNine\Cloud\Model\Word;
use SixtyNine\Cloud\Storage\Recordable;
use SixtyNine\Cloud\Storage\RecordableInterface;

/**
 * Contributed by @jaskra and @mrahmadt
 */
class DefaultFontSizeGenerator implements FontSizeGeneratorInterface
{
    /** @var int */
    protected $minFontSize;
    /** @var int */
    protected $maxFontSize;
    /** @var int */
    protected $minCount = PHP_INT_MAX;
    /** @var int */
    protected $maxCount = 0;

    /**
     * @param int $minFontSize Minimal font size
     * @param int $maxFontSize Maximal font size
     */
    public function __construct($minFontSize, $maxFontSize)
    {
        $this->minFontSize = $minFontSize;
        $this->maxFontSize = $maxFontSize;
    }

    /** {@inheritdoc} */
    public function calculateFontSize(Word $word)
    {
        if ($word->getCount() > $this->maxCount) {
            $this->maxCount = $word->getCount();
        }
        if ($word->getCount() < $this->minCount) {
            $this->minCount = $word->getCount();
        }
        $diffCount = ($this->maxCount - $this->minCount) != 0 ? ($this->maxCount - $this->minCount) : 1;
        $diffSize = ($this->maxFontSize - $this->minFontSize) != 0 ? ($this->maxFontSize - $this->minFontSize) : 1;
        $slope = $diffSize / $diffCount;
        $yIntercept = $this->maxFontSize - ($slope * $this->maxCount);

        $font_size = (integer)($slope * $word->getCount() + $yIntercept);

        if ($font_size < $this->minFontSize) {
            $font_size = $this->minFontSize;
        } elseif ($font_size > $this->maxFontSize) {
            $font_size = $this->maxFontSize;
        }
        
        return $font_size;
    }

    /**
     * @return array
     */
    function getParamsArray()
    {
        return array('minSize' => $this->minFontSize, 'maxSize' => $this->maxFontSize);
    }

    /**
     * @param array $params
     * @return mixed
     */
    static function fromParamsArray(array $params)
    {
        Recordable::requireArrayKeysExist(array('minSize', 'maxSize'), $params);
        return new DefaultFontSizeGenerator($params['minSize'], $params['maxSize']);
    }
}

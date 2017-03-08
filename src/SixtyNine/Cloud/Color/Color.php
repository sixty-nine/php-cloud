<?php

namespace SixtyNine\Cloud\Color;

class Color
{
    /** @var string */
    protected $hex = '000000';

    /** @var array */
    protected $rgb = array(0, 0, 0);

    /**
     * @return string
     */
    public function getHex()
    {
        return $this->hex;
    }

    /**
     * @param string $hex
     * @return Color
     */
    public function setHex($hex)
    {
        $this->hex = $hex;
        $this->rgb =  array(
            hexdec(substr($hex, 0, 2)),
            hexdec(substr($hex, 2, 2)),
            hexdec(substr($hex, 4, 2))
        );
        return $this;
    }

    /**
     * @return array
     */
    public function getRgb()
    {
        return $this->rgb;
    }

    /**
     * @param int $r
     * @param int $g
     * @param int $b
     * @return Color
     */
    public function setRgb($r, $g, $b)
    {
        $this->rgb = array($r, $g, $b);
        $this->hex = '';
        foreach (array(dechex($r), dechex($g), dechex($b)) as $part) {
            $this->hex .= strlen($part) >= 2 ? $part : '0' . $part;
        }
        return $this;
    }

    /**
     * @return string
     */
    public function getHtml()
    {
        return sprintf('#%s', $this->getHex());
    }
}

<?php

namespace SixtyNine\Cloud\Renderer;

use SixtyNine\Cloud\Color\Color;
use SixtyNine\Cloud\Model\Text;
use SixtyNine\Cloud\Model\TextList;
use SixtyNine\Cloud\Usher\UsherInterface;

class Renderer
{
    /**
     * @param $imgWidth
     * @param $imgHeight
     * @return Image
     */
    public function createImage($imgWidth, $imgHeight)
    {
        $image = new Image($imgWidth, $imgHeight);
        return $image;
    }

    /**
     * Render the TextList in an image with dimensions $imgWith, $imageHeight
     * If $drawBB is set, the text bounding box is also dawn
     *
     * @param Image $image
     * @param TextList $words
     * @param bool $drawBB
     * @return Image
     */
    public function render(Image $image, TextList $words, $drawBB = false)
    {
        $style = $words->getStyle();
        $image->fillBackground($style->getBackgroundColor());

        if ($style->getColorGenerator()) {
            $colors = $style->getColorGenerator()->getPalette();
            foreach ($colors as $color) {
                $image->allocateColor($color);
            }
        } else {
            /** @var Text $text */
            foreach ($words as $text) {
                $image->allocateColor($text->getWord()->getStyle()->getColor());
            }
        }

        // Draw the words
        foreach($words as $text) {
            if ($text->getVisible()) {
                $color = $style->getColorGenerator()
                    ? $style->getColorGenerator()->getNextColor()
                    : $text->getWord()->getStyle()->getColor()
                ;
                $image->drawText($text, $style->getFont(), $color, $drawBB);
            }
        }
//
//        // Crop the image
//        $image = $this->cropImage($image, $x1, $y1, $x2, $y2, $bgcol);
//
//        // Adjust the map to the cropped image
//        $cloud->getMask()->adjust(-$x1, -$y1);

        return $image;
    }

    /**
     * Draw the search path of the given $usher to the $image.
     * @param Image $image
     * @param UsherInterface $usher
     * @param \SixtyNine\Cloud\Color\Color $color
     * @param int $maxIterations
     */
    public function drawUsher(Image $image, UsherInterface $usher, Color $color, $maxIterations = 100)
    {
        $i = 0;
        $cur = $usher->getFirstPlaceToTry();

        while($cur) {

            $next = $usher->getNextPlaceToTry($cur);

            if ($next) {
                $image->drawLine($cur, $next, $color);
            }

            $i++;
            $cur = $next;

            if ($i >= $maxIterations) {
                break;
            }
        }
    }
}

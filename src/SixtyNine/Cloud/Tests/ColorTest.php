<?php


namespace SixtyNine\Cloud\Tests;


use SixtyNine\Cloud\Color\Color;
use SixtyNine\Cloud\Color\PaletteFactory;

class ColorTest extends \PHPUnit_Framework_TestCase
{
    public function testColorSetters()
    {
        $color = new Color();
        $this->assertEquals('000000', $color->getHex());
        $this->assertEquals('#000000', $color->getHtml());
        $this->assertEquals(array(0, 0, 0), $color->getRgb());

        $color->setHex('FFFFFF');
        $this->assertEquals('FFFFFF', $color->getHex());
        $this->assertEquals('#FFFFFF', $color->getHtml());
        $this->assertEquals(array(255, 255, 255), $color->getRgb());

        $color->setRgb(127, 127, 127);
        $this->assertEquals('7f7f7f', $color->getHex());
        $this->assertEquals('#7f7f7f', $color->getHtml());
        $this->assertEquals(array(127, 127, 127), $color->getRgb());

        $color->setRgb(0, 0, 0);
        $this->assertEquals('000000', $color->getHex());
        $this->assertEquals('#000000', $color->getHtml());
        $this->assertEquals(array(0, 0, 0), $color->getRgb());
    }

    public function testRandomPalette()
    {
        $palette = new PaletteFactory();
        $colors = $palette->getRandomPalette(3, array(
            'luminosity' => 'bright',
            'hue' => array('purple'),
        ));

        $this->assertCount(3, $colors);

        foreach ($colors as $color) {
            $this->assertInstanceOf('\SixtyNine\Cloud\Color\Color', $color);
        }
    }
}
 
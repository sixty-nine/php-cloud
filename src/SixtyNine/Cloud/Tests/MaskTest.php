<?php


namespace SixtyNine\Cloud\Tests;


use SixtyNine\Cloud\Model\Vector;
use SixtyNine\Cloud\Mask\Mask;
use SixtyNine\Cloud\Model\Box;

class MaskTest extends \PHPUnit_Framework_TestCase
{
    public function testMask()
    {
        $mask = new Mask();
        $this->assertEquals(new Vector(), $mask->getEnclosingBox()->getPosition());
        $this->assertEquals(new Vector(), $mask->getEnclosingBox()->getSize());

        $mask->add(new Box(new Vector(10, 10), new Vector(5, 5)));
        $this->assertEquals(new Vector(10, 10), $mask->getEnclosingBox()->getPosition());
        $this->assertEquals(new Vector(5, 5), $mask->getEnclosingBox()->getSize());

        $mask->add(new Box(new Vector(5, 5), new Vector(5, 5)));
        $this->assertEquals(new Vector(5, 5), $mask->getEnclosingBox()->getPosition());
        $this->assertEquals(new Vector(10, 10), $mask->getEnclosingBox()->getSize());

        $mask->add(new Box(new Vector(50, 50), new Vector(10, 10)));
        $this->assertEquals(new Vector(5, 5), $mask->getEnclosingBox()->getPosition());
        $this->assertEquals(new Vector(55, 55), $mask->getEnclosingBox()->getSize());
    }

    public function testPerformance()
    {
        $mask = new Mask();

        for ($i = 1; $i <= 1000; $i++) {
            $mask->add(new Box(new Vector($i * 10, $i), new Vector(10, 100)));
        }

        $this->assertFalse($mask->overlaps(
            new Box(new Vector(50, 150), new Vector(10, 10))
        ));
        $this->assertTrue($mask->overlaps(
            new Box(new Vector(50, 50), new Vector(10, 10))
        ));
    }
}
 
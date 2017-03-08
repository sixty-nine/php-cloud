<?php

namespace SixtyNine\Cloud\Tests;

use SixtyNine\Cloud\Model\Box;
use SixtyNine\Cloud\Model\Vector;

class BoxTest extends \PHPUnit_Framework_TestCase
{
    public function testBoxConstructor()
    {
        $box = new Box();
        $this->assertEquals(new Vector(), $box->getPosition());
        $this->assertEquals(new Vector(), $box->getSize());

        $pos =new Vector(10, 100);
        $size = new Vector(50, 20);
        $box = new Box($pos, $size);
        $this->assertEquals($pos, $box->getPosition());
        $this->assertEquals($size, $box->getSize());

        $this->assertEquals(10, $box->getX());
        $this->assertEquals(100, $box->getY());

        $this->assertEquals(100, $box->getTop());
        $this->assertEquals(120, $box->getBottom());
        $this->assertEquals(10, $box->getLeft());
        $this->assertEquals(60, $box->getRight());

        $this->assertEquals($pos, $box->getTopLeftPoint());
        $this->assertEquals($pos->move($size->x, 0), $box->getTopRightPoint());
        $this->assertEquals($pos->move(0, $size->y), $box->getBottomLeftPoint());
        $this->assertEquals($pos->move($size->x, $size->y), $box->getBottomRightPoint());
    }

    public function testFromBoundingBox()
    {
        $box = new Box();

        $box->fromBoundingBox(10, 100, array(5, 10, 25, 10, 5, 60, 25, 60));
        $this->assertEquals(new Vector(15, 110), $box->getPosition());
        $this->assertEquals(new Vector(20, 50), $box->getSize());

        $box->fromBoundingBox(10, 100, array(5, 10, 5, 60, 25, 60, 25, 10));
        $this->assertEquals(new Vector(15, 110), $box->getPosition());
        $this->assertEquals(new Vector(20, 50), $box->getSize());

        $box->fromBoundingBox(0, 0, array(5, 10, 5, 60, 25, 60, 25, 10));
        $this->assertEquals(new Vector(5, 10), $box->getPosition());
        $this->assertEquals(new Vector(20, 50), $box->getSize());
    }

    public function testIntersect()
    {
        $box1 = new Box(null, new Vector(10, 10));

        $this->assertTrue($box1->intersects(new Box(null, new Vector(10, 10))));
        $this->assertTrue($box1->intersects(new Box(new Vector(5, 5), new Vector(10, 10))));
        $this->assertTrue($box1->intersects(new Box(new Vector(10, 10), new Vector(10, 10))));
        $this->assertFalse($box1->intersects(new Box(new Vector(15, 15), new Vector(10, 10))));

        $this->assertTrue($box1->intersects(new Box(new Vector(5, 5), new Vector(10, 2))));
        $this->assertFalse($box1->intersects(new Box(new Vector(5, 15), new Vector(10, 2))));
        $this->assertFalse($box1->intersects(new Box(new Vector(15, 5), new Vector(10, 2))));
    }

    public function testIsInside()
    {
        $box1 = new Box(null, new Vector(100, 100));
        $box2 = new Box(new Vector(50, 20), new Vector(10, 10));
        $box3 = new Box(new Vector(30, 10), new Vector(100, 100));

        $this->assertTrue($box2->isInside($box1));
        $this->assertFalse($box1->isInside($box2));
        $this->assertFalse($box3->isInside($box1));
        $this->assertFalse($box1->isInside($box3));
        $this->assertTrue($box2->isInside($box3));
        $this->assertFalse($box3->isInside($box2));

    }
}

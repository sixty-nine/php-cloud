<?php

namespace SixtyNine\Cloud\Model;

/**
 * An axis-aligned rectangle with collision detection
 */
class Box
{
    /** @var Vector */
    protected $position;

    /** @var Vector */
    protected $size;

    /**
     * Construct a Box from a $position and a $size
     * @param Vector $position
     * @param Vector $size
     */
    public function __construct(Vector $position = null, Vector $size = null)
    {
        $this->position = $position ? $position : new Vector();
        $this->size = $size ? $size : new Vector();
    }

    /** @return Vector */
    public function getPosition()
    {
        return $this->position;
    }

    /** @return Vector */
    public function getSize()
    {
        return $this->size;
    }

    /** @return int */
    public function getX()
    {
        return $this->position->x;
    }

    /** @return int */
    public function getY()
    {
        return $this->position->y;
    }

    /** @return int */
    public function getWidth()
    {
        return $this->size->x;
    }

    /** @return int */
    public function getHeight()
    {
        return $this->size->y;
    }

    /** @return int */
    public function getLeft()
    {
        return $this->position->x;
    }

    /** @return int */
    public function getRight()
    {
        return $this->position->x + $this->size->x;
    }

    /** @return int */
    public function getTop()
    {
        // TODO: is it really the top? i.e. drawing to bottom of the screen increases y?
        return $this->position->y;
    }
    /** @return int */
    public function getBottom()
    {
        return $this->position->y + $this->size->y;
    }

    /** @return Vector */
    public function getTopLeftPoint()
    {
        return $this->position;
    }

    /** @return Vector */
    public function getTopRightPoint()
    {
        return $this->position->move($this->size->x, 0);
    }

    /** @return Vector */
    public function getBottomLeftPoint()
    {
        return $this->position->move(0, $this->size->y);
    }

    /** @return Vector */
    public function getBottomRightPoint()
    {
        return $this->position->move($this->size->x, $this->size->y);
    }

    /**
     * Construct a new rectangle from a point and a bounding box
     * @param int $x The point x coordinate
     * @param int $y The point x coordinate
     * @param array $bb The bounding box given in an array of 8 coordinates
     */
    public function fromBoundingBox($x, $y, $bb)
    {
        $x1 = $bb[0];
        $y1 = $bb[1];
        $x2 = $bb[2];
        $y2 = $bb[3];
        $x3 = $bb[4];
        $y3 = $bb[5];
        $x4 = $bb[6];
        $y4 = $bb[7];

        $this->position = new Vector(
            min($x1, $x2, $x3, $x4),
            min($y1, $y2, $y3, $y4)
        );
        $this->size = new Vector(
            max($x1, $x2, $x3, $x4) - $this->position->x,
            max($y1, $y2, $y3, $y4) - $this->position->y
        );
        $this->position = $this->position->move($x, $y);
    }

    public function toArray()
    {
        return array(
            $this->position->x, $this->position->y,
            $this->size->x, $this->size->y
        );
    }

    /**
     * Detect box collision
     * This algorithm only works with Axis-Aligned boxes!
     * @param Box $box The other rectangle to test collision with
     * @return boolean True is the boxes collide, false otherwise
     */
    function intersects(Box $box)
    {
        if ($this->getBottom() < $box->getTop()) {
            return false;
        }
        if ($this->getTop() > $box->getBottom()) {
            return false;
        }
        if ($this->getRight() < $box->getLeft()) {
            return false;
        }
        if ($this->getLeft() > $box->getRight()) {
            return false;
        }

        return true;
    }

    /**
     * @param Box $other
     * @return bool
     */
    public function isInside(Box $other)
    {
        return ($this->position->x >= $other->position->x)
            && ($this->getBottomRightPoint()->x <= $other->getBottomRightPoint()->x)
            && ($this->position->y >= $other->position->y)
            && ($this->getBottomRightPoint()->y <= $other->getBottomRightPoint()->y)
        ;
    }
    public function move($dx, $dy)
    {
        $this->position = $this->position->move($dx, $dy);
    }

    /**
     * @param Vector $pos
     */
    public function moveTo(Vector $pos)
    {
        $this->position = $pos;
    }

    public function __toString()
    {
        return sprintf('%s-%s', $this->position, $this->size);
    }
}


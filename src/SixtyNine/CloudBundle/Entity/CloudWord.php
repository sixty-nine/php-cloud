<?php

namespace SixtyNine\CloudBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CloudWord
 *
 * @ORM\Table(name="cloud_word")
 * @ORM\Entity(repositoryClass="SixtyNine\CloudBundle\Repository\CloudWordRepository")
 */
class CloudWord
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var int
     *
     * @ORM\Column(name="size", type="integer")
     */
    protected $size;

    /**
     * @var int
     * @ORM\Column(type="integer")
     */
    protected $angle;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    protected $color;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    protected $text;

    /**
     * @var array
     * @ORM\Column(type="array")
     */
    protected $position;

    /**
     * @var array
     * @ORM\Column(type="array")
     */
    protected $box;

    /**
     * @var bool
     * @ORM\Column(name="is_visible", type="boolean")
     */
    protected $isVisible;

    /**
     * @var Cloud
     * @ORM\ManyToOne(targetEntity="Cloud", inversedBy="words")
     * @ORM\JoinColumn(name="cloud_id", referencedColumnName="id", nullable=false)
     */
    protected $cloud;

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set size
     *
     * @param integer $size
     *
     * @return CloudWord
     */
    public function setSize($size)
    {
        $this->size = $size;

        return $this;
    }

    /**
     * Get size
     *
     * @return int
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * @param \SixtyNine\CloudBundle\Entity\Cloud $cloud
     * @return $this
     */
    public function setCloud($cloud)
    {
        $this->cloud = $cloud;
        return $this;
    }

    /**
     * @return \SixtyNine\CloudBundle\Entity\Cloud
     */
    public function getCloud()
    {
        return $this->cloud;
    }

    /**
     * @param array $position
     * @return $this
     */
    public function setPosition($position)
    {
        $this->position = $position;
        return $this;
    }

    /**
     * @return array
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * @param boolean $isVisible
     * @return $this
     */
    public function setIsVisible($isVisible)
    {
        $this->isVisible = $isVisible;
        return $this;
    }

    /**
     * @return boolean
     */
    public function getIsVisible()
    {
        return $this->isVisible;
    }

    /**
     * @param int $angle
     * @return $this
     */
    public function setAngle($angle)
    {
        $this->angle = $angle;
        return $this;
    }

    /**
     * @return int
     */
    public function getAngle()
    {
        return $this->angle;
    }

    /**
     * @param string $color
     * @return $this
     */
    public function setColor($color)
    {
        $this->color = $color;
        return $this;
    }

    /**
     * @return string
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * @param string $text
     * @return $this
     */
    public function setText($text)
    {
        $this->text = $text;
        return $this;
    }

    /**
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param array $box
     * @return $this
     */
    public function setBox($box)
    {
        $this->box = $box;
        return $this;
    }

    /**
     * @return array
     */
    public function getBox()
    {
        return $this->box;
    }
}


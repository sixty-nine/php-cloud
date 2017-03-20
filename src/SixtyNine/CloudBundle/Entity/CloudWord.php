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
     * @ORM\Column(name="size", type="integer", nullable=true)
     */
    protected $size;

    /**
     * @var array
     * @ORM\Column(type="array", nullable=true)
     */
    protected $position;

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
     * @var Word
     * @ORM\ManyToOne(targetEntity="Word")
     * @ORM\JoinColumn(name="word_id", referencedColumnName="id", nullable=false)
     */
    protected $word;

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
     * @param \SixtyNine\CloudBundle\Entity\Word $word
     * @return $this
     */
    public function setWord($word)
    {
        $this->word = $word;
        return $this;
    }

    /**
     * @return \SixtyNine\CloudBundle\Entity\Word
     */
    public function getWord()
    {
        return $this->word;
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
}


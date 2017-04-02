<?php

namespace SixtyNine\CloudBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Blameable\Traits\BlameableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;

class Cloud
{
    use TimestampableEntity;
    use BlameableEntity;

    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $backgroundColor;

    /**
     * @var int
     */
    protected $width;

    /**
     * @var int
     */
    protected $height;

    /**
     * @var string
     */
    protected $font;

    /**
     * @var string
     */
    protected $placer;

    /**
     * @var Account
     */
    protected $user;

    /**
     * @var WordsList
     */
    protected $list;

    /**
     * @var ArrayCollection
     */
    protected $words;

    function __construct()
    {
        $this->words = new ArrayCollection();
    }

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
     * @param \SixtyNine\CloudBundle\Entity\Account $user
     * @return $this
     */
    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return \SixtyNine\CloudBundle\Entity\Account
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param string $backgroundColor
     * @return $this
     */
    public function setBackgroundColor($backgroundColor)
    {
        $this->backgroundColor = $backgroundColor;
        return $this;
    }

    /**
     * @return string
     */
    public function getBackgroundColor()
    {
        return $this->backgroundColor;
    }

    /**
     * @param string $font
     * @return $this
     */
    public function setFont($font)
    {
        $this->font = $font;
        return $this;
    }

    /**
     * @return string
     */
    public function getFont()
    {
        return $this->font;
    }

    /**
     * @param \SixtyNine\CloudBundle\Entity\WordsList $list
     * @return $this
     */
    public function setList($list)
    {
        $this->list = $list;
        return $this;
    }

    /**
     * @return \SixtyNine\CloudBundle\Entity\WordsList
     */
    public function getList()
    {
        return $this->list;
    }

    /**
     * @param CloudWord $word
     * @return $this
     */
    public function addWord(CloudWord $word)
    {
        if (!$this->words->contains($word)) {
            $this->words->add($word);
        }
        return $this;
    }

    /**
     * @param CloudWord $word
     * @return $this
     */
    public function removeWord(CloudWord $word)
    {
        $this->words->remove($word);
        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getWords()
    {
        return $this->words;
    }

    /**
     * @param int $height
     * @return $this
     */
    public function setHeight($height)
    {
        $this->height = $height;
        return $this;
    }

    /**
     * @return int
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * @param int $width
     * @return $this
     */
    public function setWidth($width)
    {
        $this->width = $width;
        return $this;
    }

    /**
     * @return int
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * @param string $placer
     * @return $this
     */
    public function setPlacer($placer)
    {
        $this->placer = $placer;
        return $this;
    }

    /**
     * @return string
     */
    public function getPlacer()
    {
        return $this->placer;
    }
}


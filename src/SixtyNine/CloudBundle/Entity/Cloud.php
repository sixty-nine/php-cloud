<?php

namespace SixtyNine\CloudBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Blameable\Traits\BlameableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use SixtyNine\Cloud\Model\Cloud as BaseCloud;

class Cloud extends BaseCloud
{
    use TimestampableEntity;
    use BlameableEntity;

    /** @var int */
    protected $id;

    /** @var string */
    protected $placer;

    /** @var string */
    protected $fontSizeGenerator;

    /** @var int */
    protected $minFontSize;

    /** @var int */
    protected $maxFontSize;

    /** @var Account */
    protected $user;

    /**
     * @var WordsList
     */
    protected $list;

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

    /**
     * @param string $fontSizeGenerator
     * @return $this
     */
    public function setFontSizeGenerator($fontSizeGenerator)
    {
        $this->fontSizeGenerator = $fontSizeGenerator;
        return $this;
    }

    /**
     * @return string
     */
    public function getFontSizeGenerator()
    {
        return $this->fontSizeGenerator;
    }

    /**
     * @param int $maxFontSize
     * @return $this
     */
    public function setMaxFontSize($maxFontSize)
    {
        $this->maxFontSize = $maxFontSize;
        return $this;
    }

    /**
     * @return int
     */
    public function getMaxFontSize()
    {
        return $this->maxFontSize;
    }

    /**
     * @param int $minFontSize
     * @return $this
     */
    public function setMinFontSize($minFontSize)
    {
        $this->minFontSize = $minFontSize;
        return $this;
    }

    /**
     * @return int
     */
    public function getMinFontSize()
    {
        return $this->minFontSize;
    }
}


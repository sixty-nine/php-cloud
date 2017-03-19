<?php

namespace SixtyNine\CloudBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Blameable\Traits\BlameableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * Cloud
 *
 * @ORM\Table(name="cloud")
 * @ORM\Entity(repositoryClass="SixtyNine\CloudBundle\Repository\CloudRepository")
 */
class Cloud
{
    use TimestampableEntity;
    use BlameableEntity;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    protected $name;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    protected $backgroundColor;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    protected $font;

    /**
     * @var Account
     * @ORM\ManyToOne(targetEntity="Account")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
     */
    protected $user;

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
     * @param string $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
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
}


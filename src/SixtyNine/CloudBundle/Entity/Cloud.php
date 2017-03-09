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
     * @var Account
     * @ORM\ManyToOne(targetEntity="Account")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
     */
    protected $user;

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="Word", mappedBy="cloud")
     */
    protected $words;

    /**
     * Constructor
     */
    public function __construct()
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
     * Check if a Word with the same text already exists in the Cloud
     * @param string $text
     * @return null|Word
     */
    public function getWordForText($text)
    {
        /** @var Word $word */
        foreach ($this->words as $word) {
            if ($word->getText() === $text) {
                return $word;
            }
        }
        return null;
    }

    /**
     * @param Word $word
     * @return $this
     */
    public function addWord(Word $word)
    {
        if ($this->words->contains($word)) {
            $this->words->add($word);
        }


        return $this;
    }

    /**
     * @param Word $word
     * @return $this
     */
    public function removeWord(Word $word)
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
}


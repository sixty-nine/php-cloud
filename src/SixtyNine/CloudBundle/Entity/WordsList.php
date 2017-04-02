<?php

namespace SixtyNine\CloudBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Blameable\Traits\BlameableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use JMS\Serializer\Annotation as JMS;

/**
 * @JMS\ExclusionPolicy("all")
 */
class WordsList
{
    use TimestampableEntity;
    use BlameableEntity;

    /**
     * @var int
     * @JMS\Expose
     */
    private $id;

    /**
     * @var string
     * @JMS\Expose
     */
    protected $name;

    /**
     * @var Account
     */
    protected $user;

    /**
     * @var ArrayCollection
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
        if (!$this->words->contains($word)) {
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

    /**
     * @return int
     * @JMS\VirtualProperty
     * @JMS\SerializedName("count")
     */
    public function getWordsCount()
    {
        return $this->words->count();
    }
}


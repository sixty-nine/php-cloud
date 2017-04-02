<?php

namespace SixtyNine\CloudBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Blameable\Traits\BlameableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use JMS\Serializer\Annotation as JMS;

/**
 * @JMS\ExclusionPolicy("all")
 */
class WordsList extends \SixtyNine\Cloud\Model\WordsList
{
    use TimestampableEntity;
    use BlameableEntity;

    /**
     * @var int
     * @JMS\Expose
     */
    private $id;

    /**
     * @var Account
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
}

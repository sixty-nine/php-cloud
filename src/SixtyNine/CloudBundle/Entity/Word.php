<?php

namespace SixtyNine\CloudBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Blameable\Traits\BlameableEntity;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use JMS\Serializer\Annotation as JMS;

/**
 * @JMS\ExclusionPolicy("all")
 */
class Word extends \SixtyNine\Cloud\Model\Word
{
    use TimestampableEntity;
    use BlameableEntity;

    /**
     * @var int
     * @ORM\GeneratedValue(strategy="AUTO")
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


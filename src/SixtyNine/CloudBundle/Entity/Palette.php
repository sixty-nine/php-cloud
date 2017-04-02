<?php

namespace SixtyNine\CloudBundle\Entity;

use Gedmo\Blameable\Traits\BlameableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;

class Palette extends \SixtyNine\Cloud\Model\Palette
{
    use TimestampableEntity;
    use BlameableEntity;

    /**
     * @var int
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


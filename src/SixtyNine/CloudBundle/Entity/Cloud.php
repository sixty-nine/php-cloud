<?php

namespace SixtyNine\CloudBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Blameable\Traits\BlameableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;

class Cloud extends \SixtyNine\Cloud\Model\Cloud
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
}


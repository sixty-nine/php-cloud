<?php

namespace SixtyNine\CloudBundle\Entity;

class CloudWord extends \SixtyNine\Cloud\Model\CloudWord
{
    /**
     * @var int
     */
    protected $id;

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
}


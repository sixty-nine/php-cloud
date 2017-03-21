<?php

namespace SixtyNine\CloudBundle\Repository;

use SixtyNine\CloudBundle\Entity\Account;
use SixtyNine\CloudBundle\Entity\Cloud;

/**
 * CloudRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CloudRepository extends \Doctrine\ORM\EntityRepository
{
    /**
     * @param Account $user
     * @param string $cloudName
     * @return Cloud
     */
    public function createCloud(Account $user, $cloudName)
    {
        $cloud = new Cloud();
        $cloud
            ->setName($cloudName)
            ->setUser($user)
        ;

        $this->_em->persist($cloud);
        $this->_em->flush($cloud);

        return $cloud;
    }

    public function deleteCloud(Cloud $cloud)
    {
        $this->_em->remove($cloud);
        $this->_em->flush();
    }

    public function deleteWords(Cloud $cloud)
    {
        foreach ($cloud->getWords() as $word) {
            $this->_em->remove($word);
        }

        $this->_em->flush();
    }
}


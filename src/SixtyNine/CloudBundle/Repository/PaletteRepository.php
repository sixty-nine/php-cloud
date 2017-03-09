<?php

namespace SixtyNine\CloudBundle\Repository;
use SixtyNine\CloudBundle\Entity\Account;
use SixtyNine\CloudBundle\Entity\Palette;

/**
 * PaletteRepository
 */
class PaletteRepository extends \Doctrine\ORM\EntityRepository
{
    public function importPalette($name, array $colors)
    {
        $palette = new Palette();
        $palette->setName($name)->setColors($colors);
        $this->_em->persist($palette);
        $this->_em->flush();
    }

    public function getPalettes(Account $user, $includePublicPalettes = true)
    {
        $qb = $this
            ->createQueryBuilder('p')
            ->orWhere('p.user = :user')
            ->setParameter('user', $user)
        ;

        if ($includePublicPalettes) {
            $qb->orWhere('p.user IS NULL');
        }

        return $qb->getQuery()->execute();
    }
}

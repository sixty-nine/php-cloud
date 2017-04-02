<?php

namespace SixtyNine\CloudBundle\Repository;

use SixtyNine\Cloud\Builder\PalettesBuilder;
use SixtyNine\Cloud\Color\RandomColorGenerator;
use SixtyNine\Cloud\Color\RotateColorGenerator;
use SixtyNine\CloudBundle\Entity\Account;
use SixtyNine\CloudBundle\Entity\Cloud;
use SixtyNine\CloudBundle\Entity\Palette;
use SixtyNine\CloudBundle\Entity\Word;
use SixtyNine\CloudBundle\Entity\WordsList;

/**
 * WordsListRepository
 */
class WordsListRepository extends \Doctrine\ORM\EntityRepository
{
    /**
     * @param Account $user
     * @param string $listName
     * @return Cloud
     */
    public function createWordsList(Account $user, $listName)
    {
        $list = new WordsList();
        $list
            ->setName($listName)
            ->setUser($user)
        ;

        $this->_em->persist($list);
        $this->_em->flush($list);

        return $list;
    }

    public function deleteWordsList(WordsList $list)
    {
        $this->_em->remove($list);
        $this->_em->flush();
    }

    public function randomizeWordsOrientation(WordsList $list, $verticalProbability = 50)
    {
        $list->randomizeOrientation($verticalProbability);
        $this->_em->flush();
    }

    public function randomizeWordsColors(WordsList $list, Palette $palette, $type)
    {
        $palette = PalettesBuilder::create()->getPalette($palette->getColors());
        $colorGenerator = $type == 'random'
            ? new RandomColorGenerator($palette)
            : new RotateColorGenerator($palette)
        ;

        $list->randomizeColors($colorGenerator);

        $this->_em->flush();
    }

    public function removeWord(Word $word)
    {
        $this->_em->remove($word);
        $this->_em->flush();
    }
}


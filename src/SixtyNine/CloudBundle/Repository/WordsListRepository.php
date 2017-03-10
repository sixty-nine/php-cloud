<?php

namespace SixtyNine\CloudBundle\Repository;

use SixtyNine\Cloud\Color\RandomColorGenerator;
use SixtyNine\Cloud\Color\RotateColorGenerator;
use SixtyNine\Cloud\Model\Text;
use SixtyNine\Cloud\Model\Words;
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

    public function importWords(WordsList $list, Words $words)
    {
        /** @var $word \SixtyNine\Cloud\Model\Word */
        foreach ($words->getWords() as $word) {

            $entity = $list->getWordForText($word->getText());

            if (!$entity) {
                $entity = new Word();
                $entity
                    ->setList($list)
                    ->setText($word->getText())
                    ->setUser($list->getUser())
                ;
                $list->addWord($entity);
            }

            $entity->setCount($entity->getCount() + $word->getCount());

            $this->_em->persist($entity);
            $this->_em->flush();
        }
    }

    public function randomizeWordsOrientation(WordsList $list, $verticalProbability = 50)
    {
        /** @var \SixtyNine\CloudBundle\Entity\Word $word */
        foreach ($list->getWords() as $word) {

            $orientation = random_int(0, 100) <= $verticalProbability
                ? Text::DIR_VERTICAL
                : Text::DIR_HORIZONTAL
            ;

            $word->setOrientation($orientation);
        }

        $this->_em->flush();
    }

    public function randomizeWordsColors(WordsList $list, Palette $palette, $type)
    {

        $colorGenerator = $type == 'random'
            ? new RandomColorGenerator($palette->getColors())
            : new RotateColorGenerator($palette->getColors())
        ;

        /** @var \SixtyNine\CloudBundle\Entity\Word $word */
        foreach ($list->getWords() as $word) {
            $word->setColor('#' . $colorGenerator->getNextColor());
        }

        $this->_em->flush();
    }

    public function removeWord(Word $word)
    {
        $this->_em->remove($word);
        $this->_em->flush();
    }
}


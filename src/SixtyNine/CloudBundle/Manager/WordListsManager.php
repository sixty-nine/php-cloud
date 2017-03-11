<?php

namespace SixtyNine\CloudBundle\Manager;

use Doctrine\ORM\EntityManagerInterface;
use SixtyNine\Cloud\Model\Text;
use SixtyNine\CloudBundle\Entity\Account;
use SixtyNine\CloudBundle\Entity\Word;
use SixtyNine\CloudBundle\Entity\WordsList;
use SixtyNine\CloudBundle\Repository\WordRepository;
use SixtyNine\CloudBundle\Repository\WordsListRepository;

class WordListsManager
{
    /** @var EntityManagerInterface */
    protected $em;

    /** @var WordsListRepository */
    protected $listRepo;

    /** @var WordRepository */
    protected $wordRepo;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->listRepo = $em->getRepository('SixtyNineCloudBundle:WordsList');
        $this->wordRepo = $em->getRepository('SixtyNineCloudBundle:Word');
    }

    /**
     * @param $id
     * @return null|WordsList
     */
    public function getList($id)
    {
        return $this->listRepo->find($id);
    }

    /**
     * @param $id
     * @return null|Word
     */
    public function getWord($id)
    {
        return $this->wordRepo->find($id);
    }

    public function sortWords(WordsList $list, $sortBy, $order)
    {
        $words = $this->wordRepo->getWordsOrdered($list, $sortBy, $order);
        $counter = 1;

        /** @var Word $word */
        foreach ($words as $word) {
            $word->setPosition($counter);
            $counter++;
        }

        $this->em->flush();

        return $words;
    }

    /**
     * @param Account $user
     * @return mixed
     */
    public function getUserLists(Account $user)
    {
        return $this->listRepo->findByUser($user);
    }

    public function deleteList(WordsList $list)
    {
        $this->em->remove($list);
        $this->em->flush();
    }

    public function deleteWord(Word $word)
    {
        $this->em->remove($word);
        $this->em->flush();
    }

    public function toggleWordOrientation(Word $word)
    {
        $word->setOrientation(
            $word->getOrientation() === Text::DIR_VERTICAL
                ? Text::DIR_HORIZONTAL
                : Text::DIR_VERTICAL
        );
        $this->em->flush();
    }

    public function increaseWordCount(Word $word, $count = 1)
    {
        $word->setCount($word->getCount() + $count);
        $this->em->flush();
    }

    public function decreaseWordCount(Word $word, $count = 1)
    {
        if ($word->getCount() > $count) {
            $word->setCount($word->getCount() - $count);
        }

        $this->em->flush();
    }
}

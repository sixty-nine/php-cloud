<?php

namespace SixtyNine\CloudBundle\Manager;

use Doctrine\ORM\EntityManagerInterface;
use SixtyNine\Cloud\Model\Text;
use SixtyNine\CloudBundle\Cloud\Filters\Filters;
use SixtyNine\CloudBundle\Entity\Account;
use SixtyNine\CloudBundle\Entity\Word;
use SixtyNine\CloudBundle\Entity\WordsList;
use SixtyNine\CloudBundle\Repository\WordRepository;
use SixtyNine\CloudBundle\Repository\WordsListRepository;
use SixtyNine\CloudBundle\Cloud\Filters\ChangeCase;
use SixtyNine\CloudBundle\Cloud\Filters\RemoveByLength;
use SixtyNine\CloudBundle\Cloud\Filters\RemoveCharacters;
use SixtyNine\CloudBundle\Cloud\Filters\RemoveNumbers;
use SixtyNine\CloudBundle\Cloud\Filters\RemoveTrailingCharacters;

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

    public function importWord(WordsList $list, $word, Filters $filters = null)
    {
        if ($filters) {
            $word = $filters->filterWord($word);
            if (!$word) {
                return;
            }
        }

        $entity = $list->getWordForText($word);

        if (!$entity) {
            $entity = new Word();
            $entity
                ->setList($list)
                ->setText($word)
                ->setUser($list->getUser())
                ->setOrientation('horiz')
                ->setColor('#000000')
            ;
            $list->addWord($entity);
        }

        $entity->setCount($entity->getCount() + 1);

        $this->em->persist($entity);
        $this->em->flush();
    }

    public function importText(WordsList $list, $text, $maxWords = 100, Filters $filters = null)
    {
        $array = preg_split("/[\n\r\t ]+/", $text);

        foreach (array_slice($array, 0, $maxWords) as $word) {
            $this->importWord($list, $word, $filters);
        }
    }

    public function importHtml(WordsList $list, $html, $maxWords = 100, Filters $filters = null)
    {
        if (!$html) {
            return;
        }

        $d = new \DOMDocument;
        $mock = new \DOMDocument;
        libxml_use_internal_errors(true);
        $d->loadHTML($html);
        libxml_use_internal_errors(false);
        $body = $d->getElementsByTagName('body')->item(0);
        if ($body) {
            foreach ($body->childNodes as $child) {
                $mock->appendChild($mock->importNode($child, true));
            }
        }
        $text = html_entity_decode(strip_tags($mock->saveHTML()));
        $this->importText($list, $text, $maxWords, $filters);
    }

    public function importUrl(WordsList $list, $url, $maxWords = 100, Filters $filters = null)
    {
        $this->importHtml($list, file_get_contents($url), $maxWords, $filters);
    }

    public function filterWords(WordsList $list, Filters $filters)
    {
        /** @var Word $word */
        foreach ($list->getWords() as $word) {
            $filtered = $filters->filterWord($word->getText());
            if (!$filtered) {
                $this->em->remove($word);
            }
            $word->setText($filtered);
        }
        $this->em->flush();
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

    public function createList(Account $user, $name)
    {
        $list = new WordsList();
        $list->setUser($user)->setName($name);
        $this->em->persist($list);
        $this->em->flush();
        return $list;
    }

    public function saveList(WordsList $list, $data)
    {
        $list->setName($data['name']);
        $this->em->flush();
        return $list;
    }

    public function deleteList(WordsList $list)
    {
        $this->em->remove($list);
        $this->em->flush();
    }

    public function saveWord(Word $word, $data)
    {
        $word
            ->setText($data['text'])
            ->setCount($data['count'])
            ->setOrientation($data['orientation'])
            ->setColor($data['color'])
            ->setPosition($data['position'])
        ;

        $this->em->flush();
        return $word;
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

    public function getFiltersFromData($data)
    {
        $filters = new Filters();

        if ($data['changeCaseEnabled']) {
            $filters->addFilter(
                new ChangeCase($data['case'])
            );
        }
        if (array_key_exists('removeNumbersEnabled', $data)) {
            $filters->addFilter(new RemoveNumbers());
        }
        if (array_key_exists('removeUnwantedCharEnabled', $data)) {
            $filters->addFilter(new RemoveCharacters());
        }
        if (array_key_exists('removeTrailingCharEnabled', $data)) {
            $filters->addFilter(new RemoveTrailingCharacters());
        }
        if (array_key_exists('removeByLengthEnabled', $data)) {
            $min = $data['minLength'];
            $max = $data['maxLength'];

            if ($min || $max) {
                $filters->addFilter(new RemoveByLength($min, $max));
            }
        }

        return $filters;
    }
}

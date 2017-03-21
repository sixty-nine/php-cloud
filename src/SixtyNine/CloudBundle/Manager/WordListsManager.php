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

    /**
     * Constructor
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->listRepo = $em->getRepository('SixtyNineCloudBundle:WordsList');
        $this->wordRepo = $em->getRepository('SixtyNineCloudBundle:Word');
    }

    /**
     * Get a list by $id.
     * @param int $id
     * @return null|WordsList
     */
    public function getList($id)
    {
        return $this->listRepo->find($id);
    }

    /**
     * Get the word given by $id.
     * @param int $id
     * @return null|Word
     */
    public function getWord($id)
    {
        return $this->wordRepo->find($id);
    }

    /**
     * Import a $word into a $list using the given $filters.
     * @param WordsList $list
     * @param string $word
     * @param Filters $filters
     */
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

    /**
     * Import the words of the $text into a $list using the given $filters.
     * @param WordsList $list
     * @param string $text
     * @param int $maxWords
     * @param Filters $filters
     */
    public function importText(WordsList $list, $text, $maxWords = 100, Filters $filters = null)
    {
        $array = preg_split("/[\n\r\t ]+/", $text);

        foreach (array_slice($array, 0, $maxWords) as $word) {
            $this->importWord($list, $word, $filters);
        }
    }

    /**
     * Import the words of the given $html extract into a $list using the given $filters.
     * @param WordsList $list
     * @param string $html
     * @param int $maxWords
     * @param Filters $filters
     */
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

    /**
     * Import the words from the given $url into a $list using the given $filters.
     * @param WordsList $list
     * @param $url
     * @param int $maxWords
     * @param Filters $filters
     */
    public function importUrl(WordsList $list, $url, $maxWords = 100, Filters $filters = null)
    {
        $this->importHtml($list, file_get_contents($url), $maxWords, $filters);
    }

    /**
     * Apply the given $filters to the $list.
     * @param WordsList $list
     * @param Filters $filters
     */
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

    /**
     * Sort the words of the $list.
     * @param WordsList $list
     * @param string $sortBy
     * @param string $order
     * @return mixed
     */
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
     * Get the lists of the $user.
     * @param Account $user
     * @return mixed
     */
    public function getUserLists(Account $user)
    {
        return $this->listRepo->findByUser($user);
    }

    /**
     * Create a new list and return it.
     * @param Account $user
     * @param string $name
     * @return WordsList
     */
    public function createList(Account $user, $name)
    {
        $list = new WordsList();
        $list->setUser($user)->setName($name);
        $this->em->persist($list);
        $this->em->flush();
        return $list;
    }

    /**
     * Save the changes in $data into the $list.
     * @param WordsList $list
     * @param array $data
     * @return WordsList
     */
    public function saveList(WordsList $list, $data)
    {
        $list->setName($data['name']);
        $this->em->flush();
        return $list;
    }

    /**
     * Duplicate a $list and all its words and return the new list.
     * @param WordsList $list
     * @return WordsList
     */
    public function duplicateList(WordsList $list)
    {
        $newList = new WordsList();

        $newList
            ->setName('Copy of ' . $list->getName())
            ->setUser($list->getUser())
        ;

        $this->em->persist($newList);

        /** @var Word $word */
        foreach ($list->getWords() as $word) {

            $newWord = new Word();
            $newWord
                ->setList($newList)
                ->setUser($word->getUser())
                ->setCount($word->getCount())
                ->setColor($word->getColor())
                ->setText($word->getText())
                ->setOrientation($word->getOrientation())
                ->setPosition($word->getPosition())
            ;

            $this->em->persist($newWord);
        }

        $this->em->flush();

        return $newList;
    }

    /**
     * Delete a $list.
     * @param WordsList $list
     */
    public function deleteList(WordsList $list)
    {
        $this->em->remove($list);
        $this->em->flush();
    }

    /**
     * Save the changes given in $data into the $word.
     * @param Word $word
     * @param array $data
     * @return Word
     */
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

    /**
     * Delete a $word.
     * @param Word $word
     */
    public function deleteWord(Word $word)
    {
        $this->em->remove($word);
        $this->em->flush();
    }

    /**
     * Toggle the orientation of the $word from horizontal to vertical and back.
     * @param Word $word
     */
    public function toggleWordOrientation(Word $word)
    {
        $word->setOrientation(
            $word->getOrientation() === Text::DIR_VERTICAL
                ? Text::DIR_HORIZONTAL
                : Text::DIR_VERTICAL
        );
        $this->em->flush();
    }

    /**
     * Increase the occurrences of the $word by $count.
     * @param Word $word
     * @param int $count
     */
    public function increaseWordCount(Word $word, $count = 1)
    {
        $word->setCount($word->getCount() + $count);
        $this->em->flush();
    }

    /**
     * Decrease the occurrences of the $word by $count.
     * @param Word $word
     * @param int $count
     */
    public function decreaseWordCount(Word $word, $count = 1)
    {
        if ($word->getCount() > $count) {
            $word->setCount($word->getCount() - $count);
        }

        $this->em->flush();
    }

    /**
     * Create a set of filters with the given $data.
     * @param array $data
     * @return Filters
     */
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

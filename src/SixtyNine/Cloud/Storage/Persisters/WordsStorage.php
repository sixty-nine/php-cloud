<?php


namespace SixtyNine\Cloud\Storage\Persisters;


use SixtyNine\Cloud\Filters\Filters;
use SixtyNine\Cloud\Model\Word;
use SixtyNine\Cloud\Model\Words;
use SixtyNine\Cloud\Storage\SavedData;
use SixtyNine\Cloud\Storage\Storage;
use SixtyNine\Cloud\Storage\StorageInterface;

class WordsStorage extends Storage implements StorageInterface
{
    public function __construct()
    {
        parent::__construct(Words::class);
    }

    public function save($words)
    {
        /** @var Words $words */
        parent::save($words);

        $res = array();

        /** @var Word $word */
        foreach ($words->getWords() as $word) {
            $res[$word->getText()] = $word->getCount();
        }

        arsort($res);

        return new SavedData('words', $res);
    }

    public function load(SavedData $data)
    {
        if (!$data->getType() === 'words') {
            return false;
        }

        $words = new Words(new Filters());

        foreach ($data->getData() as $key => $count) {
            $words->addWord($key, $count);
        }

        return $words;
    }}

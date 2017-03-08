<?php
namespace SixtyNine\Cloud\Storage;

use SixtyNine\Cloud\Model\Words;

interface StorageInterface
{
    /**
     * @param SavedData $data
     * @return mixed
     */
    function load(SavedData $data);

    /**
     * @param $data
     * @return SavedData
     */
    function save($data);
}

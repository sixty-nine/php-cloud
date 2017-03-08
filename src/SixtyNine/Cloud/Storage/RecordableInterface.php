<?php


namespace SixtyNine\Cloud\Storage;


interface RecordableInterface
{
    /**
     * @return array
     */
    function getParamsArray();

    /**
     * @param array $params
     * @return mixed
     */
    static function fromParamsArray(array $params);
}
 
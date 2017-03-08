<?php

namespace SixtyNine\Cloud\Storage;

abstract class Recordable implements RecordableInterface
{
    /**
     * @param array|string $names
     * @param array $params
     * @throws \InvalidArgumentException
     */
    public static function requireArrayKeysExist($names, $params)
    {
        $names = !is_array($names) ? array($names) : $names;
        foreach ($names as $name) {
            if (!array_key_exists($name, $params)) {
                throw new \InvalidArgumentException('Missing "' . $name . '" parameter');
            }
        }
    }
}

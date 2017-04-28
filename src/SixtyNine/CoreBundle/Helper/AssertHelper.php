<?php

namespace SixtyNine\CoreBundle\Helper;

use Webmozart\Assert\Assert;

class AssertHelper extends Assert
{
    /**
     * @param array $value
     * @param array $expectedParams
     */
    public static function parameters(array $value, $expectedParams)
    {
        foreach ($expectedParams as $name => $type)
        {
            self::keyExists($value, $name, sprintf('Option %s missing', $name));
            if ($type) {
                switch ($type) {
                    case 'string':
                        self::stringNotEmpty($value[$name], sprintf('Expected %s to be a string not empty', $name));
                        break;
                    case 'integer':
                        self::integer($value[$name], sprintf('Expected %s to be a integer', $name));
                        break;
                    default:
                        self::isInstanceOf($value[$name], $type, sprintf('Expected %s to be a %s, got %s', $name, $type, get_class($value[$name])));
                        break;
                }
            }
        }
    }
}

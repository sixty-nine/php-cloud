<?php


namespace SixtyNine\Cloud\Storage;


abstract class Storage implements StorageInterface
{
    /** @var string */
    protected $dataClass;

    /**
     * @param string $dataClass
     */
    public function __construct($dataClass)
    {
        $this->dataClass = $dataClass;
    }

    /** {@inheritdoc} */
    public function save($data)
    {
        if (!$data instanceof $this->dataClass) {
            throw new \InvalidArgumentException(
                sprintf(
                    'Invalid data, should be %s, got %s',
                    $this->dataClass,
                    get_class($data)
                )
            );
        }
    }

    /**
     * @param string $class
     * @return string
     */
    public function classNameToDotted($class)
    {
        return str_replace('\\', '.', get_class($class));
    }

    /**
     * @param string $class
     * @return string
     */
    public function dottedToClassName($class)
    {
        return str_replace('.', '\\', $class);
    }

    protected function createFromParams($dotteClassName, $params = array())
    {
        $class = $this->dottedToClassName($dotteClassName);
        $callable = '\\' . $class . '::fromParamsArray';
        return call_user_func($callable, $params);
    }
}

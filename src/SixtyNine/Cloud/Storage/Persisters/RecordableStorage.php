<?php


namespace SixtyNine\Cloud\Storage\Persisters;

use SixtyNine\Cloud\Storage\Recordable;
use SixtyNine\Cloud\Storage\RecordableInterface;
use SixtyNine\Cloud\Storage\SavedData;
use SixtyNine\Cloud\Storage\Storage;
use SixtyNine\Cloud\Storage\StorageInterface;

class RecordableStorage extends Storage implements StorageInterface
{
    /** @var string */
    protected $type;

    /**
     * @param string $dataClass
     * @param string $type
     */
    public function __construct($dataClass, $type)
    {
        parent::__construct($dataClass);
        $this->type = $type;
    }

    public function save($data)
    {
        parent::save($data);

        if (!$data instanceof RecordableInterface) {
            throw new \InvalidArgumentException('data must be a Recordable');
        }

        return new SavedData($this->type, array(
            'class' => $this->classNameToDotted($this->dataClass),
            'params' => $data->getParamsArray(),
        ));
    }

    /**
     * @param SavedData $data
     * @throws \InvalidArgumentException
     * @return mixed
     */
    function load(SavedData $data)
    {
        if ($data->getType() !== $this->type) {
            throw new \InvalidArgumentException('data must be of type: ' . $this->type);
        }

        Recordable::requireArrayKeysExist(array('class', 'params'), $data->getData());

        $data = $data->getData();
        return $this->createFromParams($data['class'], $data['params']);
    }
}

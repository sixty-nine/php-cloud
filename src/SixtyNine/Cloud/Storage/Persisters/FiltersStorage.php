<?php

namespace SixtyNine\Cloud\Storage\Persisters;

use SixtyNine\Cloud\Filters\FilterInterface;
use SixtyNine\Cloud\Filters\Filters;
use SixtyNine\Cloud\Storage\SavedData;
use SixtyNine\Cloud\Storage\Storage;
use SixtyNine\Cloud\Storage\StorageInterface;
use Symfony\Component\Yaml\Yaml;

class FiltersStorage extends Storage implements StorageInterface
{
    public function __construct()
    {
        parent::__construct(Filters::class);
    }

    /** {@inheritdoc} */
    function save($data)
    {
        /** @var Filters $data */

        parent::save($data);

        $res = array();

        /** @var FilterInterface $filter */
        foreach ($data->getFilters() as $filter) {
            $class = $this->classNameToDotted($filter);
            $res[$class] = $filter->getParamsArray();
        }

        return new SavedData('filters', $res);
    }

    /** {@inheritdoc} */
    function load(SavedData $data)
    {
        if (!$data->getType() === 'filters') {
            return false;
        }

        $filters = new Filters();

        foreach ($data->getData() as $key => $value) {
            $filter = $this->createFromParams($key, $value);
            $filters->addFilter($filter);
        }
        return $filters;
    }
}

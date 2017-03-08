<?php
/**
 * This file is part of the sixty-nine/word-cloud.
 * Copyright (c) 2010-2016 Daniele Barsotti<sixtynine.db@gmail.com>
 */

namespace SixtyNine\Cloud\Filters;

use SixtyNine\Cloud\Model\Word;

class Filters
{
    /**
     * An array of FilterInterface
     * @var array
     */
    protected $filters;

    /**
     * @param array $filters
     */
    public function __construct(array $filters = array())
    {
        $this->filters = array();
        $this->addFilters($filters);
    }

    /**
     * @param array(FilterInterface) $filters
     */
    public function addFilters($filters)
    {
        foreach ($filters as $filter) {
            if ($this->isFilter($filter)) {
                $this->filters[] = $filter;
            }
        }
    }

    /**
     * Add a filter to filter out words.
     * @param FilterInterface $filter
     * @return bool
     */
    public function addFilter(FilterInterface $filter)
    {
        if ($this->isFilter($filter) && !in_array($filter, $this->filters)) {

            $this->filters[] = $filter;
        }
    }

    /**
     * Is $filter a FilterInterface?
     * @param $filter
     * @return bool
     */
    protected function isFilter($filter)
    {
        return $filter instanceof FilterInterface;
    }

    /**
     * @param Word $word
     * @return bool
     */
    public function filterWord(Word $word)
    {
        /** @var FilterInterface $filter*/
        foreach($this->filters as $filter) {
            if (!$filter->keepWord($word)) {
                return false;
            }
            $word = $filter->filterWord($word);
        }

        return $word;
    }

    /**
     * @return array
     */
    public function getFilters()
    {
        return $this->filters;
    }
}

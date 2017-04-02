<?php

namespace SixtyNine\CloudBundle\Cloud\Builder;

use SixtyNine\CloudBundle\Cloud\Filters\ChangeCase;
use SixtyNine\CloudBundle\Cloud\Filters\Filters;

class FiltersBuilder
{
    /** @var array */
    protected $allowedCase;
    /** @var string|null */
    protected $case;
    /** @var bool */
    protected $removeNumbers = true;
    /** @var bool */
    protected $removeUnwanted = true;
    /** @var bool */
    protected $removeTrailing = true;
    /** @var int|null */
    protected $minLength;
    /** @var int|null */
    protected $maxLength;

    function __construct()
    {
        $this->allowedCase = array(
            ChangeCase::LOWERCASE,
            ChangeCase::UPPERCASE,
            ChangeCase::UCFIRST,
        );
    }

    /**
     * @param string $case
     * @return FiltersBuilder
     */
    public function setCase($case)
    {
        $case = strtolower($case);
        if (in_array($case, $this->allowedCase)) {
            $this->case = $case;
        }
        return $this;
    }

    /**
     * @param boolean $enabled
     * @return FiltersBuilder
     */
    public function setRemoveNumbers($enabled)
    {
        $this->removeNumbers = (bool)$enabled;
        return $this;
    }

    /**
     * @param boolean $enabled
     * @return FiltersBuilder
     */
    public function setRemoveUnwanted($enabled)
    {
        $this->removeUnwanted = (bool)$enabled;
        return $this;
    }

    /**
     * @param boolean $enabled
     * @return FiltersBuilder
     */
    public function setRemoveTrailing($enabled)
    {
        $this->removeTrailing = (bool)$enabled;
        return $this;
    }

    /**
     * @param int $maxLength
     * @return FiltersBuilder
     */
    public function setMaxLength($maxLength)
    {
        $this->maxLength = $maxLength;
        return $this;
    }

    /**
     * @param int $minLength
     * @return FiltersBuilder
     */
    public function setMinLength($minLength)
    {
        $this->minLength = $minLength;
        return $this;
    }

    /**
     * @return Filters
     */
    public function build()
    {
        $filters = new Filters();

        if ($this->case) {
            $filters->addFilter(new ChangeCase($this->case));
        }

        return $filters;
    }
} 
<?php

namespace SixtyNine\Cloud\Performance;

use Symfony\Component\Stopwatch\Stopwatch;

trait StopwatchAware {

    /** @var Stopwatch */
    protected $stopwatch;

    /**
     * @param Stopwatch $stopwatch
     * @return void
     */
    public function setStopwatch(Stopwatch $stopwatch)
    {
        $this->stopwatch = $stopwatch;
    }

    /**
     * @return Stopwatch
     */
    public function getStopwatch()
    {
        return $this->stopwatch;
    }

    /**
     * @param $name
     */
    public function stopwatchStart($name)
    {
        if ($this->stopwatch) {
            $this->stopwatch->start($name);
        }
    }

    /**
     * @param $name
     */
    public function stopwatchStop($name)
    {
        if ($this->stopwatch) {
            $this->stopwatch->stop($name);
        }
    }

    /**
     * @param null|string $id
     */
    public function stopwatchOpenSection($id = null)
    {
        if ($this->stopwatch) {
            $this->stopwatch->openSection($id);
        }
    }

    /**
     * @param null|string $id
     */
    public function stopwatchStopSection($id = null)
    {
        if ($this->stopwatch) {
            $this->stopwatch->stopSection($id);
        }
    }
}

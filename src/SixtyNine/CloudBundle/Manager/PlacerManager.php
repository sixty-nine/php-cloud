<?php

namespace SixtyNine\CloudBundle\Manager;

use SixtyNine\CloudBundle\Cloud\Placer\CircularPlacer;
use SixtyNine\CloudBundle\Cloud\Placer\LinearPlacer;
use SixtyNine\CloudBundle\Cloud\Placer\SpiranglePlacer;
use SixtyNine\CloudBundle\Cloud\Placer\WordlePlacer;
use SixtyNine\CloudBundle\Entity\Cloud;

class PlacerManager
{
    protected $placers = array(
        'Circular' => CircularPlacer::class,
        'Wordle' => WordlePlacer::class,
        'Spirangle' => SpiranglePlacer::class,
        'Linear' => LinearPlacer::class,
    );

    public function getPlacersList()
    {
        $res = array();
        foreach ($this->placers as $name => $class) {
            $res[$name] = $name;
        }
        return $res;
    }

    public function getPlacerClass($name) {

        if (!array_key_exists($name, $this->placers)) {
            throw new \InvalidArgumentException('Placer not found');
        }

        return $this->placers[$name];
    }
 }

<?php

namespace SixtyNine\CloudBundle\Manager;

use SixtyNine\Cloud\Placer\CircularPlacer;
use SixtyNine\Cloud\Placer\LinearHorizontalPlacer;
use SixtyNine\Cloud\Placer\LinearVerticalPlacer;
use SixtyNine\Cloud\Placer\LissajouPlacer;
use SixtyNine\Cloud\Placer\SpiranglePlacer;
use SixtyNine\Cloud\Placer\WordlePlacer;

class PlacerManager
{
    protected $placers = array(
        'Circular' => CircularPlacer::class,
        'Wordle' => WordlePlacer::class,
        'Spirangle' => SpiranglePlacer::class,
        'Linear Horizontal' => LinearHorizontalPlacer::class,
        'Linear Vertical' => LinearVerticalPlacer::class,
        'Lissajou' => LissajouPlacer::class,
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

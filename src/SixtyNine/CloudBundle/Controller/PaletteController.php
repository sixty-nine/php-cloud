<?php

namespace SixtyNine\CloudBundle\Controller;

use SixtyNine\CoreBundle\Controller\Controller;

class PaletteController extends Controller
{
    public function indexAction()
    {
        $palettes = $this
            ->getRepository('SixtyNineCloudBundle:Palette')
            ->getPalettes($this->getUser())
        ;

        return $this->render(
            'SixtyNineCloudBundle:Palette:index.html.twig',
            array(
                'palettes' => $palettes,
            )
        );
    }

}

<?php

namespace SixtyNine\CloudBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class PaletteController extends Controller
{
    public function indexAction()
    {
        $palettes = $this
            ->getDoctrine()
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

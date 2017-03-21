<?php

namespace SixtyNine\CloudBundle\Controller;

use SixtyNine\CloudBundle\Manager\FontsManager;
use SixtyNine\CoreBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class SettingsController extends Controller
{
    public function indexAction()
    {
        return $this->render(
            'SixtyNineCloudBundle:Settings:index.html.twig',
            array()
        );
    }

    public function palettesAction()
    {
        $palettes = $this
            ->getRepository('SixtyNineCloudBundle:Palette')
            ->getPalettes($this->getUser())
        ;

        return $this->render(
            'SixtyNineCloudBundle:Settings:palettes.html.twig',
            array(
                'palettes' => $palettes,
            )
        );
    }

    public function fontsAction()
    {
        return $this->render(
            'SixtyNineCloudBundle:Settings:fonts.html.twig',
            array(
                'fonts' => $this->get('sn_cloud.fonts_manager')->getFonts(),
            )
        );
    }

    public function previewFontAction($name)
    {
        /** @var FontsManager $manager */
        $manager = $this->get('sn_cloud.fonts_manager');
        try {
            $image = $manager->preview($name);
        } catch (\InvalidArgumentException $ex) {
            throw  $this->createNotFoundException('Font not found');
        }

        return new Response($image->get('png'), 200, array(
            'Content-type' => 'image/png',
        ));
    }

}

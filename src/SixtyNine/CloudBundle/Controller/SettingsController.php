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
        $fonts = $manager->getFontsByName();

        $font = new \Imagine\Gd\Font(
            $manager->getFullFontPath($fonts[$name]),
            24,
            new \Imagine\Image\Color('#FFFFFF')
        );
        $font1 = new \Imagine\Gd\Font(
            $manager->getFullFontPath($fonts['Arial']),
            12,
            new \Imagine\Image\Color('#FF2222')
        );

        $imagine = new \Imagine\Gd\Imagine();
        $size  = new \Imagine\Image\Box(800, 75);
        $image = $imagine->create($size, new \Imagine\Image\Color('#000000'));
        $image->draw()->text(
            $name,
            $font1,
            new \Imagine\Image\Point(10, 8)
        );

        $image->draw()->text(
            'The quick brown fox jumps over the lazy dog',
            $font,
            new \Imagine\Image\Point(10, 30)
        );

        return new Response($image->get('png'), 200, array(
            'Content-type' => 'image/png',
        ));
    }

}

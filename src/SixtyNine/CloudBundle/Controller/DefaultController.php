<?php

namespace SixtyNine\CloudBundle\Controller;

use SixtyNine\Cloud\Config\Config;
use SixtyNine\Cloud\TextListFilter\OrientationVisitor;
use SixtyNine\CloudBundle\Builder\CloudBuilder;
use SixtyNine\CloudBundle\Entity\Cloud;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    public function indexAction(Request $request)
    {
        $data = array(
            'palette' => 'aqua',
            'font' => 'Arial.ttf',
            'orientation' => OrientationVisitor::WORDS_MAINLY_HORIZONTAL,
            'frame' => false,
            'minSize' => 20,
            'maxSize' => 80,
        );

        $styleForm = $this->createForm(
            'SixtyNine\CloudBundle\Form\Forms\CloudStyleFormType',
            $data
        );
        $styleForm->handleRequest($request);

        if ($styleForm->isValid()) {

            $data = $styleForm->getData();
        }

        $list = $this->getDoctrine()->getRepository('SixtyNineCloudBundle:WordsList')->find(1);
        $builder = new CloudBuilder();
        $words = $builder->buildWordsFromWordsList($list);
        $image = $builder->createImage($words, new Config(), $data);

        return $this->render(
            'SixtyNineCloudBundle:Default:bla.html.twig',
            array(
                'image' => base64_encode($image->getRawPngContent()),
                'form' => $styleForm->createView(),
            )
        );
    }
}

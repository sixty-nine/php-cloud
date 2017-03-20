<?php

namespace SixtyNine\CloudBundle\Controller;

use SixtyNine\CloudBundle\Entity\Cloud;
use SixtyNine\CloudBundle\Manager\CloudManager;
use SixtyNine\CloudBundle\Manager\FontsManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;

class CloudController extends Controller
{

    /** @var CloudManager $manager */
    protected $cloudManager;

    /** @var FontsManager */
    protected $fontsManager;

    public function setContainer(ContainerInterface $container = null)
    {
        parent::setContainer($container);

        $this->cloudManager = $this->get('sn_cloud.cloud_manager');
        $this->fontsManager = $this->get('sn_cloud.fonts_manager');
    }

    public function indexAction(Request $request)
    {
        $form = $this->createForm(
            'SixtyNine\CloudBundle\Form\Forms\CreateCloudFormType',
            array(),
            array('fonts_manager' => $this->fontsManager)
        );

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $font = $form->get('font')->getData();
            $color = $form->get('color')->getData();
            $list = $form->get('words')->getData();

            $cloud = $this->cloudManager->createCloud($this->getUser(), $list, $font, $color);

            return $this->redirectToRoute('sn_cloud_render', array('id' => $cloud->getId()));
        }

        return $this->render(
            'SixtyNineCloudBundle:Cloud:index.html.twig',
            array(
                'form' => $form->createView(),
            )
        );
    }

    public function renderAction(Cloud $cloud)
    {
        $image = $this->cloudManager->buildCloud($cloud);

        return $this->render(
            'SixtyNineCloudBundle:Cloud:render.html.twig',
            array(
                'image' => base64_encode($image->getRawPngContent()),
                'cloud' => $cloud,
            )
        );
    }
}

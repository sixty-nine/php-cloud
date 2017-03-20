<?php

namespace SixtyNine\CloudBundle\Controller;

use Imagine\Image\Box;
use SixtyNine\CloudBundle\Entity\Cloud;
use SixtyNine\CloudBundle\Manager\CloudManager;
use SixtyNine\CloudBundle\Manager\FontsManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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

    public function createAction(Request $request)
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

            return $this->redirectToRoute('sn_cloud_generate', array('id' => $cloud->getId()));
        }

        return $this->render(
            'SixtyNineCloudBundle:Cloud:index.html.twig',
            array(
                'form' => $form->createView(),
            )
        );
    }

    public function generateAction(Cloud $cloud)
    {
        $this->cloudManager->generateCloudWords($cloud, 20, 60);
        $this->cloudManager->placeWords($cloud);

        return $this->redirectToRoute('sn_cloud_view', array('id' => $cloud->getId()));
    }

    public function listAction()
    {
        $clouds = $this->cloudManager->getClouds($this->getUser());

        return $this->render(
            'SixtyNineCloudBundle:Cloud:list.html.twig',
            array(
                'clouds' => $clouds,
            )
        );
    }

    public function viewAction(Cloud $cloud)
    {
        $cloud = $this->cloudManager->getCloud($cloud->getId());

        return $this->render(
            'SixtyNineCloudBundle:Cloud:view.html.twig',
            array(
                'image' => $this->generateUrl('sn_cloud_render', array('id' => $cloud->getId())),
                'cloud' => $cloud,
            )
        );
    }

    public function renderAction(Request $request, Cloud $cloud)
    {
        $image = $this->cloudManager->render($cloud);

        $width = $request->get('width');
        $height = $request->get('height');

        if ($width && $height) {
            $image->resize(new Box($width, $height));
        }
        return new Response($image->get('png'), 200, array(
            'Content-type' => 'image/png',
        ));
    }
}
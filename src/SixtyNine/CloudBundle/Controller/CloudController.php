<?php

namespace SixtyNine\CloudBundle\Controller;

use Imagine\Image\Box;
use Imagine\Image\Color;
use Imagine\Image\Point;
use SixtyNine\CloudBundle\Cloud\Placer\CircularPlacer;
use SixtyNine\CloudBundle\Cloud\Placer\LinearPlacer;
use SixtyNine\CloudBundle\Cloud\Placer\SpiranglePlacer;
use SixtyNine\CloudBundle\Cloud\Placer\WordlePlacer;
use SixtyNine\CloudBundle\Entity\Cloud;
use SixtyNine\CloudBundle\Form\Forms\CreateCloudFormType;
use SixtyNine\CloudBundle\Manager\CloudManager;
use SixtyNine\CloudBundle\Manager\FontsManager;
use SixtyNine\CloudBundle\Manager\PlacerManager;
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
    /** @var PlacerManager */
    protected $placersManager;

    public function setContainer(ContainerInterface $container = null)
    {
        parent::setContainer($container);

        $this->cloudManager = $this->get('sn_cloud.cloud_manager');
        $this->fontsManager = $this->get('sn_cloud.fonts_manager');
        $this->placersManager = $this->get('sn_cloud.placers_manager');
    }

    public function createAction(Request $request)
    {
        $form = $this->createForm(
            CreateCloudFormType::class,
            array(),
            array(
                'fonts_manager' => $this->fontsManager,
                'placers_manager' => $this->placersManager,
            )
        );

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $font = $form->get('font')->getData();
            $color = $form->get('color')->getData();
            $list = $form->get('words')->getData();
            $width = $form->get('imageWidth')->getData();
            $height = $form->get('imageHeight')->getData();
            $placerName = $form->get('placer')->getData();

            $cloud = $this->cloudManager->createCloud(
                $this->getUser(), $list, $font, $color, $placerName, $width, $height
            );

            return $this->redirectToRoute('sn_cloud_generate', array(
                'id' => $cloud->getId(),
                'min' => $form->get('minSize')->getData(),
                'max' => $form->get('maxSize')->getData(),
                'gen' => $form->get('fontSize')->getData(),
            ));
        }

        return $this->render(
            'SixtyNineCloudBundle:Cloud:create.html.twig',
            array(
                'form' => $form->createView(),
            )
        );
    }

    public function editAction(Request $request, Cloud $cloud)
    {
        $data = array(
            'words' => $cloud->getList(),
            'placer' => $cloud->getPlacer(),
            'font' => $cloud->getFont(),
            'color' => $cloud->getBackgroundColor(),
            'imageWidth' => $cloud->getWidth(),
            'imageHeight' => $cloud->getHeight(),
        );

        $form = $this->createForm(
            CreateCloudFormType::class,
            $data,
            array(
                'fonts_manager' => $this->fontsManager,
                'placers_manager' => $this->placersManager,
            )
        );

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $font = $form->get('font')->getData();
            $color = $form->get('color')->getData();
            $list = $form->get('words')->getData();
            $width = $form->get('imageWidth')->getData();
            $height = $form->get('imageHeight')->getData();
            $placerName = $form->get('placer')->getData();

            $cloud = $this->cloudManager->saveCloud(
                $cloud, $list, $font, $color, $placerName, $width, $height
            );

            return $this->redirectToRoute('sn_cloud_generate', array(
                'id' => $cloud->getId(),
                'min' => $form->get('minSize')->getData(),
                'max' => $form->get('maxSize')->getData(),
                'gen' => $form->get('fontSize')->getData(),
            ));
        }

        return $this->render(
            'SixtyNineCloudBundle:Cloud:edit.html.twig',
            array(
                'form' => $form->createView(),
            )
        );
    }

    public function generateAction(Request $request, Cloud $cloud)
    {
        $minSize = $request->get('minSize', 10);
        $maxSize = $request->get('maxSize', 100);
        $generator = $request->get('gen', 'linear');

        $this->cloudManager->generateCloudWords($cloud, $minSize, $maxSize, $generator);
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
        $showBoundingBoxes = $request->get('show-bb', false);
        $showPlacer = $request->get('show-placer', false);

        $image = $this->cloudManager->render($cloud, $showBoundingBoxes);

        if ($showPlacer) {
            $className = $this->placersManager->getPlacerClass($cloud->getPlacer());
            $placer = new $className;
            $this->cloudManager->renderUsher(
                $image,
                $placer,
                new Color('#FF0000'),
                $cloud->getWidth(),
                $cloud->getHeight()
            );
        }

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

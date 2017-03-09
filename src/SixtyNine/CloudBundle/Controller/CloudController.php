<?php

namespace SixtyNine\CloudBundle\Controller;

use SixtyNine\Cloud\Config\Config;
use SixtyNine\Cloud\Filters\ChangeCase;
use SixtyNine\Cloud\Filters\Filters;
use SixtyNine\Cloud\Filters\RemoveByLength;
use SixtyNine\Cloud\Filters\RemoveCharacters;
use SixtyNine\Cloud\Filters\RemoveNumbers;
use SixtyNine\Cloud\Filters\RemoveTrailingCharacters;
use SixtyNine\Cloud\TextListFilter\OrientationVisitor;
use SixtyNine\CloudBundle\Builder\CloudBuilder;
use SixtyNine\CloudBundle\Entity\Cloud;
use SixtyNine\CloudBundle\Entity\Word;
use SixtyNine\CloudBundle\Repository\CloudRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CloudController extends Controller
{
    /**
     * @var CloudRepository
     */
    protected $cloudRepo;

    public function setContainer(ContainerInterface $container = null)
    {
        parent::setContainer($container);

        $this->cloudRepo = $this
            ->getDoctrine()
            ->getRepository('SixtyNineCloudBundle:Cloud')
        ;
    }

    public function indexAction()
    {
        return $this->render(
            'SixtyNineCloudBundle:Cloud:index.html.twig',
            array()
        );
    }

    /**
     * List all the clouds of the user.
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction()
    {
        $clouds = $this->cloudRepo->findBy(array('user' => $this->getUser()));

        return $this->render(
            'SixtyNineCloudBundle:Cloud:list.html.twig',
            array(
                'clouds' => $clouds,
            )
        );
    }

    /**
     * View the detail of a specific cloud.
     * @param Request $request
     * @param Cloud $cloud
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function viewAction(Request $request, Cloud $cloud)
    {
        $addWordsForm = $this->createForm('SixtyNine\CloudBundle\Form\Forms\AddWordsFormType');
        $addWordsForm->handleRequest($request);

        $filtersForm = $this->createForm('SixtyNine\CloudBundle\Form\Forms\FiltersFormType');
        $filtersForm->handleRequest($request);

        return $this->render(
            'SixtyNineCloudBundle:Cloud:view.html.twig',
            array(
                'addWordsForm' => $addWordsForm->createView(),
                'filtersForm' => $filtersForm->createView(),
                'cloud' => $cloud,
            )
        );
    }

    /**
     * Show the form to create a new cloud.
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function newAction(Request $request)
    {
        $form = $this->createForm(
            'SixtyNine\CloudBundle\Form\Forms\CreateCloudFormType',
            array(
                'action' => $this->generateUrl('sn_cloud_create'),
                'method' => 'POST',
            )
        );
        $form->handleRequest($request);

        return $this->render(
            'SixtyNineCloudBundle:Cloud:create.html.twig',
            array(
                'form' => $form->createView(),
            )
        );
    }

    /**
     * Create a new cloud.
     * @param Request $request
     * @return RedirectResponse
     */
    public function createAction(Request $request)
    {
        $form = $this->createForm('SixtyNine\CloudBundle\Form\Forms\CreateCloudFormType');
        $form->handleRequest($request);

        // TODO: if fails --> goto list

        $cloud = $this->cloudRepo->createCloud(
            $this->getUser(),
            $form->get('name')->getData()
        );

        return $this->redirectToRoute('sn_cloud_view', array('id' => $cloud->getId()));
    }

    /**
     * Delete a cloud.
     * @param Cloud $cloud
     * @return RedirectResponse
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function deleteAction(Cloud $cloud)
    {
        if ($cloud->getUser() !== $this->getUser()) {
            throw new NotFoundHttpException();
        }

        $this->cloudRepo->deleteCloud($cloud);

        return $this->redirectToRoute('sn_cloud_list');
    }

    /**
     * Add one or more word to a cloud.
     * @param Request $request
     * @param Cloud $cloud
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function addWordsAction(Request $request, Cloud $cloud)
    {
        $form = $this->createForm('SixtyNine\CloudBundle\Form\Forms\AddWordsFormType');
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $text = $form->get('text')->getData();
            $builder = $this->get('sn_cloud.cloud_builder');
            $words = $builder->createWords($text);
            $this->cloudRepo->importWords($cloud, $words);

            return $this->redirectToRoute('sn_cloud_view', array('id' => $cloud->getId()));
        }

        return $this->render(
            'SixtyNineCloudBundle:Default:add-words.html.twig',
            array(
                'form' => $form->createView(),
            )
        );
    }

    /**
     * Import words from an URL into the cloud.
     * @param Request $request
     * @param Cloud $cloud
     * @return RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function importWordsAction(Request $request, Cloud $cloud)
    {
        $form = $this->createForm('SixtyNine\CloudBundle\Form\Forms\FiltersFormType');
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $url = $form->get('url')->getData();
            $builder = $this->get('sn_cloud.cloud_builder');

            $filters = new Filters();

            if ($form->get('changeCaseEnabled')->getData()) {
                $filters->addFilters(
                    new ChangeCase($form->get('case')->getData())
                );
            }
            if ($form->get('removeNumbersEnabled')) {
                $filters->addFilter(new RemoveNumbers());
            }
            if ($form->get('removeUnwantedCharEnabled')) {
                $filters->addFilter(new RemoveCharacters());
            }
            if ($form->get('removeTrailingCharEnabled')) {
                $filters->addFilter(new RemoveTrailingCharacters());
            }
            if ($form->get('removeByLengthEnabled')) {
                $min = $form->get('minLength')->getData();
                $max = $form->get('maxLength')->getData();

                if ($min || $max) {
                    $filters->addFilter(new RemoveByLength($min, $max));
                }
            }

            $words = $builder->createWordsFromUrl($url, $filters);
            $this->cloudRepo->importWords($cloud, $words);

            return $this->redirectToRoute('sn_cloud_view', array('id' => $cloud->getId()));
        }

        return $this->render(
            'SixtyNineCloudBundle:Default:add-words.html.twig',
            array(
                'form' => $form->createView(),
            )
        );
    }

    public function increaseWordAction(Word $word)
    {
        $word->setCount($word->getCount() + 1);
        $this->getDoctrine()->getEntityManager()->flush();
        return $this->redirectToRoute('sn_cloud_view', array('id' => $word->getCloud()->getId()));
    }

    public function decreaseWordAction(Word $word)
    {
        if ($word->getCount() > 1) {
            $word->setCount($word->getCount() - 1);
        }
        $this->getDoctrine()->getEntityManager()->flush();
        return $this->redirectToRoute('sn_cloud_view', array('id' => $word->getCloud()->getId()));
    }

    /**
     * Remove a word from a cloud.
     * @param Request $request
     * @param Word $word
     * @return RedirectResponse
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function removeWordAction(Request $request, Word $word)
    {
        if ($this->getUser() !== $word->getCloud()->getUser()) {
            throw new NotFoundHttpException();
        }

        $this->cloudRepo->removeWord($word);

        return $this->redirectToRoute('sn_cloud_view', array('id' => $word->getCloud()->getId()));
    }

}

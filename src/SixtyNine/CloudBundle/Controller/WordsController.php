<?php

namespace SixtyNine\CloudBundle\Controller;

use SixtyNine\Cloud\Filters\ChangeCase;
use SixtyNine\Cloud\Filters\Filters;
use SixtyNine\Cloud\Filters\RemoveByLength;
use SixtyNine\Cloud\Filters\RemoveCharacters;
use SixtyNine\Cloud\Filters\RemoveNumbers;
use SixtyNine\Cloud\Filters\RemoveTrailingCharacters;
use SixtyNine\Cloud\TextListFilter\OrientationVisitor;
use SixtyNine\CloudBundle\Entity\Word;
use SixtyNine\CloudBundle\Entity\WordsList;
use SixtyNine\CloudBundle\Repository\WordsListRepository;
use SixtyNine\CoreBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class WordsController extends Controller
{
    /**
     * @var WordsListRepository
     */
    protected $listRepo;

    public function setContainer(ContainerInterface $container = null)
    {
        parent::setContainer($container);

        $this->listRepo = $this->getRepository('SixtyNineCloudBundle:WordsList');
    }

    public function indexAction()
    {
        return $this->render(
            'SixtyNineCloudBundle:Words:index.html.twig',
            array()
        );
    }

    /**
     * List all the word lists of the user.
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction()
    {
        $list = $this->listRepo->findByUser($this->getUser());

        return $this->render(
            'SixtyNineCloudBundle:Words:list.html.twig',
            array(
                'lists' => $list,
            )
        );
    }

    /**
     * View the detail of a specific word list.
     * @param Request $request
     * @param \SixtyNine\CloudBundle\Entity\WordsList $list
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function viewAction(Request $request, WordsList $list)
    {
        $addWordsForm = $this->createForm('SixtyNine\CloudBundle\Form\Forms\AddWordsFormType');
        $addWordsForm->handleRequest($request);

        $filtersForm = $this->createForm('SixtyNine\CloudBundle\Form\Forms\FiltersFormType');
        $filtersForm->handleRequest($request);

        return $this->render(
            'SixtyNineCloudBundle:Words:view.html.twig',
            array(
                'addWordsForm' => $addWordsForm->createView(),
                'filtersForm' => $filtersForm->createView(),
                'list' => $list,
                'orientations' => array(
                    OrientationVisitor::WORDS_HORIZONTAL => 'Horizontal',
                    OrientationVisitor::WORDS_MAINLY_HORIZONTAL => 'Mainly horizontal',
                    OrientationVisitor::WORDS_MIXED => 'Mixed',
                    OrientationVisitor::WORDS_MAINLY_VERTICAL => 'Mainly vertical',
                    OrientationVisitor::WORDS_VERTICAL => 'Vertical',
                ),
                'palettes' => $this
                        ->getDoctrine()
                        ->getRepository('SixtyNineCloudBundle:Palette')
                        ->getPalettes($this->getUser())
            )
        );
    }

    /**
     * Show the form to create a new word list.
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function newAction(Request $request)
    {
        $form = $this->createForm(
            'SixtyNine\CloudBundle\Form\Forms\CreateWordsListFormType',
            array(
                'action' => $this->generateUrl('sn_words_create'),
                'method' => 'POST',
            )
        );
        $form->handleRequest($request);

        return $this->render(
            'SixtyNineCloudBundle:Words:create.html.twig',
            array(
                'form' => $form->createView(),
            )
        );
    }

    /**
     * Create a new word list.
     * @param Request $request
     * @return RedirectResponse
     */
    public function createAction(Request $request)
    {
        $form = $this->createForm('SixtyNine\CloudBundle\Form\Forms\CreateWordsListFormType');
        $form->handleRequest($request);

        // TODO: if fails --> goto list

        $list = $this->listRepo->createWordsList(
            $this->getUser(),
            $form->get('name')->getData()
        );

        return $this->redirectToRoute('sn_words_view', array('id' => $list->getId()));
    }

    /**
     * Delete a word list.
     * @param WordsList $list
     * @return RedirectResponse
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function deleteAction(WordsList $list)
    {
        if ($list->getUser() !== $this->getUser()) {
            throw new NotFoundHttpException();
        }

        $this->listRepo->deleteWordsList($list);

        return $this->redirectToRoute('sn_words_list');
    }

    /**
     * Add one or more word to a word list.
     * @param Request $request
     * @param WordsList $list
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function addWordsAction(Request $request, WordsList $list)
    {
        $form = $this->createForm('SixtyNine\CloudBundle\Form\Forms\AddWordsFormType');
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $text = $form->get('text')->getData();
            $builder = $this->get('sn_cloud.cloud_builder');
            $words = $builder->createWords($text);
            $this->listRepo->importWords($list, $words);

            return $this->redirectToRoute('sn_words_view', array('id' => $list->getId()));
        }

        return $this->render(
            'SixtyNineCloudBundle:Default:add-words.html.twig',
            array(
                'form' => $form->createView(),
            )
        );
    }

    /**
     * Import words from an URL into the words list.
     * @param Request $request
     * @param WordsList $list
     * @return RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function importWordsAction(Request $request, WordsList $list)
    {
        $form = $this->createForm('SixtyNine\CloudBundle\Form\Forms\FiltersFormType');
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $url = $form->get('url')->getData();
            $builder = $this->get('sn_cloud.cloud_builder');

            $filters = new Filters();

            if ($form->get('changeCaseEnabled')->getData()) {
                $filters->addFilter(
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
            $this->listRepo->importWords($list, $words);

            return $this->redirectToRoute('sn_words_view', array('id' => $list->getId()));
        }

        return $this->render(
            'SixtyNineCloudBundle:Default:add-words.html.twig',
            array(
                'form' => $form->createView(),
            )
        );
    }

    public function increaseWordAction(WordsList $list, $wordId)
    {
        $word = $this
            ->getDoctrine()
            ->getRepository('SixtyNineCloudBundle:Word')
            ->find($wordId)
        ;
        $word->setCount($word->getCount() + 1);
        $this->getDoctrine()->getEntityManager()->flush();
        return $this->redirectToRoute('sn_words_view', array('id' => $word->getList()->getId()));
    }

    public function decreaseWordAction(WordsList $list, $wordId)
    {
        $word = $this
            ->getDoctrine()
            ->getRepository('SixtyNineCloudBundle:Word')
            ->find($wordId)
        ;
        if ($word->getCount() > 1) {
            $word->setCount($word->getCount() - 1);
        }
        $this->getDoctrine()->getEntityManager()->flush();
        return $this->redirectToRoute('sn_words_view', array('id' => $word->getList()->getId()));
    }

    /**
     * Remove a word from a list.
     * @param Word $word
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @return RedirectResponse
     */
    public function removeWordAction(Word $word)
    {
        if ($this->getUser() !== $word->getList()->getUser()) {
            throw new NotFoundHttpException();
        }

        $this->listRepo->removeWord($word);

        return $this->redirectToRoute('sn_words_view', array('id' => $word->getList()->getId()));
    }

    public function randomizeOrientationsAction(WordsList $list, $orientation)
    {
        $this
            ->getRepository('SixtyNineCloudBundle:WordsList')
            ->randomizeWordsOrientation($list, (int)$orientation)
        ;
        return $this->redirectToRoute('sn_words_view', array('id' => $list->getId()));
    }

    public function randomizeColorsAction(WordsList $list, $type, $paletteId)
    {
        $palette = $this
            ->getRepository('SixtyNineCloudBundle:Palette')
            ->find($paletteId)
        ;

        if (!$palette) {
            throw new NotFoundHttpException('Palette not found');
        }

        if (!in_array($type, array('cycle', 'random'))) {
            throw new NotFoundHttpException('Type not found');
        }

        $this
            ->getRepository('SixtyNineCloudBundle:WordsList')
            ->randomizeWordsColors($list, $palette, $type)
        ;
        return $this->redirectToRoute('sn_words_view', array('id' => $list->getId()));
    }
}

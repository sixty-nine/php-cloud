<?php

namespace SixtyNine\CloudBundle\Controller;

use SixtyNine\Cloud\TextListFilter\OrientationVisitor;
use SixtyNine\CloudBundle\Entity\WordsList;
use SixtyNine\CloudBundle\Manager\WordListsManager;
use SixtyNine\CloudBundle\Repository\WordRepository;
use SixtyNine\CloudBundle\Repository\WordsListRepository;
use SixtyNine\CoreBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class WordsController extends Controller
{
    /** @var WordsListRepository */
    protected $listRepo;
    /** @var WordListsManager */
    protected $listsManager;
    /** @var WordRepository */
    protected $wordsRepo;

    public function setContainer(ContainerInterface $container = null)
    {
        parent::setContainer($container);

        $this->listRepo = $this->getRepository('SixtyNineCloudBundle:WordsList');
        $this->listsManager = $this->get('sn_cloud.word_lists_manager');
        $this->wordsRepo = $this->getRepository('SixtyNineCloudBundle:WordsList');
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

        $filtersForm = $this->createForm('SixtyNine\CloudBundle\Form\Forms\ImportUrlFormType');
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
            $this->listsManager->importText($list, $text);

            return $this->redirectToRoute('sn_words_view', array('id' => $list->getId()));
        }

        return $this->createNotFoundException();
    }

    /**
     * Import words from an URL into the words list.
     * @param Request $request
     * @param WordsList $list
     * @return RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function importWordsAction(Request $request, WordsList $list)
    {
        $form = $this->createForm('SixtyNine\CloudBundle\Form\Forms\ImportUrlFormType');
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $url = $form->get('url')->getData();

            $filters = $this->listsManager->getFiltersFromData($form->getData());
            $this->listsManager->importUrl($list, $url, 100, $filters);

            return $this->redirectToRoute('sn_words_view', array('id' => $list->getId()));
        }

        return $this->render(
            'SixtyNineCloudBundle:Default:add-words.html.twig',
            array(
                'form' => $form->createView(),
            )
        );
    }

    public function randomizeOrientationsAction(WordsList $list, $orientation)
    {
        $this->listRepo->randomizeWordsOrientation($list, (int)$orientation);
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

        $this->listRepo->randomizeWordsColors($list, $palette, $type);

        return $this->redirectToRoute('sn_words_view', array('id' => $list->getId()));
    }
}

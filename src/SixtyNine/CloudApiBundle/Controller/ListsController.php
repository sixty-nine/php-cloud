<?php

namespace SixtyNine\CloudApiBundle\Controller;

use FOS\RestBundle\Controller\Annotations as FOS;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;
use SixtyNine\CloudApiBundle\Form\Type\WordFormType;
use SixtyNine\CloudBundle\Entity\Word;
use SixtyNine\CloudBundle\Entity\WordsList;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * @FOS\NamePrefix("cloud_api_")
 */
class ListsController extends FOSRestController
{
    /** @var \SixtyNine\CloudBundle\Manager\WordListsManager */
    protected $listsManager;

    /** @var \Symfony\Component\Form\FormFactory */
    protected $formFactory;

    public function setContainer(ContainerInterface $container = null)
    {
        parent::setContainer($container);
        // TODO DI would be better, but then the controller needs to be a service
        $this->listsManager = $this->get('sn_cloud.word_lists_manager');
        $this->formFactory = $this->get('form.factory');
    }

    public function cgetListsAction()
    {
        $data = $this->listsManager->getUserLists($this->getUser());
        return $this->handleView($this->view($data, 200));
    }

    public function getListAction($id)
    {
        $list = $this->getMyList($id);
        return $this->handleView($this->view($list, 200));
    }

    public function deleteListAction($id)
    {
        $list = $this->getMyList($id);
        $this->listsManager->deleteList($list);
        return $this->handleView($this->view(null, 204));
    }

    /**
     * @FOS\Get("/lists/{id}/words")
     */
    public function getWordsAction($id)
    {
        $list = $this->getMyList($id);
        return $this->handleView($this->view($list->getWords(), 200));
    }

    /**
     * @FOS\Post("/lists/{id}/words/sort")
     */
    public function sortWordsAction(Request $request, $id)
    {

        $list = $this->getMyList($id);
        $sortBy = $request->get('sortBy');
        $order = $request->get('order');
        $list = $this->listsManager->sortWords($list, $sortBy, $order);
        return $this->handleView($this->view($list, 200));
    }

    /**
     * @FOS\Get("/lists/{id}/words/{wordId}")
     */
    public function getWordAction($id, $wordId)
    {
        $word = $this->getMyWord($id, $wordId);
        return $this->handleView($this->view($word, 200));
    }

    /**
     * @FOS\Put("/lists/{id}/words/{wordId}")
     */
    public function putWordAction(Request $request, $id, $wordId)
    {
        $word = $this->getMyWord($id, $wordId);
        $data = json_decode($request->getContent(), true);

        if ($this->isValidData(new WordFormType(), $data, $request)) {
            $this->listsManager->saveWord($word, $data);
            return $this->handleView($this->view($word, 200));
        }

        throw new BadRequestHttpException();
    }

    /**
     * @FOS\Delete("/lists/{id}/words/{wordId}")
     */
    public function deleteWordAction($id, $wordId)
    {
        $word = $this->getMyWord($id, $wordId);
        $this->listsManager->deleteWord($word);
        return $this->handleView($this->view(null, 204));
    }

    /**
     * @FOS\Get("/lists/{id}/words/{wordId}/toggle-orientation")
     */
    public function toggleWordOrientationAction($id, $wordId)
    {
        $word = $this->getMyWord($id, $wordId);
        $this->listsManager->toggleWordOrientation($word);
        return $this->handleView($this->view($word, 200));
    }

    /**
     * @FOS\Get("/lists/{id}/words/{wordId}/count/inc")
     */
    public function increaseWordCountAction($id, $wordId)
    {
        $word = $this->getMyWord($id, $wordId);
        $this->listsManager->increaseWordCount($word);
        return $this->handleView($this->view($word, 200));
    }

    /**
     * @FOS\Get("/lists/{id}/words/{wordId}/count/dec")
     */
    public function decreaseWordCountAction($id, $wordId)
    {
        $word = $this->getMyWord($id, $wordId);
        $this->listsManager->decreaseWordCount($word);
        return $this->handleView($this->view($word, 200));
    }

    /**
     * Get the WordsList given by $id.
     * Throws an exception if the list does not exist or the current user
     * is not the owner of the list.
     *
     * @param int $id
     * @return null|WordsList
     * @throws \Symfony\Component\Security\Core\Exception\AccessDeniedException
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    protected function getMyList($id)
    {
        if (null === $list = $this->listsManager->getList($id)) {
            throw $this->createNotFoundException('List not found');
        }

        if ($list->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException();
        }

        return $list;
    }

    /**
     * Get the Word given by $wordId then check it belongs to the WordsList given
     * by $listId.
     * Throws an exception if the word does not exist or the current user is not
     * the owner of the list containing the word.
     * @param int $listId
     * @param int $wordId
     * @return null|Word
     * @throws \Symfony\Component\Security\Core\Exception\AccessDeniedException
     * @throws \Symfony\Component\HttpKernel\Exception\BadRequestHttpException
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    protected function getMyWord($listId, $wordId)
    {
        if (null === $word = $this->listsManager->getWord($wordId)) {
            throw $this->createNotFoundException('Word not found');
        }

        if ((int)$listId !== $word->getList()->getId()) {
            throw new BadRequestHttpException('Mismatch');
        }

        if ($word->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException();
        }

        return $word;
    }

    /**
     * Validate the given $data against the $formType form.
     * @param $formType
     * @param $data
     * @param Request $request
     * @return bool
     */
    public function isValidData($formType, $data, Request $request)
    {
        $form = $this->formFactory->create($formType, $data, array('method' => $request->getMethod()));
        $form->submit($request->get($form->getName()));
        return $form->isValid();
    }
}

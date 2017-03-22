<?php

namespace SixtyNine\CloudApiBundle\Controller;

use FOS\RestBundle\Controller\Annotations as FOS;
use FOS\RestBundle\Controller\FOSRestController;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use SixtyNine\CloudBundle\Entity\Cloud;
use SixtyNine\CloudBundle\Entity\Word;
use SixtyNine\CloudBundle\Entity\WordsList;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

abstract class ApiController extends FOSRestController
{
    /** @var \SixtyNine\CloudBundle\Manager\WordListsManager */
    protected $listsManager;

    /** @var \SixtyNine\CloudBundle\Manager\CloudManager */
    protected $cloudManager;

    /** @var \Symfony\Component\Form\FormFactory */
    protected $formFactory;

    public function setContainer(ContainerInterface $container = null)
    {
        parent::setContainer($container);
        // TODO DI would be better, but then the controller needs to be a service
        $this->listsManager = $this->get('sn_cloud.word_lists_manager');
        $this->cloudManager = $this->get('sn_cloud.cloud_manager');
        $this->formFactory = $this->get('form.factory');
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
     * Get the Cloud given by $id.
     * Throws an exception if the cloud does not exist or the current user
     * is not the owner of the cloud.
     *
     * @param int $id
     * @return null|Cloud
     * @throws \Symfony\Component\Security\Core\Exception\AccessDeniedException
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function getMyCloud($id)
    {
        if (null === $cloud = $this->cloudManager->getCloud($id)) {
            throw $this->createNotFoundException('Cloud not found');
        }

        if ($cloud->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException();
        }

        return $cloud;
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
    protected function isValidData($formType, $data, Request $request)
    {
        $form = $this->formFactory->create($formType, $data, array('method' => $request->getMethod()));
        $form->submit($data);
        return $form->isValid();
    }
}

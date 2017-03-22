<?php

namespace SixtyNine\CloudApiBundle\Controller;

use FOS\RestBundle\Controller\Annotations as FOS;
use FOS\RestBundle\Controller\FOSRestController;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use SixtyNine\CloudBundle\Entity\Cloud;
use SixtyNine\CloudBundle\Entity\CloudWord;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * @FOS\NamePrefix("cloud_api_")
 */
class CloudsController extends ApiController
{
    /**
     * Get the clouds of the current user.
     * @return \Symfony\Component\HttpFoundation\Response
     * @ApiDoc(
     *      section="Clouds",
     *      statusCodes={
     *          200="Successful",
     *      }
     * )
     */
    public function cgetCloudsAction()
    {
        $data = $this->cloudManager->getClouds($this->getUser());
        return $this->handleView($this->view($data, 200));
    }

    /**
     * Delete a cloud.
     * @ApiDoc(
     *      section="Clouds",
     *      statusCodes={
     *          200="Successful",
     *          403="Not authorized",
     *          404="Not found"
     *     }
     * )
     */
    public function deleteCloudAction($id)
    {
        $cloud = $this->getMyCloud($id);
        $this->cloudManager->deleteCloud($cloud);
        return $this->handleView($this->view(null, 204));
    }
}

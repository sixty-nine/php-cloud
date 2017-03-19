<?php

namespace SixtyNine\CloudBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class CloudController extends Controller
{
    public function indexAction(Request $request)
    {
        $form = $this->createForm(
            'SixtyNine\CloudBundle\Form\Forms\CreateCloudFormType',
            array()
        );

        $form->handleRequest($request);

        if ($form->isValid()) {

            die('valid');
        }

        return $this->render(
            'SixtyNineCloudBundle:Cloud:index.html.twig',
            array(
                'form' => $form->createView(),
            )
        );
    }
}

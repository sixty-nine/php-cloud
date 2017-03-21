<?php

namespace SixtyNine\CloudBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    public function indexAction(Request $request)
    {
        return $this->render(
            'SixtyNineCloudBundle:Default:index.html.twig',
            array(
            )
        );
    }
}

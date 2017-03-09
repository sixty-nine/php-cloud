<?php

namespace SixtyNine\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller as BaseController;

abstract class Controller extends BaseController
{
    protected function getRepository($name)
    {
        return $this->getDoctrine()->getRepository($name);
    }
}

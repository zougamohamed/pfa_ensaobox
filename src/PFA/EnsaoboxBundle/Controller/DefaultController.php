<?php

namespace PFA\EnsaoboxBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('PFAEnsaoboxBundle:Default:index.html.twig', array('name' => $name));
    }
}

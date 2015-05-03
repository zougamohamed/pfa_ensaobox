<?php

namespace PFA\EnsaoboxBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class DashboardController extends Controller
{
    public function indexAction()
    {
        return $this->render('PFAEnsaoboxBundle:dashboard:index.html.twig');
    }

    public function addDocumentAction()
    {
        return new response('ICI VOUS DEVELOPPEZ l\'ajout d\'un document, cette action n\'est accessible que par les professeurs');
    }

    public function adminAction()
    {
        return new Response('ICI VOUS DEVELOPPEZ la page l\'administrateur');
    }
}

<?php

namespace PFA\EnsaoboxBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use PFA\EnsaoboxBundle\Entity\Document;
use Symfony\Component\HttpFoundation\Request;

class DashboardController extends Controller
{
    public function indexAction()
    {

        return $this->render('PFAEnsaoboxBundle:dashboard:index.html.twig');
    }
    public function ajouterAction(Request $request)
    {
        if($request->getMethod()=='POST')
        {

        }
        else
        {

        }
    	$document=new Document();
    	// On crée le FormBuilder grâce au service form factory
	    $formBuilder = $this->get('form.factory')->createBuilder('form', $document);

	    // On ajoute les champs de l'entité que l'on veut à notre formulaire
	    $formBuilder
	      ->add('file','file')
	    ;

        // Pour l'instant, pas de candidatures, catégories, etc., on les gérera plus tard

        // À partir du formBuilder, on génère le formulaire
        $form = $formBuilder->getForm();
        return $this->render('PFAEnsaoboxBundle:addFiles:addFiles.html.twig', array(
      	'form' => $form->createView(),
      	));
    }
}

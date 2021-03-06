<?php

namespace PFA\EnsaoboxBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use PFA\EnsaoboxBundle\Entity\Document;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class DashboardController extends Controller
{
    public function indexAction(Request $request )
    {
        $filiere= $this->getUser()->getFilieres();
        $niveau= $this->getUser()->getClasses();
        $userType= $this->getUser()->getRoles()[0];

        $request->getSession()->set('filiere',$filiere->getNomFiliere());
        $request->getSession()->set('niveau',$niveau->getNomClasse());
        $request->getSession()->set('utilisateur',$userType);


        $repository = $this->getDoctrine()->getRepository("PFAEnsaoboxBundle:Document");
        $query = $repository->createQueryBuilder('documentPartage')
            ->where('documentPartage.filieres = :filiere')
            ->setParameter('filiere',$filiere->getId())
            ->andWhere('documentPartage.classes = :classe')
            ->setParameter('classe', $niveau->getId())
            ->orderBy('documentPartage.id', 'DESC')
            ->setMaxResults(12)
            ->getQuery();
        $documentEnregistreByName = $query->getResult();
//        var_dump($documentEnregistreByName);
        return $this->render('PFAEnsaoboxBundle:dashboard:index.html.twig', array(
            'documentPartage'=>$documentEnregistreByName
        ));

        $user = $this->getUser();
        $request->getSession()->set('user',$user);

        if ($this->get('security.context')->isGranted('ROLE_ADMIN'))
            return $this->redirect($this->generateUrl('pfa_ensaobox_admin'));


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

    public function addDocumentAction()
    {
        return new response('ICI VOUS DEVELOPPEZ l\'ajout d\'un document, cette action n\'est accessible que par les professeurs');
    }

    public function adminAction()
    {
        return new Response('ICI VOUS DEVELOPPEZ la page l\'administrateur');
    }
}

<?php

namespace PFA\EnsaoboxBundle\Controller;

use PFA\EnsaoboxBundle\Form\DocumentType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use PFA\EnsaoboxBundle\Entity\Document;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class UploadController extends Controller
{
    public function ajouterAction(Request $request)
    {
        $documentTeleccharger=new Document();
          $form=$this->createFormBuilder($documentTeleccharger)
              ->add('filieres','entity',array('class'=>'PFA\EnsaoboxBundle\Entity\Filieres','property'=>'nomFiliere',))
              ->add('classes','entity',array('class'=>'PFA\EnsaoboxBundle\Entity\Classes','property'=>'nomClasse',))
              ->add('matieres','entity',array('class'=>'PFA\EnsaoboxBundle\Entity\Matieres','property'=>'nomMatiere',))
              ->add('name','text',array('label'=>'Nouvelle matiére','required'    => false))
              ->add('file')
              ->add('envoyer','submit')
              ->getForm();
//        var_dump($form);
        $error="";
        if ($request->isMethod('POST'))
        {
            $form->handleRequest($request);

//            var_dump($documentTeleccharger);
            if ($form->isValid()) {
                $documentEnregistreByName=$this->getDoctrine()->getRepository("PFAEnsaoboxBundle:Document")->findOneBy(array('name'=>$form['name']->getData()));
                var_dump($documentEnregistreByName);

                if(!empty($documentEnregistreByName))
                {
                    global $error;
                    $error="le fichier existe deja merci de renemer votre fichier ";
                }
                else
                {
                    var_dump('je suis dans else');
                $em = $this->getDoctrine()->getManager();
                $documentTeleccharger->upload();
                $em->persist($documentTeleccharger);
                $em->flush();
                $this->redirect($this->generateUrl('pfa_ensaobox_ajouter_files'));
//                return $this->redirect($this->generateUrl('pfa_ensaobox_ajouter_files', array("doc"=>$documentEnregistre)));
//                return new response('hhhhh'.print_r($documentTeleccharger));
                }
            }
        }
        $documentEnregistre=$this->getDoctrine()->getRepository("PFAEnsaoboxBundle:Document")->findAll();

        return $this->render('PFAEnsaoboxBundle:addFiles:addFiles.html.twig', array(
            'form' => $form->createView(),"doc"=>$documentEnregistre,'error'=>$error
        ));
//        $documentTeleccharger=new Document();
//        $form=$this->createForm(new DocumentType(),$documentTeleccharger);
//        $form=$this->handleRequest($request);
//        if($form->handleRequest($request)->isValid())
//        {
//            $em=$this->getDoctrine()->getManager();
//            $em->persist($documentTeleccharger);
//            $em->flush();
//        }
//        return new response('je suis la');
//        return $this->render('PFAEnsaoboxBundle:addFiles:addFiles.html.twig',array('uploadedUrl'=>$uploadedUrl));

    }
}

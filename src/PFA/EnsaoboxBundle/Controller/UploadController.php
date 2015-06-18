<?php

namespace PFA\EnsaoboxBundle\Controller;

use PFA\EnsaoboxBundle\Entity\Matieres;
use PFA\EnsaoboxBundle\Form\DocumentType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use PFA\EnsaoboxBundle\Entity\Document;
use Symfony\Component\HttpFoundation\Request;

class UploadController extends Controller
{
    public function ajouterAction(Request $request)
    {
        $userType= $this->getUser()->getRoles()[0];
        $request->getSession()->set('utilisateur',$userType);
        $matieresDeChaqueProf=array();
        $matiereEnregistreByNamex = $this->getDoctrine()->getRepository("PFAEnsaoboxBundle:Matieres")->findBy(array('professeur' => $this->getUser()->getUsername()));
        foreach($matiereEnregistreByNamex as $x)
        {
            $matieresDeChaqueProf[]= $x->getNomMatiere();
            var_dump($matieresDeChaqueProf);
            echo '<br>';
        }
//        var_dump($matiereEnregistreByNamex->getNomMatiere());
        $error="";
        $userNameSession=$this->getUser()->getUsername();
        $newMatiere=new Matieres();
        $documentTeleccharger=new Document();
//        $form=$this->createForm(new DocumentType(),$documentTeleccharger);
        //----------------------------------------------------------------------
        $form = $this->createFormBuilder($documentTeleccharger)
            ->add('filieres','entity',array('class'=>'PFA\EnsaoboxBundle\Entity\Filieres','property'=>'nomFiliere','multiple'  => false,'attr' => array('class' => 'form-control','style' => 'background-color:rgb(250, 255, 189);color:black;font-size:15px;  ')))
            ->add('classes','entity',array('class'=>'PFA\EnsaoboxBundle\Entity\Classes','property'=>'nomClasse','attr' => array('class' => 'form-control','style' => 'background-color:rgb(250, 255, 189);color:black;font-size:15px;  ')))
            ->add('matieres','entity',array('class'=>'PFA\EnsaoboxBundle\Entity\Matieres','property'=>'nomMatiere','attr' => array('class' => 'form-control','style' => 'background-color:rgb(250, 255, 189);color:black;font-size:15px;  ')))
            ->add('name','text',array('label'=>'Nouvelle matiére','required'    => false,'attr' => array('class' => 'form-control' ,'style' => 'background-color:rgb(250, 255, 189);color:black;font-size:15px;  ')))
            ->add('file','file',array('required' => true,'attr'     =>   array('style' => 'font-size:17px;')))
            ->add('envoyer','submit',array('attr' => array('class' => 'btn btn-shadow btn-success','style' => 'font-size:17px ')))
            ->getForm();
        //----------------------------------------------------------------------

        if ($request->isMethod('POST'))
        {
            $form->handleRequest($request);
            if ($form->isValid()) {

                if($form['file']->getData()!=null)
                {
                    if($form['name']->getData()!=null)
                    {
                        $matiereEnregistreByName = $this->getDoctrine()->getRepository("PFAEnsaoboxBundle:Matieres")->findOneBy(array('nomMatiere' => $form['name']->getData()));
//                        var_dump($matiereEnregistreByName);
                        if($matiereEnregistreByName==null) {
                            $newMatiere->setNomMatiere($form['name']->getData());
                            $newMatiere->setProfesseur($userNameSession);
                            $em = $this->getDoctrine()->getManager();
                            $em->persist($newMatiere);
                            $em->flush();
                            $documentTeleccharger->setMatieres($newMatiere);
                        }
                        else
                        {
                            global $error;
                            $error='le matiere existe deja . choisissez votre a partir de la liste ';

                        }
                    }
                    if($form['matieres']->getData()!=null)
                    {
                        $repository = $this->getDoctrine()->getRepository("PFAEnsaoboxBundle:Document");
                        $query = $repository->createQueryBuilder('p')
                            ->where('p.path = :path')
                            ->setParameter('path', $form['file']->getData()->getClientOriginalName())
                            ->andWhere('p.matieres = :matiere')
                            ->setParameter('matiere', $form['matieres']->getData()->getId())
                            ->andWhere('p.classes = :classe')
                            ->setParameter('classe', $form['classes']->getData()->getId())
                            ->andWhere('p.filieres = :filiere')
                            ->setParameter('filiere', $form['filieres']->getData()->getId())
                            ->getQuery();
                        $documentEnregistreByName = $query->getResult();
                        if (!empty($documentEnregistreByName))
                        {
                            global $error;
                            $error = "le fichier existe deja dans votre liste merci de renomer votre fichier ";
                        }
                        else
                        {

                            global $error;
                            $error = "le fichier est bien telecharger";
                            if($form['name']->getData()==null  && $form['matieres']->getData()!=null)
                            {
                                $documentTeleccharger->name='empty';
                                $documentTeleccharger->upload($form['filieres']->getData()->getNomFiliere() . '/' . $form['classes']->getData()->getNomClasse() . '/' .$this->getUser()->getUsername().'/'. $form['matieres']->getData()->getNomMatiere());
                                global $error;
                                $documentTeleccharger->setProfesseur($userNameSession);
                                //................................................
                                $em = $this->getDoctrine()->getManager();
                                $em->persist($documentTeleccharger);
                                $em->flush();

                            }
                            elseif($form['name']->getData()!=null &&($form['matieres']->getData()!=null || $form['matieres']->getData()==null ))
                            {
                                $documentTeleccharger->upload($form['filieres']->getData()->getNomFiliere() . '/' . $form['classes']->getData()->getNomClasse(). '/' .$this->getUser()->getUsername() . '/' . $form['name']->getData());
                                global $error;
                                $documentTeleccharger->setProfesseur($userNameSession);

                                $em = $this->getDoctrine()->getManager();
                                $em->persist($documentTeleccharger);
                                $em->flush();
                            }
                            else
                            {
                                global $error;
                                $error='vous devez choisir votre matiere si il nexiste dans la liste vous devez linserer ';
                            }



                            $this->redirect($this->generateUrl('pfa_ensaobox_ajouter_files'));
//                      return $this->redirect($this->generateUrl('pfa_ensaobox_ajouter_files', array("doc"=>$documentEnregistre)));
//                      return new response('hhhhh'.print_r($documentTeleccharger));
                        }
                    }
                    else
                    {
                        global $error;
                        $error = "le fichier est bien telecharger";
                        if($form['name']->getData()==null  && $form['matieres']->getData()!=null)
                        {
                            $documentTeleccharger->name='empty';
                            $documentTeleccharger->upload($form['filieres']->getData()->getNomFiliere() . '/' . $form['classes']->getData()->getNomClasse() . '/' .$this->getUser()->getUsername().'/'. $form['matieres']->getData()->getNomMatiere());
                            global $error;
                            $documentTeleccharger->setProfesseur($userNameSession);
                            //................................................
                            $em = $this->getDoctrine()->getManager();
                            $em->persist($documentTeleccharger);
                            $em->flush();

                        }
                        elseif($form['name']->getData()!=null &&($form['matieres']->getData()!=null || $form['matieres']->getData()==null ))
                        {
                            $documentTeleccharger->upload($form['filieres']->getData()->getNomFiliere() . '/' . $form['classes']->getData()->getNomClasse(). '/' .$this->getUser()->getUsername() . '/' . $form['name']->getData());
                            global $error;
                            $documentTeleccharger->setProfesseur($userNameSession);

                            $em = $this->getDoctrine()->getManager();
                            $em->persist($documentTeleccharger);
                            $em->flush();
                        }
                        else
                        {
                            global $error;
                            $error='vous devez choisir votre matiere si il nexiste dans la liste vous devez linserer ';
                        }



                        $this->redirect($this->generateUrl('pfa_ensaobox_ajouter_files'));
//                      return $this->redirect($this->generateUrl('pfa_ensaobox_ajouter_files', array("doc"=>$documentEnregistre)));
//                      return new response('hhhhh'.print_r($documentTeleccharger));

                    }

                }
                else
                {
                    global $error;
                    $error="veulliez telecharger un fichier s'il vous plait";
                }
            }
        }
//        $documentEnregistre2=$this->getDoctrine()->getRepository("PFAEnsaoboxBundle:Document")->findAll();
        $documentEnregistre = $this->getDoctrine()->getRepository("PFAEnsaoboxBundle:Document")->findBy(array('professeur' => $userNameSession));

        return $this->render('PFAEnsaoboxBundle:addFiles:addFiles.html.twig', array(
            'form' => $form->createView(),"doc"=>$documentEnregistre,'error'=>$error,'userNameSession'=>$userNameSession
        ));

    }
}

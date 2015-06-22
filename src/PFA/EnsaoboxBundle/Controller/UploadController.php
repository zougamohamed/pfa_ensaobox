<?php
namespace PFA\EnsaoboxBundle\Controller;
use PFA\EnsaoboxBundle\Entity\Matieres;
use PFA\EnsaoboxBundle\Form\DocumentType;
use Swift_Message;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use PFA\EnsaoboxBundle\Entity\Document;
use Symfony\Component\BrowserKit\Response;
use Symfony\Component\CssSelector\Parser\Reader;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Validator\Constraints\Null;

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
        }
        $matieresDeChaqueProf[]='NOUVELLE MATIERE';
        $stop=false;
        $error="";
        $info="";
        $userNameSession=$this->getUser()->getUsername();
        $newMatiere=new Matieres();
        $documentTeleccharger=new Document();
        //----------------------------------------------------------------------
        $form = $this->createFormBuilder($documentTeleccharger)
            ->add('filieres','entity',array('class'=>'PFA\EnsaoboxBundle\Entity\Filieres','property'=>'nomFiliere','multiple'  => false,'attr' => array('class' => 'form-control','style' => 'background-color:rgb(250, 255, 189);color:black;font-size:15px;  ')))
            ->add('classes','entity',array('class'=>'PFA\EnsaoboxBundle\Entity\Classes','property'=>'nomClasse','label' => 'Niveau','attr' => array('class' => 'form-control','style' => 'background-color:rgb(250, 255, 189);color:black;font-size:15px;  ')))
//            ->add('matieres','entity',array('class'=>'PFA\EnsaoboxBundle\Entity\Matieres','property'=>'nomMatiere','attr' => array('class' => 'form-control','style' => 'background-color:rgb(250, 255, 189);color:black;font-size:15px;  ')))
//            ->add('matieres', 'choice', array( 'choices' => $matieresDeChaqueProf, 'attr' => array('class' => 'form-control', 'style' => 'background-color:rgb(250, 255, 189);color:black;font-size:15px;  ') ))
            ->add('matieres','entity', array(
                'class' => 'PFA\EnsaoboxBundle\Entity\Matieres','property'=>'nomMatiere','attr' => array('class' => 'form-control','style' => 'background-color:rgb(250, 255, 189);color:black;font-size:15px;  '),
                'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->where('u.professeur=:prof')
                        ->setParameter('prof',$this->getUser()->getUsername())
                        ->orderBy('u.nomMatiere', 'DESC');
                },
            ))
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
                        if($matiereEnregistreByName==null) {
                            $newMatiere->setNomMatiere($form['name']->getData());
                            $newMatiere->setProfesseur($userNameSession);
                            $em = $this->getDoctrine()->getManager();
                            $em->persist($newMatiere);
                            $em->flush();
                            $documentTeleccharger->setMatieres($newMatiere);
                        }
//                        else
//                        {
//                            global $error;
//                            $error='la matière existe déja . choisissez la matière à partir de la liste ';
//                            global $stop;
//                            $stop=true;
//
//                        }
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
                            $error = "le fichier existe déja dans votre répertoitre !";
                        }
                        else
                        {
                            if($form['name']->getData()==null  && $form['matieres']->getData()!=null)
                            {
                                global $info;
                                $info = "le fichier est bien téléchargé";
                                $documentTeleccharger->name='empty';
                                $documentTeleccharger->upload($form['filieres']->getData()->getNomFiliere() . '/' . $form['classes']->getData()->getNomClasse() . '/' .$this->getUser()->getUsername().'/'. $form['matieres']->getData()->getNomMatiere());
                                global $error;
                                $documentTeleccharger->setProfesseur($userNameSession);
                                //................................................
                                $em = $this->getDoctrine()->getManager();
                                $em->persist($documentTeleccharger);
                                $em->flush();

                                //Partie Envoi des emails : ************************************************************

                                $repository = $this->getDoctrine()->getRepository("PFAEnsaoboxBundle:User");
                                $query = $repository->createQueryBuilder('x')
                                    ->where('x.filieres = :filiere')
                                    ->setParameter('filiere',$form['filieres']->getData())
                                    ->andWhere('x.classes = :classe')
                                    ->setParameter('classe', $form['classes']->getData())
                                    ->getQuery();
                                $listeEtudiants = $query->getResult();

                                $message= Swift_Message::newInstance()
                                    ->setSubject('EnsaoBox : Nouveau Cour ajoute(e) par '.$this->getUser()->getUsername().' ('. $form['matieres']->getData()->getNomMatiere().')')
                                    ->setFrom('ensaobox@gmail.com')
                                    ->setTo('zougamohamed@gmail.com')
                                    ->setCharset('utf-8')
                                    ->setContentType('text/html')
                                    ->setBody($this->renderView('PFAEnsaoboxBundle:email:email.html.twig'));

                                foreach($listeEtudiants as $user) {
                                    if ($user->getEmailCanonical()!=Null)
                                    $message->addTo($user->getEmailCanonical());
                                }

                                $this->get('mailer')->send($message);

                                // Fin partie Envois des Emails ***********************************************************
                            }
                            elseif($form['name']->getData()!=null &&($form['matieres']->getData()!=null || $form['matieres']->getData()==null ))
                            {
                                global $info;
                                $info = "le fichier est bien téléchargé";
                                $documentTeleccharger->upload($form['filieres']->getData()->getNomFiliere() . '/' . $form['classes']->getData()->getNomClasse(). '/' .$this->getUser()->getUsername() . '/' . $form['name']->getData());
                                $documentTeleccharger->setProfesseur($userNameSession);
                                $em = $this->getDoctrine()->getManager();
                                $em->persist($documentTeleccharger);
                                $em->flush();

                                //Partie Envoi des emails : ************************************************************

                                $repository = $this->getDoctrine()->getRepository("PFAEnsaoboxBundle:User");
                                $query = $repository->createQueryBuilder('x')
                                    ->where('x.filieres = :filiere')
                                    ->setParameter('filiere',$form['filieres']->getData())
                                    ->andWhere('x.classes = :classe')
                                    ->setParameter('classe', $form['classes']->getData())
                                    ->getQuery();
                                $listeEtudiants = $query->getResult();

                                $message= Swift_Message::newInstance()
                                    ->setSubject('EnsaoBox : Nouveau Cour ajoute(e) par '.$this->getUser()->getUsername().' ('. $form['name']->getData().')')
                                    ->setFrom('ensaobox@gmail.com')
                                    ->setTo('zougamohamed@gmail.com')
                                    ->setCharset('utf-8')
                                    ->setContentType('text/html')
                                    ->setBody($this->renderView('PFAEnsaoboxBundle:email:email.html.twig'));

                                foreach($listeEtudiants as $user) {
                                    if ($user->getEmailCanonical()!=Null)
                                        $message->addTo($user->getEmailCanonical());
                                }

                                $this->get('mailer')->send($message);

                                // Fin partie Envois des Emails ***********************************************************
                            }
//                            else
//                            {
//                                global $error;
//                                $error='vous devez choisir votre matière si il n\'existe dans la liste vous devez l\'insérer ';
//                            }
                            $this->redirect($this->generateUrl('pfa_ensaobox_ajouter_files'));

                        }
                    }
                    else
                    {

                        if($form['name']->getData()==null  && $form['matieres']->getData()!=null)
                        {
                            global $info;
                            $info = "le fichier est bien téléchargé";
                            $documentTeleccharger->name='empty';
                            $documentTeleccharger->upload($form['filieres']->getData()->getNomFiliere() . '/' . $form['classes']->getData()->getNomClasse() . '/' .$this->getUser()->getUsername().'/'. $form['matieres']->getData()->getNomMatiere());
                            $documentTeleccharger->setProfesseur($userNameSession);
                            //................................................
                            $em = $this->getDoctrine()->getManager();
                            $em->persist($documentTeleccharger);
                            $em->flush();

                            //Partie Envoi des emails : ************************************************************

                            $repository = $this->getDoctrine()->getRepository("PFAEnsaoboxBundle:User");
                            $query = $repository->createQueryBuilder('x')
                                ->where('x.filieres = :filiere')
                                ->setParameter('filiere',$form['filieres']->getData())
                                ->andWhere('x.classes = :classe')
                                ->setParameter('classe', $form['classes']->getData())
                                ->getQuery();
                            $listeEtudiants = $query->getResult();

                            $message= Swift_Message::newInstance()
                                ->setSubject('EnsaoBox : Nouveau Cour ajoute(e) par '.$this->getUser()->getUsername().' ('. $form['matieres']->getData()->getNomMatiere().')')
                                ->setFrom('ensaobox@gmail.com')
                                ->setTo('zougamohamed@gmail.com')
                                ->setCharset('utf-8')
                                ->setContentType('text/html')
                                ->setBody($this->renderView('PFAEnsaoboxBundle:email:email.html.twig'));

                            foreach($listeEtudiants as $user) {
                                if ($user->getEmailCanonical()!=Null)
                                    $message->addTo($user->getEmailCanonical());
                            }

                            $this->get('mailer')->send($message);

                            // Fin partie Envois des Emails ***********************************************************
                        }
                        elseif($form['name']->getData()!=null && ($form['matieres']->getData()!=null || $form['matieres']->getData()==null ))
                        {
                            global $info;
                            $info = "le fichier est bien téléchargé";
                            $documentTeleccharger->upload($form['filieres']->getData()->getNomFiliere() . '/' . $form['classes']->getData()->getNomClasse(). '/' .$this->getUser()->getUsername() . '/' . $form['name']->getData());
                            $documentTeleccharger->setProfesseur($userNameSession);
                            $em = $this->getDoctrine()->getManager();
                            $em->persist($documentTeleccharger);
                            $em->flush();

                            //Partie Envoi des emails : ************************************************************

                            $repository = $this->getDoctrine()->getRepository("PFAEnsaoboxBundle:User");
                            $query = $repository->createQueryBuilder('x')
                                ->where('x.filieres = :filiere')
                                ->setParameter('filiere',$form['filieres']->getData())
                                ->andWhere('x.classes = :classe')
                                ->setParameter('classe', $form['classes']->getData())
                                ->getQuery();
                            $listeEtudiants = $query->getResult();

                            $message= Swift_Message::newInstance()
                                ->setSubject('EnsaoBox : Nouveau Cour ajoute(e) par '.$this->getUser()->getUsername().' ('. $form['name']->getData().')')
                                ->setFrom('ensaobox@gmail.com')
                                ->setTo('zougamohamed@gmail.com')
                                ->setCharset('utf-8')
                                ->setContentType('text/html')
                                ->setBody($this->renderView('PFAEnsaoboxBundle:email:email.html.twig'));

                            foreach($listeEtudiants as $user) {
                                if ($user->getEmailCanonical()!=Null)
                                    $message->addTo($user->getEmailCanonical());
                            }

                            $this->get('mailer')->send($message);

                            // Fin partie Envois des Emails ***********************************************************
                        }
//                        else
//                        {
//                            global $error;
//                            $error='vous devez choisir votre matière si il n\'éxiste dans la liste vous devez l\'insérer ';
//                        }
                        $this->redirect($this->generateUrl('pfa_ensaobox_ajouter_files'));

                    }
                }
                else
                {
                    global $error;
                    $error="veulliez télécharger un fichier s'il vous plait";
                }
            }
        }
        $documentEnregistre = $this->getDoctrine()->getRepository("PFAEnsaoboxBundle:Document")->findBy(array('professeur' => $userNameSession));
        return $this->render('PFAEnsaoboxBundle:addFiles:addFiles.html.twig', array(
            'form' => $form->createView(),
            "doc"=>$documentEnregistre,
            'error'=>$error,
            'info'=>$info,
            'userNameSession'=>$userNameSession
        ));
    }
    public function supprimerAction(Request $request)
    {

       var_dump('ici on va traiter la supprission des fichier selectionner par le professeur');
       var_dump($request->request->get('id'));
        $em = $this->getDoctrine()->getManager();
        $cours = $em->getRepository("PFAEnsaoboxBundle:Document")->find($request->request->get('id'));
        $em->remove($cours);
        $em->flush();
        $url = $this->get('router')->generate('pfa_ensaobox_ajouter_files');
        return $this->redirect($url);
    }
}
<?php

namespace PFA\EnsaoboxBundle\Controller;


use PFA\EnsaoboxBundle\Entity\Classes;
use PFA\EnsaoboxBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use PHPExcel;
use PHPExcel_IOFactory;
use PHPExcel_Reader_Excel5;
use PHPExcel_Reader_Excel2007;
use PHPExcel_Cell;
use PHPExcel_Cell_DataType;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints\Date;


class AdminController extends Controller
{
    public function createUsersAction(Request $request, $f, $n, $u)
    {
        $filieres = array
        (
            '1'     => 'GI',
            '2'    => 'GTR',
            '3'   => 'GEII',
            '4'     => 'GE',
            '5' => 'GINDUS',
            '6'     => 'GC',
            '7'   => 'STPI'
        );

        $niveaux = array
        (
            '1' => '1ère année',
            '2' => '2ème année',
            '3' => '3ème année'
        );

        $form_create = $this->get('form.factory')->createNamedBuilder('create','form')
            ->add('filiere', 'choice', array( 'choices' => $filieres, 'attr' => array('class' => 'form-control', 'style' => 'background-color:rgb(250, 255, 189);color:black;font-size:15px;  ') ))
            ->add('niveau', 'choice', array( 'choices' => $niveaux, 'attr' => array('class' => 'form-control', 'style' => 'background-color:rgb(250, 255, 189);color:black;font-size:15px;  ')))
            ->add('file', 'file',array ( 'attr'     =>   array('style' => 'font-size:17px;')))
            ->add('save', 'submit', array('attr' => array('class' => 'btn btn-success','style' => 'font-size:17px ')))
            ->getForm();

        $form_update = $this->get('form.factory')->createNamedBuilder('update','form')
            ->add('filiere', 'choice', array( 'choices' => $filieres, 'attr' => array('class' => 'form-control', 'style' => 'background-color:rgb(250, 255, 189);color:black;font-size:15px;  ') ))
            ->add('niveau', 'choice', array( 'choices' => $niveaux, 'attr' => array('class' => 'form-control', 'style' => 'background-color:rgb(250, 255, 189);color:black;font-size:15px;  ')))
            ->add('delete', 'submit', array('attr' => array('class' => 'btn btn-danger','style' => 'font-size:17px ')))
            ->add('show', 'submit', array('attr' => array('class' => 'btn btn-success','style' => 'font-size:17px ')))
            ->getForm();

        $form_new = $this->get('form.factory')->createNamedBuilder('new','form')
            ->add('upgrade', 'submit', array('attr' => array('class' => 'btn btn-danger','style' => 'font-size:17px ')))
            ->getForm();

        //Si la requete provient du formulaire ou de l'un des bouttons de suppression
        if ($request->isMethod('POST') || $u != '' )
        {
            //Si la requete provient de l'un des bouttons de suppression
            if($u != '' && !$request->isMethod('POST'))
            {
                // on supprime l'utilisateur et on le redirige vers la page d'administration avec affichage de la liste
                $em = $this->getDoctrine()->getManager();
                $etudiants = $this->getDoctrine()->getRepository("PFAEnsaoboxBundle:User")->findBy(array('id' => intval($u)));
                foreach($etudiants as $etudiant)
                {
                    $em->remove($etudiant);
                }

                $em->flush();

//                $listeEtudiants = $this->getDoctrine()->getRepository("PFAEnsaoboxBundle:User")->findBy(array('filiere' => $f,
//                    'niveau' => $n));

                $repository = $this->getDoctrine()->getRepository("PFAEnsaoboxBundle:User");
                $query = $repository->createQueryBuilder('x')
                    ->where('x.filieres = :filiere')
                    ->setParameter('filiere',$f)
                    ->andWhere('x.classes = :classe')
                    ->setParameter('classe', $n)
                    ->getQuery();
                $listeEtudiants = $query->getResult();

                return $this->render('PFAEnsaoboxBundle:admin:createUsers.html.twig', array(
                    'form_create' => $form_create->createView(),
                    'form_update' => $form_update->createView(),
                    'form_new' => $form_new->createView(),
//                            'usersforms' => $users_update_form_view,
                    'error' => '',
                    'liste' => $listeEtudiants
                ));


            }
            // Si c'est le formulaire de création qui est submité
            if ($request->request->has('create'))
            {

                $form_create->handleRequest($request);

                if ($form_create->isValid())
                {
                    $factory = $this->get('security.encoder_factory');
                    $userManager = $this->get('fos_user.user_manager');

                    $user = $userManager->createUser();

                    $encoder = $factory->getEncoder($user);

                    $filiere = $form_create->get('filiere')->getData();
                    $niveau = $form_create->get('niveau')->getData();

                    //var_dump($niveau);
                    // $file = new UploadedFile();
                    foreach ($request->files as $uploadedFile)
                    {
                        $name = $uploadedFile['file']->getClientOriginalName();
                        $extension = $uploadedFile['file']->getClientMimeType();
                        //var_dump($extension); die;
                        // gestion des erreurs
                        $name = date('Y') . '_' . $name;

                        if (($extension != 'application/vnd.ms-excel') && ($extension != 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'))
                        {
                            return $this->render('PFAEnsaoboxBundle:admin:createUsers.html.twig', array(
                                'form_create' => $form_create->createView(),
                                'form_update' => $form_update->createView(),
                                'form_new' => $form_new->createView(),
                                'error' => 'Veuillez choisir un fichier excel !',
                            ));
                        }

                        if (file_exists('uploads/documents/' . $name))
                        {
                            return $this->render('PFAEnsaoboxBundle:admin:createUsers.html.twig', array(
                                'form_create' => $form_create->createView(),
                                'form_update' => $form_update->createView(),
                                'form_new' => $form_new->createView(),
                                'error' => 'Ce fichier existe déjà !',
                            ));
                        }
                        //var_dump( $file  ); die;

                        // uploadé le fichier
                        $file = $uploadedFile['file']->move('uploads/documents', $name);

                        $objPHPExcel = PHPExcel_IOFactory::load('uploads\documents\\' . $name);

                        foreach ($objPHPExcel->getWorksheetIterator() as $worksheet)
                        {
                            $worksheetTitle = $worksheet->getTitle();
                            $highestRow = $worksheet->getHighestRow(); // e.g. 10
                            $highestColumn = $worksheet->getHighestColumn(); // e.g 'F'
                            $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
                            $nbrColumns = ord($highestColumn) - 64;

                            //echo "<br>The worksheet ".$worksheetTitle." has ";
                            //echo $nrColumns . ' columns (A-' . $highestColumn . ') ';
                            //echo ' and ' . $highestRow . ' row.';
                            //echo '<br>Data: <table border="1"><tr>';

                            // Detection du point de départ des données.
                            //-------------------------------------

                            $startcol = -1;
                            $startrow = -1;

                            for ($row = 0; $row <= $highestRow; ++$row)
                            {
                                //echo '<tr>';
                                //$users[$row - 1] = new User();
                                for ($col = 0; $col < $highestColumnIndex; ++$col)
                                {
                                    $cell = $worksheet->getCellByColumnAndRow($col, $row);
                                    $val = $cell->getValue();
                                    $dataType = PHPExcel_Cell_DataType::dataTypeForValue($val);

                                    if ($val == "N°")
                                    {
                                        $startcol = $col;
                                        $startrow = $row + 1;
                                    }
                                    //echo '<td>' . $val . '<br>(Typ ' . $dataType . ')</td>';
                                }
                                //echo '</tr>';
                            }
                            if($startcol == -1)
                            {
                                return $this->render('PFAEnsaoboxBundle:admin:createUsers.html.twig', array(
                                    'form_create' => $form_create->createView(),
                                    'form_update' => $form_update->createView(),
                                    'form_new' => $form_new->createView(),
                                    'error' => 'Ce fichier excel ne contient pas la liste des étudiants !',
                                ));
                            }
                            //var_dump($startcol);
                            //var_dump($startrow);
                            //die;
                            //-------------------------------------

                            // Création d'un tableau d'etudiants à partir des données du fichier Excel
                            //-------------------------------------
                            $users = array();
                            $j = 0;
                            for ($row = $startrow; $row <= $highestRow; ++$row)
                            {

                                $user = new User();

                                // numero
                                $cell = $worksheet->getCellByColumnAndRow($startcol, $row);
                                $val = $cell->getValue();
                                $user->setNumero($val);


                                //password
                                $cell = $worksheet->getCellByColumnAndRow($startcol + 1, $row);
                                $val = $cell->getValue();
                                if($val != "")
                                {
                                    $password = $encoder->encodePassword($val, $user->getSalt());
                                    $user->setPassword($password);
                                }


                                else
                                {
                                    $password = $encoder->encodePassword("00000000", $user->getSalt());
                                    $user->setPassword($password);
                                }


                                //username
                                $cell = $worksheet->getCellByColumnAndRow($startcol + 2, $row);
                                $val = $cell->getValue();
                                $val = str_replace(" ","_",$val);
                                $arr = explode("_", $val);


                                $username = "";
                                $x = 0;
                                for ($i = count($arr) - 1; $i >= 0 ; $i--)
                                {
                                    if($arr[$i] != "")
                                    {
                                        if($x == 0)
                                        {
                                            $username = $arr[$i];
                                            $x=1;
                                        }
                                        else
                                        {
                                            $username = $arr[$i] . "_" .  $username ;
                                        }
                                    }
                                }
                                //var_dump(1);
                                $listeClasses = $this->getDoctrine()->getRepository("PFAEnsaoboxBundle:Classes")->findBy(array('id' => $niveau));
                                foreach($listeClasses as $niv)
                                {
                                    //$classe = new Classes();
                                    //$classe->setNomClasse($niv[''])
                                    //var_dump($niv);
                                    $user->setClasses($niv);
                                }

                                $listeFilieres = $this->getDoctrine()->getRepository("PFAEnsaoboxBundle:Filieres")->findBy(array('id' => $filiere));
                                foreach($listeFilieres as $fil)
                                {
                                    //var_dump($fil);
                                    $user->setFilieres($fil);
                                }

                                $user->setUsername($username);
                                $user->setRoles(array('ROLE_ETUDIANT'));
                                $user->setEnabled(true);

                                $users[$j] = $user;
                                $j++;
                            }
                            //var_dump(1); die;
                            //var_dump($users); die;

                            //--------------------------------------

                            //Insertion des étudiants en base de donnée.
                            //--------------------------------------------------
                            //$em = $this->getDoctrine()->getManager();
                            for ($i = 0; $i < count($users); $i++)
                            {
                                $userManager->updateUser($users[$i],true);
                            }
                            //$em->flush();
                            //--------------------------------------------------
                        }
                    }
                }

                return $this->render('PFAEnsaoboxBundle:admin:createUsers.html.twig', array(
                    'form_create' => $form_create->createView(),
                    'form_update' => $form_update->createView(),
                    'form_new' => $form_new->createView(),
                    'error' => '',
                    'success' => 'Les étudiants ont été généré avec succès !'
                ));
            }

            //si le formulaire d'update est submité (formulaire à droite)
            elseif ($request->request->has('update'))
            {
                $form_update->handleRequest($request);

                if ($form_update->isValid())
                {
                    // Si on clique sur afficher on affiche la liste
                    if ($form_update->get('show')->isClicked())
                    {
//                        $listeEtudiants = $this->getDoctrine()->getRepository("PFAEnsaoboxBundle:User")->findBy(array('filiere' => $form_update['filiere']->getData(),
//                            'niveau' => $form_update['niveau']->getData()));

                        $repository = $this->getDoctrine()->getRepository("PFAEnsaoboxBundle:User");
                        $query = $repository->createQueryBuilder('x')
                            ->where('x.filieres = :filiere')
                            ->setParameter('filiere',$form_update['filiere']->getData())
                            ->andWhere('x.classes = :classe')
                            ->setParameter('classe', $form_update['niveau']->getData())
                            ->getQuery();
                        $listeEtudiants = $query->getResult();

                        return $this->render('PFAEnsaoboxBundle:admin:createUsers.html.twig', array(
                            'form_create' => $form_create->createView(),
                            'form_update' => $form_update->createView(),
                            'form_new' => $form_new->createView(),
//                            'usersforms' => $users_update_form_view,
                            'error' => '',
                            'liste' => $listeEtudiants
                        ));
                    }
                    // si on clique sur supprimer on efface la liste
                    elseif($form_update->get('delete')->isClicked())
                    {
                        $em = $this->getDoctrine()->getManager();
//                        $listeEtudiants = $this->getDoctrine()->getRepository("PFAEnsaoboxBundle:User")->findBy(array('filiere' => $form_update['filiere']->getData(),
//                            'niveau' => $form_update['niveau']->getData()));

                        $repository = $this->getDoctrine()->getRepository("PFAEnsaoboxBundle:User");
                        $query = $repository->createQueryBuilder('x')
                            ->where('x.filieres = :filiere')
                            ->setParameter('filiere',$form_update['filiere']->getData())
                            ->andWhere('x.classes = :classe')
                            ->setParameter('classe', $form_update['niveau']->getData())
                            ->getQuery();
                        $listeEtudiants = $query->getResult();


                        foreach( $listeEtudiants as $user )
                        {
                            $em->remove($user);
                        }

                        $em->flush();

                        return $this->redirect($this->generateUrl('pfa_ensaobox_admin'));
                    }
                }
            }
        }
        // Si on reçoit la page pour la 1ere fois, on l'affiche
        return $this->render('PFAEnsaoboxBundle:admin:createUsers.html.twig', array(
            'form_create' => $form_create->createView(),
            'form_update' => $form_update->createView(),
            'form_new' => $form_new->createView(),
            'error'=> '',
        ));
    }
}
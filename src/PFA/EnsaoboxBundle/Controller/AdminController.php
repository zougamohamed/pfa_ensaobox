<?php

namespace PFA\EnsaoboxBundle\Controller;


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
    public function createUsersAction(Request $request)
    {
        $userType= $this->getUser()->getRoles()[0];
        $request->getSession()->set('utilisateur',$userType);
        $filieres = array
        (
            'GI'     => 'GI',
            'GTR'    => 'GTR',
            'GINDUS' => 'GINDUS',
            'GC'     => 'GC',
            'GE'     => 'GE',
            'GEII'   => 'GEII',
            'STPI'   => 'STPI'
        );

        $niveaux = array
        (
            '1' => '1er année',
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

        $form_new = $this->createFormBuilder()
            ->add('upgrade', 'submit', array('attr' => array('class' => 'btn btn-danger','style' => 'font-size:17px ')))
            ->getForm();

        if ($request->isMethod('POST'))
        {
            // Si c'est le formulaire de création qui est submité
            if ($request->request->has('create'))
            {

                $form_create->handleRequest($request);

                if ($form_create->isValid())
                {
                    $factory = $this->get('security.encoder_factory');
                    $user = new User();

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
                                'error' => 'Veuillez choisir un fichier excel !'
                            ));
                        }

                        if (file_exists('uploads/documents/' . $name))
                        {
                            return $this->render('PFAEnsaoboxBundle:admin:createUsers.html.twig', array(
                                'form_create' => $form_create->createView(),
                                'form_update' => $form_update->createView(),
                                'form_new' => $form_new->createView(),
                                'error' => 'Ce fichier existe déjà !'
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

                            $startcol = 0;
                            $startrow = 0;

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
                                $user->setUsername($username);
                                $user->setRoles(array('ROLE_ETUDIANT'));
                                $user->setNiveau($niveau);
                                $user->setFiliere($filiere);

                                $users[$j] = $user;
                                $j++;
                            }

                            //var_dump($users); die;

                            //--------------------------------------

                            //Insertion des étudiants en base de donnée.
                            //--------------------------------------
//                            $em = $this->getDoctrine()->getManager();
//
//                            for ($i = 0; $i < count($users); $i++)
//                            {
//                                $em->persist($users[$i]);
//                            }
//                            $em->flush();
                            //--------------------------------------


                        }

                    }

                    //ICI On récuperera nos données à partir du fichier excel excel
                    //$a = new PHPExcel();
                    //$objReader = \PHPExcel_IOFactory::createReader();
                    //var_dump($a); die;

                    //-------------------
                    //var_dump($_FILES['file']); die;
                }
            }
        }
        return $this->render('PFAEnsaoboxBundle:admin:createUsers.html.twig', array(
            'form_create' => $form_create->createView(),
            'form_update' => $form_update->createView(),
            'form_new' => $form_new->createView(),
            'error'=> ''
        ));
    }
}
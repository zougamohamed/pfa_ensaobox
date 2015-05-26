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


class AdminController extends Controller
{
    public function createUsersAction(Request $request)
    {
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

        $form = $this->createFormBuilder()
            ->add('filiere', 'choice', array( 'choices' => $filieres, 'attr' => array('class' => 'form-control', 'style' => 'background-color:rgb(250, 255, 189);color:black;font-size:15px;  ') ))
            ->add('niveau', 'choice', array( 'choices' => $niveaux, 'attr' => array('class' => 'form-control', 'style' => 'background-color:rgb(250, 255, 189);color:black;font-size:15px;  ')))
            ->add('file', 'file',array ( 'attr'     =>   array('style' => 'font-size:17px;')))
            ->add('save', 'submit', array('attr' => array('class' => 'btn btn-success','style' => 'font-size:17px ')))
            ->getForm();

        if ($request->isMethod('POST'))
        {
            $form->handleRequest($request);

            if ($form->isValid())
            {
               // $file = new UploadedFile();
                foreach($request->files as $uploadedFile) {
                    $name = $uploadedFile['file']->getClientOriginalName();
                    $file = $uploadedFile['file']->move('uploads/documents' , $name);
                    //var_dump( $uploadedFile['file']->guessExtension() ); die;
                    //if($uploadedFile['file']->getMimeType() != 'application/vnd.ms-excel')
                    //{
//
                    //    return $this->render('PFAEnsaoboxBundle:admin:createUsers.html.twig', array(
                    //        'form' => $form->createView(),
                    //        'error'=> 'PLEASE SELECT AN EXCEL FILE !'
                    //    ));
                    //}
                    $objPHPExcel = PHPExcel_IOFactory::load('uploads\documents\\'.$name);

                    foreach ($objPHPExcel->getWorksheetIterator() as $worksheet)
                    {
                        $worksheetTitle     = $worksheet->getTitle();
                        $highestRow         = $worksheet->getHighestRow(); // e.g. 10
                        $highestColumn      = $worksheet->getHighestColumn(); // e.g 'F'
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

                        for ($row = 0; $row <= $highestRow; ++ $row)
                        {
                            //echo '<tr>';
                            //$users[$row - 1] = new User();
                            for ($col = 0; $col < $highestColumnIndex; ++ $col)
                            {
                                $cell = $worksheet->getCellByColumnAndRow($col, $row);
                                $val = $cell->getValue();
                                $dataType = PHPExcel_Cell_DataType::dataTypeForValue($val);

                                if($val == "Nom")
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
                        $i = 0;
                        for ($row = $startrow; $row <= $highestRow; ++ $row)
                        {

                            $user = new User();

                                // nom
                                $cell = $worksheet->getCellByColumnAndRow($startcol , $row);
                                $val = $cell->getValue();
                                $user->setNom($val);


                                //prenom
                                $cell = $worksheet->getCellByColumnAndRow($startcol + 1, $row);
                                $val = $cell->getValue();
                                $user->setPrenom($val);

                                //email
                                $cell = $worksheet->getCellByColumnAndRow($startcol + 2, $row);
                                $val = $cell->getValue();
                                $user->setEmail($val);

                            $users[$i] = $user;
                            $i++;
                        }

                        var_dump($users); die;

                        //--------------------------------------

                        //Insertion des étudiants en base de donnée.
                        //--------------------------------------
//                        $em = $this->getDoctrine()->getManager();
//
//                        for($i = 0; $i < count($users); $i++)
//                        {
//                            $username =
//                            $em->persist($users[$i]);
//                        }
//                        $em->flush();
                        //--------------------------------------


                    }


                    //var_dump($a); die;
                }

                // ICI On récuperera nos données à partir du fichier excel excel
                //$a = new PHPExcel();
                //$objReader = \PHPExcel_IOFactory::createReader();
                //var_dump($a); die;
                
                //-------------------
                //var_dump($_FILES['file']); die;
            }
        }
        return $this->render('PFAEnsaoboxBundle:admin:createUsers.html.twig', array(
            'form' => $form->createView(),
            'error'=> ''
        ));
    }
}
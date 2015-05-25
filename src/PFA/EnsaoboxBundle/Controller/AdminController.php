<?php

namespace PFA\EnsaoboxBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use PHPExcel;
use Symfony\Component\HttpFoundation\File\UploadedFile;


class AdminController extends Controller
{
    public function createUsersAction(Request $request)
    {
        $filieres = array
        (
            'STPI'   => 'STPI',
            'GI'     => 'GI',
            'GTR'    => 'GTR',
            'GINDUS' => 'GINDUS',
            'GC'     => 'GC',
            'GE'     => 'GE',
            'GEII'   => 'GEII'
        );

        $niveaux = array
        (
            '1' => '1er année',
            '2' => '2ème année',
            '3' => '3ème année'
        );

        $form = $this->createFormBuilder()
            ->add('Filiere', 'choice', array( 'choices' => $filieres))
            ->add('Niveau', 'choice', array( 'choices' => $niveaux))
            ->add('file', 'file')
            ->add('save', 'submit')
            ->getForm();

        if ($request->isMethod('POST'))
        {
            $form->handleRequest($request);

            if ($form->isValid())
            {
               // $file = new UploadedFile();
                foreach($request->files as $uploadedFile) {
                    $name = $uploadedFile['file']->getClientOriginalName();
                    //$file = $uploadedFile['file']->move('uploads/documents' , $name);

                    if($uploadedFile['file']->getMimeType() != 'application/vnd.ms-excel')
                    {
                        return $this->render('PFAEnsaoboxBundle:admin:createUsers.html.twig', array(
                            'form' => $form->createView(),
                            'error'=> 'PLEASE SELECT AN EXCEL FILE !'
                        ));
                    }
                }

                // ICI On récuperera nos données à partir du fichier excel excel
                $a = new PHPExcel();
                var_dump($a); die;
                
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
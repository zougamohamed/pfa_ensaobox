<?php

namespace PFA\EnsaoboxBundle\Controller;


use PFA\EnsaoboxBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;



class ListeEtudiantsController extends Controller
{
    public function createListeAction(Request $request)
    {
        $filieres = array
        (
            'GI' => 'GI',
            'GTR' => 'GTR',
            'GINDUS' => 'GINDUS',
            'GC' => 'GC',
            'GE' => 'GE',
            'GEII' => 'GEII',
            'STPI' => 'STPI'
        );

        $niveaux = array
        (
            '1' => '1er année',
            '2' => '2ème année',
            '3' => '3ème année'
        );

        $form = $this->createFormBuilder()
            ->add('filiere', 'choice', array('choices' => $filieres, 'attr' => array('class' => 'form-control', 'style' => 'background-color:rgb(250, 255, 189);color:black;font-size:15px;  ')))
            ->add('niveau', 'choice', array('choices' => $niveaux, 'attr' => array('class' => 'form-control', 'style' => 'background-color:rgb(250, 255, 189);color:black;font-size:15px;  ')))
            ->add('submit', 'submit', array('attr' => array('class' => 'btn btn-success', 'style' => 'font-size:17px ')))
            ->getForm();

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $listeEtudiants = $this->getDoctrine()->getRepository("PFAEnsaoboxBundle:User")->findBy(array('filiere' => $form['filiere']->getData(),
                    'niveau' => $form['niveau']->getData()));
//                var_dump($form['filiere']->getData());
/*
                return $this->render('PFAEnsaoboxBundle:admin:liste.html.twig', array(
                    'form' => $form->createView(), "liste" => $listeEtudiants
                ));*/


                $html = $this->container->get('templating')->render('PFAEnsaoboxBundle:admin:liste.html.twig', array(
                     "liste" => $listeEtudiants
                ));

                $html2pdf = new \Html2Pdf_Html2Pdf('P','A4','fr');
                $html2pdf->pdf->SetAuthor('EnsaoBox');
                $html2pdf->pdf->SetTitle('Liste Etudiants '.$form['filiere']->getData().'niveau'.$form['niveau']->getData());
                $html2pdf->pdf->SetSubject('Liste etudiants avec photos');
                $html2pdf->pdf->SetDisplayMode('real');
                $html2pdf->writeHTML($html);
                $html2pdf->Output('Liste_Etudiants_'.$form['filiere']->getData().'_Niveau_'.$form['niveau']->getData().'.pdf');

//                $response = new Response();
//                $response->headers->set('Content-type' , 'application/pdf');
//
//                return $response;
                return $html2pdf;



            }

        }

        return $this->render('PFAEnsaoboxBundle:admin:createListe.html.twig', array(
            'form' => $form->createView(),
            'error'=> ''
        ));
    }

}

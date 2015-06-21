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

                return $this->render('PFAEnsaoboxBundle:admin:liste.html.twig', array(
                    'form' => $form->createView(), "liste" => $listeEtudiants
                ));

            }

        }

        return $this->render('PFAEnsaoboxBundle:admin:createListe.html.twig', array(
            'form' => $form->createView(),
            'error'=> ''
        ));
    }
}

<?php

namespace PFA\EnsaoboxBundle\Controller;

use PFA\EnsaoboxBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use PFA\EnsaoboxBundle\Form\UserType;

class UserController extends Controller
{

    public function viewAction($id)
    {
        // On récupère l'EntityManager
        $em = $this->getDoctrine()->getManager();

        // Pour récupérer un profil d'un user on utilise find()
        $user = $em->getRepository('PFAEnsaoboxBundle:User')->find($id);

        // On vérifie que l'user avec id existe !!
        if ($user === null) {
            return $this->render('PFAEnsaoboxBundle:User:notfound.html.twig');
        }

        // Puis modifiez la ligne du render comme ceci, pour prendre en compte les variables :
        return $this->render('PFAEnsaoboxBundle:User:view.html.twig', array(
            'user' => $user,
        ));
    }


    public function editAction($id, Request $request)
    {
        // de cette façon j'aurais les données de mon user dans les champs pour pouvoir les modifier
        $user = $this->getDoctrine()
            ->getManager()
            ->getRepository('PFAEnsaoboxBundle:User')
            ->find($id);

        if ($user === null) {
            return $this->render('PFAEnsaoboxBundle:User:notfound.html.twig');
        }

        //racourci pour création du fomulaire (externe) à partir de mon user (selon id)
        $form = $this->createForm(new UserType(), $user);

        if ($form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $request->getSession()->getFlashBag()->add('notice', 'Profil bien modifié.');

            return $this->redirect($this->generateUrl('pfa_ensaobox_user_view', array('id' => $user->getId())));
        }

        return $this->render('PFAEnsaoboxBundle:User:edit.html.twig', array(
            'form' => $form->createView(),
        ));
        }
    }




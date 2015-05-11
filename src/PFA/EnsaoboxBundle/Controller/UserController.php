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
            'user'           => $user,
        ));
    }


    public function editAction($id, Request $request)
    {
        // de cette façon j'aurais les données de mon user dans les champs pour pouvoir les modifier
        $user = $this->getDoctrine()
            ->getManager()
            ->getRepository('PFAEnsaoboxBundle:User')
            ->find($id)
        ;

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

    public function deleteAction($id)
    {
        // Ici, on récupérera l'annonce correspondant à $id

        // Ici, on gérera la suppression de l'annonce en question

        return $this->render('PFAEnsaoboxBundle:User:delete.html.twig');
    }

    public function addAction(Request $request)
    {
        $user = new User();

        // J'ai raccourci cette partie, car c'est plus rapide à écrire !
        $form = $this->get('form.factory')->createBuilder('form', $user)
            ->add('nom',      'text')
            ->add('prenom',     'text')
            ->add('dateNaissance',   'date')
            ->add('aPropos',    'textarea')
            ->add('save',      'submit')
            ->getForm()
        ;

        // On fait le lien Requête <-> Formulaire
        // À partir de maintenant, la variable $user contient les valeurs entrées dans le formulaire par le visiteur
        $form->handleRequest($request);

        // On vérifie que les valeurs entrées sont correctes
        // (Nous verrons la validation des objets en détail dans le prochain chapitre)
        if ($form->isValid()) {
            // On l'enregistre notre objet $User dans la base de données, par exemple
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $request->getSession()->getFlashBag()->add('notice', 'Utilisateur bien ajouté');

            // On redirige vers la page de visualisation de l'annonce nouvellement créée
            return $this->redirect($this->generateUrl('pfa_ensaobox_user', array('id' => $user->getId())));
        }

        // À ce stade, le formulaire n'est pas valide car :
        // - Soit la requête est de type GET, donc le visiteur vient d'arriver sur la page et veut voir le formulaire
        // - Soit la requête est de type POST, mais le formulaire contient des valeurs invalides, donc on l'affiche de nouveau
        return $this->render('PFAEnsaoboxBundle:User:ajout.html.twig', array(
            'form' => $form->createView(),
        ));
    }
}
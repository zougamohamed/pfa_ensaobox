<?php

namespace PFA\EnsaoboxBundle\Controller;

use PFA\EnsaoboxBundle\Entity\Matieres;
use PFA\EnsaoboxBundle\Form\DocumentType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use PFA\EnsaoboxBundle\Entity\Document;
use Symfony\Component\HttpFoundation\Request;

class ShowFilesController extends Controller
{
    public function ajouterAction()
    {

        $niveau= $this->getUser()->getClasses()->getId();
        $filiere= $this->getUser()->getFilieres()->getId();
        $repository = $this->getDoctrine()->getRepository("PFAEnsaoboxBundle:Document");
        $query = $repository->createQueryBuilder('documentPartage')
            ->where('documentPartage.filieres = :filiere')
            ->setParameter('filiere',$filiere)
            ->andWhere('documentPartage.classes = :classe')
            ->setParameter('classe', $niveau)
            ->getQuery();
        $documentEnregistreByName = $query->getResult();
//        $repository2 = $this->getDoctrine()->getRepository("PFAEnsaoboxBundle:Document");
//        $query2 = $repository2->createQueryBuilder('matierePartage.')
//            ->where('matierePartage.filieres = :filiere')
//            ->setParameter('filiere',$filiere)
//            ->andWhere('matierePartage.classes = :classe')
//            ->setParameter('classe', $niveau)
//            ->getQuery();
//        $documentEnregistreByName2 = $query->getResult();
        $matiereArray=array();
        for($i=0;$i<count($documentEnregistreByName);$i++)
        {
//            var_dump($documentEnregistreByName[$i]->getMatieres()->getNomMatiere());
            if(!in_array($documentEnregistreByName[$i]->getMatieres()->getNomMatiere(),$matiereArray))
            {

                $matiereArray[]=$documentEnregistreByName[$i]->getMatieres()->getNomMatiere();

            }

        }

//        $matiereArray[0]='INTERCONNEXION RESEAU2';
//        var_dump($matiereArray);
        return $this->render('PFAEnsaoboxBundle:showFiles:showFiles.html.twig', array(
        'documentPartage'=>$documentEnregistreByName,'matiere'=>$matiereArray
        ));

    }
}

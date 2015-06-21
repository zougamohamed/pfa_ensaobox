<?php

namespace PFA\EnsaoboxBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ShowFilesController extends Controller
{
    public function afficherAction(Request $request)
    {
        $niveau= $this->getUser()->getClasses()->getId();
        $filiere= $this->getUser()->getFilieres()->getId();

        //--------------------------------------------------------------------------------

        $page = $request->get('page');
        $count_per_page = 8;
        $total_count = $this->getTotalDocuments($filiere,$niveau);
        $total_pages=ceil($total_count/$count_per_page);

        if(!is_numeric($page)){
            $page=1;
        }
        else{
            $page=floor($page);
        }
        if($total_count<=$count_per_page){
            $page=1;
        }
        if(($page*$count_per_page)>$total_count){
            $page=$total_pages;
        }
        $offset=0;
        if($page>1){
            $offset = $count_per_page * ($page-1);
        }

//        $em = $this->getDoctrine()->getEntityManager();
//        $ctryQuery = $em->createQueryBuilder()
//            ->select('documentPartage')
//            ->from('PFAEnsaoboxBundle:Document', 'documentPartage')
//            ->where('documentPartage.filieres = :filiere')
//            ->setParameter('filiere',$filiere)
//            ->andWhere('documentPartage.classes = :classe')
//            ->setParameter('classe', $niveau)
//            ->setFirstResult($offset)
//            ->setMaxResults($count_per_page);
//        $ctryFinalQuery = $ctryQuery->getQuery();

        $repository = $this->getDoctrine()->getRepository("PFAEnsaoboxBundle:Document");
        $query = $repository->createQueryBuilder('documentPartage')
            ->where('documentPartage.filieres = :filiere')
            ->setParameter('filiere',$filiere)
            ->andWhere('documentPartage.classes = :classe')
            ->setParameter('classe', $niveau)
            ->orderBy('documentPartage.id', 'DESC')
            ->setFirstResult($offset)
            ->setMaxResults($count_per_page)
            ->getQuery();
        $documentEnregistreByName = $query->getResult();


        //-------------------------------------------------------------------------------
        $repository2 = $this->getDoctrine()->getRepository("PFAEnsaoboxBundle:Document");
        $query2 = $repository2->createQueryBuilder('documentPartage')
            ->where('documentPartage.filieres = :filiere')
            ->setParameter('filiere',$filiere)
            ->andWhere('documentPartage.classes = :classe')
            ->setParameter('classe', $niveau)
            ->getQuery();
        $documentEnregistreByName2 = $query2->getResult();
        $matiereArray=array();

        for($i=0;$i<count($documentEnregistreByName2);$i++)
        {
            if(!in_array($documentEnregistreByName2[$i]->getMatieres()->getNomMatiere(),$matiereArray))
            {

                $matiereArray[]=$documentEnregistreByName2[$i]->getMatieres()->getNomMatiere();

            }

        }

        return $this->render('PFAEnsaoboxBundle:showFiles:showFiles.html.twig', array(
            'documentPartage'=>$documentEnregistreByName,
            'matiere'=>$matiereArray,
            'total_pages'=>$total_pages,
            'current_page'=> $page
        ));

    }

    public function getTotalDocuments($filiere,$niveau) {
        $em = $this->getDoctrine()->getEntityManager();
        $countQuery = $em->createQueryBuilder()
            ->select('Count(documentPartage)')
            ->from('PFAEnsaoboxBundle:Document', 'documentPartage')
            ->where('documentPartage.filieres = :filiere')
            ->setParameter('filiere',$filiere)
            ->andWhere('documentPartage.classes = :classe')
            ->setParameter('classe', $niveau);
        $finalQuery = $countQuery->getQuery();
        $total = $finalQuery->getSingleScalarResult();
        return $total;
    }

    public function filtrerAction($nomMatiere)
    {
        $niveau= $this->getUser()->getClasses()->getId();
        $filiere= $this->getUser()->getFilieres()->getId();
        $repository = $this->getDoctrine()->getRepository("PFAEnsaoboxBundle:Document");

        $query = $repository->createQueryBuilder('documentPartage')
            ->where('documentPartage.filieres = :filiere')
            ->setParameter('filiere',$filiere)
            ->andWhere('documentPartage.classes = :classe')
            ->setParameter('classe', $niveau)
            ->leftJoin('documentPartage.matieres','matiere')
            ->addSelect('matiere')
            ->andWhere('matiere.nomMatiere = :mat')
            ->orderBy('documentPartage.id', 'DESC')
            ->setParameter('mat', $nomMatiere)
            ->getQuery();
        $documentEnregistreByName = $query->getResult();

        $query2 = $repository->createQueryBuilder('documentPartage')
            ->where('documentPartage.filieres = :filiere')
            ->setParameter('filiere',$filiere)
            ->andWhere('documentPartage.classes = :classe')
            ->setParameter('classe', $niveau)
            ->getQuery();
        $documentEnregistreByName2 = $query2->getResult();

        $matiereArray=array();
        for($i=0;$i<count($documentEnregistreByName2);$i++)
        {
            if(!in_array($documentEnregistreByName2[$i]->getMatieres()->getNomMatiere(),$matiereArray))
            {

                $matiereArray[]=$documentEnregistreByName2[$i]->getMatieres()->getNomMatiere();

            }

        }

        return $this->render('PFAEnsaoboxBundle:showFiles:showFiles.html.twig', array(
            'documentPartage'=>$documentEnregistreByName,
            'matiere'=>$matiereArray
        ));
    }
    public function rechercherDocAction(Request $request)
    {

        $niveau= $this->getUser()->getClasses()->getId();
        $filiere= $this->getUser()->getFilieres()->getId();
        $docCherche=$request->query->get('cours');


        $repository = $this->getDoctrine()->getRepository("PFAEnsaoboxBundle:Document");
        $query = $repository->createQueryBuilder('documentPartage')
            ->where('documentPartage.filieres = :filiere')
            ->setParameter('filiere',$filiere)
            ->andWhere('documentPartage.classes = :classe')
            ->setParameter('classe', $niveau)
            ->andWhere('documentPartage.path like :doc')
            ->setParameter('doc', '%'.$docCherche.'%')
            ->getQuery();
        $documentEnregistreByName = $query->getResult();

        $query2 = $repository->createQueryBuilder('documentPartage')
            ->where('documentPartage.filieres = :filiere')
            ->setParameter('filiere',$filiere)
            ->andWhere('documentPartage.classes = :classe')
            ->setParameter('classe', $niveau)
            ->orderBy('documentPartage.id', 'DESC')
            ->getQuery();
        $documentEnregistreByName2 = $query2->getResult();

        $matiereArray=array();
        for($i=0;$i<count($documentEnregistreByName2);$i++)
        {
            if(!in_array($documentEnregistreByName2[$i]->getMatieres()->getNomMatiere(),$matiereArray))
            {

                $matiereArray[]=$documentEnregistreByName2[$i]->getMatieres()->getNomMatiere();

            }

        }

        return $this->render('PFAEnsaoboxBundle:showFiles:showFiles.html.twig', array(
            'documentPartage'=>$documentEnregistreByName,
            'matiere'=>$matiereArray
        ));
    }
}

<?php

namespace PFA\EnsaoboxBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Matieres
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="PFA\EnsaoboxBundle\Entity\MatieresRepository")
 */
class Matieres
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nomMatiere", type="string", length=255, nullable=true)
     */
    private $nomMatiere;

    /**
     * @var string
     *
     * @ORM\Column(name="professeur", type="string", length=255, nullable=true)
     */
    private $professeur;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set nomProf
     *
     * @param string $nomMatiere
     * @return Matieres
     */
    public function setNomMatiere($nomMatiere)
    {
        $this->nomMatiere = $nomMatiere;
    
        return $this;
    }

    /**
     * Get nomMatiere
     *
     * @return string 
     */
    public function getNomMatiere()
    {
        return $this->nomMatiere;
    }


    /**
     * Set professeur
     *
     * @param string $professeur
     */
    public function setProfesseur($professeur)
    {
        $this->professeur = $professeur;
    }

    /**
     * Get professeur
     *
     * @return string
     */
    public function getProfesseur()
    {
        return $this->professeur;
    }

}

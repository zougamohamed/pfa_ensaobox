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
     * @ORM\Column(name="nomMatiere", type="string", length=255)
     */
    private $nomMatiere;


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
}

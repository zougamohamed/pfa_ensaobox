<?php

namespace PFA\EnsaoboxBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Filieres
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="PFA\EnsaoboxBundle\Entity\FilieresRepository")
 */
class Filieres
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
     * @ORM\Column(name="nomFiliere", type="string", length=255)
     */
    private $nomFiliere;


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
     * Set nomFiliere
     *
     * @param string $nomFiliere
     * @return Filieres
     */
    public function setNomFiliere($nomFiliere)
    {
        $this->nomFiliere = $nomFiliere;
    
        return $this;
    }

    /**
     * Get nomFiliere
     *
     * @return string 
     */
    public function getNomFiliere()
    {
        return $this->nomFiliere;
    }
}

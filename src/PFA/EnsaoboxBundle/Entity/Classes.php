<?php

namespace PFA\EnsaoboxBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Classes
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="PFA\EnsaoboxBundle\Entity\ClassesRepository")
 */
class Classes
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
     * @ORM\Column(name="nomClasse", type="string", length=255)
     */
    private $nomClasse;


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
     * Set nomClasse
     *
     * @param string $nomClasse
     * @return Classes
     */
    public function setNomClasse($nomClasse)
    {
        $this->nomClasse = $nomClasse;
    
        return $this;
    }

    /**
     * Get nomClasse
     *
     * @return string 
     */
    public function getNomClasse()
    {
        return $this->nomClasse;
    }
}

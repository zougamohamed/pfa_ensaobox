<?php

namespace PFA\EnsaoboxBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Document
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="PFA\EnsaoboxBundle\Entity\DocumentRepository")
 */
class Document
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
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="professor", type="string", length=255)
     */
    private $professor;

    /**
     * @var string
     *
     * @ORM\Column(name="classe", type="string", length=255)
     */
    private $classe;

    /**
     * @var string
     *
     * @ORM\Column(name="matiere", type="string", length=255)
     */
    private $matiere;


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
     * Set name
     *
     * @param string $name
     * @return Document
     */
    public function setName($name)
    {
        $this->name = $name;
    
        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set professor
     *
     * @param string $professor
     * @return Document
     */
    public function setProfessor($professor)
    {
        $this->professor = $professor;
    
        return $this;
    }

    /**
     * Get professor
     *
     * @return string 
     */
    public function getProfessor()
    {
        return $this->professor;
    }

    /**
     * Set classe
     *
     * @param string $classe
     * @return Document
     */
    public function setClasse($classe)
    {
        $this->classe = $classe;
    
        return $this;
    }

    /**
     * Get classe
     *
     * @return string 
     */
    public function getClasse()
    {
        return $this->classe;
    }

    /**
     * Set matiere
     *
     * @param string $matiere
     * @return Document
     */
    public function setMatiere($matiere)
    {
        $this->matiere = $matiere;
    
        return $this;
    }

    /**
     * Get matiere
     *
     * @return string 
     */
    public function getMatiere()
    {
        return $this->matiere;
    }
}

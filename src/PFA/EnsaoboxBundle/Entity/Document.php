<?php

namespace PFA\EnsaoboxBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Document
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="PFA\EnsaoboxBundle\Entity\DocumentRepository")
 */
/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class Document
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    public $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    public $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    public $path;
    /**
     * @Assert\File(
     *     maxSize = "12M",
     * )
     */
    public $file;

    /**
     * @ORM\ManyToOne(targetEntity="PFA\EnsaoboxBundle\Entity\Filieres")
     */
    private $filieres;

    /**
     * @return mixed
     */
    public function getFilieres()
    {
        return $this->filieres;
    }

    /**
     * @param mixed $filieres
     */
    public function setFilieres($filieres)
    {
        $this->filieres = $filieres;
    }

    /**
     * @ORM\ManyToOne(targetEntity="PFA\EnsaoboxBundle\Entity\Classes")
     */
    private $classes;

    /**
     * @return mixed
     */
    public function getClasses()
    {
        return $this->classes;
    }


    /**
     * @param mixed $classes
     */
    public function setClasses($classes)
    {
        $this->classes = $classes;
    }

    /**
     * @ORM\ManyToOne(targetEntity="PFA\EnsaoboxBundle\Entity\Matieres")
     */
    private $matieres;

    /**
     * @return mixed
     */
    public function getMatieres()
    {
        return $this->matieres;
    }
    /**
     * @param mixed $matieres
     */
    public function setMatieres($matieres)
    {
        $this->matieres = $matieres;
    }

    public function getAbsolutePath()
    {
        return null === $this->path ? null : $this->getUploadRootDir().'/'.$this->path;
    }

    public function getWebPath()
    {
        return null === $this->path ? null : $this->getUploadDir().'/'.$this->path;
    }

    protected function getUploadRootDir()
    {
        // le chemin absolu du répertoire où les documents uploadés doivent être sauvegardés
        return __DIR__.'/../../../../web/'.$this->getUploadDir();
    }

    protected function getUploadDir()
    {
        // on se débarrasse de « __DIR__ » afin de ne pas avoir de problème lorsqu'on affiche
        // le document/image dans la vue.
        return 'uploads/documents';
    }
//    /**
//     * @ORM\PrePersist()
//     * @ORM\PreUpdate()
//     */
//    public function preUpload()
//    {
//        if (null !== $this->file) {
//            // faites ce que vous voulez pour générer un nom unique
//            $this->path = sha1(uniqid(mt_rand(), true)).'.'.$this->file->guessExtension();
//        }
//    }
//    /**
//     * @ORM\PostPersist()
//     * @ORM\PostUpdate()
//     */
//    public function upload()
//    {
//        if (null === $this->file) {
//            return;
//        }
//
//        // s'il y a une erreur lors du déplacement du fichier, une exception
//        // va automatiquement être lancée par la méthode move(). Cela va empêcher
//        // proprement l'entité d'être persistée dans la base de données si
//        // erreur il y a
//        $this->file->move($this->getUploadRootDir(), $this->path);
//
//        unset($this->file);
//    }
//    /**
//     * @ORM\PostRemove()
//     */
//    public function removeUpload()
//    {
//        if ($file = $this->getAbsolutePath()) {
//            unlink($file);
//        }
//    }
    public function upload()
    {
        // la propriété « file » peut être vide si le champ n'est pas requis
        if (null === $this->file) {
            return;
        }

        // utilisez le nom de fichier original ici mais
        // vous devriez « l'assainir » pour au moins éviter
        // quelconques problèmes de sécurité

        // la méthode « move » prend comme arguments le répertoire cible et
        // le nom de fichier cible où le fichier doit être déplacé
        $this->file->move($this->getUploadRootDir(), $this->file->getClientOriginalName());

        // définit la propriété « path » comme étant le nom de fichier où vous
        // avez stocké le fichier
        $this->path = $this->file->getClientOriginalName();

        // « nettoie » la propriété « file » comme vous n'en aurez plus besoin
        $this->file = null;
    }

}
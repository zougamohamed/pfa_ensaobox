<?php
/**
 * Created by PhpStorm.
 * User: Achraf
 * Date: 26/04/2015
 * Time: 19:56
 */

namespace PFA\EnsaoboxBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=30)
     */

    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="prenom", type="string", length=30)
     */
    private $prenom;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_naissance", type="date")
     */
    private $dateNaissance;

    /**
     * @var string
     *
     * @ORM\Column(name="filiere", type="string", length=30)
     */
    private $filiere;

    /**
     * @var string
     *
     * @ORM\Column(name="niveau", type="string", length=20)
     */
    private $niveau;

    /**
     * @var string
     *
     * @ORM\Column(name="lien_avatar", type="string", length=100)
     */
    private $lienAvatar;

    /**
     * @var string
     *
     * @ORM\Column(name="lien_linkedin", type="string", length=100)
     */
    private $lienLinkedin;

    /**
     * @var string
     *
     * @ORM\Column(name="lien_facebook", type="string", length=100)
     */
    private $lienFacebook;

    /**
     * @var string
     *
     * @ORM\Column(name="a_propos", type="text")
     */
    private $aPropos;


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
     * Set nom
     *
     * @param string $nom
     * @return User
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set prenom
     *
     * @param string $prenom
     * @return User
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;

        return $this;
    }

    /**
     * Get prenom
     *
     * @return string
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * Set dateNaissance
     *
     * @param \DateTime $dateNaissance
     * @return User
     */
    public function setDateNaissance($dateNaissance)
    {
        $this->dateNaissance = $dateNaissance;

        return $this;
    }

    /**
     * Get dateNaissance
     *
     * @return \DateTime
     */
    public function getDateNaissance()
    {
        return $this->dateNaissance;
    }

    /**
     * Set filiere
     *
     * @param string $filiere
     * @return User
     */
    public function setFiliere($filiere)
    {
        $this->filiere = $filiere;

        return $this;
    }

    /**
     * Get filiere
     *
     * @return string
     */
    public function getFiliere()
    {
        return $this->filiere;
    }

    /**
     * Set niveau
     *
     * @param string $niveau
     * @return User
     */
    public function setNiveau($niveau)
    {
        $this->niveau = $niveau;

        return $this;
    }

    /**
     * Get niveau
     *
     * @return string
     */
    public function getNiveau()
    {
        return $this->niveau;
    }

    /**
     * Set lienAvatar
     *
     * @param string $lienAvatar
     * @return User
     */
    public function setLienAvatar($lienAvatar)
    {
        $this->lienAvatar = $lienAvatar;

        return $this;
    }

    /**
     * Get lienAvatar
     *
     * @return string
     */
    public function getLienAvatar()
    {
        return $this->lienAvatar;
    }

    /**
     * Set lienLinkedin
     *
     * @param string $lienLinkedin
     * @return User
     */
    public function setLienLinkedin($lienLinkedin)
    {
        $this->lienLinkedin = $lienLinkedin;

        return $this;
    }

    /**
     * Get lienLinkedin
     *
     * @return string
     */
    public function getLienLinkedin()
    {
        return $this->lienLinkedin;
    }

    /**
     * Set lienFacebook
     *
     * @param string $lienFacebook
     * @return User
     */
    public function setLienFacebook($lienFacebook)
    {
        $this->lienFacebook = $lienFacebook;

        return $this;
    }

    /**
     * Get lienFacebook
     *
     * @return string
     */
    public function getLienFacebook()
    {
        return $this->lienFacebook;
    }

    /**
     * Set aPropos
     *
     * @param string $aPropos
     * @return User
     */
    public function setAPropos($aPropos)
    {
        $this->aPropos = $aPropos;

        return $this;
    }

    /**
     * Get aPropos
     *
     * @return string
     */
    public function getAPropos()
    {
        return $this->aPropos;
    }

    public function __construct()
    {
        parent::__construct();
        // your own logic
    }
}
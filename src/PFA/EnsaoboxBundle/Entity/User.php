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
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Security\Core\Util\SecureRandom;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
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
     * @ORM\Column(name="nom", type="string", length=30,nullable=false)
     *@Assert\Length(
     *     min=3,
     *     max="40",
     *     minMessage="Votre nom est trop court !",
     *     maxMessage="Votre nom est trop long !",
     * )
     */

    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="prenom", type="string", length=30,nullable=false)
     *@Assert\Length(
     *     min=3,
     *     max="40",
     *     minMessage="Votre prénom est trop court !",
     *     maxMessage="Votre prénom est trop long !",
     * )
     */
    private $prenom;

    /**
     * @var \DateTime
     * @ORM\Column(name="date_naissance", type="date", nullable=false)
     */
    private $dateNaissance;

    /**
     * @var string
     * @ORM\Column(name="filiere", type="string", length=30,nullable=true)
     */
    private $filiere;

    /**
     * @var string
     * @ORM\Column(name="niveau", type="string", length=20,nullable=true)
     */
    private $niveau;


    /**
     * @var string
     * @ORM\Column(name="lien_linkedin", type="string", length=100,nullable=true)
     * @Assert\Length(max=255)
     * @Assert\Url()
     */
    private $lienLinkedin;

    /**
     * @var string
     * @ORM\Column(name="lien_facebook", type="string", length=100, nullable=true)
     * @Assert\Length(max=100)
     * @Assert\Url()
     */
    private $lienFacebook;

    /**
     * @var string
     *
     * @ORM\Column(name="a_propos", type="text",nullable=true)
     */
    private $aPropos;


    /**
     * @ORM\ManyToOne(targetEntity="PFA\EnsaoboxBundle\Entity\Filieres")
     */
    private $filieres;

    // Partie de photo de profil //////////////////////////////////
    //**************************************************************


    /**
     * @Assert\File(maxSize="2048k")
     * @Assert\Image(mimeTypesMessage="Please upload a valid image.")
     */
    protected $profilePictureFile;

    // for temporary storage
    private $tempProfilePicturePath;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $profilePicturePath;


/////// Partie de Getters et Setters //////////////////////////////

    public function __construct()
    {
        parent::__construct();
        // your own logic
    }

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


    ///////////Partie de photo de profil //////////////////////////

    /**
     * Sets the file used for profile picture uploads
     *
     * @param UploadedFile $file
     * @return object
     */
    public function setProfilePictureFile(UploadedFile $file = null) {
        // set the value of the holder
        $this->profilePictureFile       =   $file;
        // check if we have an old image path
        if (isset($this->profilePicturePath)) {
            // store the old name to delete after the update
            $this->tempProfilePicturePath = $this->profilePicturePath;
            $this->profilePicturePath = null;
        } else {
            $this->profilePicturePath = 'initial';
        }

        return $this;
    }

    /**
     * Get the file used for profile picture uploads
     *
     * @return UploadedFile
     */
    public function getProfilePictureFile() {

        return $this->profilePictureFile;
    }

    /**
     * Set profilePicturePath
     *
     * @param string $profilePicturePath
     * @return User
     */
    public function setProfilePicturePath($profilePicturePath)
    {
        $this->profilePicturePath = $profilePicturePath;

        return $this;
    }

    /**
     * Get profilePicturePath
     *
     * @return string
     */
    public function getProfilePicturePath()
    {
        return $this->profilePicturePath;
    }

    /**
     * Get the absolute path of the profilePicturePath
     */
    public function getProfilePictureAbsolutePath() {
        return null === $this->profilePicturePath
            ? null
            : $this->getUploadRootDir().'/'.$this->profilePicturePath;
    }

    /**
     * Get root directory for file uploads
     *
     * @return string
     */
    protected function getUploadRootDir($type='profilePicture') {
        // the absolute directory path where uploaded
        // documents should be saved
        return __DIR__.'/../../../../web/'.$this->getUploadDir($type);
    }

    /**
     * Specifies where in the /web directory profile pic uploads are stored
     *
     * @return string
     */
    protected function getUploadDir($type='profilePicture') {
        // the type param is to change these methods at a later date for more file uploads
        // get rid of the __DIR__ so it doesn't screw up
        // when displaying uploaded doc/image in the view.
        return 'user/profilepics';
    }

    /**
     * Get the web path for the user
     *
     * @return string
     */
    public function getWebProfilePicturePath() {

        return $this->getUploadDir().'/'.$this->getProfilePicturePath();
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUploadProfilePicture() {
        if (null !== $this->getProfilePictureFile()) {
            // a file was uploaded
            // generate a unique filename
            $filename = $this->generateRandomProfilePictureFilename();
            $this->setProfilePicturePath($filename.'.'.$this->getProfilePictureFile()->guessExtension());
        }
    }

    /**
     * Generates a 32 char long random filename
     *
     * @return string
     */
    public function generateRandomProfilePictureFilename() {
        $count                  =   0;
        do {
            $generator = new SecureRandom();
            $random = $generator->nextBytes(16);
            $randomString = bin2hex($random);
            $count++;
        }
        while(file_exists($this->getUploadRootDir().'/'.$randomString.'.'.$this->getProfilePictureFile()->guessExtension()) && $count < 50);

        return $randomString;
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     *
     * Upload the profile picture
     *
     * @return mixed
     */
    public function uploadProfilePicture() {
        // check there is a profile pic to upload
        if ($this->getProfilePictureFile() === null) {
            return;
        }
        // if there is an error when moving the file, an exception will
        // be automatically thrown by move(). This will properly prevent
        // the entity from being persisted to the database on error
        $this->getProfilePictureFile()->move($this->getUploadRootDir(), $this->getProfilePicturePath());

        // check if we have an old image
        if (isset($this->tempProfilePicturePath) && file_exists($this->getUploadRootDir().'/'.$this->tempProfilePicturePath)) {
            // delete the old image
            unlink($this->getUploadRootDir().'/'.$this->tempProfilePicturePath);
            // clear the temp image path
            $this->tempProfilePicturePath = null;
        }
        $this->profilePictureFile = null;
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeProfilePictureFile()
    {
        if ($file = $this->getProfilePictureAbsolutePath() && file_exists($this->getProfilePictureAbsolutePath())) {
            unlink($file);
        }
    }

}
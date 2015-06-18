<?php
namespace PFA\EnsaoboxBundle\Form;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class DocumentType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {

      $builder
        ->add('filieres','entity',array('class'=>'PFA\EnsaoboxBundle\Entity\Filieres','property'=>'nomFiliere','multiple'  => false,'attr' => array('class' => 'form-control','style' => 'background-color:rgb(250, 255, 189);color:black;font-size:15px;  ')))
        ->add('classes','entity',array('class'=>'PFA\EnsaoboxBundle\Entity\Classes','property'=>'nomClasse','attr' => array('class' => 'form-control','style' => 'background-color:rgb(250, 255, 189);color:black;font-size:15px;  ')))
        ->add('matieres','entity',array('class'=>'PFA\EnsaoboxBundle\Entity\Matieres','property'=>'nomMatiere','attr' => array('class' => 'form-control','style' => 'background-color:rgb(250, 255, 189);color:black;font-size:15px;  ')))
        ->add('name','text',array('label'=>'Nouvelle matiére','required'    => false,'attr' => array('class' => 'form-control' ,'style' => 'background-color:rgb(250, 255, 189);color:black;font-size:15px;  ')))
        ->add('file','file',array('required' => true,'attr'     =>   array('style' => 'font-size:17px;')))
        ->add('envoyer','submit',array('attr' => array('class' => 'btn btn-shadow btn-success','style' => 'font-size:17px ')))
    ;
  }

  public function setDefaultOptions(OptionsResolverInterface $resolver)
  {
    $resolver->setDefaults(array(
      'data_class' => 'PFA\EnsaoboxBundle\Entity\Document'
    ));
  }

  public function getName()
  {
    return 'pfa_ensaobox_ajouter_files';
  }
}
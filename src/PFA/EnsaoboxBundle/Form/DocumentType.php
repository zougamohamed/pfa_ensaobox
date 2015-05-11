<?php
namespace PFA\EnsaoboxBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class DocumentType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder
        ->add('name')
        ->add('file', 'file')
    ;
  }

//  public function setDefaultOptions(OptionsResolverInterface $resolver)
//  {
//    $resolver->setDefaults(array(
//      'data_class' => 'PFA\EnsaoboxBundle\Entity\Document'
//    ));
//  }

  public function getName()
  {
    return 'pfa_ensaobox_ajouter_files';
  }
}